<?php namespace App\Http\Middleware;

use Auth;
use App\Models\User;
use Closure;
use App\Services\Crm;
use App\Services\WxWyeth;

/**
 * 根据auth中user的subscribe_status来判读用户是否关注
 */
class Subscribed
{

    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user->subscribe_status) {
            return $next($request);
        }

        // 没关注的用户再主动获一次用户的信息
        // 查询用户的关注状态
        // $wxWyeth = new WxWyeth();
        // $is_subscribed = $wxWyeth->getSubscribeStatus($user->openid);
        // if ($is_subscribed) {
        //     $user->subscribe_status = User::WX_SUBSCRIBE_STATUS_YES;
        //     // 查询用户的crm用户信息
        //     $crm = new Crm();
        //     $memberInfo = $crm->searchMemberInfo($user->openid);
        //     if ($memberInfo['Flag']) {
        //         $user->crm_province = $memberInfo['Province'];
        //         $user->crm_city = $memberInfo['City'];
        //         $user->baby_birthday = $memberInfo['MemberEDC'];
        //         $user->crm_status = ($memberInfo['Member'] == 1) ? 1 : 0;
        //         $user->crm_hasShop = $memberInfo['IsHaveShop'];
        //         $user->crm_NeverBuyIMF = $memberInfo['NeverBuyIMF'];
        //     }
        //     $user->save();
        //     return $next($request);
        // }

        return Redirect('/mobile/attention');
    }

}
