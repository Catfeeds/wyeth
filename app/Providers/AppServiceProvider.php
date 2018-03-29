<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Lib\Gateway\Gateway AS ChatGateway;
use Queue;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 加载mns
        Queue::extend('mns', function() {
            return new \LaravelMns\Queue\Connectors\MnsConnector();
        });

        // 设置CHAT 的注册地址
        ChatGateway::seRegisterAddress(config('course.chat_register_address'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
