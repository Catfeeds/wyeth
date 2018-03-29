<?php namespace App\Http\Middleware;

use Auth;
use Closure;

/**
 * 根据auth中user的crm_status来判读用户是否是crm用户
 */
class LoginCrm
{

    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user->crm_status) {
            return $next($request);
        } else {
            return Redirect('/mobile/card');
        }
    }

}
