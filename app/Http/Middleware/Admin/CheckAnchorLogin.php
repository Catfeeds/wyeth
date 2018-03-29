<?php namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckAnchorLogin
{

    public function handle($request, Closure $next)
    {

        if (Session::has('anchor_info') || $request->route()->getUri() == 'admin/login') {
            return $next($request);
        } else {
            return Redirect('/admin/login');
        }
    }

}