<?php

namespace App\Http\Controllers;

use App\CIData\Cidata;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * 地址缩短服务
 */
class ShortUrlController extends Controller
{
    // 进行跳转
    function index(Request $request, $hash)
    {
        if (!$hash) {
            abort(403);
        }
        $shortUrl = new ShortUrl();
        $url = $shortUrl->decode($hash);
        if (!$url) {
            abort(404);
        }

        return redirect($url);
    }

    //统计外链
    function link(Request $request){
        $url = urldecode($request->input('url'));
        if (!$url){
            abort(404);
        }
        $openid = Session::get('openid');
        $action = $request->input('action');
        $label = $request->input('label');
        $value = $request->input('value');
        $event_params = null;
        if ($label){
            $event_params['label'] = $label;
        }
        if ($value){
            $event_params['value'] = $value;
        }
        if ($label == 'subject' && $value){
            $event_params = ['subject' => $value];
        }

        Cidata::init(config('oneitfarm.appkey'));
        Cidata::sendEvent($openid, null, null, $action, $event_params);

        return redirect($url);
    }

    //根据传过来的params统计外链
    function params_link(Request $request)
    {
        $url = urldecode($request->input('url'));
        if (!$url) {
            abort(404);
        }

        $openid = Session::get('openid');
        $action = $request->input('action');
        $params = json_decode(base64_decode($request->input('params')), true);
        if (!$params || !is_array($params)){
            $params = [];
        }

        $uid = null;
        if ($openid){
            $user = User::where('openid', $openid)->first();
            if ($user){
                $uid = $user->id;
            }
        }

        //用uid当做标识
        Cidata::init(config('oneitfarm.appkey'));
        Cidata::sendEvent($uid, null, null, $action, $params);

        return redirect($url);
    }

    //获取短链接
    function get_short_link(Request $request) {
        $short = new ShortUrl();
        $url = $request->input('pre_link');

        $exist = ShortUrl::where('url', $url)->first();
        if ($exist) {
            return $this->returnData(url('/url/' . $exist->hash));
        } else {
            return $this->returnData($short->encode($url));
        }
    }

    //成功返回数据
    protected function returnData($data = []){
        return [
            'ret' => 1,
            'data' => $data
        ];
    }
}
