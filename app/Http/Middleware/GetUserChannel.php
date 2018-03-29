<?php namespace App\Http\Middleware;

use App\CIData\Cidata;
use App\Helpers\SessionKey;
use App\Models\CourseStat;
use App\Services\WoaapQrcodeService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class GetUserChannel
{

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        $channel = 'heywow';
        if ($request->has('from')) {
            $channel = $request->input('from');
            Session::put('channel', $channel);
        } else if ($request->has('scene_str')){
            //识别二维码进入的
            $qrcode = new WoaapQrcodeService();
            $params = $qrcode->getParamsBySceneStr($request->input('scene_str'));
            if ($params && isset($params['aid'])){
                $channel = 'hd' . $params['aid'];
                Session::put('channel', $channel);
            }
        } else if ($request->has('_hw_c')) {
            $channel = $request->input('_hw_c');
            Session::put('channel', $channel);
            //统计微信打开模板消息 并且非首页且含有cid来的
            if (strpos($channel, 'tpl') !== false && !($request->has('cid') && strpos($request->url(), '/mobile/index') == true)){
                Cidata::init(config('oneitfarm.appkey'));
                $cid = $request->input('cid', 0);
                $event_params = ['cid' => $cid, 'wyeth_channel' => $channel];
                if (strpos($channel, 'wxtpl_tjhg') !== false){
                    //统计回顾推荐的
                    $event_params['wxtpl_tjhg'] = 1;
                    $event_params['template_id'] = 2;
                }
                //统计模板消息类型
                if (strpos($channel, 'wxtpl_hg') !== false){
                    $event_params['template_id'] = 4;
                }elseif (strpos($channel, 'wxtpl_kk') !== false){
                    $event_params['template_id'] = 1;
                }
                //统计模板消息id
                $user = Auth::user();
                Cidata::sendEvent($user->id, $user->channel, $user->getUserProperties($user), 'open_tplmsg', $event_params);

                if ($cid){
                    //设置CourseStat的channel
                    $courseStat = CourseStat::firstOrCreate(['uid' => $user->id, 'cid' => $cid]);
                    $courseStat->channel = $channel;
                    $courseStat->save();
                }
            }
        } else if (Session::has('channel')) {
            $channel = empty(Session::get('channel')) ? $channel : Session::get('channel');
        } else {
            Session::put('channel', $channel);
        }

        //更新用户渠道

        if ($user && empty($user->channel)) {
            $user->channel = $channel;
            $user->save();
        }
        if ($user){
            View::share('user_channel', $user->channel);

            //用于cidata统计用户属性
            $properties = $user->getUserProperties($user);
            View::share('user_properties', $properties);
        }
        

        View::share('channel', $channel);


        return $next($request);
    }
}
