<?php

namespace App\Http\Controllers\Mobile;

use Agent;
use App\Lib\Gateway\Gateway;
use App\Models\AppConfig;
use App\Models\Course;
use App\Models\CourseStat;
use App\Models\Courseware;
use App\Models\Estimation;
use App\Models\Message;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\SigninGameConfig;
use App\Services\CounterService;
use App\Services\WxWyeth;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Input;
use View;
use App\Services\QcloudService;
use App;

class UserLivingController extends Controller
{
    protected $package = [];

    public function __construct()
    {
        $this->middleware('subscribed', ['only' => ['index']]);

        //$this->middleware('signCourse', ['only' => ['index']]);

        //微信jssdk参数

        $wxWyeth = new WxWyeth();
        // $this->package = $wxWyeth->getSignPackage();

        // openid token 指到所有的模板中
        $this->openid = Session::get('openid');
        $this->token = Session::get('token');
        View::share('openid', $this->openid);
        View::share('token', $this->token);
    }

    private function verification()
    {
        //
    }

    /**
     * 直播首页
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $openid = $this->openid;

        $token = $this->token;
        $cid = $request->request->get('cid');
        $data = [];
        $user = Auth::user();
        if (!$user) {
            return $this->showError('User is not exist');
        }

        $uid = $user->id;
        // $wxWyeth = new WxWyeth();
        // $is_subscribed = $wxWyeth->getSubscribeStatus($user->openid);

        //course
        $course = Course::find($cid);
        if (!$course) {
            return $this->showError('课程不存在');
        }
        if ($course->display_status != 1) {
            return $this->showError('课程图片不存在, 管理员要上传了');
        }
        if ($course->status == Course::COURE_REG_STATUS) {
            return Redirect('/mobile/reg?cid=' . $cid);
        } else if ($course->status == Course::COURSE_END_STATUS) {
            return Redirect('/mobile/end?cid=' . $cid);
        }

        // user_course
        $user_course = UserCourse::where('uid', $uid)->where('cid', $cid)->first();
        if (!$user_course) {
            return Redirect('/mobile/reg?cid=' . $cid);
        }

        //cid为332和40这两节课跳转到新的地址
        if ($cid == 40 || $cid == 332) {
            header("Location:  http://mudu.tv/?c=activity&a=live&id=35451");
            exit;
        }

        $platform = Agent::platform(); //设备系统
        $version = Agent::version($platform); //设备版本号
        // course stat 处理直播中的用户来源
        $courseStat = CourseStat::firstOrCreate(['uid' => $uid, 'cid' => $cid]);
        $courseStat->channel = Session::get('channel');

        $checkEdit = false;
        if (empty($courseStat->channel)) {
            $courseStat->channel = Session::get('channel');
            $checkEdit = true;
        }
        if (empty($courseStat->device)) {
            $courseStat->device = $platform . ' ' . $version;
            $checkEdit = true;
        }
        if ($checkEdit) {
            $courseStat->save();
        }

        // hls url
        $stream = config('course.aodianyun_stream_pre') . $cid;
        $hlsUrl = config('course.aodianyun_play_url') . "/{$stream}.m3u8";
        // 腾讯云拉流地址
        $qcloud = new QcloudService;
        $bizid = config('course.qcloud_bizid');
        $environment = App::environment();
        $streamId = 'wyeth_'.$environment.'_'.$cid;
        $qcloudUrl = $qcloud->getPlayUrl($bizid, $streamId);
        $data['video'] = [
            'hlsUrl' => $hlsUrl,
            'site_id' => config('record.mz_siteid'),
            'static_url' => config('course.static_url'),
            'isPublish' => false,
            'appUrl' => config('app.url'),
            'qcloudUrl' => $qcloudUrl,
        ];
        $startTime = "$course->start_day $course->start_time";
        $startTime = strtotime($startTime);
        $countdownSeconds = time() - $startTime;
        if ($course->extend && isset($course->extend->is_switch_audio_source)) {
            $isSwitchAudioSource = $course->extend->is_switch_audio_source;
        } else {
            $isSwitchAudioSource = 'no';
        }
        $data['course'] = [
            'id' => $cid,
            'signin_status' => $course->signin_status,
            'teacher_avatar' => $course->teacher_avatar,
            'start_time' => $course->start_time,
            'start_day' => $course->start_day,
            'reply_notify_word' => $course->reply_notify_status ? config('course.reply_notify_word') : '',
            'flowers' => $course->flowers,
            'teacher_avatar' => $course->teacher_avatar,
            'teacher_name' => $course->teacher_name,
            'teacher_hospital' => $course->teacher_hospital,
            'teacher_position' => $course->teacher_position,
            'audio' => $course->audio,
            'startTime' => $startTime,
            'playStatus' => $course->play_status,
            'countdownSeconds' => $countdownSeconds,
            'type' => $course->type,
            'isSwitchAudioSource' => $isSwitchAudioSource,
        ];
        $data['user'] = [
            'openid' => $openid,
            'uid' => $uid,
            'token' => $token->get(),
            'user_type' => 1,
        ];

        //flash
        $data['coursewares'] = Courseware::where('cid', $cid)->get();
        if (!$data['coursewares']) {
            return $this->showError('课程图片不存在, 管理员要上传了');
        }

        //分享
        $data['share'] = [
            'living_firend_title' => '妈妈微课堂',
            'living_firend_subtitle' => '我发现了一堂好课，在成为好妈妈的路上又近了一步，一起加入好妈妈的行列吧！',
            'living_share_title' => '我发现了一堂好课，在成为好妈妈的路上又近了一步，一起加入好妈妈的行列吧！',
            'living_share_picture' => config('course.static_url') . '/mobile/images/logo.jpg',
        ];
        if ($course->living_firend_title) {
            $data['share']['living_firend_title'] = $course->living_firend_title;
        }
        if ($course->living_firend_subtitle) {
            $data['share']['living_firend_subtitle'] = $course->living_firend_subtitle;
        }
        if ($course->living_share_title) {
            $data['share']['living_share_title'] = $course->living_share_title;
        }
        if ($course->living_share_picture) {
            $data['share']['living_share_picture'] = $course->living_share_picture;
        }

        //点击播放按钮浮层图片
        $flatingLayer = AppConfig::where('module', 'index')->where('key', 'flatingLayer')->orderBy('displayorder')->get()->pluck('data')->toArray();
        $scorePicture = AppConfig::where('module', 'index')->where('key', 'scorePicture')->orderBy('displayorder')->get()->pluck('data')->toArray();
        if (!$flatingLayer) {
            $flatingLayer = [];
        }
        if (!$scorePicture) {
            $scorePicture[0]['img'] = '';
        }
        $user = Auth::user();
        $userStatus = '';
        $userStatusBaiduStatistics = '';
        if ($user->subscribe_status == 0 && $user->crm_hasShop == 0) {
            $userStatus = USER::USERTYPE_NN;
            $userStatusBaiduStatistics = '没有关注，无主';
        } else if ($user->subscribe_status == 1 && $user->crm_hasShop == 0) {
            $userStatus = USER::USERTYPE_SN;
            $userStatusBaiduStatistics = '有关注，无主';
        } else if ($user->subscribe_status == 0 && $user->crm_hasShop == 1) {
            $userStatus = USER::USERTYPE_NH;
            $userStatusBaiduStatistics = '没有关注，有主';
        } else if ($user->subscribe_status == 1 && $user->crm_hasShop == 1) {
            $userStatus = USER::USERTYPE_SH;
            $userStatusBaiduStatistics = '有关注，有主';
        }
        $flatingLayer = collect($flatingLayer)->first(function ($key, $value) use ($userStatus) {
            return $value['attr'] == $userStatus;
        });
        $scorePicture = collect($scorePicture)->first(function ($key, $value) use ($userStatus) {
            return $value['attr'] == $userStatus;
        });
        //判断是否为分享页面
        if (isset($_GET['signStr'])) {
            $data['show_sign'] = 1;
        } else {
            $data['show_sign'] = 2;
        }
        $data['signinGameConfig'] = $signinGameConfig = SigninGameConfig::where('cid', '=', $cid)->first();
        $data['flatingLayer'] = $flatingLayer;
        $data['scorePicture'] = $scorePicture['img'];
        $data['userStatusBaiduStatistics'] = $userStatusBaiduStatistics;
        return view('mobile.course.boardcast', $data);
    }

    /**
     * 送花
     * @param Request $request
     * @return mixed
     */
    public function presentFlower(Request $request)
    {
        $openid = $this->openid;

        $token = $this->token;
        $cid = $request->request->get('cid');
        $data = [];

        $user = Auth::user();
        if (!$user) {
            return $this->showAjaxError("User is not exist");
        }

        $uid = $user->id;

        $user_course = UserCourse::where('uid', $uid)->where('cid', $cid)->first();
        if (!$user_course) {
            return $this->showAjaxError("user_course is not exist");
        }

        $course = Course::where('id', $cid)->where('display_status', 1)->first();
        if (!$course) {
            return $this->showAjaxError("course is not exist");
        }

        $flowerNum = Message::where('cid', $cid)->where('author_id', $uid)->where('type', Message::TYPE_PRESENT_FLOWER)->count();
        if ($flowerNum >= 5) {
            return $this->showAjaxError("你已经献花五次了", 2);
        }

        $name = $user->nickname;
        if ($user->avatar) {
            $avatar = $user->avatar;
        } else {
            $avatar = env('STATIC_URL') . '/mobile/img/default_header.png';
        }

        $message = new Message();
        $message->cid = $cid;
        $message->author_id = $uid;
        $message->author_type = User::TYPE_USER;
        $message->type = Message::TYPE_PRESENT_FLOWER;
        $message->content = "";
        $message->source_id = 0;
        $message->source_author_id = 0;
        $message->display = Message::DISPLAY_YES;
        $message->state = Message::NORMAL;
        $message->save();

        // 更新鲜花数
        $course->increment('flowers');

        \App\Models\UserEvent::create(['uid' => $uid,
            'user_type' => User::TYPE_USER,
            'cid' => $cid,
            'type' => 'presentFlower',
        ]);

        // 向ws发消息
        $chatMessage = [
            'type' => 'say',
            'message_type' => 'presentFlower',
            'cid' => $cid,
            'user_id' => $user->id,
            'author_id' => $user->id,
            'name' => $name,
            'avatar' => $avatar,
            'time' => date('Y-m-d H:i:s'),
        ];
        $room_id = get_room_id(config('course.chat_channel'), $cid);
        Gateway::sendToGroup($room_id, json_encode($chatMessage));

        return $this->showAjax('ok');
    }

    /**
     * 直播结束后主持人提交触后用户评论，处理返回数据
     * @param Request $request
     * @return mixed
     */
    public function processEstimation(Request $request)
    {
        $openid = $this->openid;

        $token = $this->token;
        $cid = $request->request->get('cid');
        $mark = $request->request->get('mark');

        $content = $request->get('content');

        $user = Auth::user();
        if (!$user) {
            return $this->showAjaxError("User is not exist");
        }
        $uid = $user->id;

        $user_course = UserCourse::where('uid', $uid)->where('cid', $cid)->first();
        if (!$user_course) {
            return $this->showAjaxError("user_course is not exist");
        }

        $course = Course::where('id', $cid)->where('display_status', 1)->first();
        if (!$course) {
            return $this->showAjaxError("course is not exist");
        }

        $count = Estimation::where('cid', $cid)->where('uid', $uid)->count();

        if ($count > 0) {
            return $this->showAjaxError("error,has been estimation", 1);
        }

        $estimation = new Estimation();
        $estimation->cid = $cid;
        $estimation->uid = $uid;
        $estimation->mark = $mark;
        $estimation->content = $content;
        $estimation->save();

        return $this->showAjax('ok');
    }

    /**
     * @description  记录用户访问网络状态 2G/3G/4G/WIFI
     * @param cid type
     * @return mixed
     */
    public function setUserNetWorkType(Request $request)
    {
        $type = $request->input('type');
        $cid = $request->input('cid');
        $user = Auth::user();
        $uid = $user->id;
        $courseStat = CourseStat::where(['cid' => $cid, 'uid' => $uid])->first();
        $device = $courseStat->device;
        if (!strpos($device, ',')) {
            $courseStat->device = $device . ',' . $type;
        }
        $courseStat->save();
        if ($courseStat) {
            return $this->showAjax('ok');
        } else {
            return $this->showAjaxError('error');
        }
    }

    public function getPlayStatus(Request $request)
    {
        $cid = $request->input('cid');
        $course = Course::find($cid);
        $playStatus = $course->play_status;
        return $playStatus;
    }

    public function qcloudTest()
    {
        $data = [];
        return view('mobile.course.qcloudTest', $data);
    }
}
