<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ResourceVersion
{

    public function handle($request, Closure $next)
    {
        //版本参数
        View::share('resource_version', env('STATIC_RESOURCE_VERSION', 1));

        //用户uid
        $uid = Auth::id();
        View::share('uid', $uid);

        //用户类型
        $openid_type = Session::get('openid_type');
        View::share('openid_type', $openid_type);

        //定义统计page
        $page = 0;
        $path = $request->path();
        if ($request->has('cid')) {
            $cid = $request->input('cid');
            if ($path == 'mobile/reg') {
                $page = $cid . '1';
            } else if ($path == 'mobile/living') {
                $page = $cid . '2';
            } else if ($path == 'mobile/end') {
                $page = $cid . '3';
            }
        }

        switch ($path) {
            case 'mobile/index':
                $page = 1;
                break;
            case 'mobile':
                $page = 1;
                break;
            case 'mobile/mine':
                $page = 26;
                break;
            case 'mobile/card':
                $page = 20;
                break;
            case 'mobile/sign_ok':
                $page = 21;
                break;
            case 'mobile/course_ok':
                $page = 22;
                break;
            case 'mobile/attention':
                $page = 23;
                break;
            case 'mobile/attention':
                $page = 23;
                break;
            case 'mobile/attention':
                $page = 23;
                break;
        }

        View::share('page', $page);

        // 处理 webpack 生成的文件
        $fileName = base_path('resources/assets/output') . '/static.json';
        if (file_exists($fileName)) {
            $assetConfig = json_decode(file_get_contents($fileName), true);
            View::share('assetConfig', $assetConfig);
        }

        // alias view share
        // 静态资源版本号
        View::share('rv', env('STATIC_RESOURCE_VERSION', 1));
        // 静态资源网址前缀
        View::share('su', config('course.static_url'));

        return $next($request);
    }

}
