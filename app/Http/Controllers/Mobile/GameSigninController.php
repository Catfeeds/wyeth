<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use App\Models\SigninItem;
use App\Models\SigninWinRecords;
use App\Models\SigninRecord;
use App\Models\Course;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\VerifyCode;
use App\Models\SigninGameConfig;
use App\Services\WxWyeth;
use DB;
use App\Services\Sms;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

class GameSigninController extends Controller
{
    protected $package = [];

    public function __construct()
    {

        $this->middleware('subscribed', ['only' => ['living', 'card']]);

        $this->middleware('signCourse', ['only' => ['living']]);

        $this->middleware('courseHot', ['only' => ['reg', 'living', 'end']]);

        $this->middleware('loginCrm', ['only' => ['end']]);

        //微信jssdk参数
        $wxWyeth = new WxWyeth();
        $this->package = $wxWyeth->getSignPackage();

        // openid token 指到所有的模板中
        $this->openid = Session::get('openid');
        $this->token = Session::get('token');
        View::share('openid', $this->openid);
        View::share('token', $this->token);
        View::share('package', $this->package);
    }

    /**
     * 报错页面, 必须return
     * @param  string $message 报错语
     * @return View
     */
    private function message_error($message)
    {
        return view('mobile.message_error', ['message' => $message]);
    }

    /**
     * @param Request $request
     * @param $signin_id 游戏id
     * @description 判断进入用户是否为游戏发起人 是:将标识传入并跳转到直播中页面 并且显示排行榜弹窗 否:跳转到签到页
     */
    public function jump(Request $request, $signin_id)
    {
        //判断当前用户是否为游戏发起人
        $user = Auth::user();
        $userId = $user->id;
        $openId = $request->input('from_openid');
        //获取游戏信息
        $signinItem = SigninItem::where(['id' => $signin_id]);
        $signinItemInfo = $signinItem->first();
        //获取游戏配置信息
        $signinGameConfig = SigninGameConfig::where('cid', '=', $signinItemInfo->cid)->first();
        //获奖名额
        $win_num = isset($signinGameConfig->win_num) && !empty($signinGameConfig->win_num) ? $signinGameConfig->win_num : SigninItem::AWARD_RANKING;
        if ($signinItemInfo->start_uid == $userId) {
            //判断游戏是否结束 结束查询是否获奖 跳转到完成 或者 失败页
            $course_id = $signinItemInfo->cid;
            $course = Course::select('status')->where('id', '=', $course_id)->first();
            //直播结束
            if ($course->status != Course::COURSE_LIVING_STATUS) {
                //查询当前游戏是否中奖
                $signOrder = $this->get_sign_order_by_id($signin_id);
                if ($signOrder > $win_num) {
                    $redirect_url = config('app.url') . '/mobile/game/signin/fail/' . $signin_id;
                } else {
                    $redirect_url = config('app.url') . '/mobile/game/signin/success/' . $signin_id;
                }
            } else {
                //当前用户为活动发起人 跳转到直播中页面 并显示弹窗
                $redirect_url = config('app.url') . '/mobile/living?cid=' . $course_id . '&signStr';
            }
        } else {
            //活动是否结束
            $signInfo = SigninItem::where('id', '=', $signin_id)->first();
            $courseId = $signInfo->cid;
            $course = Course::select('status')->where('id', '=', $courseId)->first();
            if ($course->status == Course::COURSE_LIVING_STATUS) {
                //当前用户不是活动发起人 跳转到签到页
                $redirect_url = config('app.url') . '/mobile/game/signin/sign_page/' . $signin_id;
            } else {
                //判断是否获奖
                $signOrder = $this->get_sign_order_by_id($signin_id);
                if ($signOrder > $win_num) {
                    $redirect_url = config('app.url') . '/mobile/game/signin/fail/' . $signin_id;
                } else {
                    $redirect_url = config('app.url') . '/mobile/game/signin/success/' . $signin_id;
                }
            }
        }
        return redirect($redirect_url);
    }

    /**
     * @param Request $request
     * @param $signin_id 游戏id
     * @description 签到页面
     */
    public function sign(Request $request, $signin_id)
    {
        //获取游戏发起人信息
        $sign_User_Info = SigninItem::where(['id' => $signin_id])->first();
        //判断游戏是否存在
        if (!$sign_User_Info) {
            echo 'Not Find!';
            die();
        }
        //获取游戏配置信息
        $data['signinGameConfig'] = $signinGameConfig = SigninGameConfig::where('cid', '=', $sign_User_Info->cid)->first();
        $start_uid = $sign_User_Info->start_uid;
        $userInfo = user::select('avatar', 'nickname')->where(['id' => $start_uid])->first();
        //判断直播状态 游戏是否结束
        $course_id = $sign_User_Info->cid;
        $course = Course::select('status')->where('id', '=', $course_id)->first();
        if ($course->status != Course::COURSE_LIVING_STATUS) {
            $signOrder = $this->get_sign_order_by_id($signin_id);
            if ($signOrder <= SigninItem::AWARD_RANKING) {
                return redirect(config('app.url') . '/mobile/game/signin/success/' . $signin_id);
            } else {
                return redirect(config('app.url') . '/mobile/game/signin/fail/' . $signin_id);
            }
        }
        //检查是否登录 是否关注公众号
        //检查是否为游戏发起人 是则调回游戏主页
        $user = Auth::user();
        $userId = $user->id;
        $checkUser = SigninItem::where(['id' => $signin_id, 'start_uid' => $userId]);
        if ($checkUser->count() > 0) {
            $signin = $checkUser->first();
            return redirect(config('app.url') . '/mobile/living?cid=' . $signin->cid . '&signStr');
        }
        //检查是否为当前游戏签到过
        $checkSign = SigninRecord::where(['uid' => $userId, 'sid' => $signin_id])->count();
        if ($checkSign > 0) {
            $data['checkStatus'] = 1;
        } else {
            $data['checkStatus'] = 0;
        }
        $data['signInfo'] = $sign_User_Info;
        $data['userInfo'] = $userInfo;
        return view('mobile.signingame.index', $data);
    }

    public function sign_insert(Request $request)
    {
        $signId = $request->input('signId');
        $user = Auth::user();
        $userId = $user->id;
        //判断是否为当前游戏发起人
        $checkSignItem = SigninItem::where(['id' => $signId, 'start_uid' => $userId]);
        if ($checkSignItem->count() > 0) {
            return $this->_show_ajax_error('不能给自己签到哟!');
        }
        //判断是否为当前游戏签到过
        $checkSign = SigninRecord::where(['uid' => $userId, 'sid' => $signId])->count();
        if ($checkSign > 0) {
            return $this->_show_ajax_error('你已为当前游戏签到过!');
        }
        //去签到
        $signinRecord = new SigninRecord();
        $signinRecord->sid = $signId;
        $signinRecord->uid = $userId;
        $signinRecord->save();
        if ($signinRecord) {
            //signin_item 表  signin_num + 1
            $signInfo = SigninItem::where(['id' => $signId])->first();
            $SignItemUpdate = SigninItem::find($signId);
            $SignItemUpdate->signin_num = $signInfo->signin_num + 1;
            $SignItemUpdate->save();
            return $this->_show_ajax(['id' => $signinRecord->id]);
        } else {
            return $this->_show_ajax_error('签到失败');
        }
    }

    /**
     * @param Request $request
     * @param $signin_id 游戏id
     * @description 任务完成页
     */
    public function success(Request $request, $signin_id)
    {
        //判断游戏是否存在
        $SignItem = SigninItem::where('id', '=', $signin_id);
        if ($SignItem->count() == 0) {
            echo 'Not Find!';
            die();
        }
        //课程信息
        $data['sign_info'] = $sign_Info = SigninItem::where(['id' => $signin_id])->first();
        $start_uid = $sign_Info->start_uid;
        //获取游戏配置信息
        $data['signinGameConfig'] = $signinGameConfig = SigninGameConfig::where('cid', '=', $sign_Info->cid)->first();
        //获奖名额
        $win_num = isset($signinGameConfig->win_num) && !empty($signinGameConfig->win_num) ? $signinGameConfig->win_num : SigninItem::AWARD_RANKING;
        //用户信息
        $data['userInfo'] = $userInfo = user::select('avatar', 'nickname')->where(['id' => $start_uid])->first();
        //判断当前用户是否为游戏发起人
        $user = Auth::user();
        $userId = $user->id;
        //判断是否为当前游戏发起人
        $checkSignItem = $SignItem->where('start_uid', '=', $userId);
        $isStartUser = 2;    //1 发起用户  2 签到用户
        if ($checkSignItem->count() > 0) {
            $isStartUser = 1;
        }
        //判断游戏是否获奖
        $signOrder = $this->get_sign_order_by_id($signin_id);
        if ($signOrder > $win_num) {
            //没有获奖跳转到失败页
            return redirect(config('app.url') . '/mobile/game/signin/fail/' . $signin_id);
            die();
        }
        //是否领过奖
        $signinWinRecord = SigninWinRecords::where('signin_item_id', $signin_id)->count();
        if ($signinWinRecord == 0) {
            $isAward = 1;
        } else {
            $isAward = 2;
        }
        $showAwardBtn = 0;
        if ($isStartUser == 1 && $isAward == 1) {
            $showAwardBtn = 1;
        }
        $data['showAwardBtn'] = $showAwardBtn;
        return view('mobile.signingame.done', $data);
    }

    /**
     * @param Request $request
     * @param $signin_id
     * @description 任务失败页面
     */
    public function fail(Request $request, $signin_id)
    {
        $signinItem = SigninItem::where(['id' => $signin_id]);
        if ($signinItem->count() == 0) {
            echo 'Not Find!';
            die();
        }
        $data['sign_info'] = $signInfo = $signinItem->first();
        //获取游戏配置信息
        $data['signinGameConfig'] = $signinGameConfig = SigninGameConfig::where('cid', '=', $signInfo->cid)->first();
        //课程信息
        $start_uid = $signInfo->start_uid;
        //用户信息
        $data['userInfo'] = $userInfo = user::select('avatar', 'nickname')->where(['id' => $start_uid])->first();
        //查询出所有我报名的课程
        $user = Auth::user();
        $userId = $user->id;
        $courseArr = [];
        $userCourse = UserCourse::select('cid')->where('uid', '=', $userId)->get()->toArray();
        for ($i = 0; $i < count($userCourse); $i++) {
            $courseArr[$i] = $userCourse[$i]['cid'];
        }
        //获取最近一期的报名课程
        $course = Course::select('id', 'title', 'img', 'teacher_name', 'teacher_desc')
            ->where('status', '=', Course::COURE_REG_STATUS)
            ->whereNotIn('id', $courseArr)
            ->where('start_day', '>=', strtotime(date('Y-m-d', time())))
            ->where('display_status', '=', 1)
            ->where('start_time', '>=', strtotime(date('H:i:s', time())))
            ->orderBy('start_day', 'desc')
            ->orderBy('start_time', 'asc')
            ->first();
        $data['course'] = $course;
        return view('mobile.signingame.fail', $data);
    }

    /**
     * @param Request $request
     * @param $signin_id
     * @description 填写用户物流信息页
     */
    public function user_info(Request $request, $signin_id)
    {
        //查询是否存在游戏
        $signItem = SigninItem::where('id', '=', $signin_id);
        if ($signItem->count() == 0) {
            echo 'Game Not Find!';
            die();
        }
        $data['signId'] = $signin_id;
        $data['signInfo'] = $signInfo = $signItem->first();
        //获取游戏配置信息
        $data['signinGameConfig'] = $signinGameConfig = SigninGameConfig::where('cid', '=', $signInfo->cid)->first();
        //获奖名额
        $win_num = isset($signinGameConfig->win_num) && !empty($signinGameConfig->win_num) ? $signinGameConfig->win_num : SigninItem::AWARD_RANKING;
        //判断游戏是否获奖
        $signOrder = $this->get_sign_order_by_id($signin_id);
        if ($signOrder > $win_num) {
            //没有获奖跳转到失败页
            return redirect(config('app.url') . '/mobile/game/signin/fail/' . $signin_id);
            die();
        }
        return view('mobile.signingame.info', $data);
    }

    /**
     * @param Request $request
     * @param $signin_id
     * @description 信息页提交地址 成功加载模板 success  失败则加载模板  fail
     */
    public function user_submit(Request $request, $signin_id)
    {
        //查询是否存在游戏
        $signItem = SigninItem::where('id', '=', $signin_id);
        if ($signItem->count() == 0) {
            echo 'Game Not Find!';
            die();
        }
        return view('mobile.signingame.success');
    }

    /**
     * @param Request $request
     * @description ajax 获取某一用户发起的游戏详细信息
     */
    public function get_sign_info_by_id_ajax(Request $request)
    {
    }

    /**
     * @param Request $request ->signin_id $request->limit
     * @description 获取某一用户发起游戏所在排名
     */
    public function get_sign_order_by_id_ajax(Request $request)
    {
        //获取当前
        $signId = $request->input('signId');
        $signItem = SigninItem::where('id', '=', $signId)->first();
        $signNum = $signItem->signin_num;
        $signId = $signItem->id;
        $courseId = $signItem->cid;
        $created_at = $signItem->created_at;
        $before = SigninItem::where('signin_num', '>', $signNum)->where('cid', '=', $courseId)->count();
        $equal = SigninItem::where('signin_num', '=', $signNum)->where('created_at', '<', $created_at)->where('cid', '=', $courseId)->count();
        $order = $before + $equal + 1;
        $result = [
            'order' => $order,
            'signNum' => $signNum
        ];
        return $this->_show_ajax($result);
    }

    public function get_sign_order_by_id($signId)
    {
        //获取当前
        $signItem = SigninItem::where('id', '=', $signId)->first();
        $signNum = $signItem->signin_num;
        $signId = $signItem->id;
        $courseId = $signItem->cid;
        $created_at = $signItem->created_at;
        $before = SigninItem::where('signin_num', '>', $signNum)->where('cid', '=', $courseId)->count();
        $equal = SigninItem::where('signin_num', '=', $signNum)->where('created_at', '<', $created_at)->where('cid', '=', $courseId)->count();
        return $before + $equal + 1;
    }

    /**
     * @param Request $request ->signin_id
     * @description 根据ID 获取前后10名
     */
    public function get_sign_ajax(Request $request)
    {
        $signId = $request->input('signId');
        $signItem = SigninItem::where('id', '=', $signId)->first();
        $signNum = $signItem->signin_num;
        $signId = $signItem->id;
        $courseId = $signItem->cid;
        $created_at = $signItem->created_at;
        //获取所有排名在我前面的
        $beforelMe = SigninItem::where('cid', '=', $courseId)
            ->where('signin_num', '>', $signNum)
            ->orderBy('signin_num', 'asc')
            ->orderBy('created_at', 'desc');
        //签到相同 时间比我早
        $equalMe = SigninItem::where('cid', '=', $courseId)
            ->where('signin_num', '=', $signNum)
            ->where('created_at', '<', $created_at)
            ->orderBy('created_at', 'desc');
        //签到相同时间比我晚
        $lateMe = SigninItem::where('cid', '=', $courseId)
            ->where('signin_num', '=', $signNum)
            ->where('created_at', '>', $created_at)
            ->orderBy('created_at', 'asc');
        //签到少于我的
        $lessMe = SigninItem::where('cid', '=', $courseId)
            ->where('signin_num', '<', $signNum)
            ->orderBy('signin_num', 'desc')
            ->orderBy('created_at', 'asc');
        if ($equalMe->count() >= 10) {
            $beforeRev = $equalMe->limit(10)->get()->toArray();
            $beforeArr = array_reverse($beforeRev);
        } else {
            $beforeLimit = 10 - ($equalMe->count());
            $before = $beforelMe->limit($beforeLimit)->get()->toArray();
            $equalRe = $equalMe->get()->toArray();
            $beforeArr = array_merge_recursive(array_reverse($before), array_reverse($equalRe));
        }
        if ($lateMe->count() >= 10) {
            $afterArr = $lateMe->limit(10)->get()->toArray();
        } else {
            $afterLimit = 10 - ($lateMe->count());
            $after = $lessMe->limit($afterLimit)->get()->toArray();
            $afterArr = array_merge_recursive($lateMe->get()->toArray(), $after);
        }
        $signMeItem[0] = $signItem->toArray();
        $orderArr = array_merge_recursive($beforeArr, $signMeItem, $afterArr);
        $orderResult = [];
        foreach ($orderArr as $key => $value) {
            $itemId = $value['id'];
            $itemUid = $value['start_uid'];
            $userOrder = SigninItem::select('signin_items.id', 'signin_items.start_uid', 'signin_items.signin_num', 'user.nickname', 'user.avatar')
                ->leftjoin('user', 'signin_items.start_uid', '=', 'user.id')
                ->where(['signin_items.id' => $itemId])->first();
            $orderResult[$key]['signId'] = $userOrder->id;
            $orderResult[$key]['nickname'] = $userOrder->nickname;
            $orderResult[$key]['sign_num'] = $userOrder->signin_num;
            $orderResult[$key]['avatar'] = $userOrder->avatar;
            $orderResult[$key]['order'] = $this->get_sign_order_by_id($userOrder->id);

        }
        return $this->_show_ajax($orderResult);
    }

    /**
     * @param Request $request ->signin_id $request->limit
     * @description 获取某一用户发起的游戏 最近签到的 {limit} 个好友
     */
    public function get_the_most_sign_by_id_ajax(Request $request)
    {
        $signId = $request->input('signId');
        $signinRecord = SigninRecord::select('signin_records.*', 'user.nickname')
            ->leftjoin('user', 'signin_records.uid', '=', 'user.id')
            ->orderBy('signin_records.created_at', 'desc')
            ->where(['sid' => $signId])->get();
        return $signinRecord;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 用户点击邀请好友链接判断 当前用户 当前课程下是否创建过游戏  没有则创建一条
     */
    public function checkSign(Request $request)
    {
        $cid = $request->input('cid');
        $user = Auth::user();
        $userId = $user->id;
        $check = SigninItem::where(['cid' => $cid, 'start_uid' => $userId]);
        if ($check->count() > 0) {
            $signin = $check->first();
            $signin_id = $signin->id;
            $result = ['cid' => $cid, 'signid' => $signin_id];
        } else {
            $signinItem = new SigninItem();
            $signinItem->cid = $cid;
            $signinItem->start_uid = $userId;
            $signinItem->save();
            $result = ['cid' => $cid, 'signid' => $signinItem->id];
        }
        return $this->_show_ajax($result);
    }

    // 返回error
    protected function _show_ajax_error($msg, $status = 1)
    {
        $result = [
            'status' => $status,
            'error_msg' => $msg,
            'data' => [],
        ];
        return response()->json($result);
    }

    protected function _show_ajax($data, $status = 0)
    {
        $result = [
            'status' => $status,
            'error_msg' => '',
            'data' => $data,
        ];
        return response()->json($result);
    }

    public function sendCode(Request $request)
    {
        $response = new Response();
        $mobile = $request->input('mobile');
        $code = rand(1111, 9999);
        $sms = new Sms();
        $send_ret = $sms->sendCode($mobile, $code);
        if ($send_ret['status'] == 0) {
            $result['status'] = 0;
            $result['error_msg'] = $send_ret['error_msg'];
        } else {
            //将记录写入数据库
            $model = new VerifyCode();
            $model->mobile = $mobile;
            $model->code = $code;
            $model->expired_in = time() + 1800;
            $model->save();
            $result = [
                'status' => 1,
                'error_msg' => '',
                'data' => [],
            ];
        }
        return response()->json($result);
    }

    public function user_info_insert(Request $request)
    {
        $signId = $request->input('signId');
        $realname = $request->input('uName');
        $mobile = $request->input('uMobile');
        $code = $request->input('uCode');
        $address = $request->input('uAddr');
        $description = $request->input('uDescription');
        $verifyCode = VerifyCode::where(['mobile' => $mobile, 'code' => $code])
            ->where('expired_in', '>', time());
        if ($verifyCode->count() == 0) {
            return $this->_show_ajax_error('验证码错误');
            die();
        }
        $verifyInfo = $verifyCode->first();
        if ($mobile != $verifyInfo->mobile) {
            return $this->_show_ajax_error('手机号码有误');
            die();
        }
        //判断当前游戏是否领过奖
        $signinWinRecords = SigninWinRecords::where('signin_item_id', '=', $signId);
        if ($signinWinRecords->count() > 0) {
            return $this->_show_ajax_error('您已提交过过信息。', 2);
            die();
        }
        $signinItem = SigninItem::where(['id' => $signId])->first();
        if (!$signinItem) {
            echo 'Game Not Find!';
            die();
        }
        //获取游戏配置信息
        $signinGameConfig = SigninGameConfig::where('cid', '=', $signinItem->cid)->first();
        //获奖名额
        $win_num = isset($signinGameConfig->win_num) && !empty($signinGameConfig->win_num) ? $signinGameConfig->win_num : SigninItem::AWARD_RANKING;
        //获取排名
        $signOrder = $this->get_sign_order_by_id($signId);
        //判断是否获奖
        if ($signOrder > $win_num) {
            return $this->_show_ajax_error('未查询到您的获奖记录!');
        }
        //插入数据到领奖记录表
        $signinWinRecords = new SigninWinRecords;
        $signinWinRecords->signin_item_id = $signId;
        $signinWinRecords->mobile = $mobile;
        $signinWinRecords->realname = $realname;
        $signinWinRecords->address = $address;
        $signinWinRecords->remark = $description;
        $signinWinRecords->save();
        if ($signinWinRecords) {
            $result = ['id' => $signinWinRecords->id];
            return $this->_show_ajax($result);
        } else {
            return $this->_show_ajax_error('提交错误');
        }
    }
}
