<?php namespace App\Http\Controllers;

use App\CIService\Account;
use App\Helpers\SessionKey;
use App\Helpers\Weixin\WxBizDataCrypt;
use App\Http\Controllers\Wyeth\WyethBaseController;
use App\Http\Middleware\VerifyToken;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Crm;
use App\Services\MqService;
use App\Services\Pregnotice;
use App\Services\WxWyeth;
use App\Services\BLogger;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Input;
use JWTAuth;
use JWTFactory;
use App\Lib\Timer;

/**
 * 微信oauth验证
 */
class WxAuthController extends WyethBaseController
{

    public function index(Request $request)
    {
        $timer = Timer::start();
        $log = BLogger::getLogger('api');

        $openid = Input::get('openid');
        $access_token = Input::get('access_token');
        $type = Input::get('type');


        $client = new Client(['timeout' => 5.0]);
        $back_url = base64_decode(Input::get('back_url'));
        $back_url_parsed = parse_url($back_url);
        if (!$back_url_parsed) {
            return 'back_url error';
        }


        //新版微信登录接口，用户信息在url里 2017-01-10 真想吐槽齐数的命名
        $result = json_decode($request->input('josninfo'), true);

        if (!$result && $request->input('josninfo')){
            //昵称中带有&会被截断，齐数还没改好，先暂时处理下
            $params = $request->all();
            $head = $params['josninfo'];
            unset($params['josninfo']);
            unset($params['back_url']);
            unset($params['sign']);
            foreach ($params as $k => $v) {
                $head .= "&{$k}{$v}";
            }
            $result = json_decode($head, true);
        }


        if (!isset($result['openid'])) {
            //齐数的bug。。。有时会返回失败，直接返回，防止循环跳转，试3次
            $log->error('新版微信登录失败', $result);
            $fail_count = Session::get(SessionKey::ERROR_COUNT, 0) + 1;
            Session::put(SessionKey::ERROR_COUNT, $fail_count);
            if ($fail_count >= 3) {
                return '微信登录失败，请重试';
            }
            return redirect($back_url);
        }
        Session::put(SessionKey::ERROR_COUNT, 0);
        $openid = $result['openid'];


        if ($result && array_key_exists('nickname', $result)) {
            $first = false; //是否为第一次注册

            $unionid = isset($result['unionid']) ? $result['unionid'] : '';
            if ($unionid) {
                //微信返回有unionid的话
                $user = User::where('unionid', $unionid)->first();
                if (!$user) {
                    //有openid 没有unionid 给慧摇接口注册的人
                    $user = User::where('openid', $openid)->first();
                    if (!$user) {
                        //完全的新人
                        $user = User::create(['openid' => $openid]);
                        $first = true;
                    } elseif (!$user->unionid) {
                        //第一次来
                        $first = true;
                    }
                } else {
                    $user->openid = $openid;
                }
            } else {
                $user = User::where('openid', $openid)->first();
                if (!$user) {
                    $user = User::create(['openid' => $openid]);
                    $first = true;
                }
            }
            $user->type = User::OPENID_TYPE_WX;
            $user->nickname = $result['nickname'];
            $user->sex = $result['sex'];
            $user->avatar = $result['headimgurl'];
            $user->country = $result['country'];
            $user->province = $result['province'];
            $user->city = $result['city'];
            $user->unionid = isset($result['unionid']) ? $result['unionid'] : '';
        } else {
            $log->info(__FUNCTION__ . '获取用户信息失败' . $openid, $result);
            return '获取用户信息失败';
        }


        //查询用户的crm用户信息
        $crm = new Crm();
        $memberInfo = $crm->searchMemberInfo($openid);
        if ($memberInfo && $memberInfo['Flag']) {
            $user->crm_province = $memberInfo['Province'];
            $user->crm_city = $memberInfo['City'];
            $user->baby_birthday = $memberInfo['MemberEDC'];
            $user->crm_status = $memberInfo['Member'] == 1 ? 1 : 0;
            $user->crm_hasShop = $memberInfo['IsHaveShop'];
            $user->crm_NeverBuyIMF = $memberInfo['NeverBuyIMF'];
        }

        $log->info(__FUNCTION__, ['timer' => $timer->mark(), 'type' => 'crm_done']);

        $user->save();
        if ($first) {
            Session::put(SessionKey::NEW_USER, 'new_user');
            MqService::increase($user->id, MqService::ADD_TYPE_REG);
        }
        $log->info(__FUNCTION__, ['timer' => $timer->mark(), 'type' => 'WxWyeth_done']);

        Session::put('openid', $openid);
        Session::put('openid_type', User::OPENID_TYPE_WX);
        // 使用jwt生成token
        $payload = JWTFactory::make([
            'user_type' => User::TYPE_USER,
            'uid' => $user->id,
            'nickname' => $user->nickname
        ]);
        $token = JWTAuth::encode($payload);
        Session::put('token', $token);
        Session::put(SessionKey::USER_TOKEN, $token);
        Auth::setUser($user);

        //孕期提醒的登录
        if ($type == 'pregnotice') {
            //孕期提醒的宝宝生日
            if (!$user->pregdate || $user->pregdate == '0000-00-00') {
                if ($user->baby_birthday && $user->baby_birthday != '0000-00-00 00:00:00') {
                    $user->pregdate = date('Y-m-d', strtotime($user->baby_birthday));
                    $user->save();
                }
            }
            //注册孕期提醒
            if (!$user->preg_id) {
                $preg_id = (new Pregnotice())->getPregId($user->id, $user->nickname, $user->avatar, $user->pregdate);
                if ($preg_id) {
                    $user->preg_id = $preg_id;
                    $user->save();
                }
            }
            $params = [
                'openid' => $openid, //微信openid
                'access_token' => $access_token, //微信access_token
                'wyeth_id' => $user->id, //惠氏微课堂user_id
                'preg_id' => $user->preg_id, //孕期提醒user_id
                'type' => $type, //pregnotice
                'account_id' => (new Account())->getAccountId() //中台账号account_id
            ];
            $params['token'] = (new VerifyToken())->getToken($params);
            $back_url = $back_url . (strpos($back_url, '?') === false ? '?' : '') . http_build_query($params);
        }

        //记录日志
        $log->info(__FUNCTION__, ['timer' => $timer->mark(), 'type' => 'oauth_done']);

        return redirect($back_url);
    }

    //微信小程序登录
    public function mini(Request $request)
    {
        $code = $request->input('code');
        $user_info = json_decode($request->input('userInfo'), true);
        $encrypted_data = $request->input('encryptedData');
        $iv = $request->input('iv');

        if (!$code) {
            return $this->returnError('no code');
        }
        if (!$user_info) {
            return $this->returnError('no user_info');
        }

        $appid = config('oneitfarm.mini_appid');
        $secret = config('oneitfarm.mini_secret');

        $client = new Client([
            'base_uri' => 'https://api.weixin.qq.com',
            'timeout' => 3,
            'http_errors' => false
        ]);
        $ret = $client->request('GET', '/sns/jscode2session', ['query' => [
            'appid' => $appid,
            'secret' => $secret,
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        ]]);
        $body = $ret->getBody();
        $result = json_decode($body, true);


        \Log::info(__FUNCTION__, $result);
        if (!isset($result['openid'])) {
            return $this->returnError($result['errcode'] . ' ' . $result['errmsg']);
        }

        //解密数据获取unionid
        $session_key = $result['session_key'];
        $decrypt = new WXBizDataCrypt($appid, $session_key);
        $err_code = $decrypt->decryptData($encrypted_data, $iv, $data);
        if ($err_code == 0) {
            $data = json_decode($data, true);
            $unionid = isset($data['unionId']) ? $data['unionId'] : '';

            \Log::info(__FUNCTION__ . '解密数据获取unionid', $data);
        } else {
            \Log::info(__FUNCTION__ . '解密失败' . $err_code);
            $unionid = '';
        }

        $mini_openid = $result['openid'];

        $first = false; //是否为第一次注册

        if ($unionid) {
            //有unionid的话
            $user = User::where('unionid', $unionid)->first();
            if (!$user) {
                $user = User::create(['unionid' => $unionid]);
                $first = true;
            }
            $user->mini_openid = $mini_openid;
        } else {
            $user = User::where('mini_openid', $mini_openid)->first();
            if (!$user) {
                $user = User::create(['mini_openid' => $mini_openid]);
                $first = true;
            }
        }
        $user->type = User::OPENID_TYPE_WX;

        //更新用户信息
        if (isset($user_info['nickName'])) {
            $user->nickname = $user_info['nickName'];
        }
        if (isset($user_info['avatarUrl'])) {
            $user->avatar = $user_info['avatarUrl'];
        }
        if (isset($user_info['gender'])) {
            $user->sex = $user_info['gender'];
        }
        if (isset($user_info['country'])) {
            $user->country = $user_info['country'];
        }
        if (isset($user_info['province'])) {
            $user->province = $user_info['province'];
        }
        if (isset($user_info['city'])) {
            $user->city = $user_info['city'];
        }

        //查询是否为crm会员
        if (config('oneitfarm.is_wyeth') && $unionid && $user->crm_status != 2) {
            $is_crm = (new Crm())->searchMemberByUnionid($unionid);
            $user->crm_status = $is_crm;
        }

        $user->save();

        //首次注册加积分
        if ($first) {
            MqService::increase($user->id, MqService::ADD_TYPE_REG);
            //设置小程序注册渠道
            $user->channel = 'wyeth_mini';
            $user->save();
        }

        // 使用jwt生成token
        $payload = JWTFactory::make([
            'user_type' => User::TYPE_USER,
            'uid' => $user->id,
            'nickname' => $user->nickname
        ]);
        $token = JWTAuth::encode($payload);

        Auth::setUser($user);

        $login_info = (new UserRepository())->getLoginInfo('mini');
        $login_info['user_token'] = $token->get();

        return $this->returnData($login_info);
    }
}
