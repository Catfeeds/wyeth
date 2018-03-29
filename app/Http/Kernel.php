<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\DebugDev::class,
//        \App\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'oauth' => \App\Http\Middleware\Oauth::class,
        'admin.checkLogin' => \App\Http\Middleware\Admin\CheckLogin::class,
        'admin.checkAnchorLogin' => \App\Http\Middleware\Admin\CheckAnchorLogin::class,
        'admin.checkTeacherLogin' => \App\Http\Middleware\Admin\CheckTeacherLogin::class,
        'loginCrm' => \App\Http\Middleware\LoginCrm::class,
        'subscribed' => \App\Http\Middleware\Subscribed::class,
        'share' => \App\Http\Middleware\Share::class,
        'signCourse' => \App\Http\Middleware\SignCourse::class,
        'courseHot' => \App\Http\Middleware\CourseHot::class,
        'jwt.auth' => \App\Http\Middleware\GetUserFromToken::class,
        'jwt.refresh' => Tymon\JWTAuth\Middleware\RefreshToken::class,
        'resource.version' => \App\Http\Middleware\ResourceVersion::class,
        'signature' => \App\Http\Middleware\Signature::class,
        'channel' => \App\Http\Middleware\GetUserChannel::class,
        'token' => \App\Http\Middleware\VerifyToken::class,
        //验证user_token
        'jwt.user_token' => \App\Http\Middleware\VerifyUserToken::class,
    ];
}
