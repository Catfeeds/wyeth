<?php

namespace App\Providers;

use App\Services\CensorService;
use Illuminate\Support\ServiceProvider;

// 敏感词
class CensorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('censor', function () {
            return new CensorService();
        });

        //使用bind绑定实例到接口以便依赖注入
        $this->app->bind('App\Contracts\CensorContract', function () {
            return new CensorService();
        });
    }
}
