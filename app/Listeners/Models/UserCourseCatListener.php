<?php

namespace App\Listeners\Models;

use App\Services\CounterService;

class UserCourseCatListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function created($userCourseCat)
    {
        // 计数器总计
        CounterService::courseCatRegAllIncrement($userCourseCat->catid);
    }
}
