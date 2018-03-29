<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use JWTAuth, JWTFactory;
use App\Http\Controllers\Controller;
use Redirect;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use App\CIService\CIDataQuery;
use Endroid\QrCode\QrCode;
use GuzzleHttp;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        $data = new CIDataQuery();
        $start_day = date('Y-m-d',time());
        $end_day = date('Y-m-d',strtotime("+1 day"));
        $yesterday = date('Y-m-d',strtotime("-1 day"));
        try{
            $all = @$data->timeSeries($start_day,$end_day,"all","pv",null,null)['data'][0];
            $ad = @$data->timeSeries($start_day,$end_day,"all","server_advertise")['data'][0];
            $sign = @$data->timeSeries($start_day,$end_day,"all","sign")['data'][0];
            $all_yesterday = @$data->timeSeries($yesterday,$start_day,"all","pv",null,null)['data'][0];
            $yesterday_push = @$data->timeSeries($yesterday,$start_day,"all","send_tplmsg",null,["wxtpl_tjhg != 1"])['data'][0];
        }catch (\Exception $e){
            $all='';
            $ad = '';
            $sign = '';
            $all_yesterday = '';
            $yesterday_push = '';
        }
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
        $start_day = $all['from'];
        $end_day = $all['to'];
        if($start_day >= $end_day){
            return view('admin.error',['msg'=>'开始日期不能大于结束日期']);
        }
        try{
            $data = new CIDataQuery();
            $start_day = date('Y-m-d',time());
            $end_day = date('Y-m-d',strtotime("+1 day"));
            $yesterday = date('Y-m-d',strtotime("-1 day"));
            $pv_today = @$data->timeSeries($start_day,$end_day,"all","pv",null,null)['data'][0];
            $ad = @$data->timeSeries($start_day,$end_day,"all","server_advertise")['data'][0];
            $sign = @$data->timeSeries($start_day,$end_day,"all","sign")['data'][0];
            $all_yesterday = @$data->timeSeries($yesterday,$start_day,"all","pv",null,null)['data'][0];
            $yesterday_push = @$data->timeSeries($yesterday,$start_day,"all","send_tplmsg",null,["wxtpl_tjhg != 1"])['data'][0];
            $push = @$data->timeSeries($start_day,$end_day,"all","send_tplmsg",null,["wxtpl_tjhg != 1"])['data'][0];
            if(isset($all['id']) && trim($all['id']) ==''){
                $all_s = @$data->timeSeries($start_day,$end_day,"all","pv",null,null)['data'][0];
                $ad_s = @$data->timeSeries($start_day,$end_day,"all","server_advertise",null)['data'][0];
                $res = @$data->groupBy($start_day,$end_day,"play","__duration","event_arg","pv","buildin","descending",1000,null,null);
                $sign_s = @$data->timeSeries($start_day,$end_day,"all","sign")['data'][0];
//            $sql = "select count(distinct cid,uid) as total from user_events where created_at >'".$start_day."' and created_at<'".$end_day."' and type in ('review_in')";
//            $in_class_num = DB::select($sql)[0]->total;
                $in_class_num = @$data->timeSeries($start_day,$end_day,'all','class')['data'][0]['uv'];
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
                foreach ($res as $item) {
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
                $ad_s = @$data->timeSeries($start_day,$end_day,"all","server_advertise",null)['data'][0];
                $sign_s = @$data->timeSeries($start_day,$end_day,"all","sign",null,["cid = $cid"])['data'][0];
                //$push = @$data->timeSeries($start_day,$end_day,"all","send_tplmsg",null,["wxtpl_tjhg != 1"])['data'][0];
                $res = @$data->groupBy($start_day,$end_day,"play","__duration","event_arg","pv","buildin","descending",1000,null,["cid = $cid"]);
//            $sql = "select count(distinct cid,uid) as total from user_events where cid=$cid and created_at >'".$start_day."' and created_at<'".$end_day."' and type in ('review_in')";
//            $in_class_num = DB::select($sql)[0]->total;
                $in_class_num = @$data->timeSeries($start_day,$end_day,'all','class')['data'][0]['uv'];
                $total_count = 0;
                $total_duration = 0;

                foreach ($res as $item) {
                    if(!isset($item['event'])){
                        $listen_time_s="服务端返回异常";
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
        }catch (\Exception $e){
            $pv_today = '';
            $ad = '';
            $all_s = '';
            $ad_s = '';
            $push = '';
            $listen_time_s = '';
            $stay_time_s = '';
            $sign = '';
            $sign_s = '';
            $all_yesterday = '';
            $yesterday_push = '';
            $in_class_num = '';

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
            'yesterday_push'=>$yesterday_push,
            'in_class_num'=>$in_class_num,
        ];
        return view('admin.index.index', $data)
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    function login(Request $request){
        $params = $request->all();
        if(!empty($params['username']) && !empty($params['password'])){
            $user_info = Admin::where('username', $params['username'])->first();
            if(empty($user_info)){
                return view('admin.error', ['msg'=>'用户不存在']);
            }else{
                if($user_info->password != md5($params['password'])){
                    return view('admin.error', ['msg'=>'密码错误']);
                }
                // 使用jwt生成token
                // $payload = JWTFactory::make(['user_type' => 'admin', 'adminid' => $user_info->id, 'username' => $user_info->username]);
                // Session::put('token', JWTAuth::encode($payload));
                if ($user_info->user_type == 2) {
                    Session::put('admin_info', $user_info);
//                    Session::put('teacher_info', $user_info);
                    return Redirect('/admin/course_review/manage');
                } else if ($user_info->user_type == 3) {
                    Session::put('admin_info', $user_info);
//                    Session::put('anchor_info', $user_info);
//                    return Redirect('/admin/anchor');
                } else if ($user_info->user_type == 5) {
                    Session::put('admin_info', $user_info);
                    return Redirect('/admin/prize');
                } else {
                    Session::put('admin_info', $user_info);
                }
                return Redirect('/admin/index');
            }
        }
        return view('admin.index.login', ['params'=>$params]);
    }

    function logout()
    {
        Session::forget('admin_info');
        return view('admin.error', ['msg'=>'管理员已退出','url'=>'/admin/login']);
    }

    function anchorLogout()
    {
        Session::forget('anchor_info');
        return view('admin.error', ['msg'=>'主持人账号已退出','url'=>'/admin/login']);
    }

    function teacherLogout()
    {
        Session::forget('teacher_info');
        return view('admin.error', ['msg'=>'讲师账号已退出','url'=>'/admin/login']);
    }

    function error()
    {
        return view('admin.error');
    }

}
