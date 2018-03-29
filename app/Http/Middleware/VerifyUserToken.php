<?php namespace App\Http\Middleware;

use App\CIService\Account;
use App\Helpers\SessionKey;
use App\Helpers\WyethError;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use JWTAuth;
use JWTFactory;
use Auth;
use Config;
use Tymon\JWTAuth\Token;

/**
 * 根据user_token获取用户
 */

class VerifyUserToken
{
    public function handle(Request $request, Closure $next)
    {
        //访客身份登录
        $visitor = $request->input('user');
        if ($visitor && $visitor == 'visitor'){
            $user = User::find(10000);
            Auth::setUser($user);
            return $next($request);
        }

        //for test
        if ($request->input('uid') && $request->input('debug') == 'true') {
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
                return $next($request);
            }
        }
        
        $error = new WyethError();

        try {
            //从url获取
            if (! $token = $this->getToken()) {
                //从session获取
                if (! $token = Session::get(SessionKey::USER_TOKEN)){
                    //从input获取
                    if (! $token_request = $request->input(SessionKey::USER_TOKEN)){
                        return $this->returnError($error->NO_USER_TOKEN ,$request);
                    }
                    if (! $token = new Token($token_request)){
                        return $this->returnError($error->NO_USER_TOKEN,$request);
                    }
                }
            }

            $payload = JWTAuth::decode($token);
            $uid = $payload->get('uid');
            $user = User::find($uid);

        } catch (TokenExpiredException $e) {
            return $this->returnError($error->USER_TOKEN_EXPIRED ,$request);
        } catch (JWTException $e) {
            return $this->returnError($error->USER_TOKEN_INVALID ,$request);
        }

        if (!$user){
            return $this->returnError($error->NO_USER ,$request);
        }
        Auth::setUser($user);

        return $next($request);
    }

    private function returnError($error, $request){
        $error['login_url'] = $this->getRedirectUrl($request);
        return response()->json($error);
    }

    private function getToken($method = 'bearer', $header = 'authorization', $query = SessionKey::USER_TOKEN){
        try {
            return JWTAuth::parseToken($method, $header, $query)->getToken();
        } catch (JWTException $e) {
            return false;
        }
    }

    /**
     * 获取oauth跳转url
     * @param Request $request
     * @return string
     */
    private function getRedirectUrl(Request $request)
    {

        $back_url = Config::get('app.url') .  "/mobile/index?defaultPath=/login";

        $redirect = Config::get('app.url') . '/wx_auth?back_url=' . base64_encode($back_url);

        if (config('oneitfarm.is_wyeth')){
            $params = [
                'code' => 52066926,
                'type' => 'userinfo',
                'redirect' => $redirect,
            ];
            $url = 'http://www.e-shopwyeth.com/pioneer/oauth/api/wyeth?' . http_build_query($params);
        }else{
            $url = (new Account())->getWxOauthUrl($redirect);
        }

        return $url;
    }



}