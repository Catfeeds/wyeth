<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/20
 * Time: 下午7:42
 */

namespace App\Http\Controllers\Admin;

use App\Jobs\getTplData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Redirect;


//给运营的查询接口

class QueryController extends Controller
{
    public function index(){
        //$params = Request::all();
        $info = '无信息';
        $tpl_info=storage_path()."/WeekData/tpl_info.txt";
        if(file_exists($tpl_info)){
            $fp = fopen($tpl_info,'r');
            $str = fread($fp,filesize($tpl_info));
            $info = $str;
        }
        $state_path = storage_path() . "/WeekData/tpl_state.txt";
        $state = 0;
        if(file_exists($state_path)){
            $fp = fopen($state_path,'r');
            $str = fread($fp,filesize($state_path));
            if (intval(trim($str)) == '1'){
                $state = 1;
            }
        }
        return view('admin.course.tplmsg',['info' => $info,'state' => $state])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }
    //这个是后台页面的查询接口,以前接口保留
    public function tplmsg2(Request $request){
        $cids=$request->input('cid');
        $start=$request->input('from');
        $end=$request->input('to');
        $params=$request->all();
        $type = 0;
        $tpl_1 = 0;
        $auto = false;
        // 0,3 type!=2  1: type =1 4: type =4
        if(isset($params['hg']) && !isset($params['kk'])){
            $type = 4;
        }elseif(!isset($params['hg']) && isset($params['kk'])){
            $type = 1;
        }
        if(isset($params['11'])){
            $tpl_1 = 1;
        }

        $from = !empty($params['from']) ? $params['from'] : null;
        $to = !empty($params['to']) ? $params['to'] : null;
        if ($from === null || $to === null || (strtotime($start) > strtotime($end))){
            return view('admin.error',['msg' => '请填写正确的日期']);
        }
        if ($cids && $start && $end && !isset($params['auto']))
        {
            $cid = $params['cid'];
            $week_new =explode(',',$cid);
            foreach($week_new as $v) {
                if (!is_numeric(trim($v))){
                    return view('admin.error',['msg' => '请检查推送课程id是否有误']);
                }
            }
        }
        else {
            if(isset($params['auto'])){
                $auto = true;
            }else{
                return view('admin.error',['msg' => '请填写完整的信息']);
            }
        }
        $state_path = storage_path() . "/WeekData/tpl_state.txt";
        if(file_exists($state_path)){
            $fp = fopen($state_path,'r');
            $str = fread($fp,filesize($state_path));
            if ($str == '1'){
                fclose($fp);
                return view('admin.error',['msg' => '上次的数据正在生成，请稍候']);
            }
        }
        $this->dispatch((new getTplData($params['from'],$params['to'],$cids,$type,$tpl_1,$auto)));
        // 到队列执行有一段时间，在此之前进行状态控制
        $state_path = storage_path()."/WeekData/tpl_state.txt";
        file_put_contents($state_path, '1');
        return view('admin.error',['msg' => '可能会花几分钟时间，请稍候。。。']);
    }

    //查推送 成功/失败 人数
    public function tplmsg(Request $request){
        $cid = $request->input('cid');
        $type = $request->input('type');
        $start = $request->input('start');
        $end = $request->input('end');

        $table = DB::connection('mysql_read')->table('tplmsgs');

        if ($cid){
            $table = $table->where('cid', $cid);
        }
        if ($type){
            $table = $table->where('type', $type);
        }
        if ($start){
            $table = $table->where('created_at', '>', date('Y-m-d H:i:s', strtotime($start)));
        }
        if ($end){
            $table = $table->where('created_at', '<', date('Y-m-d H:i:s', strtotime($end)));
        }

        $success_table = clone $table;
        $success = $success_table->where('status', 1)->count();
        echo "推送成功: $success <br>";

        $fail_table = clone $table;
        $fail = $fail_table->where('status', 0)->count();
        echo "推送失败: $fail <br>";
        $total = $success + $fail;
        echo "总推送数: $total <br>";
    }



    //查一段时间的成功推送人数 精确到每个课程
    public function tplmsgCourse(Request $request){
        $start = $request->input('start');
        $end = $request->input('end');

        $table = DB::connection('mysql_read')
            ->table('tplmsgs')
            ->whereIn('type', [1, 4]);

        if ($start){
            $table = $table->where('created_at', '>', date('Y-m-d H:i:s', strtotime($start)));
        }
        if ($end){
            $table = $table->where('created_at', '<', date('Y-m-d H:i:s', strtotime($end)));
        }
        echo "$start - $end <br>";
        echo "课程id 推送成功人数 <br>";

        $clone_table = clone $table;
        $cids = $clone_table->select(DB::raw('DISTINCT cid'))->get();
        foreach ($cids as $item){
            $cid = $item->cid;
            $clone_table = clone $table;
            $count = $clone_table->where('cid', $cid)->where('status', 1)->count();
            echo "$cid $count <br>";
        }
    }

    //根据cid导出听课的openid
    public function listenOpenid(Request $request){
        $cid = $request->input('cid');
        $start = $request->input('start');
        $end = $request->input('end');

        if (!$cid){
            die('no cid');
        }

        $user_event = DB::connection('mysql_read')
            ->table('user_events')
            ->where('cid', $cid)
            ->whereIn('type', ['review_in', 'review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause']);
        if ($start){
            $user_event = $user_event->where('created_at', '>', date('Y-m-d H:i:s', strtotime($start)));
        }
        if ($end){
            $user_event = $user_event->where('created_at', '<', date('Y-m-d H:i:s', strtotime($end)));
        }

        $users = $user_event->select(DB::raw('DISTINCT uid'))->get();

        foreach ($users as $item){
            $uid = $item->uid;
            $user = DB::connection('mysql_read')
                ->table('user')
                ->find($uid);
            $openid = $user->openid;
            echo "$openid<br>";
        }
        die();
    }
    public function export(){
        $state_path = storage_path() . "/WeekData/tpl_status.txt";
        if(file_exists($state_path)){
            $fp = fopen($state_path,'r');
            $str = fread($fp,filesize($state_path));
            if ($str == '1'){
                fclose($fp);
                return view('admin.error',['msg' => '上次的数据正在生成，请稍后']);
            }
        }
        $file_name=storage_path()."/WeekData/Tpl.csv";
        if(!file_exists($file_name)){
            return view('admin.error',['msg' => '无数据']);
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        readfile($file_name);
    }
}