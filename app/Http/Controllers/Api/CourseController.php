<?php namespace App\Http\Controllers\Api;

use App\CIData\Cidata;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\CourseStat;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\VerifyCode;
use App\Services\CounterService;
use App\Services\CourseService;
use App\Services\Crm;
use App\Services\MobileQQ;
use App\Services\Sms;
use App\Services\WxWyeth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use App\Jobs\SendTemplateMessageBySignUp;
use Log;

use App\Console\Commands\CrmOpenid;

class CourseController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['adList', 'getByCid', 'getListByEdc', 'xxjp']]);
        $this->middleware('token', ['only' => ['getByCid', 'getListByEdc', 'xxjp']]);
    }

    protected $result = [
        'status' => 1,
        'error_msg' => '',
        'data' => [],
    ];

    public function index(Request $request)
    {
        $user = Auth::user();

        //todo 分页查询用户报名过的课程id
        $uid = $user->id;
        $cids = UserCourse::where('uid', $uid)->lists('cid');

        $list = [];
        if (count($cids) > 0) {
            $course = Course::whereIn('id', $cids)->where('id', '<>', 40)->limit(100)->get();
            foreach ($course as $row) {
                $list[] = [
                    'cid' => $row->id,
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
        $grouped = collect($list)->groupBy('status');
        $dataLiving = $grouped->get(2) ?: collect([]);
        $dataLiving = $dataLiving->sortBy('start_day');
        $dataReg = $grouped->get(1) ?: collect([]);
        $dataReg = $dataReg->sortBy('start_day');
        $dataReview = $grouped->get(3) ?: collect([]);
        $dataReview = $dataReview->sortByDesc('start_day');
        $list = $dataLiving
            ->merge($dataReg)
            ->merge($dataReview);

        $this->result['data'] = [
            'hasNextPage' => 0,
            'list' => $list,
        ];

        return response()->json($this->result);
    }

    /**
     * 列表页
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getList(Request $request)
    {
        $user = Auth::user();

        //todo 分页查询用户报名过的课程id
        $uid = $user->id;
        $cids = UserCourse::where('uid', $uid)->lists('cid');

        $list = [];
        if (count($cids) > 0) {
            $course = Course::whereIn('id', $cids)->where('id', '<>', 40)->get();
            foreach ($course as $row) {
                $list[] = [
                    'cid' => $row->id,
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

            //修正课程热度数据
            $sign_list = UserCourse::whereIn('cid', $cids)
                ->select(DB::raw('count(*) as num, cid'))
                ->groupby('cid')
                ->get()
                ->toArray();

            foreach ($sign_list as $v) {
                $cid = $v['cid'];
                $signs[$cid] = $v['num'];
            }

            $sign = 0;
            foreach ($list as &$row) {
                $cid = $row['cid'];
                if (isset($signs[$cid])) {
                    $sign = $signs[$cid] * 2; //修正报名热度
                }
                $row['hot'] = $row['hot'] + $sign; //修正报名热度
            }
        }
        $grouped = collect($list)->groupBy('status');
        $dataLiving = $grouped->get(2) ?: collect([]);
        $dataLiving = $dataLiving->sortBy('start_day');
        $dataReg = $grouped->get(1) ?: collect([]);
        $dataReg = $dataReg->sortBy('start_day');
        $dataReview = $grouped->get(3) ?: collect([]);
        $dataReview = $dataReview->sortByDesc('start_day');
        $list = $dataLiving
            ->merge($dataReg)
            ->merge($dataReview);

        $this->result['data'] = [
            'hasNextPage' => 0,
            'list' => $list,
        ];

        return response()->json($this->result);
    }

    //
    public function sign(Request $request)
    {
        $params = $request->all();

        //检查验证码是否正确
        $mobile = $params['phone'];
        $code = $params['code'];
        $time = time();
        $verifyCode = VerifyCode::where(['mobile' => $mobile, 'code' => $code])
            ->where('expired_in', '>', $time)
            ->first();
        if (!$verifyCode) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = '验证码不正确！';
            return response()->json($this->result);
        } else {
            $verifyCode->expired_in = $time -1;
            $verifyCode->save();
        }

        //更新用户信息
        $uid = $params['uid'];
        $cid = $params['cid'];
        $user = User::find($uid);
        $user->mobile = $mobile;
        $user->realname = $params['realname'];
        $user->crm_province = $params['province'];
        $user->crm_city = $params['city'];
        $user->baby_birthday = $params['birthday'];
        $user->save();

        //注册CRM用户信息
        $openid = $user->openid;
        if ($user->type == User::OPENID_TYPE_WX) {
            $crm = new Crm();
            $params = [
                'Mobiletel' => $mobile,
                'Mamaname' => $params['realname'],
                'Province' => $params['province'],
                'City' => $params['city'],
                'BBirthday' => $params['birthday'],
                'Wxopenid' => $openid,
            ];
            $sign_res = $crm->signUser($params);
            if (!$sign_res['Flag']) {
                $this->result['status'] = 0;
                $this->result['error_msg'] = $sign_res['Message'];
                return response()->json($this->result);
            }
            //更新用户状态
            $user->crm_status = 1;
            $user->save();
        } else if ($user->type == User::OPENID_TYPE_SQ) {
            $mobileQQ = new MobileQQ();
            $sign_res = $mobileQQ->signUser($user);
            if (!$sign_res['status']) {
                $this->result['status'] = 0;
                $this->result['error_msg'] = $sign_res['Message'];
                return response()->json($this->result);
            }
            //更新用户状态
            $user->crm_status = 1;
            $user->save();
        }

        //记录用户统计日志
        $courseStat = CourseStat::where(['uid' => $uid, 'cid' => $cid])->first();
        if ($courseStat) {
            $courseStat->sign_time = date("Y-m-d H:i:s");
            $courseStat->save();
        }

        $courseIds = explode('.', $cid);
        foreach($courseIds as $courseId){
            $userCourse = UserCourse::firstOrCreate(['uid' => $uid, 'cid' => $courseId]);
            $userCourse->channel = Session::get('channel');
            $userCourse->save();
        }

        if (mb_strlen($request->input('redirect')) > 0) {
            $this->result['status'] = 2;
            $this->result['error_msg'] = 'go to back';
        }
        return response()->json($this->result);
    }

    /*
    public function sign(Request $request)
    {
        $params = $request->all();

        //检查验证码是否正确
        $mobile = $params['phone'];
        $code = $params['code'];
        $time = time();
        $code_status = VerifyCode::where(['mobile' => $mobile, 'code' => $code])
            ->where('expired_in', '<', $time)
            ->count();

        if ($code_status) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = 'code invalid';
            return response()->json($this->result);
        } else {
            //todo 更新验证码状态
        }

        //更新用户信息
        $uid = $params['uid'];
        $cid = $params['cid'];
        $data = [
            'mobile' => $mobile,
            'realname' => $params['realname'],
            'crm_province' => $params['province'],
            'crm_city' => $params['city'],
            'baby_birthday' => $params['birthday'],
        ];

        $model = User::where('id', $uid);

        $affectedRows = $model->update($data);
        if (!$affectedRows) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = "save data fail";
            return response()->json($this->result);
        }

        //注册用户信息
        $crm = new Crm();
        $openid = User::where('id', $uid)->pluck('openid');
        $params = [
            'Mobiletel' => $mobile,
            'Mamaname' => $params['realname'],
            'Province' => $params['province'],
            'City' => $params['city'],
            'BBirthday' => $params['birthday'],
            'Wxopenid' => $openid,
        ];
        $sign_res = $crm->signUser($params);
        if (!$sign_res['Flag']) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = $sign_res['Message'];
            return response()->json($this->result);
        } else {
            //更新用户状态
            $model->update(['crm_status' => 1]);

            //记录用户统计日志
            $courseStat = CourseStat::where(['uid' => $uid, 'cid' => $cid])->first();
            if ($courseStat) {
                $courseStat->sign_time = date("Y-m-d H:i:s");
                $courseStat->save();
            }
        }

        //记录用户报名用户表
        $userCourse = UserCourse::firstOrCreate(['uid' => $uid, 'cid' => $cid]);
        $userCourse->channel = Session::get('channel');
        $userCourse->save();

        $user = User::find($uid);
        if ($user->type == User::OPENID_TYPE_WX) {
            //发送模版消息
            $is_subscribed = $user->subscribe_status;
            if ($is_subscribed) {
                $course = Course::where('id', $cid)->first();
                $content = "您已成功报名" . date("m月d日", strtotime($course->start_day)) . "妈妈微课堂《" . $course->title . "》\n";
                $content .= "讲师：" . $course->teacher_name . " " . $course->teacher_hospital . "\n";
                $content .= "开始时间：" . date("Y年m月d日", strtotime($course->start_day)) . " " . date("H:i", strtotime($course->start_time));

                //发送模版消息
                $params = [
                    'openid' => $openid,
                    'content' => $content,
                    'url' => config('app.url') . '/mobile/mine?&_hw_c=tplmsg',
                    'remark' => '记得要来准时听课哦～',
                ];
                $wxWyeth = new WxWyeth();
                $wxWyeth->pushpushCustomMessage($params);
            }
        } else if ($user->type == User::OPENID_TYPE_SQ) {
            //同步数据到手Q
            $sq_service = new MobileQQ();
            $sq_info = $sq_service->searchMemberInfo($openid);
            if ($sq_info['status'] && empty($sq_info['data'])) {
                $sq_service->signUser($user);
            }
        } else {}

        return response()->json($this->result);
    }
    */

    //todo 惠氏用户报名
    public function crmSign(Request $request)
    {
        $uid = $request->input('uid');
        $cid = $request->input("cid");

        if (empty($cid) || empty($uid)) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = '请求参数错误';
            return response()->json($this->result);
        }

        //课程信息
        $course = Course::where('id', $cid)->first();
        if (empty($course)) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = '课程不存在';
            return response()->json($this->result);
        }

        //用户信息
        $user = User::where('id', $uid)->first();
        if (!$user) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = '用户不存在';
            return response()->json($this->result);
        }

        //检查用户报名数量
        $usercouses = CourseService::reg($cid);
        if ($usercouses >= $course->sign_limit) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = '报名人数已满';
            return response()->json($this->result);
        }

        //用户是crm用户
        $crm_status = $user->crm_status;
        if (!$crm_status) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = '您不是crm用户';
            return response()->json($this->result);
        }
        //记录用户报名信息
        $userCourse = UserCourse::where(['cid' => $cid, 'uid' => $uid])->first();
        if (empty($userCourse)) {
            $userCourse = new UserCourse();
            $userCourse->cid = $cid;
            $userCourse->uid = $uid;
            $userCourse->channel = Session::get('channel');
            $userCourse->save();

            //记录用户统计日志
            $courseStat = CourseStat::where(['uid' => $uid, 'cid' => $cid])->first();
            if ($courseStat) {
                $courseStat->sign_time = date("Y-m-d H:i:s");
                $courseStat->save();
            }

            //CIData统计报名
            Cidata::init(config('oneitfarm.appkey'));
            Cidata::sendEvent($uid, $user->channel, null, 'sign', ['cid' => $cid, 'wyeth_channel' => Session::get('channel')]);

            $is_subscribed = $user->subscribe_status;
            if ($is_subscribed && $user->type == User::OPENID_TYPE_WX) {
                // 推荐课程
                $params = CourseService::recommendCourseIdGet($user, $course);
                Log::info('crmSign', [
                    'uid' => $user->id,
                    'sid' => $params?$params['sign_up_course']->id : 0,
                    'rid' => $params?$params['recommend_course']->id : 0
                ]);
                if ($params) {
                    $job = (new SendTemplateMessageBySignUp($params));
                    $this->dispatch($job);
                }
            }
        }

        //判断课程是否开始
        $status = $course->status;
        if ($status == 2) {
            //课程开始了
            $this->result['data']['url'] = config('app.url') . '/mobile/living?cid=' . $cid;
        } else {
            //$this->result['data']['url'] = config('app.url') . '/mobile/course_ok?cid=' . $cid . '&uid=' . $uid;
            $this->result['data']['url'] = config('app.url') . '/mobile/sign?cid=' .$cid;
        }
        return response()->json($this->result);
    }

    //发送验证码
    public function sendCode(Request $request)
    {
        $mobile = $request->input('mobile');
        $code = rand(1111, 9999);

        $sms = new Sms();
        $send_ret = $sms->sendCode($mobile, $code);

        if ($send_ret['status'] == 0) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = $send_ret['error_msg'];
            return response()->json($this->result);
        }

        //记录短信信息
        $model = new VerifyCode();
        $model->mobile = $mobile;
        $model->code = $code;
        $model->expired_in = time() + 1800;
        $model->save();

        return response()->json($this->result);
    }

    //记录用户分享信息
    public function share(Request $request)
    {
        if ($request->has('cid') && $request->has('type')) {
            $cid = $request->input('cid');
            $type = $request->input('type');
            $uid = Auth::id();

            //记录用户统计日志
            $courseStat = CourseStat::where(['uid' => $uid, 'cid' => $cid])->first();
            if ($courseStat) {
                if ($type == 1) {
                    $courseStat->share_sign_page = 1;
                } else if ($type == 2) {
                    $courseStat->share_living_page = 1;
                } else {
                    $courseStat->share_review_page = 1;
                }

                $courseStat->save();
            }
        }

        return response()->json($this->result);
    }

    public function adList()
    {
        $courses = Course::where('display_status', 1)
            ->whereIn('status', [Course::COURE_REG_STATUS, Course::COURSE_END_STATUS])
            ->whereIn('user_type', [0, User::OPENID_TYPE_WX])
            ->where('id', '<>', '40')
            ->orderBy('status', 'asc')
            ->orderBy('start_day', 'asc')
            ->limit(100)
            ->get();

        //课程信息
        $course_ids = [];
        foreach ($courses as $row) {
            if ($row->status == Course::COURSE_END_STATUS) {
                $course_ids[] = $row->id;
            }

            $data[] = [
                'cid' => $row->id,
                'title' => $row->title,
                'img' => $row->img,
                'start_day' => $row->start_day,
                'start_time' => date("H:i", strtotime($row->start_time)),
                'end_time' => date("H:i", strtotime($row->end_time)),
                'teacher_name' => $row->teacher_name,
                'teacher_avatar' => $row->teacher_avatar,
                'teacher_hospital' => $row->teacher_hospital,
                'teacher_position' => $row->teacher_position,
                'audio' => '',
                'status' => $row->status,
            ];
        }

        //查询回顾课程音频
        if ($course_ids) {
            $course_reviews = CourseReview::whereIn('cid', $course_ids)->get();
            foreach ($course_reviews as $r) {
                $cid = $r['cid'];
                $reviews[$cid] = $r->video;
            }

            foreach ($data as &$row) {
                $cid = $row['cid'];
                if (isset($reviews[$cid])) {
                    $row['audio'] = $reviews[$cid];
                } else {
                    if ($row['status'] == Course::COURSE_END_STATUS) {
                        $row['status'] = Course::COURE_REG_STATUS;
                    }
                }
            }
        }

        $this->result['data'] = $data;

        return response()->json($this->result);
    }

    //根据cid查课程
    public function getByCid(Request $request){
        $cid = $request->input('cid');

        if ($cid == 10000) {
            $data = [
                'cid' => $cid,
                'title' => '魔栗指南丨生完娃也能不“月半”，做辣妈超简单~',
                'img' => 'https://wyeth-uploadsites.nibaguai.com/wyethcourse/activity/5/molizhinan.png',
                'url' => 'http://mama-weiketang-wyeth.woaap.com/mobile/hd?aid=5&_hw_c=xxjp_10000'
            ];
        } elseif ($cid == 10001) {
            $data = [
                'cid' => $cid,
                'title' => '养娃后就只能吃土？分享一个赚“大钱”的小窍门',
                'img' => 'http://mmbiz.qpic.cn/mmbiz_jpg/TPicbosy1q8ichtZ1GCmKYtmRZhQtpHtHRsUUibVE6jvKb51KJYkFvFOIfyibUic3QjmhGONcnSRGjaa8ib2L31fPTAQ/640?tp=webp&wxfrom=5&wx_lazy=1',
                'url' => 'http://e.cn.miaozhen.com/r/k=2057321&p=7C0DS&dx=__IPDX__&rt=2&ns=__IP__&ni=__IESID__&v=__LOC__&xa=__ADPLATFORM__&tr=__REQUESTID__&mo=__OS__&m0=__OPENUDID__&m0a=__DUID__&m1=__ANDROIDID1__&m1a=__ANDROIDID__&m2=__IMEI__&m4=__AAID__&m5=__IDFA__&m6=__MAC1__&m6a=__MAC__&vo=329c3f4d5&vr=2&o=http%3A%2F%2Fmp.weixin.qq.com%2Fs%2FqZ9KQFXeJVHKoHTNP73GcA'
            ];
        }else {
            $row = Course::find($cid);
            if (!$row){
                $this->result['status'] = 0;
                $this->result['error_msg'] = '课程不存在';
                return response()->json($this->result);
            }

            $data = [
                'cid' => $row->id,
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
                'url' => config('app.url') . "/mobile/end?cid=$cid&_hw_c=xxjp_$cid"
            ];

            $mq_cids = [393,301,343,367,407,210,427,297,52,389,166,218,320,423,265];
            if (in_array($cid, $mq_cids)){
                $data['title'] = '养娃后就只能吃土？分享一个赚“大钱”的小窍门';
                $data['img'] = 'http://mmbiz.qpic.cn/mmbiz_jpg/TPicbosy1q8ichtZ1GCmKYtmRZhQtpHtHRsUUibVE6jvKb51KJYkFvFOIfyibUic3QjmhGONcnSRGjaa8ib2L31fPTAQ/640?tp=webp&wxfrom=5&wx_lazy=1';
                $data['url'] = 'http://e.cn.miaozhen.com/r/k=2057321&p=7C0DS&dx=__IPDX__&rt=2&ns=__IP__&ni=__IESID__&v=__LOC__&xa=__ADPLATFORM__&tr=__REQUESTID__&mo=__OS__&m0=__OPENUDID__&m0a=__DUID__&m1=__ANDROIDID1__&m1a=__ANDROIDID__&m2=__IMEI__&m4=__AAID__&m5=__IDFA__&m6=__MAC1__&m6a=__MAC__&vo=329c3f4d5&vr=2&o=http%3A%2F%2Fmp.weixin.qq.com%2Fs%2FqZ9KQFXeJVHKoHTNP73GcA';
            }
        }

        $this->result['data'] = $data;

        return response()->json($this->result);
    }

    //根据edc(宝宝预产期或生日)查3节精品课
    public function getListByEdc(Request $request){
        $openid = $request->input('openid');
        if(!$openid) {
            $this->result['status'] = 0;
            $this->result['error_msg'] = 'no openid';
            return response()->json($this->result);
        }

        //现在下行指南10000 和MQ推文100001，给两个固定的id
        $this->result['data'] = [10000, 10001];
        return response()->json($this->result);

        $babyBirthday = $request->input('edc');
        if($babyBirthday) {
            $babyBirthday = strtotime($babyBirthday);
        }

        //一次先取30条
        $query = Course::where('is_competitive', 1)
            ->where('display_status', 1)
            ->limit(30);

        $now = time();
        if (!$babyBirthday) {
            $stage = 0;
        } else if ($babyBirthday <= $now) {
            $stage = 3;
        } else if (($babyBirthday - $now) > 60*60*24*30*7) {
            $stage = 1;
        } else {
            $stage = 2;
        }

        if ($stage > 0) {
            if($stage == 1){
                //早期
                $query->where('course.stage_from','<', 203);
                $query->where('course.stage_from','>', 100);
            }else if($stage == 2){
                //中晚期
                $query->where(function ($query){
                    $query->whereBetween('course.stage_to', [203, 210]);
                    $query->orWhere(function ($query) {
                        $query->whereBetween('course.stage_from', [203, 210]);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('course.stage_to', '<', 203);
                        $query->where('course.stage_from', '>', 210);
                    });
                });

            }else if($stage == 3){
                //宝宝
                $query->where('course.stage_to', '>=', 300);
                $query->where('course.stage_from', '>=', 100);
            }
        }

        $data = $query->get()->pluck('id')->all();
        //随机取三个
        if (count($data) >= 3){
            $result = [];
            $rand = array_rand($data, 3);
            foreach ($rand as $k => $v){
                $result[] = $data[$v];
            }
            $data = $result;
        }else{
            //少于3条随机补充其他精品课
            $other_num = 3 - count($data);
            $not_in_cid = $data;
            $other_course = Course::where('cid', 39)
                ->where('display_status', 1)
                ->whereNotIn('id', $not_in_cid)
                ->limit($other_num)
                ->get()
                ->pluck('id')
                ->all();
            $data = array_merge($data, $other_course);

        }
        $this->result['data'] = $data;

        return response()->json($this->result);
    }
    
    //记录自动下行精品课的数量
    //没有cid只要总数
    public function xxjp(Request $request){
        $cid = $request->input('cid', 0);
        $push_num = $request->input('push_num');
        $push_count = $request->input('push_count');
        $t_date = $request->input('t_date', '0000-00-00 00:00:00');

        if (!$push_num || !preg_match("/^\d*$/", $push_num)){
            $this->result['status'] = 0;
            $this->result['error_msg'] = 'push_num不合法';
            return response()->json($this->result);
        }

        if (!$push_count || !preg_match("/^\d*$/", $push_count)){
            $this->result['status'] = 0;
            $this->result['error_msg'] = 'push_count不合法';
            return response()->json($this->result);
        }

        $time = strtotime($t_date);
        if (!$time){
            $this->result['status'] = 0;
            $this->result['error_msg'] = 't_date不合法';
            return response()->json($this->result);
        }

//        $course = Course::find($cid);
//        if (!$course){
//            $this->result['status'] = 0;
//            $this->result['error_msg'] = '课程不存在';
//            return response()->json($this->result);
//        }

        $created_at = date('Y-m-d H:i:s');
        $insert = DB::table('huiyao_xxjp')->insertGetId([
            'cid' => $cid,
            'push_num' => $push_num,
            'push_count' => $push_count,
            't_date' => date('Y-m-d H:i:s', $time),
            'created_at' => $created_at,
            'updated_at' => $created_at
        ]);
        if ($insert){
            $this->result['status'] = 1;
            $this->result['data'] = ['id' => $insert];
        }else{
            $this->result['status'] = 0;
            $this->result['error_msg'] = '插入失败';
        }

        return response()->json($this->result);
    }
}
