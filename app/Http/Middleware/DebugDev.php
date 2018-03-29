<?php

namespace App\Http\Middleware;

use Closure;
use App;

/**
 * dev 测试分支切换
 * Class DebugDev
 * @package App\Http\Middleware
 */
class DebugDev
{
    public function handle($request, Closure $next)
    {
        $dev = $request->input('hw_dev');
        $request->dev = '';
        if ($dev && in_array($dev, ['jjy', 'zzk', 'xj', 'xzx', 'yz', 'lyh', 'fjc', 'glt', 'xsb', 'sxm', 'lhq'])) {
            setcookie('hw_dev', $dev, time() + 3600*24, '/');
            $request->dev = $dev;
        }
        if ($request->has('hw_dev_clean')) {
            setcookie('hw_dev', '', time() - 3600, '/');
        }

        // 生产环境, 并且有hw_dev cookie时,清除掉
        if (App::environment('production') && isset($_COOKIE['hw_dev'])) {
            setcookie('hw_dev', '', time() - 3600, '/');
        }

        $envStr = '';
        if (!App::environment('production')) {
            $envStr = App::environment();
        }
        view()->share('envStr', $envStr);


        return $next($request);
    }
}
