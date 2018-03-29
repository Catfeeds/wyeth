<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/12
 * Time: 下午3:01
 */

namespace App\Http\Controllers\Admin;


use App\CIService\CIDataQuery;
use App\Models\Course;
use App\Models\UserEnrollPush;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HuiYaoCoursePushController extends BaseController
{
    public function index(Request $request){
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = $request->all();
        $from = !empty($params['from']) ? $params['from'] :'';
        $to = !empty($params['to']) ? $params['to'] : '';
        $cid = !empty($params['cid']) ?$params['cid'] :'';
        $params['from'] = $from;
        $params['to'] = $to;
        if($from && $to){
            if((strtotime($from) - strtotime($to)) > 0){
                return view('admin.error',['msg' => '结束时间不能小于开始时间']);
            }
            if((strtotime($to) - strtotime($from)) > 10*86400){
                return view('admin.error',['msg' => '查询不能大于10天']);
            }
            if($cid){
                $list = DB::table('user_enroll_push')
                    ->where('push_time','>',$from)
                    ->where('push_time','<',$to)
                    ->where('cid',$cid)
                    ->where('status',1)
                    ->groupby('cid')
                    ->paginate($per_page);
            }
            else{
                $list = DB::table('user_enroll_push')
                    ->where('push_time','>',$from)
                    ->where('push_time','<',$to)
                    ->where('status',1)
                    ->groupby('cid')
                    ->paginate($per_page);
            }
        }elseif ($cid){
                $list = DB::table('user_enroll_push')
                    ->where('cid',$cid)
                    ->where('status',1)
                    ->groupby('cid')
                    ->paginate($per_page);
        }else{
            $list = DB::table('user_enroll_push')
                ->where('status',1)
                ->groupby('cid')
                ->paginate($per_page);
        }

        foreach ($list as $item) {
            $course = DB::table('course')->where('id', $item->cid)->first();
            $item->id = $item->cid;
            if ($course){
                if($from && $to){
                    // 待推送
                    $wait_num = DB::table('user_enroll_push')
                        ->where('cid',$item->cid)
                        ->where('push_time','>',$from)
                        ->where('push_time','<',$to)
                        ->where('status',0)
                        ->count();

                    // 已推送
                    $push_num = DB::table('user_enroll_push')
                        ->where('cid',$item->cid)
                        ->where('push_time','>',$from)
                        ->where('push_time','<',$to)
                        ->where('status',1)
                        ->count();
                }else{
                    // 待推送
                    $wait_num = DB::table('user_enroll_push')
                        ->where('cid',$item->cid)
                        ->where('status',0)
                        ->count();
                    // 已推送
                    $push_num = DB::table('user_enroll_push')
                        ->where('cid',$item->cid)
                        ->where('status',1)
                        ->count();
                }
                $item->wait_num = $wait_num;
                $item->excepted = $push_num;
                $item->course_name = $course->title;
                }else{
                    $item->course_name = '';
                    $item->excepted = '';
                }
        }
        return view('admin.course.huiyao_course_push', ['list' => $list, 'user_info' => $user_info,'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function detail(Request $request){
        $cid = $request->input('cid');
        $cidata = new CIDataQuery();
        $from = $request->input('from');
        $to = $request->input('to');
        if($from && $to){
            if((strtotime($to) - strtotime($from)) > 10*86400){
                $info = "统计时间过长，不能大于10天";
                return response()->json($info);
            }
            $end = strtotime($to);
            $begin = strtotime($from);
            $begin_time = $from;
            $data = DB::connection('mysql_read')->table('user_enroll_push')
                ->where('cid',$cid)
                ->where('push_time','>=',$begin_time)
                ->where('push_time','<=',$to);
            $all = $data->where('status','1')->count();
            $defeat = $data->where('status','-1')->count();
            $success = $all - $defeat;
            $title = '课程ID';
            // 打开人数
            $open_num = @$cidata->timeseries($begin, $end, "all", "open_tplmsg",null,["cid = $cid","template_id = 4"])['data'][0];
        }else{
            $end = time();
            $begin_time = date('Y-m-d',$end - 86400 * 3);

            $data = DB::connection('mysql_read')->table('user_enroll_push')
                ->where('cid',$cid)
                ->where('push_time','>=',$begin_time);
            $all = $data->where('status','1')->count();
            $defeat = $data->where('status','-1')->count();
            $success = $all - $defeat;
            $title = '课程ID';
            // 打开人数，CIData统计,只统计3天内的打开率
            $end = time();
            $begin = $end - 86400 * 3;
            $open_num = @$cidata->timeseries($begin, $end, "all", "open_tplmsg",null,["cid = $cid","template_id = 4"])['data'][0];
        }

        if(!empty($open_num)){
            $open_num_uv = $open_num['uv'];
            $open_num_pv = $open_num['pv'];
        }else{
            $open_num_uv = 0;
            $open_num_pv = 0;
        }
        /*
            $sql = "select count(DISTINCT uid) as total from user_events WHERE cid='$cid' and created_at>'$begin_time' and type='review_in'";
            $open_num_uv = DB::select($sql)[0]->total;
            $sql = "select count(id) as total from user_events WHERE cid='$cid' and created_at>'$begin_time' and type='review_in'";
            $open_num_pv = DB::select($sql)[0]->total;
        */
        if($all>0){
            $success_rate = number_format($success*100/$all,2)."%";
        }else{
            $success_rate = 0;
        }
        if(is_numeric($open_num_uv) && $success>0){
            $open_rate = number_format(100*$open_num_uv/$success,2)."%";
        }else{
            $open_rate ='0';
        }
        $html ="<div class=\"col-md-12 col-xs-11\" style=\"margin: 10px 0 0 0\">
                <label class=\"col-sm-3 control-label col-lg-3\">$title</label>
                <span>$cid</span>
            </div>
            <div class=\"col-md-12 col-xs-11\" style=\"margin: 10px 0 0 0\">
                <label class=\"col-sm-3 control-label col-lg-3\">总推送</label>
                <span>$all</span>
            </div>
            <div class=\"col-md-12 col-xs-11\" style=\"margin: 10px 0 0 0\">
                <label class=\"col-sm-3 control-label col-lg-3\">推送成功</label>
                <span>$success</span>
                <span style='margin-left: 30px'>成功率：$success_rate</span>
            </div>
            <div class=\"col-md-12 col-xs-11\" style=\"margin: 10px 0 20px 0\">
                <label class=\"col-sm-3 control-label col-lg-3\">推送失败</label>
                <span>$defeat</span>
            </div>
            <div class=\"col-md-12 col-xs-11\" style=\"margin: 10px 0 0 0\">
                <label class=\"col-sm-3 control-label col-lg-3\">打开</label>
                <span>pv：$open_num_pv</span>
                <span style='margin-left: 30px'>uv：$open_num_uv</span>
            </div>
            <div class=\"col-md-12 col-xs-11\" style=\"margin: 10px 0 0 0\">
                <label class=\"col-sm-3 control-label col-lg-3\">打开率</label>
                <span>$open_rate</span>
            </div>
            ";
        return response()->json($html);
    }

    public function delete (Request $request) {
        $id = $request->input('id');

        $course_push = CoursePush::find($id);
        if (!$course_push) {
            return $this->ajaxError('推送不存在');
        }

        DB::table('course_push')->where("id", $id)->delete();

        return $this->ajaxMsg("删除成功", 1);
    }

}