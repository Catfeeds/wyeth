<?php namespace App\Http\Middleware;

use Agent;
use App\CIService\Account;
use App\Helpers\SessionKey;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\UserRelation;
use Illuminate\Support\Facades\Auth;
use Closure;
use Config;
use Illuminate\Support\Facades\Session;
use JWTAuth;
use JWTFactory;
use App\Services\MobileQQ;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use View;
use App\Services\Crm;
use App\Services\WxWyeth;

class Oauth
{

    public function handle(Request $request, Closure $next)
    {
        $debug = $request->input('debug') == 'true' ? true : false;

        if (Session::has('browser')) {
            $browser = Session::get('browser');
        } else {
            $isMobile = Agent::isMobile();
            if (!$isMobile && !$debug) {
                return view('mobile.qq_share');
            }

            // qq手机 Mozilla/5.0 (Linux; U; Android 4.4.4; zh-cn; HM NOTE 1S Build/KTU84P) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/5.4 TBS/025489 Mobile Safari/533.1
            // V1_AND_SQ_6.2.1_326_HDBM_T QQ/6.2.1.2670 NetType/WIFI WebP/0.3.0 Pixel/720
            // 微信   Mozilla/5.0 (Linux; U; Android 4.4.4; zh-cn; HM NOTE 1S Build/KTU84P) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/5.4 TBS/025489 Mobile Safari/533.1
            // MicroMessenger/6.3.9.48_refecd3e.700 NetType/WIFI Language/zh_CN
            $server = $request->server->all();
            $agent = isset($server['HTTP_USER_AGENT']) ? $server['HTTP_USER_AGENT'] : '';
            if (strpos($agent, 'MicroMessenger') !== false || $debug) {
                //微信
                $browser = 'WXBrowser';
            } else {
                return '请通过微信公众号进行访问';
            }

            Session::put('browser', $browser);
        }

        //for test
        if ($request->input(SessionKey::USER_BRAND)) {
            Session::put(SessionKey::USER_BRAND, $request->input(SessionKey::USER_BRAND));
        }
        if ($request->input('uid') && $debug) {
            $uid = $request->input('uid');
            $user = User::find($uid);
            if ($user) {
                $openid = $user->openid;
                $openid_type = $user->type;
                Session::put('openid', $openid);
                Session::put('openid_type', $openid_type);
                Session::put('browser', $openid_type == User::OPENID_TYPE_WX ? 'WXBrowser' : 'SQ');
                Auth::setUser($user);
                // 使用jwt生成token
                $payload = JWTFactory::make(['user_type' => 'user', 'uid' => $user->id, 'nickname' => $user->nickname]);
                Session::put('token', JWTAuth::encode($payload));
                Session::put(SessionKey::USER_TOKEN, JWTAuth::encode($payload));
                View::share('openid', $openid);
                View::share('browser', $browser);
                return $next($request);
            }
        }

        $openid = Session::get('openid');

        // 用于
        View::share('openid', $openid);
        View::share('browser', $browser);


        //检查user_token是否过期,是否登录
        $is_login = true;
        try {
            $token = Session::get(SessionKey::USER_TOKEN);

            if ($token){
                $payload = JWTAuth::decode($token);
            }else{
                $is_login = false;
            }

        } catch (TokenExpiredException $e) {
            $is_login = false;
        } catch (JWTException $e) {
            $is_login = false;
        }
        if (!$is_login || !$openid){
            $url = $this->getRedirectUrl($request);
            return Redirect($url);
        }


        $user = User::where('openid', $openid)->first();
        if (!$user) {
            Session::flush();
            $url = $this->getRedirectUrl($request);
            return Redirect($url);
        }

        //没有unionid重新登录一下
        if (!$user->unionid){
            $url = $this->getRedirectUrl($request);
            return Redirect($url);
        }

        Auth::setUser($user);
        return $next($request);
    }

    /**
     * 获取oauth跳转url
     * @param Request $request
     * @return string
     */
    private function getRedirectUrl(Request $request)
    {
        $redirect = config('app.url') . '/wx_auth?back_url=' . base64_encode($this->getUri($request));

        $params = [
            'redirect' => $redirect
        ];

        $url = (is_https() ? 'https' : 'http') . '://wyeth.woaap.com/pioneer/oauth2/api_info?' . http_build_query($params);
        return $url;
    }

    /**
     * 获取顺序不变的url,$request->getUri()的参数会按顺序排列
     * @param Request $request
     * @return string
     */
    private function getUri(Request $request)
    {
        $qs = $request->server->get('QUERY_STRING');
        if ('' !== $qs) {
            $qs = '?'.$qs;
        }

        return config('app.url').$request->getBaseUrl().$request->getPathInfo().$qs;
    }

}
