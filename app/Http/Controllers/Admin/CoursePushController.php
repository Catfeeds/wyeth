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
use App\Models\CoursePush;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CoursePushController extends BaseController
{
    public function index(Request $request){

        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = $request->all();
        $from = !empty($params['from']) ? $params['from'] :'';
        $to = !empty($params['to']) ? $params['to'] : '';
        $type = !empty($params['status']) ? $params['status']:'all';
        $params['status_value'] = '';
        switch ($type){
            case 'all':
                $params['status_value'] = '全部';
                break;
            case 'normal':
                $params['status_value'] = '开课与回顾';
                break;
            case 'special':
                $params['status_value'] = '系统推送';
                break;
        }
        if($from && $to){
            if($type == 'normal'){
                $list = DB::table('course_push')
                    ->where('push_time','>',$from)
                    ->where('push_time','<',$to)
                    ->where('type',0)
                    ->orderBy('push_time', 'desc')
                    ->paginate($per_page);
            }elseif($type == 'special'){
                $list = DB::table('course_push')
                    ->where('push_time','>',$from)
                    ->where('push_time','<',$to)
                    ->where('type','!=',0)
                    ->orderBy('push_time', 'desc')
                    ->paginate($per_page);
            }else{
                $list = DB::table('course_push')
                    ->where('push_time','>',$from)
                    ->where('push_time','<',$to)
                    ->orderBy('push_time', 'desc')
                    ->paginate($per_page);
            }
        }elseif($type != 'all'){
            if($type == 'normal'){
                $list = DB::table('course_push')
                    ->where('type',0)
                    ->orderBy('push_time', 'desc')
                    ->paginate($per_page);
            }else{
                $list = DB::table('course_push')
                    ->where('type','!=',0)
                    ->orderBy('push_time', 'desc')
                    ->paginate($per_page);
            }
        }else{
            $list = DB::table('course_push')
                ->orderBy('push_time', 'desc')
                ->paginate($per_page);
        }

        foreach ($list as $item) {
            if ($item->type == 0){
                //正常的
                $course = DB::table('course')->where('id', $item->cid)->first();
                if ($course){
                    if($item->status == 0){
                        if($item->sign_start == 0){
                            $push_num = DB::table('user_course')->where('cid',$item->cid)->count();
                        }else{
                            $push_num = DB::table('user_course')
                                ->where('cid',$item->cid)
                                ->where('created_at','>',$item->sign_start)
                                ->where('created_at','<',$item->sign_end)
                                ->count();
                        }
                        $item->excepted = $push_num;
                    }
                    $item->course_name = $course->title;
                }else{
                    $item->course_name = '';
                    $item->excepted = '';
                }
            }else{
                //特殊推送类型
                $item->course_name = CoursePush::getTypeArray()[$item->type];
            }
        }
        return view('admin.course.course_push', ['list' => $list, 'user_info' => $user_info,'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function detail(Request $request){
        $id = $request->input('id');
        $course_push = CoursePush::find($id);
        $cid = $course_push->cid;
        $type = $course_push->type;
        $begin_time = $course_push->push_time;
        $action = $course_push->action; //cidata 的 action

        $cidata = new CIDataQuery();
        $end_time = date('Y-m-d H:i:s',strtotime($begin_time)+86400*1);
        $end = date('Y-m-d H:i:s',strtotime($begin_time)+86400*3); // 一般统计三天内打开率
        if ($type == 0){
            $data = DB::connection('mysql_read')->table('tplmsgs')
                ->where('cid',$cid)
                ->where('type', '<>', 2)
                ->where('created_at','>=',$begin_time)
                ->where('created_at','<=',$end_time);
            $all = $data->count();
            $defeat = $data->where('status',0)->count();
            $success = $all - $defeat;
            $title = '课程ID';
            // 打开人数，CIData统计
            $open_num = @$cidata->timeseries($begin_time, $end, "all", "open_tplmsg",null,["cid = $cid","wxtpl_tjhg != 1"])['data'][0];
        }else{
            $data = DB::table('tplmsgs')
                ->where('type', $type)
                ->where('created_at','>=',$begin_time)
                ->where('created_at','<=',$end_time);
            $all = $data->count();
            $defeat = $data->where('status',0)->count();
            $success = $all - $defeat;
            $title = '推送名称';
            $cid = CoursePush::getTypeArray()[$type];
            $channel = CoursePush::getTypeChannel()[$type];
            if(in_array($channel, ['wxtpl_night', 'wxtpl_fuli_draw', 'wxtpl_fuli_dtc', 'wxtpl_huiyao'])){
                $end = date('Y-m-d h:i:s',strtotime($begin_time)+86400*1); // 这个只统计一天
            }
            $open_num = @$cidata->timeseries($begin_time, $end, "all", $action, null, ["wyeth_channel = $channel"])['data'][0];
        }
        if(!empty($open_num)){
            $open_num_uv = $open_num['uv'];
            $open_num_pv = $open_num['pv'];
        }else{
            $open_num_uv = 0;
            $open_num_pv = 0;
        }
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
            <div class=\"col-md-12 col-xs-11\" style=\"margin: 10px 0 20px 0\">
                <label class=\"col-sm-3 control-label col-lg-3\">推送时间</label>
                <span>$begin_time</span>
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
                <label class=\"col-sm-12 control-label col-lg11\">【提示：外链将无法统计到打开情况哦！】</label>
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

    public function add(Request $request){
        $cid = $request->input('cid');
        $push_time = $request->input('push_time');
        $sign_start = $request->input('sign_start') ? $request->input('sign_start') : '0000-00-00 00:00:00';
        $sign_end = $request->input('sign_end') ? $request->input('sign_end') : '0000-00-00 00:00:00';

        $validate = $this->validateTime($push_time, $sign_start, $sign_end);
        if ($validate !== true){
            return $validate;
        }

        $course = Course::where("id", $cid)->first();
        if (empty($course)) {
            return $this->ajaxError('课程不存在');
        }

        $course_push = CoursePush::where('cid', $cid)->where('status', CoursePush::COURSE_PUSH_WAIT)->first();
        if ($course_push){
            return $this->ajaxError('该课程已添加到推送');
        }

        $course_push = new CoursePush();
        $course_push->cid = $cid;
        $course_push->push_time = $push_time;
        $course_push->sign_start = $sign_start;
        $course_push->sign_end = $sign_end;
        $course_push->save();

        return $this->ajaxMsg('添加成功');
    }

    public function edit (Request $request) {
        $id = $request->input('id');
        $push_time = $request->input('push_time');
        $sign_start = $request->input('sign_start') ? $request->input('sign_start') : '0000-00-00 00:00:00';
        $sign_end = $request->input('sign_end') ? $request->input('sign_end') : '0000-00-00 00:00:00';

        $validate = $this->validateTime($push_time, $sign_start, $sign_end);
        if ($validate !== true){
            return $validate;
        }

        $course_push = CoursePush::find($id);
        if (!$course_push) {
            return $this->ajaxError('推送不存在');
        }
        $course_push->push_time = $push_time;
        $course_push->save();
        $data = $request->all();
        unset($data['id']);
        DB::table('course_push')->where('id', $id)->update($data);
        return $this->ajaxMsg("更新成功", 1);
    }

    private function validateTime($push_time, $sign_start, $sign_end){
        if (strtotime($push_time) < time()){
            return $this->ajaxError('推送时间不合法');
        }

        $start = strtotime($sign_start);
        $end = strtotime($sign_end);
        if (!$start || !$end){
            return $this->ajaxError('指定报名时间不合法');
        }
        if ($start > $end){
            return $this->ajaxError('指定报名区间的开始时间大于结束时间');
        }
        return true;
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