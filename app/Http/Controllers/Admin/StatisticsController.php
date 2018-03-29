<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2018/1/5
 * Time: 17:40
 */

namespace App\Http\Controllers\Admin;


use App\CIService\CIDataOpenApi;
use App\CIService\CIDataQuery;
use App\Helpers\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Cache;


class StatisticsController extends BaseController
{

    public function index(Request $request)
    {
        // 当日0点
        $current_time = strtotime(date('Y-m-d',time()));
        $data = new CIDataQuery();
        $start_day = date('Y-m-d',time());
        $end_day = date('Y-m-d',strtotime("+1 day"));
        $yesterday = date('Y-m-d',strtotime("-1 day"));

        $data_device = new CIDataOpenApi();
        // 查询累计设备的时间设置（从2018-1-1开始）
        $end_day_total = $current_time;
        $start_day_total = strtotime(date('Y-m-d',time()-29));

        // 查询近7日活跃设备的时间设置
       $end_day_week = $current_time;
       $start_day_week = strtotime(date('Y-m-d', time())) - 3600 * 24 * 7;


        // 查询近30日活跃设备的时间设置
        $end_day_month = $current_time;
        $start_day_month = strtotime(date('Y-m-d', time())) - 3600 * 24 * 30;

        // 今日与昨日每个小时新增设备数组
        $new_add_today = array();
        $new_add_yesterday_reverse = array();
        $new_add_yesterday = array();

        // 因暂未提供pv和uv查询，使用老的接口
        try{
            $all = @$data->timeSeries($start_day,$end_day,"all","pv",null,null)['data'][0];
            $ad = @$data->timeSeries($start_day,$end_day,"all","server_advertise")['data'][0];
            $sign = @$data->timeSeries($start_day,$end_day,"all","sign")['data'][0];
            $all_yesterday = @$data->timeSeries($yesterday,$start_day,"all","pv",null,null)['data'][0];
            $yesterday_push = @$data->timeSeries($yesterday,$start_day,"all","send_tplmsg",null,["wxtpl_tjhg != 1"])['data'][0];

            // 计算累计设备量（每日新增相加）
            if(Cache::has(CacheKey::H5_COUNT_DEVICES)){
                $total_device = Cache::get(CacheKey::H5_COUNT_DEVICES);
            }else{
                $total_device_active_array = @$data_device->userCount($start_day_total, $end_day_total,"active", "all", null)['data'];
                $total_device = 0;
                foreach ($total_device_active_array as $item){
                    $total_device += $item['device'];
                }

            }


            // 计算近7天的活跃设备量
            $week_device_active_array = @$data_device->userCount($start_day_week, $end_day_week,"active", "all", null)['data'];
            $week_device = 0;
            foreach ($week_device_active_array as $item){
                $week_device += $item['device'];
            }

            // 计算近7天与上7天的比值
            $pre_week_device_array = @$data_device->userCount($start_day_week - 3600 * 24 * 7, $end_day_week- 3600 * 24 * 7,"active", "all", null)['data'];
            $pre_week_device = 0;
            foreach ($pre_week_device_array as $item){
                $pre_week_device += $item['device'];
            }
            $week_rate = round(($week_device - $pre_week_device)/$pre_week_device,4) * 100 ."%";

            //计算近30天的设备量
            $month_device_array = @$data_device->userCount($start_day_month, $end_day_month,"active", "all", null)['data'];
            $month_device = 0;
            foreach ($month_device_array as $item){
                $month_device += $item['device'];
            }

            // 计算近30天与上30的比值
            $pre_month_device_array = @$data_device->userCount($start_day_month - 3600 * 24 * 30, $end_day_month- 3600 * 24 * 30,"active", "all", null)['data'];
            $pre_month_device = 0;
            foreach ($pre_month_device_array as $item){
                $pre_month_device += $item['device'];
            }
            $month_rate = round(($month_device - $pre_month_device)/$pre_month_device,4) * 100 ."%";

            // 计算昨日每个小时新增设备数
                $new_add_yesterday_tem = @$data_device->userCount($current_time-3600 * 24, $current_time,"new", "hour", null)['data'];
                foreach ($new_add_yesterday_tem as $item){
                    array_push($new_add_yesterday_reverse, $item['device']);
                }
                $new_add_yesterday = array_reverse($new_add_yesterday_reverse);


            // 计算今天（离当前时刻最近的一个整点为止）的新增设备数
            // 当前最近的整点时间
            $time_tem = strtotime(date("Y-m-d H", time()).":0:0");
            $tem1 = @$data_device->userCount($current_time, $time_tem,"new", "hour", null)['data'];
            foreach ($tem1 as $item){
                array_push($new_add_today,$item['device']);
            }
            // 数组不足24，补0
            $size = count($new_add_today);
            for($i = 0; $i < 24-$size; $i++){
                array_push($new_add_today, 0);
            }
        }catch (\Exception $e){
            $all='';
            $ad = '';
            $sign = '';
            $all_yesterday = '';
            $yesterday_push = '';
            $total_device = '';
            $week_device = '';
            $month_device = '';
            $week_rate = '';
            $month_rate = '';
        }
        $data=[
            'all'=>$all,
            'ad'=>$ad,
            'id'=>'',
            'from'=>'',
            'to'=>'',
            'sign'=>$sign,
            'yesterday'=>$all_yesterday,
            'yesterday_push'=>$yesterday_push,
            'total_device'=>$total_device,
            'week_device'=>$week_device,
            'month_device'=>$month_device,
            'week_rate' => $week_rate,
            'month_rate' => $month_rate,
            'new_add_yesterday'=>$new_add_yesterday,
            'new_add_today'=>$new_add_today
        ];

        return view('admin.statistics.index', $data)
                    ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
                    ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
                    ->nest('footer', 'admin.common.footer', []);
    }
}