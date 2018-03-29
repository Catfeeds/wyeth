<?php

namespace App\Http\Controllers\Mobile;

use App\CIData\Cidata;
use App\CIService\Account;
use App\CIService\Hd;
use App\Helpers\SessionKey;
use App\Helpers\WyethUtil;
use App\Http\Controllers\Wyeth\PageController;
use App\Jobs\SendTemplateMessage;
use App\Models\Activity;
use App\Models\AppConfig;
use App\Models\Course;
use App\Models\CourseCat;
use App\Models\CourseReview;
use App\Models\CourseReviewQuestions;
use App\Models\CourseStat;
use App\Models\Courseware;
use App\Models\Invitation;
use App\Models\Tag;
use App\Models\CourseTag;
use App\Models\User;
use App\Models\SearchRecord;
use App\Models\UserEvent;
use App\Models\UserCourse;
use App\Models\RecommendCourse;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;
use App\Services\CounterService;
use App\Services\CourseReviewService;
use App\Services\CourseService;
use App\Services\MqService;
use App\Services\WxWyeth;
use App\Services\UserEventService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;
use Cache;
use App\Jobs\SendTemplateMessageBySignUp;
use Log;
use Orzcc\Opensearch\Sdk\CloudsearchClient;
use Orzcc\Opensearch\Sdk\CloudsearchSearch;
use Orzcc\Opensearch\Sdk\CloudsearchDoc;

use App\Services\Crm;

class CourseController extends Controller
{
    protected $package = [];

    public function __construct()
    {
        $this->middleware('subscribed', ['only' => ['living', 'card']]);

        $this->middleware('signCourse', ['only' => ['living']]);

        $this->middleware('courseHot', ['only' => ['reg', 'living', 'end']]);

        // $this->middleware('loginCrm', ['only' => ['end']]);

        //微信jssdk参数
        // $wxWyeth = new WxWyeth();
        // $this->package = $wxWyeth->getSignPackage();

        // openid token 指到所有的模板中
        $this->openid = Session::get('openid');
        $this->token = Session::get('token');
        $this->encOpenid = $this->createStrByOpenid($this->openid);
        View::share('openid', $this->openid);
        View::share('token', $this->token);
        // View::share('package', $this->package);
    }

    //最新课程
    public function new_course()
    {
        $user = Auth::user();
        $user_type = $user->type;
        // 离今天最近的开课
        $course = Course::where('display_status', 1)
            ->where('start_day', '>=', date('Y-m-d'))
            ->whereIn('user_type', [0, $user_type])
            ->orderBy('start_day', 'asc')
            ->first();
        if ($course) {
            $status = $course->status;
            $cid = $course->id;
            if ($status == 1) {
                $status_str = "reg";
            } else if ($status == 2) {
                $status_str = "living";
            } else {
                $status_str = "end";
            }

            return redirect('/mobile/' . $status_str . '?cid=' . $cid);
        }
    }

    // 非惠氏crm用户－报名成功页面
    public function sign_ok(Request $request)
    {
        $uid = $request->input('uid');
        $cid = $request->input('cid');
        $user = Auth::user();

        $u = new User();
        $user = $u->getUserInfo($user);
        $course = Course::where(['id' => $cid, 'display_status' => 1])->first();

        return view('mobile.course.user_sign_ok', ['user' => $user, 'course' => $course, 'user_type' => $user->type]);
    }

    // 惠氏crm用户－报名成功页面
    public function course_ok(Request $request)
    {
        $user = Auth::user();
        $uid = $request->input('uid');
        $cid = $request->input('cid');
        $u = new User();
        $user = $u->getUserInfo($user);
        $course = Course::where(['id' => $cid, 'display_status' => 1])->first();

        return view('mobile.course.course_ok', ['user' => $user, 'course' => $course, 'user_type' => $user->type]);
    }

    /**
     * 会员卡注册页
     * @param Request $request
     * @return mixed
     */
    public function card(Request $request)
    {
        if (Session::get('browser') == 'WXBrowser') {
            //重定向到大平台注册页面,回调地址为mobile/crmCallback
            //写入session
            if ($request->has('redirect')) {
                $redirect = $request->input('redirect');
                Session::put('crm_redirect', $redirect);
            }
            $courseIdStr = $request->input('cid');
            Session::put('crm_cid_str', $courseIdStr);

            $url = config('course.register_crm');
            return redirect($url);
        }

        $user = Auth::user();
        $uid = $user->id;
        $courseIdStr = $request->input('cid');
        $courseIds = explode('.', $courseIdStr);
        $cid = current($courseIds);

        $user = User::where('id', $uid)->first();
        $course = Course::where(['id' => $cid, 'display_status' => 1])->first();
        if ($request->has('redirect')) {
            $redirect = $request->input('redirect');
        } else {
            $redirect = '';
        }

        return view('mobile.course.card', ['user' => $user, 'course' => $course, 'courseIdStr' => $courseIdStr, 'redirect' => $redirect]);
    }

    /**
     * 单节课程报名页
     * @param Request $request
     * @return string|View
     */
    public function reg(Request $request)
    {
        if ($request->has('cid')) {
            //课程信息
            $cid = $request->input('cid');
            $course = Course::where(['id' => $cid, 'display_status' => 1])->first();
            if (!$course) {
                return '课程不存在';
            }
            if ($course->status == Course::COURSE_END_STATUS) {
                return Redirect('/mobile/end?cid=' . $cid);
            }

            //用户信息
            $uid = Auth::id();
            $user = Auth::user();
            $is_signed = UserCourse::where(['uid' => $uid, 'cid' => $cid])->count();
            // 已报名并且课程是直播中，直接进入直播课堂
            if ($is_signed && $course->status == Course::COURSE_LIVING_STATUS) {
                return Redirect('/mobile/living?cid=' . $cid);
            }

            $course = $course->toArray();
            //报名信息
            $course['sign_num'] = CourseService::reg($cid);
            if ($cid == 45) {
                $course['sign_num'] = $course['sign_num'] + 2000;
            }

            //剩余报名人数
            $course['left_sign_num'] = $course['sign_limit'] - $course['sign_num'] ?: 0;

            //适用阶段
            $course['stage_from'] = $stageFrom = $this->returnStage($course['stage_from']);
            $course['stage_to'] = $stageTo = $this->returnStage($course['stage_to']);

            //用户信息
            $u = new User();
            $userinfo = $u->getUserInfo($user);
            $user_type = $userinfo->type;

            $is_shared = 0;
            if ($is_signed) {
                $is_shared = UserCourse::where(['cid' => $cid, 'uid' => $userinfo->id])->pluck('is_shared');
            }

            //todo 分享关闭
            $is_shared = 1;

            $is_subscribed = $userinfo->subscribe_status;

            $user = [
                'uid' => $userinfo->id,
                'is_signed' => $is_signed,
                'is_shared' => $is_shared,
                'is_crmmember' => $userinfo->crm_status,
                'is_subscribed' => $is_subscribed,
            ];

            //检查来源链接
            $refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/mobile/index';
            if (empty($refer)) {
                $refer = 'javascript:history.back()';
            }

            if (!$course['firend_title']) {
                $course['firend_title'] = '妈妈微课堂';
            }
            if (!$course['firend_subtitle']) {
                $course['firend_subtitle'] = '我发现了一堂好课，在成为好妈妈的路上又近了一步，一起加入好妈妈的行列吧！';
            }
            if (!$course['share_title']) {
                $course['share_title'] = '我发现了一堂好课，在成为好妈妈的路上又近了一步，一起加入好妈妈的行列吧！';
            }
            if (!$course['share_picture']) {
                $course['share_picture'] = config('course.static_url') . '/mobile/images/logo.jpg';
            }

            $userinfo->imgtype = 'qr';
            $qrcode = Course::where('id', $cid)->where('qrcode_type', 1)->first();
            if ($qrcode) {
                $userinfo->display = 1;
                $userinfo->imgtype = 'ad';
                $userinfo->imgurl = $qrcode->qrcode;
            }

            //查询用户状态
            return view('mobile.course.reg', ['user' => $user, 'course' => $course, 'refer' => $refer, 'user_type' => $user_type, 'userinfo' => $userinfo]);
        }

        //todo
        return 'params invalid';
    }

    //我的课程
    public function mine()
    {
        $user = Auth::user();
        //好友排名
        $rank = UserCourse::getListenTime($user->id);
        return view('mobile.course.minelesson', ['user' => $user, 'rank' => $rank]);
    }

    public function getMinePage(Request $request)
    {
        $user = Auth::user();
        //todo 分页查询用户报名过的课程id
        $uid = $user->id;
        $curPage = $request->input('curPage');
        $minId = $request->input('minId');
        $perPage = $request->input('perPage');
        $cids = UserCourse::where('uid', $uid)->lists('cid');
        $list = [];
        if (count($cids) > 0) {
            $course = Course::whereIn('id', $cids)->where('id', '<>', 40)->where('display_status', '=', 1)->get();
            foreach ($course as $row) {
                $id = $row->id;
                $notify_id = null;
                if ($row->notify_url) {
                    $parse_params = parse_url($row->notify_url);
                    if (isset($parse_params['query'])) {
                        parse_str($parse_params['query'], $query_params);
                        if (isset($query_params['cid'])) {
                            $cid = $query_params['cid'];
                            $notify_id = $cid;
                        }
                    }

                }

                if (strtotime($row->start_day) < strtotime(date("Y-m-d H:i:s")) && $row->status == Course::COURE_REG_STATUS && (!$row->notify_url || ($row->notify_url && $notify_id == $row->id))) {

                } else {
                    if ($row->status == Course::COURE_REG_STATUS && $row->notify_url && $notify_id && $notify_id != $id) {
                        $id = $notify_id;
                    }

                    $list[] = [
                        'id' => $id,
                        'cid' => $id,
                        'title' => $row->title,
                        'img' => $row->img,
                        'start_day' => $row->start_day,
                        'start_time' => date("H:i", strtotime($row->start_time)),
                        'end_time' => date("H:i", strtotime($row->end_time)),
                        'teacher_name' => $row->teacher_name,
                        'teacher_hospital' => $row->teacher_hospital,
                        'teacher_position' => $row->teacher_position,
                        'hot' => $row->hot,
                        'status' => $row->status,
                    ];
                }

            }
        }
        $grouped = collect($list)->groupBy('status');
        $dataLiving = $grouped->get(Course::COURSE_LIVING_STATUS) ?: collect([]);
        $dataLiving = $dataLiving->sortBy('start_day');
        $dataReg = $grouped->get(Course::COURE_REG_STATUS) ?: collect([]);
        $dataReg = $dataReg->sortBy('start_day');
        $dataReview = $grouped->get(Course::COURSE_END_STATUS) ?: collect([]);
        $dataReview = $dataReview->sortByDesc('start_day');
        $list = $dataLiving
            ->merge($dataReg)
            ->merge($dataReview);
//        $list = $dataReg
//            ->merge($dataReview);
        $collection = collect($list)->forPage($curPage, $perPage);
        $collection->all();
        if (count($collection) > 0) {
            $this->result['data'] = [
                'hasNextPage' => 0,
                'list' => $collection
            ];
        } else {
            $this->result['data'] = [
                'hasNextPage' => 1,
                'text' => '没有更多了...'
            ];
        }
        return response()->json($this->result);
    }

    /**
     * 课程回顾页
     * @param  Request $request
     * @return view
     */
    public function end(Request $request)
    {
        $user = Auth::user();
        $uid = Auth::id();
        $cid = $request->input('cid');
        $userType = $user->type;

        //cid为332和40这两节课跳转到新的地址
        $course_review = CourseReview::where('cid', $cid)->where('status', CourseReview::STATUS_YES)->first();
        if (empty($course_review) && ($cid == 40 || $cid == 332)) {
            header("Location:  http://mudu.tv/?c=activity&a=live&id=35451");
            exit;
        }

        // 课程信息
        $course = Course::find($cid);
        if (empty($course)) {
            return redirect()->back();
        }

        if ($course->status == 1 || $course->status == 4) {
            $redirect_url = config('app.url') . '/mobile/index?defaultPath=/courseNew/' . $course->id;
        } else {
            //判断是否有可用的回顾课程
            if (empty($course_review)) {
                $redirect_url = config('app.url') . '/mobile/index?defaultPath=/courseNew/' . $course->id;
            } else {
                if ($course_review->review_type == 1) {
                    $redirect_url = config('app.url') . '/mobile/index?defaultPath=/courseAudio/' . $course->id;
                } else if ($course_review->review_type == 2) {
                    $redirect_url = config('app.url') . '/mobile/index?defaultPath=/courseVideo/' . $course->id;
                } else {
                    $redirect_url = config('app.url') . '/mobile/index?defaultPath=/courseNew/' . $course->id;
                }
            }
        }

        //统计从end进入,2017-09-04
        Cidata::init(config('oneitfarm.appkey'));
        Cidata::sendEvent($user->id, $user->channel, null, 'server_end', [
            'cid' => $cid,
            'wyeth_channel' => Session::get('channel')
        ]);

        $params = $request->all();

        $redirect_url = $redirect_url . '&' . http_build_query($params);

        // 跳到新版课程详情页
        return redirect($redirect_url);

        // 记录用户打开这页面的日志,即使报错也记录
        $courseStat = CourseStat::firstOrCreate(['uid' => $uid, 'cid' => $cid]);
        if ($courseStat->go_review_time == '0000-00-00 00:00:00') {
            $courseStat->go_review_time = date("Y-m-d H:i:s");
            $courseStat->save();
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

        //判断是否有可用的回顾课程
        if (empty($course_review)) {
            $coursesReviewRecommend = CourseService::signOkCoursesRecommended($uid, $userType, 'unsignreview', 3, $cid);
            return $this->reviewError(1, ['list' => $coursesReviewRecommend]);
        }
        //课件
        $coursewares = Courseware::where('cid', $cid)->get()->toArray();
        if (empty($coursewares)) {
            $coursesReviewRecommend = CourseService::signOkCoursesRecommended($uid, $userType, 'unsignreview', 3, $cid);
            return $this->reviewError(2, ['list' => $coursesReviewRecommend]);
        }

        //广告位1
        $carouselsEnd1 = [];
        $appConfig1 = AppConfig::where('module', 'end')->where('key', 'carousels_end1')->get()->toArray();
        if ($appConfig1) {
            if ($appConfig1[0]['data']['link']) {
                $carouselsEnd1['link'] = $appConfig1[0]['data']['link'];
            } else {
                $carouselsEnd1['link'] = 'javascript:void(0);';
            }
            $carouselsEnd1['img'] = $appConfig1[0]['data']['img'];
        }

        //广告位2
        $carouselsEnd2 = [];
        $appConfig2 = AppConfig::where('module', 'end')->where('key', 'carousels_end2')->get()->toArray();
        if ($appConfig2) {
            if ($appConfig2[0]['data']['link']) {
                $carouselsEnd2['link'] = $appConfig2[0]['data']['link'];
            } else {
                $carouselsEnd2['link'] = 'javascript:void(0);';
            }
            $carouselsEnd2['img'] = $appConfig2[0]['data']['img'];
        }

        //推荐课程
        //三个课程一组 第一组
        $notInCid = [];
        $coursesRecommend1 = $this->coursesRecommend($cid);
        foreach ($coursesRecommend1 as $k => $v) {
            $notInCid[] = $v['cid'];
            $coursesRecommend1[$k]['group'] = '1';
        }

        //三个课程一组 第二组
        $coursesRecommend2 = $this->coursesRecommend($cid, $notInCid);
        foreach ($coursesRecommend2 as $k => $v) {
            $notInCid[] = $v['cid'];
            $coursesRecommend2[$k]['group'] = '2';
        }

        //三个课程一组 第三组
        $coursesRecommend3 = $this->coursesRecommend($cid, $notInCid);
        foreach ($coursesRecommend3 as $k => $v) {
            $coursesRecommend3[$k]['group'] = '3';
        }

        //合并三组课程、
        $coursesRecommend = array_merge($coursesRecommend1, $coursesRecommend2, $coursesRecommend3);

        //为推荐课程的url加上一个参数（当前访问的cid），方便做百度统计
        foreach ($coursesRecommend as $key => $value) {
            $coursesRecommend[$key]['url'] = $value['url'] . '&_hw_c=hgtj' . $cid;
        }

        //点赞数量
        $reviewLikesNum = $this->reviewLikesNum($course);

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
        $update_cid_arr = [301, 385, 46, 223];
        if (in_array($cid, $update_cid_arr)) {
            if ($mothersNum < 10000) {
                $mothersNum += 10000;
            }
            if ($reviewLikesNum < 50) {
                $reviewLikesNum = 50 + substr($reviewLikesNum, -1);
            }
        }

        $data = [
            'course' => $course,
            'course_review' => $course_review,
            'coursewares' => $coursewares,
            'carouselsEnd1' => $carouselsEnd1,
            'carouselsEnd2' => $carouselsEnd2,
            'coursesRecommend' => $coursesRecommend,
            'is_subscribed' => $is_subscribed,
            'reviewLikesNum' => $reviewLikesNum,
            'isLike' => $isLike,
            'mothersNum' => $mothersNum,
            'qAndA' => $qAndA,
            'isCrm' => $user->crm_status,
            'reviewInId' => $userEvent->id
        ];
        return view('mobile.course.end', $data);
    }

    public static function coursesRecommend($cid, $notInCid = [])
    {
        $user = Auth::user();
        $userType = $user->type;
        $uid = Auth::id();
        //推荐课程 规则：同阶段  第一条 报名中  第二条 报名中  第三条 回顾
        //同阶段
//        $coursesUnsignedSameStage = CourseService::endCoursesRecommended($uid, $userType, 'unsigned', 3, $cid, true, $notInCid);
//        if (count($coursesUnsignedSameStage) >= 2) {
//            $coursesReviewSameStageNum = 1;
//        } else {
//            $coursesReviewSameStageNum = 3 - count($coursesUnsignedSameStage);
//        }
        $coursesReviewSameStageNum = 3;
        $coursesReviewSameStage = CourseService::endCoursesRecommended($uid, $userType, 'review', $coursesReviewSameStageNum, $cid, true, $notInCid);
//        if ($coursesReviewSameStage) {
//            $coursesRecommendSameStage = array_merge(array_slice($coursesUnsignedSameStage, 0, 2), $coursesReviewSameStage);
//        } else {
//            $coursesRecommendSameStage = $coursesUnsignedSameStage;
//        }
        $coursesRecommendSameStage = $coursesReviewSameStage;

        //如果同阶段够三条记录，那么取同阶段课程，否则用不同阶段的课程按照规则补齐三条
        if (count($coursesRecommendSameStage) == 3) {
            $coursesRecommend = $coursesRecommendSameStage;
        } else {
            foreach ($coursesRecommendSameStage as $v) {
                $notInCid[] = $v['cid'];
            }
//            $coursesUnsignedDifferentStage = CourseService::endCoursesRecommended($uid, $userType, 'unsigned', 3, $cid, false, $notInCid);
//            if (count($coursesUnsignedDifferentStage) >= 2) {
//                $coursesReviewDifferentStageNum = 1;
//            } else {
//                $coursesReviewDifferentStageNum = 3 - count($coursesUnsignedDifferentStage);
//            }
            $coursesReviewDifferentStageNum = 3;
            $coursesReviewDifferentStage = CourseService::endCoursesRecommended($uid, $userType, 'review', $coursesReviewDifferentStageNum, $cid, false, $notInCid);
//            if ($coursesReviewDifferentStage) {
//                $coursesRecommendDifferentStage = array_merge(array_slice($coursesUnsignedDifferentStage, 0, 2), $coursesReviewDifferentStage);
//            } else {
//                $coursesRecommendDifferentStage = $coursesUnsignedDifferentStage;
//            }
            $coursesRecommendDifferentStage = $coursesReviewDifferentStage;
            $coursesRecommend = array_merge($coursesRecommendSameStage, $coursesRecommendDifferentStage);
            $coursesRecommend = array_slice($coursesRecommend, 0, 3);
        }

        //如果课程url为空，则不跳转
        foreach ($coursesRecommend as $k => $v) {
            if ($v['url'] == '') {
                $coursesRecommend[$k]['url'] = 'javascript:void(0);';
            }
        }

        return $coursesRecommend;
    }

    public function attention()
    {
        //todo 由于关注状态结果延迟时间20s，所以先写死
        //        $uid = Auth::id();
        //        User::where('id', $uid)->update(array('subscribe_status' => 1));

        $open_type = Session::get('openid_type');

        return view('mobile.course_attention', ['open_type' => $open_type]);
    }

    // 首页页面
    public function index(Request $request)
    {
        //用户类型
        $user = Auth::user();

        //统计首页加载时间,打点
        Cidata::init(config('oneitfarm.appkey'));
        $event_params = [
            'action' => 'start',
            'version' => 2
        ];
        Cidata::sendEvent($user->id, $user->channel, null, 'load_home', $event_params);


        //获取配置文件
        $test = $request->input('test');
        if (!$test) {
            $test = config('oneitfarm.web_test');
        }
        $config = WyethUtil::getManifestName($test);

        $home_page = new PageController();
        $res = $home_page->getHomePageData($request)->getData(true);
        $data = $res['data'];

        //配置
        $data['app_config'] = (new UserRepository())->getAppConfig();

        $data['config'] = $config;
        $data['test'] = $test;
        return view('mobile.course.home', $data);
    }

    public function loadHome()
    {
        $user = Auth::user();
        //统计首页加载时间,打点
        Cidata::init(config('oneitfarm.appkey'));
        $event_params = [
            'action' => 'end',
            'version' => 2,
            'time' => intval(round(microtime(true) * 1000))
        ];
        Cidata::sendEvent($user->id, $user->channel, null, 'load_home', $event_params);
        return 1;
    }

    // 全部课程页面
    public function all(Request $request)
    {
        $user = Auth::user();
        $userType = $user->type;
        $uid = Auth::id();
        // $tagId = $request->get('tag', false);
        // $name = $request->get('name', '');

        // tag名截取长度默认为4
        $length = 4;

        $type = $request->get('type', 'review');
        $page = $request->get('page', 1);
        $stage = $request->get('stage', 0);
        $tag = $request->get('tag', '');
        $tagId = false;
        if ($tag) {
            $tagId = Tag::getTagIdByName($tag);
        }

        if (!$tagId) {
            $tags = Tag::randChunk(7);
            $tagsReview = Tag::randChunk(7, true);
        } else {
            $tags = Tag::randChunk(6, false, $length, $tagId);
            $tagsReview = Tag::randChunk(6, true, $length, $tagId);
            $tag = mb_substr($tag, 0, $length);
            $tags = array_merge([['name' => $tag, 'id' => $tagId]], $tags);
            $tagsReview = array_merge([['name' => $tag, 'id' => $tagId]], $tagsReview);
        }
        $types = ['new', 'hot', 'review'];
        if (!in_array($type, $types)) {
            $type = 'review';
        }
        if ($stage > 4) {
            $stage = 0;
        }
        // 默认1页6条记录
        $number = 6 * $page;

        $courses = CourseService::getDynamicData($uid, $userType, 1, $type, $number, $stage, $tagId);

        return view('mobile.course.all', [
            'contents' => $courses,
            'tagId' => $tagId,
            'tagName' => $tag,
            'tags' => $tags,
            'tagsReview' => $tagsReview,
            'stage' => $stage,
            'page' => $page + 1,
            'type' => $type,
        ]);
    }

    /**
     * 页面动态下拉，切换时通过ajax读取数据
     *
     * @param  requety $request
     *
     * @return string
     */
    public function getDroploadData(Request $request)
    {
        $type = $request->get('type', 'new');
        $page = $request->get('page', 1);
        $stage = $request->get('stage', 0);
        $tag = $request->get('tag', false);

        $uid = Auth::id();
        $user = Auth::user();
        $userType = $user->type;

        //因为现在课的数量小，客户要求一次加载完，数值先留着，估计过几天要改
        $number = 6; //原来是6
        $contents = CourseService::getDynamicData($uid, $userType, $page, $type, $number, $stage, $tag);

        return json_encode($contents);
    }

    /**
     * 取出套课和此类信息, 条件当前用户是否对这个课报了名了
     * @param  Request $request
     * @return
     */
    public function cat(Request $request)
    {
        //准备数据
        $id = $request->input('id');

        //处理当前用户id, 合并到课程信息
        $uid = Auth::id();
        $temp_userCourses = userCourse::where('uid', $uid)->get();
        $userCourses = [];
        foreach ($temp_userCourses as $temp_userCourse) {
            $userCourses[$temp_userCourse['cid']] = $temp_userCourse['uid'];
        }

        //计算当前报名人数
        $number = CounterService::courseCatRegAllGet($id) + 2937;

        //取得分类数据信息
        $courseCat = CourseCat::where('id', $id)->first();

        //取得当前分类下的课程信息
        $query = Course::where('cid', $id);
        //$query->whereIn('status',[Course::COURE_REG_STATUS, Course::COURSE_LIVING_STATUS]);
        //$query->whereIn('status',[Course::COURSE_END_STATUS]);
        $user = Auth::user();
        $user_type = $user->type;
        $query->whereIn('user_type', [0, $user_type]);
        $query->orderBy(DB::raw('field(status,' . Course::COURSE_STATUS_ORDER . ')'));
        $query->orderBy('start_day');
        $query->orderBy('start_time');
        $courses = $query->get();

        $teachers = [];
        foreach ($courses as $key => $course) {
            if (isset($userCourses[$course['id']])) {
                $courses[$key]['sign'] = true;
            }
            $teacher = array('teacher_name' => $course['teacher_name'], 'teacher_avatar' => $course['teacher_avatar']);
            if (!in_array($teacher, $teachers)) {
                $teachers[] = $teacher;
            }
            $review = CourseReview::where('cid', $course->id)->first();
            if ($review) {
                $course->audio_src = $review->audio;
            }
        }

        //用户信息，用于查看用户是否分享或者关注，
        $userModel = new User();
        $tempUserInfo = $userModel->getUserInfo($user);

        $userInfo = [
            'is_crmmember' => $tempUserInfo->crm_status,
            'is_subscribed' => $tempUserInfo->subscribe_status,
            'id' => $tempUserInfo->id,
        ];

        //分享参数
        $share = [
            'title' => '妈妈微课堂',
            'link' => '',
            'imgUrl' => $courseCat['img'],
            'desc' => '我已经报名了【' . $courseCat['name'] . '】每天15分钟，实用育儿知识轻松学，你也快来吧！',
        ];

        return view('mobile.course.cat_new', ['courseCat' => $courseCat, 'courses' => $courses, 'number' => $number, 'share' => $share, 'userInfo' => $userInfo, 'teachers' => $teachers]);
    }

    /**
     * 功能： 报名成功页
     *
     * @param Request $request
     * @return string|View
     */
    public function regOk(Request $request)
    {
        if ($request->method() == 'GET') {

            //报名成功了，那么显示相关课程，条件是同阶段 > 报名中 > 回顾
            $user = Auth::user();
            $userType = $user->type;
            $uid = Auth::id();
            $cid = $request->get('cid');

            $share = [
                'title' => '妈妈微课堂',
                'link' => config('app.url') . '/mobile/index?from_openid=' . $user->openid,
                // 'link' => config('app.url') . '/mobile/index?from_openid=',
                'imgUrl' => config('course.static_url') . "/mobile/images/lessonlist_share.jpg",
                'desc' => '有了这些护理知识，宝宝再也不用担心我手忙脚乱了～在线妈妈微课堂，专业医生讲解1对1答疑，超实用方便！收藏！',
            ];

            //取得后台设定的指定显示页面
            $ads = AppConfig::where('module', 'index')->where('key', 'signSuccess')->orderBy('displayorder')->get()->pluck('data')->toArray();
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
            //替换广告链接
            foreach ($ads as &$item) {
                $item['link'] = AppConfig::getUrlLink($item['link'], 'server_ad', 'subject', $item['subject']);
            }
            $ads = collect($ads)->filter(function ($item) use ($userStatus) {
                return $item['attr'] == $userStatus;
            });
            $coursesUnsigned = CourseService::signOkCoursesRecommended($uid, $userType, 'unsigned', 1, $cid);
            $coursesReview = CourseService::signOkCoursesRecommended($uid, $userType, 'review', 2, $cid);
            $courses = [];
            if ($coursesReview) {
                $courses[] = $coursesReview[0];
            }
            if ($coursesUnsigned) {
                $courses[] = $coursesUnsigned[0];
            } else if (isset($coursesReview[1])) {
                $courses[] = $coursesReview[1];
            }
            return view('mobile.course.reg_ok', [
                'share' => $share,
                'courses' => $courses,
                'ads' => $ads,
                'user' => $user,
                'userStatusBaiduStatistics' => $userStatusBaiduStatistics
            ]);
        } else {
            //套课报名
            $json = ['mark' => true];

            $courseIds = $request->input('course_ids');
            $uid = Auth::id();
            $user = Auth::user();

            if (count($courseIds) <= 0) {
                $json['mark'] = false;
                $json['message'] = 'ids 为空';
                return json_encode($json);
            }

            //CIData统计报名
            Cidata::init(config('oneitfarm.appkey'));
            $valuableIds = [];
            foreach ($courseIds as $courseId) {
                $model = new UserCourse();
                //检查课程是否能够报名
                $course_status = $model->checkUserSignStatus($courseId, $uid);
                if ($course_status) {
                    $valuableIds[] = $courseId;
                    $model->uid = $uid;
                    $model->cid = $courseId;
                    $model->channel = Session::get('channel');
                    $model->save();
                    Cidata::sendEvent($uid, $user->channel, null, 'sign', ['cid' => $courseId, 'wyeth_channel' => Session::get('channel')]);
                } else {
                    $json['message'] = '此课不能添加，或者用户已经添加过';
                }
            }

            if (count($valuableIds) <= 0) {
                $json['mark'] = false;
                $json['message'] = 'ids 无效';
                return json_encode($json);
            }

            // 推荐课程
            $user = Auth::user();
            $course = Course::find($valuableIds[0]);
            $params = CourseService::recommendCourseIdGet($user, $course);
            if ($params) {
                Log::info('regOk', [
                    'uid' => $user->id,
                    'sid' => $params ? $params['sign_up_course']->id : 0,
                    'rid' => $params ? $params['recommend_course']->id : 0
                ]);
                $job = (new SendTemplateMessageBySignUp($params));
                $this->dispatch($job);
            }

            return json_encode($json);
        }
    }

    public function jump(Request $request)
    {
        $course = Course::where('display_status', 1)
            ->where('status', 2)
            ->where('id', '<>', 40)
            ->orderBy('start_day')
            ->orderBy('start_time')
            ->orderBy('number', 'desc')
            ->first();
        if ($course != null) {
            $id = $course->id;
            $type = 'living';
        } else {
            $course = Course::where('display_status', 1)
                ->where('status', 1)
                ->orderBy('start_day')
                ->orderBy('start_time')
                ->orderBy('number', 'desc')
                ->first();
            if ($course != null) {
                $id = $course->id;
                $type = 'reg';
            } else {
                //
                $course = Course::where('display_status', 1)
                    ->where('status', 3)
                    ->orderBy('start_day', 'desc')
                    ->orderBy('start_time', 'desc')
                    ->orderBy('number', 'desc')
                    ->first();
                if ($course != null) {
                    $id = $course->id;
                    $type = 'end';
                } else {
                    return Redirect('mobile/index');
                }
            }
        }

        $website = 'mobile/' . $type . '?cid=' . $id;
        if ($request->server->has('QUERY_STRING')) {
            $website .= '&' . $request->server->get('QUERY_STRING');
        }
        return Redirect($website);
    }

    public function review(Request $request)
    {
        $data = $request->all();
        if ($request->method() == 'POST') {

            $m = new CourseReviewQuestions();
            $m->uid = Auth::id();
            $m->cid = $data['cid'];
            $m->content = $data['question'];
            $m->save();
            if ($m->id) {
                return Redirect('/mobile/review/ok');
            }
        }
        return view('mobile.course.review', []);
    }

    public function reviewOk()
    {
        return view('mobile.course.reviewOk', []);
    }

    public function reviewAttention()
    {
        $open_type = Session::get('openid_type');

        return view('mobile.course.reviewAttention', ['open_type' => $open_type]);
    }

    public function reviewAdd(Request $request)
    {
        $data = $request->all();
        if ($request->method() == 'POST') {
            $m = new CourseReviewQuestions();
            $m->uid = Auth::id();
            $m->cid = $data['cid'];
            $m->content = $data['question'];
            $m->save();
        }
        echo $m->id;
        return;
    }

    private function returnStage($stage = false)
    {
        $stageStr = '';
        if (empty($stage)) {
            $stage = '';
        }
        $stageOne = substr($stage, 0, 1);
        $stageTwo = substr($stage, 1, 2);
        $stageTwoFirst = substr($stageTwo, 0, 1);
        if ($stageTwoFirst == 0) {
            $stageTwo = substr($stageTwo, 1, 1);
        }
        if ($stageOne == 1) {
            $stageStr = '备孕';
        } elseif ($stageOne == 2) {
            $stageStr = '孕中';
            $stageStr .= ' ' . $stageTwo . '个月';
        } elseif ($stageOne == 3) {
            $stageStr = '宝宝';
            $stageStr .= ' ' . $stageTwo . '岁';
        }
        return $stageStr;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function giveAReviewLike(Request $request)
    {
        $cid = $request->input('cid');
        $user = Auth::user();
        $course = Course::find($cid);
        if (!$course) {
            return $this->showAjaxError("course is not exist");
        }
        $res = UserEventService::giveAReviewLike($user, $course);
        if ($res) {
            return $this->showAjax("success");
        } else {
            return $this->showAjaxError("error");
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelAReviewLike(Request $request)
    {
        $cid = $request->input('cid');
        $user = Auth::user();
        $course = Course::find($cid);
        if (!$course) {
            return $this->showAjaxError("course is not exist");
        }
        $res = UserEventService::cancelAReviewLike($user, $course);
        if ($res) {
            return $this->showAjax("success");
        } else {
            return $this->showAjaxError("error");
        }
    }

    /**
     * @param Course $course
     * @return \App\Http\Controllers\Mobile\View|mixed
     */
    public static function reviewLikesNum(Course $course)
    {
        return UserEventService::reviewLikesNum($course);
    }

    /**
     * @param Request $request
     * @description 提问
     */
    public function addCourseQuestion(Request $request)
    {
        $user = Auth::user();
        $userInfo = User::where('id', $user->id)->first();
        $WxWyeth = new WxWyeth();
        $checkSubscribe = $WxWyeth->getSubscribeStatus($userInfo->openid);
        //判断用户是否关注公众号
        if ($checkSubscribe == 0) {
            $result = [
                'status' => 302,
                'message' => 'Please pay attention to us first'
            ];
            return response()->json($result);
        }

        //判断用户是否注册CRM系统
        if ($userInfo->crm_status == 0) {
            $result = [
                'status' => 301,
                'message' => 'Not Register In Crm'
            ];
            return response()->json($result);
        }
        $insertCourseQuest = new CourseReviewQuestions();
        $insertCourseQuest->cid = $request->input('cid');
        $insertCourseQuest->uid = $user->id;
        $insertCourseQuest->content = $request->input('content', true);
        $insertCourseQuest->is_send = $request->input('is_send');
        $insertCourseQuest->save();
        if ($insertCourseQuest) {
            $result = [
                'status' => 200,
                'message' => '提问成功'
            ];
            return response()->json($result);
        } else {
            $result = [
                'status' => 500,
                'message' => '提问失败'
            ];
            return response()->json($result);
        }

        return $checkReviewQuest;
    }

    /**
     * @param Request $request 课程回顾记录
     */
    public function reviewRecord(Request $request)
    {
        $cid = trim($request->input('cid'));
        $course = Course::find($cid);
        if (!$course) {
            return $this->showAjaxError("Course is not exist");
        }
        $type = $request->input('type');
        if (!in_array($type, ['review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause'])) {
            return $this->showAjaxError("Type is not exist.");
        }
        $user = Auth::user();
        $uid = Auth::id();
        $userType = $user->type;
        $userEvent = new UserEvent;
        $userEvent->cid = $cid;
        $userEvent->uid = $uid;
        $userEvent->user_type = $userType;
        $userEvent->type = $type;
        $userEvent->save();

        $id = trim($request->input('id'));
        $userEvent = UserEvent::where('id', $id)
            ->where('uid', $uid)
            ->first();
        if (!$userEvent) {
            return $this->showAjaxError("Recording is not exist");
        }
        $reviewType = $request->input('review_type');
        if (!in_array($reviewType, [1, 2])) {
            return $this->showAjaxError("Media type is not exist");
        }
        $mediaType = [
            '1' => 'audio',
            '2' => 'video'
        ];
        $timeArray = json_decode($userEvent->data, true);
        $timeArray['updated_at'] = time();
        if (isset($timeArray['duration']) && is_array($timeArray['duration']) && count($timeArray['duration']) > 0) {
            if ($type == 'review_' . $mediaType[$reviewType] . '_begin') {
                $timeArray['duration'][] = ['review_' . $mediaType[$reviewType] . '_begin' => time(), 'review_' . $mediaType[$reviewType] . '_pause' => time()];
            }
            if ($type == 'review_' . $mediaType[$reviewType] . '_pause') {
                $timeArray['duration'][count($timeArray['duration']) - 1][$type] = time();
            }
        } else {
            $timeArray['duration'][0] = ['review_' . $mediaType[$reviewType] . '_begin' => time(), 'review_' . $mediaType[$reviewType] . '_pause' => time()];
        }
        $userEvent->data = json_encode($timeArray);
        $userEvent->save();
        return $this->showAjax("success");
    }

    public function reviewTimeRecord(Request $request)
    {
        $playStatus = $request->input('playStatus');
        $uid = Auth::id();
        $id = trim($request->input('id'));
        $userEvent = UserEvent::where('id', $id)
            ->where('uid', $uid)
            ->first();
        if (!$userEvent) {
            return $this->showAjaxError("Recording is not exist");
        }
        $reviewType = $request->input('review_type');
        // dd($reviewType);
        if (!in_array($reviewType, [1, 2])) {
            return $this->showAjaxError("Media type is not exist");
        }
        $mediaType = [
            '1' => 'audio',
            '2' => 'video'
        ];
        $timeArray = json_decode($userEvent->data, true);
        $timeArray['updated_at'] = time();
        if (isset($timeArray['duration']) && is_array($timeArray['duration']) && count($timeArray['duration']) > 0) {
            $timeArray['duration'][count($timeArray['duration']) - 1]['review_' . $mediaType[$reviewType] . '_pause'] = time();
        } else {
            if ($playStatus) {
                $timeArray['duration'][0] = ['review_' . $mediaType[$reviewType] . '_begin' => time(), 'review_' . $mediaType[$reviewType] . '_pause' => time()];
            }
        }
        $userEvent->data = json_encode($timeArray);
        $userEvent->save();
        return $this->showAjax('success');
    }

    /**
     * 课程回顾推荐活动链接
     */
    public function reviewPromotion()
    {
        $user = Auth::user();

        if ($user->baby_birthday > date("Y-m-d H:i:s")) {
            $stageFrom = 200;
            $stageTo = 300;
            $stage = '孕期';
        } else {
            $stageFrom = 300;
            $stageTo = 400;
            $stage = '新生儿';
        }
        if ($user->baby_birthday == null) {
            $stageFrom = 100;
            $stageTo = 400;
        }
        $courseReviewIds = CourseReview::lists('cid')->toArray();
        $startDate = date('Y-m-d 00:00:00');
        $deadDate = date('Y-m-d 23:59:59');
        $notInIds = RecommendCourse::where('uid', $user->id)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $deadDate)
            ->lists('recommend_course_id')
            ->toArray();

        $courseIds = Course::where('stage_from', '>=', $stageFrom)
            ->where('stage_to', '<', $stageTo)
            ->whereIn('id', $courseReviewIds)
            ->whereNotIn('id', $notInIds)
            ->lists('id')->toArray();

        $cids = CourseStat::where('in_class_time', '0000-00-00 00:00:00')
            ->where('in_review_time', '0000-00-00 00:00:00')
            ->where('uid', $user->id)
            ->whereIn('cid', $courseIds)
            ->lists('cid')->toArray();

        if ($cids) {
            $cid = $cids[array_rand($cids)];
        } else {
            $cid = $courseIds[array_rand($courseIds)];
        }
        return Redirect('mobile/end?_hw_c=48hour&cid=' . $cid);
    }

    /**
     * @param $openid
     * @return 加密openID 后的6位字符串
     */
    public function createStrByOpenid($openid)
    {
        $base32 = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
            'y', 'z', '0', '1', '2', '3', '4', '5'
        );

        $hex = md5($openid);
        $hexLen = strlen($hex);
        $subHexLen = $hexLen / 8;
        $output = array();

        for ($i = 0; $i < $subHexLen; $i++) {
            //把加密字符按照8位一组16进制与0x3FFFFFFF(30位1)进行位与运算
            $subHex = substr($hex, $i * 8, 8);
            $int = 0x3FFFFFFF & (1 * ('0x' . $subHex));
            $out = '';

            for ($j = 0; $j < 6; $j++) {

                //把得到的值与0x0000001F进行位与运算，取得字符数组chars索引
                $val = 0x0000001F & $int;
                $out .= $base32[$val];
                $int = $int >> 5;
            }

            $output[] = $out;
        }

        return $output[0];
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $uid = Auth::id();
        $keyword = trim($request->input('keyword'));

        $config = config('opensearch');
        $default = $config['default'];
        $this->config = $config['connections'][$default];
        $client = new CloudsearchClient(
            $this->config['client_id'],
            $this->config['client_secret'],
            $this->config['host'],
            'aliyun'
        );
        $search_obj = new CloudsearchSearch($client);
        // 指定一个应用用于搜索
        $search_obj->addIndex($this->config['app']);
        // 指定搜索关键词
        $search_obj->setQueryString("default:" . $keyword);
        // 指定返回的搜索结果的格式为json
        $search_obj->setFormat("json");
        // 执行搜索，获取搜索结果
        $json = $search_obj->search();
        // 将json类型字符串解码
        $result = json_decode($json, true);

        if ($result['status'] == "OK") {
            $result = $result['result']['items'];
            $ids = [];
            foreach ($result as $key => $item) {
                $ids[$key] = $item['id'];
            }
            $query = Course::whereIn('id', $ids)->get()->toArray();
            $list = $this->arrayOrder($query);
        } else {
            $ids = [];
            $list = [];
        }


        //获取四条推荐课程
        if (count($ids) > 0) {
            $recommCourse = Course::whereNotIn('id', $ids)->orderBy(\DB::raw('RAND()'))->limit(4)->get()->toArray();
        } else {
            $recommCourse = Course::orderBy(\DB::raw('RAND()'))->limit(4)->get()->toArray();
        }

        $orderRecommCourse = $this->arrayOrder($recommCourse);


        if (strlen($keyword) != 0) {
            //新建一条搜索记录 并返回id
            $searchRecord = new SearchRecord();
            $searchRecord->uid = $uid;
            $searchRecord->keyword = $keyword;
            $searchRecord->result = implode(",", $ids);
            $searchRecord->save();

            if (!$searchRecord) {
                echo 'ERROR: Search record creation failed!';
                exit;
            }
            $sid = $searchRecord->id;
        } else {
            $sid = '';
        }

        return view('mobile.course.search', ['list' => $list, 'sid' => $sid, 'count' => count($list), 'keyword' => $keyword, 'recomm' => $orderRecommCourse]);
    }


    /**
     * @param status 1 报名中  2 直播中 3 回顾
     * @return 返回排序为  直播中> 回顾 > 报名中  并且去掉无效课程
     */
    private function arrayOrder($arr)
    {
        $collection = collect($arr);

        $effective = $collection->filter(function ($item) {
            return $item['display_status'] == 1;
        });

        $statusOne = $effective->filter(function ($item) {
            return $item['status'] == 1;
        });
        $statusTwo = $effective->filter(function ($item) {
            return $item['status'] == 2;
        });
        $statusThree = $effective->filter(function ($item) {
            return $item['status'] == 3;
        });

        $result = array_merge($statusTwo->toArray(), $statusThree->toArray(), $statusOne->toArray());

        return $result;
    }

    public function updateSearchInfo(Request $request)
    {
        $sid = $request->input('sid');
        $click_id = $request->input('click_id');
        $click_type = $request->input('click_type');
        $query = SearchRecord::where('id', $sid)->first();
        if (!$query || $query->count() == 0) {
            $result = ['status' => 400, 'message' => 'Not Find!'];
            echo json_encode($result);
            exit;
        }
        $query->click_id = $click_id;
        $query->click_type = $click_type;
        $query->save();
        if ($query) {
            $result = ['status' => 200, 'message' => 'Update Success!'];
        } else {
            $result = ['status' => 500, 'message' => 'Update Fild!'];
        }
        echo json_encode($result);
    }


    //大平台注册crm的回调
    public function crmCallback(Request $request)
    {
        $user = Auth::user();

        $crm = new Crm();
        //检测是否成为crm会员
        $res = $crm->searchMemberInfo($this->openid);
        if (!$res || !$res['Flag']) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = $res['Message'];
            return response()->json($this->result);
        }
        if ($res['Member'] == 1) {
            //已是会员更新user
            if ($res['Province']) {
                $user->crm_province = $res['Province'];
            }
            if ($res['City']) {
                $user->crm_city = $res['City'];
            }
            $user->crm_status = 1;
            $user->save();

            //活动 邀请好友成功
            if (Session::has(SessionKey::ACTIVITY_AID) && Session::has(SessionKey::QRCODE_PARAMS)) {
                $aid = Session::pull(SessionKey::ACTIVITY_AID);
                $params = Session::pull(SessionKey::QRCODE_PARAMS);

                if (isset($params['openid'])) {
                    $inviter = User::where('openid', $params['openid'])->first();
                    $exist = Invitation::where('aid', $aid)->where('uid', $inviter->id)->where('invitee_id', $user->id)->first();

                    if ($inviter && !$exist) {
                        $invitation = new Invitation();
                        $invitation->aid = $aid;
                        $invitation->uid = $inviter->id;
                        $invitation->invitee_id = $user->id;
                        $invitation->save();

                        $count = Invitation::where('aid', $aid)->where('uid', $inviter->id)->count();

                        $act = Activity::find($aid);
                        $content = '《' . $act->name . '》';

                        //给邀请人推送模板消息
                        if ($count < 3) {
                            if ($count == 1) {
                                $title = '已成功邀请1名好友，离解锁课程还有一步之遥，加油';
                            } else {
                                $title = "已成功邀请2名好友，快去学习成功解锁的魔栗指南吧。听完别忘了分享课程页哦";
                            }
                            $params = [
                                'title' => $title,
                                'content' => $content,
                                'odate' => date('Y-m-d'),
                                'address' => '',
                                'remark' => '点击查看详情',
                                'url' => config('app.url') . "/mobile/hd?aid=$aid"
                            ];
                            $params['openid'] = $inviter->openid;
                            $res = (new WxWyeth())->pushpushCustomMessage($params, 6, false);
//                            $this->dispatch(new SendTemplateMessage($params, $inviter->openid, 1, 6, false));
                        }
                    }

                }
                $redirect = config('app.url') . "/mobile/hd?aid=$aid";
                return redirect($redirect);

            }

            //注册crm回跳
            if (Session::has(SessionKey::REGISTER_CRM_REDIRECT)) {
                $redirect = Session::pull(SessionKey::REGISTER_CRM_REDIRECT);
                return redirect($redirect);
            }

            $uid = $user->id;
            $cid = 0;
            if (Session::has('crm_cid_str')) {
                $cid = Session::pull('crm_cid_str');

                //记录用户统计日志
                $courseStat = CourseStat::where(['uid' => $uid, 'cid' => $cid])->first();
                if ($courseStat) {
                    $courseStat->sign_time = date("Y-m-d H:i:s");
                    $courseStat->save();
                }

                $courseIds = explode('.', $cid);
                foreach ($courseIds as $courseId) {
                    $userCourse = UserCourse::firstOrCreate(['uid' => $uid, 'cid' => $courseId]);
                    $userCourse->channel = Session::get('channel');
                    $userCourse->save();
                }
            } else {
                $redirect = '/mobile/index';
            }

            if (Session::has('crm_redirect')) {
                $redirect = Session::pull('crm_redirect');
            } elseif ($cid) {
                //有报名的话
                $redirect = '/mobile/sign';
            } else {
                $redirect = '/mobile/index';
            }
        } else {
            //不是会员
            $redirect = '/mobile/index';
        }
        return redirect($redirect);
    }

    //去crm注册会员
    public function registerCrm(Request $request)
    {
        $redirect = $request->input('redirect');
        if ($redirect) {
            $redirect = urldecode($redirect);
        } else {
            $redirect = config('app.url') . '/mobile/index';
        }

        Session::put(SessionKey::REGISTER_CRM_REDIRECT, $redirect);

        return redirect(config('course.register_crm'));
    }
}
