<?php namespace app\http\controllers\api\service;

use App\CIData\Cidata;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\UserEnrollPush;
use App\Services\Crm;
use App\Models\CourseStat;
use App\Services\UserService;
use App\Services\WxWyeth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Jobs\SendTemplateMessageByOpenid;
use App\Services\CourseService;
use App\Jobs\SendTemplateMessageBySignUp;
use Log;

/**
 * 用户相关的第三方接口
 */
class UserController extends Controller
{
    protected $result = [
        'status' => 1,
        'error_msg' => '',
        'data' => [],
    ];

    public function __construct()
    {
        $this->middleware('signature');
    }

    //用户批量报名课程
    public function signCourse(Request $request)
    {
        if (!$request->has('openid')) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = 'openid is invalid';
            return response()->json($this->result);
        }
        $channel = Session::get('channel');
        $openid = $request->input('openid');
        $user = User::where(['openid' => $openid])->first();
        if (!$user) {
            $user = UserService::createByOpenId($openid, $channel);
        }
        $uid = $user->id;
        $cids = config('course.sign_course_ids');
        if ($cids) {
            $source = $request->input('key');
            //CIData统计报名
            Cidata::init(config('oneitfarm.appkey'));
            foreach ($cids as $cid) {
                $model = new UserCourse();
                //检查课程是否能够报名
                $course_status = $model->checkUserSignStatus($cid, $uid);
                if ($course_status) {
                    $model->uid = $uid;
                    $model->cid = $cid;
                    $model->channel = $source;
                    $model->save();
                    Cidata::sendEvent($uid, $user->channel, null, 'sign', ['cid' => $cid, 'wyeth_channel' => $source]);
                }
            }
        }

        return response()->json($this->result);
    }

    // 用户批量报名课程
    public function signMultCourse(Request $request)
    {
        if (!$request->has('openid') || !$request->has('cids')) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = 'params is invalid';
            return response()->json($this->result);
        }

        // save user data
        $channel = Session::get('channel');
        $openid = $request->input('openid');
        $user = User::where(['openid' => $openid])->first();
        if (!$user) {
            $user = UserService::createByOpenId($openid, $channel);
        }

        // save user sign course data
        $cids = $request->input('cids');
        $cids_arr = explode(',', $cids);
        $uid = $user->id;

        $valuableIds = [];
        //CIData统计报名
        Cidata::init(config('oneitfarm.appkey'));

        //获取最后一条的推送时间
        $last_push = UserEnrollPush::where('uid', $uid)->orderBy('id', 'desc')->first();
        $push_time = $last_push && (strtotime($last_push->push_time) > time()) ? $last_push->push_time : date('Y-m-d 00:00:00');

        foreach ($cids_arr as $cid) {
            //检查课程是否能够报名
//            $course_status = $userCourse->checkUserSignStatus($cid, $uid);
            $course = Course::where('id', $cid)->first();
            if (!$course){
                continue;
            }

            $has_user_course = UserCourse::where(['cid' => $cid, 'uid' => $uid])->first();
            if (!$has_user_course) {
                $valuableIds[] = $cid;
                $userCourse = new UserCourse();
                $userCourse->uid = $uid;
                $userCourse->cid = $cid;
                $userCourse->channel = $channel;
                $userCourse->save();
                Cidata::sendEvent($uid, $user->channel, null, 'sign', ['cid' => $cid, 'wyeth_channel' => $channel]);
            }

            //下线非河马课的课程和未到开课时间不加入推送计划中
            if ((!$course->display_status && $course->cid !== 48) || time() < strtotime($course->start_day) + 86400){
                continue;
            }

            //用户报名待推送添加数据
            //push_time +1 天
            $push_time = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($push_time)));
            if (!UserEnrollPush::where(['uid' => $uid, 'cid' => $cid])->first()){
                $push = new UserEnrollPush();
                $push->uid = $uid;
                $push->cid = $cid;
                $push->openid = $openid;
                $push->push_time = $push_time;
                $push->save();
            }

        }

        if (count($valuableIds) <= 0) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = 'params is invalid';
            return json_encode($this->result);
        }
        // 发送推荐课程模板消息
        $course = Course::find($valuableIds[0]);
        $params = CourseService::recommendCourseIdGet($user, $course);
        if ($params) {
//            Log::info('signMultCourse', [
//                'uid' => $user->id,
//                'sids' => $cids_arr,
//                'sid' => $params?$params['sign_up_course']->id : 0,
//                'rid' => $params?$params['recommend_course']->id : 0
//            ]);
            $job = (new SendTemplateMessageBySignUp($params));
            $this->dispatch($job);
        }

        return response()->json($this->result);
    }
}