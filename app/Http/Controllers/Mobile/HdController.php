<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/20
 * Time: 下午4:53
 */

namespace App\Http\Controllers\Mobile;

use App\CIData\Cidata;
use App\CIService\Account;
use App\Helpers\CacheKey;
use App\Helpers\SessionKey;
use App\Models\Activity;
use App\Models\Advertise;
use App\Models\AppConfig;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\CourseStat;
use App\Models\Courseware;
use App\Models\Invitation;
use App\Models\ShareLog;
use App\Models\User;
use App\Models\UserEvent;
use App\Models\WoaapQrcode;
use App\Repositories\HdRepository;
use App\Services\CounterService;
use App\Services\CourseReviewService;
use App\Services\CourseService;
use App\Services\Leqee;
use App\Services\WxWyeth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\CIService\Hd;
use App\Services\WoaapQrcodeService;

//中台活动页面

class HdController extends Controller
{
    
    public function index(Request $request){
        $aid = $request->input('aid');
        $scene_str = $request->input('scene_str'); //微信临时二维码场景值
        $share_str = $request->input('share_str');

        $user = Auth::user();

        $shareIndex = 0;
        if ($scene_str){
            //根据scene_str取出活动参数
            $qrcode = new WoaapQrcodeService();
            $params = $qrcode->getParamsBySceneStr($request->input('scene_str'));
            if ($params && isset($params['aid'])){
                $aid = $params['aid'];
            }
            if ($params && isset($params['shareindex'])) {
                $shareIndex = $params['shareindex'];
            }
        }

        $activity = Activity::find($aid);
        if (!$aid || !$activity){
            return '该活动不存在';
        }

        Cidata::init(config('oneitfarm.appkey'));
        if ($share_str) {
            //埋点,点击分享链接进来的
            Cidata::sendEvent($user->id, $user->channel, null, 'hd_share_str', ['aid' => $aid, 'wyeth_channel' => Session::get('channel')]);
        }else if ($scene_str){
            //埋点,扫描二维码进来的
            Cidata::sendEvent($user->id, $user->channel, null, "hd_scene_str", ['aid' => $aid, 'wyeth_channel' => Session::get('channel'), 'shareindex' => $shareIndex]);
        } else{
            //直接通过aid进来的
            Cidata::sendEvent($user->id, $user->channel, null, 'hd_aid', ['aid' => $aid, 'wyeth_channel' => Session::get('channel')]);
        }

        $shareQr = '';
        if (!$user->crm_status && $activity->crm){
            //非crm会员跳到注册页面
            //session存一下aid,hd
            if (!$share_str) {
                Session::put(SessionKey::ACTIVITY_AID, $aid);
                if (isset($params)){
                    Session::put(SessionKey::QRCODE_PARAMS, $params);
                }
                $url = config('course.register_crm');
                return redirect($url);
            } else if ($scene_str) {
                $qr = WoaapQrcode::where('scene_str', $scene_str)->first();
                $shareQr = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($qr->ticket);
            }
        } else {
            $share_str = '';
        }

        //跳转到view
        $view = $activity->view;

        $invite_num = Invitation::where('aid', $aid)->where('uid', $user->id)->count();
        $share_num = ShareLog::where('aid', $aid)->where('uid', $user->id)->count();

        if ($aid == 5 || $aid == 6) {
            $random_num = rand(0, 2);
        } else {
            $random_num = rand(0, 3);
        }


//        dd($activity->setting);
//        dd(json_decode($activity->setting)->bg);
        return view('mobile.activity.' . $view, [
            'user' => $user,
            'configs' => $activity->setting ? json_decode($activity->setting) : null,
            'invite_num' => $invite_num,
            'share_num' => $share_num,
            'share_str' => $share_str,
            'share_qr' => $shareQr,
            'aid' => $aid,
            'random' => $random_num,
            'qrInfo' => $this->getQr($aid, $user->openid, $random_num)
        ]);

    }

    private function getQr($aid, $openid, $shareIndex) {
        $params = ['aid' => $aid, 'openid' => $openid, 'shareindex' => $shareIndex];

        $qrcode_service = new WoaapQrcodeService();
        $res = $qrcode_service->addQrcode(json_encode($params));

        return $res;
    }

    public function getQrCode(Request $request) {
        $aid = $request->input('aid');
        $openId = $request->input('openid');
        $shareIndex = $request->input('shareindex');
//        if (!$aid || !$openId){
//            return $this->returnError('aid or hd invalid');
//        }
        $params = ['aid' => $aid, 'openid' => $openId, 'shareindex' => $shareIndex];

        $qrcode_service = new WoaapQrcodeService();
        $res = $qrcode_service->addQrcode(json_encode($params));

        return response()->json($res);
    }

    public function addShareLog(Request $request) {
        $user = Auth::user();
        $data = $request->all();
        $aid = $data['aid'];

        $invite_num = Invitation::where('aid', $aid)->where('uid', $user->id)->count();

        if ($invite_num > 1) {
            $isShared = Cache::get(CacheKey::ACTIVITY_SHARE_RECORD . $user->id . $aid);
            if (!$isShared) {
                $params = [
                    'title' => '分享成功！后台已经记录下你的学习成果，坐等抽奖吧！',
                    'content' => '转发成功！',
                    'odate' => date('Y-m-d'),
                    'address' => '',
                    'remark' => '点击查看详情',
                    'url' => $data['url'],
                    'openid' => trim($data['openid']),
                ];
                $wxWyeth = new WxWyeth();
                $res = $wxWyeth->pushpushCustomMessage($params, 6, false);
                Cache::put(CacheKey::ACTIVITY_SHARE_RECORD . $user->id . $aid, 1, 86400);
            }
        }

        $shareLog = new ShareLog();
        $shareLog->aid = $aid;
        $shareLog->uid = $user->id;
        $shareLog->save();
        return response()->json(array('ret' => 1));
    }
    
    public function login(Request $request){
        $aid = $request->input('aid');
        if (!$aid){
            return '该活动不存在';
        }

        $user = Auth::user();
        Cidata::init(config('oneitfarm.appkey'));
        //直接通过aid进来的
        Cidata::sendEvent($user->id, $user->channel, null, 'hd_aid', ['aid' => $aid, 'wyeth_channel' => Session::get('channel')]);

        //中台登录
        $account = new Account();
        $ci_user_token = $account->login();
        if ($ci_user_token === false){
            return '中台登录失败';
        }

        //最终跳到活动页面
        $hd = new Hd();
        return redirect($hd->getHdUrl($aid, $ci_user_token));
    }

    public function breastActivity() {
        $user = Auth::user();

        $breastMilkData = AppConfig::breastMilk(true);

        $count =  Cache::get(CacheKey::XUE_BA_CARD_USERS);
        if(!$count){
            $count = 21043;
        }

        return view('mobile.activity.breast_milk', ['user' => $user, 'breastMilkData' => $breastMilkData, 'count' => $count]);
    }

    public function activityDetail(Request $request) {
        $user = Auth::user();
        $uid = Auth::id();
        $cid = $request->input('cid');
        $userType = $user->type;

        $course_review = CourseReview::where('cid', $cid)->where('status', CourseReview::STATUS_YES)->first();
        // 课程信息
        $course = Course::find($cid);
        if (empty($course)) {
            return redirect()->back();
        }

        // 手Q用户不是crm会员时需要注册
        $browser = Session::get('browser');
        if (!$user->crm_status && $browser == 'SQ') {
            // 暂时先不跳转
            // return Redirect('/mobile/card');
        }

        //SQ用户，没关注不能看回顾
        if ($user->type == User::OPENID_TYPE_SQ && $user->subscribe_status == 0) {
            return Redirect('/mobile/attention');
        }

        //是否订阅
        $wx_wyeth = new WxWyeth();
        $is_subscribed = $wx_wyeth->getSubscribeStatus($user->openid);

        // 记录用户打开这页面的日志,即使报错也记录
        $courseStat = CourseStat::firstOrCreate(['uid' => $uid, 'cid' => $cid]);
        if ($courseStat->go_review_time == '0000-00-00 00:00:00') {
            $courseStat->go_review_time = date("Y-m-d H:i:s");
            $courseStat->save();
        }

        //判断是否有可用的回顾课程
        if (empty($course_review)) {
            $coursesReviewRecommend = CourseService::signOkCoursesRecommended($uid, $userType, 'unsignreview', 3, $cid);
            return $this->reviewError(1, ['list' => $coursesReviewRecommend]);
        }

        //广告位1
        $carouselsEnd1 = [];
        $pics1 = Advertise::getAdvertise(Advertise::POSITION_COURSE_MID, $course->brand);
        foreach ($pics1 as $index => $pic) {
            array_push($carouselsEnd1, array("img" => $pic['img'], "link" => $pic['link']));
        }
        //广告位2
        $carouselsEnd2 = [];
        $pics1 = Advertise::getAdvertise(Advertise::POSITION_COURSE_BOTTOM, $course->brand);
        foreach ($pics1 as $index => $pic) {
            array_push($carouselsEnd2, array("img" => $pic['img'], "link" => $pic['link']));
        }
//        $appConfig1 = AppConfig::where('module', 'end')->where('key', 'advertise_breast')->get()->toArray();
//        if ($appConfig1) {
//            if ($appConfig1[0]['data']['link']) {
//                $carouselsEnd1['link'] = $appConfig1[0]['data']['link'];
//            } else {
//                $carouselsEnd1['link'] = 'javascript:void(0);';
//            }
//            $carouselsEnd1['img'] = $appConfig1[0]['data']['img'];
//        }

        //推荐课程
        //三个课程一组 第一组
        $notInCid = [];
        $coursesRecommend1 = CourseController::coursesRecommend($cid);
        foreach ($coursesRecommend1 as $k => $v) {
            $notInCid[] = $v['cid'];
            $coursesRecommend1[$k]['group'] = '1';
        }

        //三个课程一组 第二组
        $coursesRecommend2 = CourseController::coursesRecommend($cid, $notInCid);
        foreach ($coursesRecommend2 as $k => $v) {
            $notInCid[] = $v['cid'];
            $coursesRecommend2[$k]['group'] = '2';
        }

        //三个课程一组 第三组
        $coursesRecommend3 = CourseController::coursesRecommend($cid, $notInCid);
        foreach ($coursesRecommend3 as $k => $v) {
            $coursesRecommend3[$k]['group'] = '3';
        }

        //合并三组课程、
        $coursesRecommend = array_merge($coursesRecommend1, $coursesRecommend2, $coursesRecommend3);

        //为推荐课程的url加上一个参数（当前访问的cid），方便做百度统计
        foreach ($coursesRecommend as $key => $value) {
            $coursesRecommend[$key]['url'] = $value['url'].'&_hw_c=hgtj'.$cid;
        }

        //点赞数量
        $reviewLikesNum = CourseController::reviewLikesNum($course);

        //是否点过赞
        $userEvent = UserEvent::where('uid', $uid)->where('cid', $cid)->where('type', 'review_like')->where('data', 'give')->first();
        if ($userEvent) {
            $isLike = true;
        } else {
            $isLike = false;
        }

        //有多少个麻麻学过
        $courseReviewStat = CourseReviewService::countAllGet($cid);

//        $courseLivingStat = Cache::remember('controller:mobile:course:end:living', 120, function () use ($cid) {
//            return CourseStat::where('cid', $cid)->where('in_class_time', '<>', '0000-00-00 00:00:00')->count();
//        });

        $mothersNum = $courseReviewStat;

        // 分享描述
        if (!$course_review['firend_title']) {
            $course_review['firend_title'] = '妈妈微课堂';
        }
        if (!$course_review['firend_subtitle']) {
            $course_review['firend_subtitle'] = '我发现了一堂好课，在成为好妈妈的路上又近了一步，一起加入好妈妈的行列吧！';
        }
        if (!$course_review['share_title']) {
            $course_review['share_title'] = '我发现了一堂好课，在成为好妈妈的路上又近了一步，一起加入好妈妈的行列吧！';
        }
        if (!$course_review['share_picture']) {
            $course_review['share_picture'] = config('course.static_url') . '/mobile/images/logo.jpg';
        }

        // xiumi 域名替换
        $course_review->guide = str_replace('img.xiumi.us', 'wyeth-xiumi.nibaguai.com', $course_review->guide);
        $course_review->desc = str_replace('img.xiumi.us', 'wyeth-xiumi.nibaguai.com', $course_review->desc);
        $qAndA = $course_review->q_and_a;
        $qUids = [];
        if ($qAndA) {
            foreach ($qAndA as $v) {
                if (isset($v['q_uid']) && $v['q_uid']) {
                    $qUids[] = $v['q_uid'];
                }
            }
        }

        $qUids = array_unique($qUids);
        $qAvatars = User::wherein('id', $qUids)
            ->whereNotNull('avatar')
            ->lists('avatar', 'id');
        if ($qAndA) {
            foreach ($qAndA as $k => $v) {
                $qAndA[$k]['q_avatar'] = '';
                if (isset($v['q_uid'])) {
                    $qUid = $v['q_uid'];
                    if (isset($qAvatars[$qUid])) {
                        $qAndA[$k]['q_avatar'] = $qAvatars[$qUid];
                    }
                }
            }
        }

        // 记录用户统计日志
        $courseStat = CourseStat::firstOrCreate(['uid' => $uid, 'cid' => $cid]);
        if ($courseStat->in_review_time == '0000-00-00 00:00:00') {
            $courseStat->in_review_time = date("Y-m-d H:i:s");
            $courseStat->save();
            CourseReviewService::countAllIncrement($cid);
        }

        //记录课程回顾日志
        $userEvent = new UserEvent;
        $userEvent->uid = $uid;
        $userEvent->cid = $cid;
        $userEvent->user_type = $userType;
        $userEvent->type = 'review_in';
        $data = json_encode(["updated_at" => time(), 'channel' => Session::get('channel')]);
        $userEvent->data = $data;
        $userEvent->save();

        //改数据。。。。
        $update_cid_arr = [301,385,46,223];
        if (in_array($cid, $update_cid_arr)){
            if ($mothersNum < 10000){
                $mothersNum += 10000;
            }
            if ($reviewLikesNum < 50){
                $reviewLikesNum = 50 + substr($reviewLikesNum,-1);
            }
        }

        // 获取活动图
        $breastMilkData = AppConfig::breastMilk(true);
        $bannerImg = [];
        $content = '';
        foreach ($breastMilkData as $index => $item) {
            if ($item['link'] && $index != 0) {
                $t = parse_url($item['link'], PHP_URL_QUERY).PHP_EOL;
                if ($t != " ") {
                    $i = explode('=', $t);
                    if (trim($i[1]) == $cid) {
                        $bannerImg[0] = $item['img'];
                        $bannerImg[1] = $item['pic'];
                        if (array_key_exists('content', $item)) {
                            $content = $item['content'];
                        }
                    }
                }
            }
        }

        $data = [
            'course' => $course,
            'course_review' => $course_review,
            'carouselsEnd1' => $carouselsEnd1,
            'carouselsEnd2' => $carouselsEnd2,
            'coursesRecommend' => $coursesRecommend,
            'is_subscribed' => $is_subscribed,
            'reviewLikesNum' => $reviewLikesNum,
            'isLike' => $isLike,
            'mothersNum' => $mothersNum,
            'qAndA' => $qAndA,
            'isCrm' => $user->crm_status,
            'reviewInId' => $userEvent->id,
            'bannerImg' => $bannerImg,
            'content' => $content
        ];

        return view('mobile.activity.activity_detail', $data);
    }

    public function S26Card(Request $request) {
        $user = Auth::user();

        $count = Cache::get(CacheKey::S26_CARD_USERS);
        if(!$count){
            $count = 21940;
        }

        $s26CardData = AppConfig::s26CardData(true);

        return view('mobile.activity.s26_card', ['user' => $user, 's26CardData' => $s26CardData, 'count' => $count]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function goodMorning(Request $request) {
        $user = Auth::user();

        $morningData = AppConfig::morningData(true);
        $todayData = [];

        foreach ($morningData as $index => $morning) {
            $course = Course::find($morning['cid']);$start_day = date($course->start_day);

            $morningData[$index]['status'] = '1';
            if (strtotime(date('y-m-d')) > strtotime($start_day)) {
                $events = UserEvent::where('uid', $user->id)->where('cid', $morning['cid'])->where('type', 'review_in')->where('created_at', 'like', '%' . $course->start_day . '%')->first();
                if ($events) {
                    $morningData[$index]['status'] = '已学';
                } else {
                    $morningData[$index]['status'] = '缺勤';
                }
            } else if (strtotime(date('y-m-d')) == strtotime($start_day)) {
                $events = UserEvent::where('uid', $user->id)->where('cid', $morning['cid'])->where('type', 'review_in')->where('created_at', 'like', '%' . $course->start_day . '%');
                if ($events) {
                    $morningData[$index]['status'] = '已学';
                } else {
                    $morningData[$index]['status'] = '打卡';
                }

//                $todayData['cid'] = $morning['cid'];
//                $todayData['img'] = $morning['img'];
//                $todayData['intro'] = $morning['introduce'];
//                $todayData['status'] = date('m/d', strtotime($start_day));
//                $todayData['start_time'] = $morning['start_time'];
//                $todayData['audio'] = CourseReview::where('cid', $morning['cid'])->first()->audio;
            } else {
                $morningData[$index]['status'] = date('m/d', strtotime($start_day));
            }
        }

        return view('mobile.activity.good_morning', ['user' => $user, 'morningData' => $morningData, 'todayData' => $todayData]);
    }

    public function columnActivity (Request $request) {
        $user = Auth::user();

        $activityData = AppConfig::where(['module' => 'activity', 'key' => 'column'])->first();

        $is_listen = $this->isListen($user);
        
        return view('mobile.activity.column_activity', [
            'user' => $user,
            'activityData' => $activityData->data,
            'is_listen' => $is_listen
        ]);
    }

    public function springSecret(Request $request) {
        $user = Auth::user();

        $invitations = Invitation::where();

        return view('mobile.activity.spring_secret', [
            'user' => $user
        ]);
    }

    /**
     * @param $user
     * @return int 0: 非会员，1：会员非首次，2：会员首次
     */
    private function isListen($user){
        $uid = $user->id;
        //表里有记录
        $res = DB::table('user_identify')
            ->where('uid', $uid)
            ->first();
        if ($res){
            return 1;
        }
        //查询是否领取会员卡
        $is_member = Leqee::isMember($user->openid);
        if ($is_member){
            DB::table('user_identify')
                ->insert([
                    'uid' => $uid,
                    'is_member' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            return 2;
        }

        return 0;
    }

    //重定向转转乐的地址
    public function draw(Request $request)
    {
        $url = (new HdRepository())->getDrawRawUrl();
        $params = $request->all();
        if ($params){
            $url .=  '&' . http_build_query($params);
        }
        return redirect($url);
    }

    public function SpringSecretNum(){
        $num = Cache::get(CacheKey::QUERY_SPRING);
        if(!$num){
            $num = 56735;
        }
        return json_encode(array('count'=>$num));
    }
}