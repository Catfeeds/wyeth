<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Storage;
use App\CIService\CIDataQuery;
use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use GuzzleHttp;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Cache;
use Input;
use Redirect;

class CourseDataActive extends Controller
{
    public function index()
    {
       //die();
        $data = new CIDataQuery();
        $start_day = date('Y-m-d',time());
        $end_day = date('Y-m-d',strtotime("+1 day"));
        $yesterday = date('Y-m-d',strtotime("-1 day"));
        $all = @$data->timeSeries($start_day,$end_day,"all","pv",null,null)['data'][0];
        $ad = @$data->timeSeries($start_day,$end_day,"all","server_ad")['data'][0];
        $sign = @$data->timeSeries($start_day,$end_day,"all","sign")['data'][0];
        $all_yesterday = @$data->timeSeries($yesterday,$start_day,"all","pv",null,null)['data'][0];
        $yesterday_push = @$data->timeSeries($yesterday,$start_day,"all","send_tplmsg",null,["wxtpl_tjhg != 1"])['data'][0];
        $data=[
            'all'=>$all,
            'ad'=>$ad,
            'id'=>'',
            'from'=>'',
            'to'=>'',
            'sign'=>$sign,
            'yesterday'=>$all_yesterday,
            'yesterday_push'=>$yesterday_push
        ];
        return view('admin.index.index', $data)
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }



    public function search(Request $request){

        // 返回时增加 今日数据
        $all = $request->all();
        if(empty($all['from']) || empty($all['to'])){
            return view('admin.error',['msg'=>'请检查课程id或日期']);
        }
        if($all['id'] !='' && !is_numeric($all['id'])){
            return view('admin.error',['msg'=>'请检查课程id或日期']);
        }
        if($all['id'] !='' && is_numeric($all['id'])){
            $res = DB::table('course')->where('id', $all['id'])->first();
            if(!$res){
                return view('admin.error',['msg'=>'课程ID不存在']);
            }
        }
        $data = new CIDataQuery();
        $start_day = date('Y-m-d',time());
        $end_day = date('Y-m-d',strtotime("+1 day"));
        $yesterday = date('Y-m-d',strtotime("-1 day"));
        $pv_today = @$data->timeSeries($start_day,$end_day,"all","pv",null,null)['data'][0];
        $ad = @$data->timeSeries($start_day,$end_day,"all","server_ad")['data'][0];
        $sign = @$data->timeSeries($start_day,$end_day,"all","sign")['data'][0];
        $all_yesterday = @$data->timeSeries($yesterday,$start_day,"all","pv",null,null)['data'][0];
        $yesterday_push = @$data->timeSeries($yesterday,$start_day,"all","send_tplmsg",null,["wxtpl_tjhg != 1"])['data'][0];
        $start_day = $all['from'];
        $end_day = $all['to'];
        if($start_day >= $end_day){
            return view('admin.error',['msg'=>'开始日期不能大于结束日期']);
        }
        $push = @$data->timeSeries($start_day,$end_day,"all","send_tplmsg",null,["wxtpl_tjhg != 1"])['data'][0];
        if(isset($all['id']) && trim($all['id']) ==''){
            $all_s = @$data->timeSeries($start_day,$end_day,"all","pv",null,null)['data'][0];
            $ad_s = @$data->timeSeries($start_day,$end_day,"all","server_ad",null)['data'][0];
            $res = @$data->groupBy($start_day,$end_day,"play","__duration","event_arg","pv","buildin","descending",1000,null,null);
            $sign_s = @$data->timeSeries($start_day,$end_day,"all","sign")['data'][0];
            $total_count = 0;
            $total_duration = 0;
            foreach ($res as $item) {
                if(!isset($item['event'])){
                    return view('admin.error',['msg'=>'服务端返回异常，请稍后重试']);
                }
                $count = $item["event"]["pv"];
                $duration = $item["event"]["__duration"];
                if ($duration >0) {
                    $total_count += $count;
                    $total_duration += $count * $duration;
                }
            }
            if ($total_count == '0'){
                $listen_time_s= 0;;
            }else{
                $listen_time_s= number_format(($total_duration / $total_count)/60,2);
            }
            $res = @$data->groupBy($start_day,$end_day,"end","__duration","event_arg","pv","buildin","descending",1000,null,null);
            $total_count= 0;
            $total_duration =  0;
            //var_dump($result);
            foreach ($res as $item) {
                //var_dump($item);
                if(!isset($item['event'])){
                    return view('admin.error',['msg'=>'服务端返回异常，请稍后重试']);
                }
                $count = $item["event"]["pv"];
                $duration = $item["event"]["__duration"];
                if ($duration > 0) {
                    $total_count += $count;
                    $total_duration += $count * $duration;
                }
            }
            if ($total_count === 0){
                $stay_time_s = 0;
            }else{
                $stay_time_s = number_format($total_duration/$total_count/60,2);
            }
        }else {
            $cid = intval(trim($all['id']));
            $all_s = @$data->timeSeries($start_day,$end_day,"all","pv",null,["cid = $cid"])['data'][0];
            $ad_s = @$data->timeSeries($start_day,$end_day,"all","server_ad",null,["cid = $cid"])['data'][0];
            $sign_s = @$data->timeSeries($start_day,$end_day,"all","sign",null,["cid = $cid"])['data'][0];
            //$push = @$data->timeSeries($start_day,$end_day,"all","send_tplmsg",null,["wxtpl_tjhg != 1"])['data'][0];
            $res = @$data->groupBy($start_day,$end_day,"play","__duration","event_arg","pv","buildin","descending",1000,null,["cid = $cid"]);
            $total_count = 0;
            $total_duration = 0;
            if(!isset($item['event'])){
                $listen_time_s="服务端返回异常";
                return view('admin.error',['msg'=>'服务端返回异常，请稍后重试']);
            }
            foreach ($res as $item) {
                $count = $item["event"]["pv"];
                $duration = $item["event"]["__duration"];
                if ($duration >0) {
                    $total_count += $count;
                    $total_duration += $count * $duration;
                }
            }
            if ($total_count == '0'){
                $listen_time_s= 0;
            }else{
                $listen_time_s= number_format(($total_duration / $total_count)/60,2);
            }
            $res = @$data->groupBy($start_day,$end_day,"end","__duration","event_arg","pv","buildin","descending",1000,null,["cid = $cid"]);
            $total_count= 0;
            $total_duration =  0;
            //var_dump($result);
            foreach ($res as $item) {
                if(!isset($item['event'])){
                    return view('admin.error',['msg'=>'服务端返回异常，请稍后重试']);
                }
                //var_dump($item);
                $count = $item["event"]["pv"];
                $duration = $item["event"]["__duration"];
                if ($duration > 0) {
                    $total_count += $count;
                    $total_duration += $count * $duration;
                }
            }
            if ($total_count === 0){
                $stay_time_s = 0;
            }else{
                $stay_time_s = number_format($total_duration/$total_count/60,2);
            }
        }
        $data=[
            'all'=>$pv_today,
            'ad'=>$ad,
            'all_s'=>$all_s,
            'ad_s'=>$ad_s,
            'push'=>$push,
            'listen_time_s'=>$listen_time_s,
            'stay_time_s'=>$stay_time_s,
            'from'=>$start_day,
            'to'=>$end_day,
            'id'=>$all['id'],
            'sign'=>$sign,
            'sign_s'=>$sign_s,
            'yesterday'=>$all_yesterday,
            'yesterday_push'=>$yesterday_push
        ];
        return view('admin.index.index', $data)
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }


}
