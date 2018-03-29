<?php namespace App\Http\Controllers\Api;

use App\Services\Crm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserFriend;
use App\Models\UserCourse;
use Auth;

class UserController extends Controller
{
    protected $result = [
        'status' => 1,
        'error_msg' => '',
        'data' => []
    ];

    function __construct()
    {
        $this->middleware('jwt.auth');
    }

    //用户的课程表信息
    function course(Request $request)
    {
        if($request->has('uid')){
            $uid = $request->input('uid');

            $listen_num = UserCourse::where('uid',$uid)->count();
            $listen_time = UserCourse::where('uid',$uid)->sum('listen_time');

            //查询好友排名
            $rank = UserCourse::getListenTime($uid);

            $this->result['data'] = [
                'listen_num' => $listen_num,
                'listen_time' => $listen_time,
                'rank' => $rank,
            ];
        }else{
            $this->result['status'] = 0;
            $this->result['error_msg'] = 'params invalid';
            return response()->json($this->result);
        }

        return response()->json($this->result);
    }

    //查询用户的好友
    function friend(Request $request)
    {
        if (!$request->has('cid')) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = 'params invalid';
            return response()->json($this->result);
        }

        $uid = Auth::id();
        $friend_uids = UserFriend::where('from_uid', $uid)->lists('to_uid');
        if ($friend_uids) {
            $cid = $request->input('cid');
            $uids = UserCourse::whereIn('uid', $friend_uids)->where('cid', $cid)->lists('uid')->toArray();
            if ($uids) {
                $users = User::whereIn('id', array_unique($uids))->select('id', 'nickname', 'avatar')->get();
                $this->result['data'] = $users;
            }
        }

        return response()->json($this->result);
    }

    //查询用户是否参与hounian课程
    function signHounian()
    {
        $sign_status = 1; //都报名了
        $uid = Auth::id();
        $hounian_end_day = config('course.hounian_end_day');
        if (strtotime($hounian_end_day) > time()) { //活动没有结束
            $courses = UserCourse::where('uid', $uid)->lists('cid')->toArray();
            if ($courses) {
                $sign_course_ids = config('course.sign_course_ids');
                $intersect_arr = array_intersect($sign_course_ids, $courses); //交集
                $diff_arr = array_diff($sign_course_ids, $intersect_arr); //差集
                $sign_status = empty($diff_arr) ? 1 : 0;
            }else{
                $sign_status = 0;//没有报名
            }
        }

        $this->result['data']['sign_status'] = $sign_status;

        return response()->json($this->result);
    }

}