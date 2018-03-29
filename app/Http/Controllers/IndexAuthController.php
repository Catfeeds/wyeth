<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Crm;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use JWTAuth, JWTFactory;

/**
 * oauth验证
 */
class IndexAuthController extends BaseController
{
    public function index(Request $request)
    {
        $this->_convertUrl($request);

        //授权获取openid
        $openid = $request->input('openid');
        $back_url = $request->input('back_url');
        $base_url = Config::get('app.url');

        if (!$openid) {
            $openid = Session::get('openid');
        }

        if (!$openid) {
            $params = array(
                'code' => 52066926,
                'type' => 'userinfo',
                'redirect' => $base_url . '/indexAuth?back_url=' . $back_url,
            );
            $url = 'http://www.e-shopwyeth.com/pioneer/oauth/api/wyeth?' . http_build_query($params);
            return Redirect::to($url, 301);
        }

        //记录openid
        if ($openid) {
            Session::put('openid', $openid);
        }

        $nickname = $request->input('nickname');
        if (!$nickname) {
            $nickname = User::where('openid', $openid)->pluck('nickname');
        }

        $headimgurl = $request->input('headimgurl');

        if ($openid && empty($nickname)) {
            //继续授权获取用户头像和昵称
            $params = array(
                'redirect' => $base_url . '/indexAuth?back_url=' . $back_url,
            );
            $url = 'http://www.e-shopwyeth.com/pioneer/oauth2/api_info?' . http_build_query($params);
            return Redirect::to($url, 301);
        }

        $crm = new Crm();
        $crm_status = $crm->isCrmMember($openid);

        //保存用户信息
        $userinfo = User::where(['openid' => $openid])->first();
        if ($userinfo) {
            $uid = $userinfo->id;
            User::where('openid', $openid)
                ->update(['nickname' => $nickname, 'avatar' => urldecode($headimgurl), 'crm_status' => $crm_status]);
        } else {
            $model = new User();
            $model->openid = $openid;
            $model->nickname = $nickname;
            $model->avatar = urldecode($headimgurl);

            //查询用户是否crm系统用户
            $crm = new Crm();
            $model->crm_status = $crm_status;

            //保存用户信息
            $model->created_at = date('Y-m-d H:i:s');
            $model->save();
            $uid = $model->id;
        }

        // 使用jwt生成token
        $payload = JWTFactory::make(['user_type' => 'user', 'uid' => $uid, 'nickname' => $nickname]);
        Session::put('token', JWTAuth::encode($payload));
        $back_url = Session::get($back_url);
        return Redirect::to($back_url);
    }

    //倘若用户是分享过来的，只需要openid，并不需要获取用户头像和昵称
    public function login(Request $request)
    {
        $this->_convertUrl($request);

        //授权获取openid
        $openid = $request->input('openid');
        $back_url = $request->input('back_url');
        $base_url = Config::get('app.url');

        if (!$openid) {
            $openid = Session::get('openid');
        }

        if (!$openid) {
            $params = array(
                'code' => 52066926,
                'type' => 'basic',
                'redirect' => $base_url . '/index?back_url=' . $back_url,
            );
            $url = 'http://www.e-shopwyeth.com/pioneer/oauth/api/wyeth?' . http_build_query($params);
            return Redirect::to($url, 301);
        }

        //保存用户信息
        Session::put('openid', $openid);

        $userinfo = User::where('openid', $openid)->first();
        if (!$userinfo) {
            $model = new User();
            $model->openid = $openid;

            //保存用户信息
            $model->created_at = date('Y-m-d H:i:s');
            $model->save();
            $uid = $model->id;
            $nickname = '';
        } else {
            $uid = $userinfo->id;
            $nickname = $userinfo->nickname;
        }

        // 使用jwt生成token
        $payload = JWTFactory::make(['uid' => $uid, 'nickname' => $nickname]);
        Session::put('token', JWTAuth::encode($payload));

        $back_url = Session::get($back_url);
        return Redirect::to($back_url);
    }

    //转换惠氏接口中的错误url
    private function _convertUrl($request)
    {
        $redirect = false;
        //奇葩，惠氏传过来的url会有两个问号
        $params = $request->query();
        foreach ($params as $k => $v) {
            if (strpos($v, '?') !== false) {
                $redirect = true;
                $v = str_replace('?', '&', $v);
                $arr = GuzzleHttp\Psr7\parse_query($v);
                foreach ($arr as $key => $val) {
                    if (empty($val)) {
                        $params[$k] = $key;
                    } else {
                        $params[$key] = $val;
                    }
                }
            }
        }
        if ($redirect) {
            $url = $request->url() . '?' . http_build_query($params);
            header('Location:' . $url);
            exit();
            //return Redirect::to($url);
        }
    }
}
