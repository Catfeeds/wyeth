<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/20
 * Time: 下午3:34
 */

namespace App\CIService;

//账号服务

use App\CIService\Lib\CiApi;
use App\Helpers\SessionKey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Account extends BaseCIService
{
    /**
     * 中台登录
     * @param bool $flush 是否重新获取token
     * @param \App\Models\User $user 传入的user
     * @return bool|mixed
     */
    public function login($flush = false, $user = null){
        if (!$user){
            if (!$user = Auth::user()){
                return false;
            }
        }

        $token = Session::get(SessionKey::CI_ACCOUNT_TOKEN);

        if (!$user->account_id || !$token || $flush){
            $res = $this->ciapi->apiCall(CiApi::API_ACCOUNT_THIRD_APP, [
                'openid' => $user->id,
                'nickname' => $user->nickname,
                'avatar_url' => $user->avatar,
                'create_user' => 1
            ]);

            if ($res['ret'] != 1 || !$res['account_id'] || !$res['token']){
                return false;
            }
            if (!$user->account_id){
                $user->account_id = $res['account_id'];
                $user->save();
            }
            $token = $res['token'];
            Session::put(SessionKey::CI_ACCOUNT_TOKEN, $token);

        }

        return $token;
    }

    //获取中台account_id
    public function getAccountId(){
        $user = Auth::user();
        if (!$user){
            return false;
        }
        if (!$user->account_id){
            $this->login();
        }
        return $user->account_id;
    }

    /**
     * 获取中台微信登录url
     * @param $redirect_uri
     * @return string
     */
    public function getWxOauthUrl($redirect_uri){
        $params = $this->getJWTParams([
            'appkey' => $this->appkey,
            'redirect_uri' => $redirect_uri,
            'scope' => 'snsapi_userinfo'
        ]);
        return $this->domain . "/account/main.php?action=wx_oauth.html&" . http_build_query($params);
    }
}