<?php

namespace App\Listeners\Models;

use App\Models\Course;
use App\Models\UserCourseCat;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\CourseStat;
use App\Services\CounterService;
use Session;
use Redis;

class UserCourseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function created($userCourse)
    {
        $uid = $userCourse->uid;
        $cid = $userCourse->cid;

        // 记录用户统计日志
        $courseStat = CourseStat::firstOrCreate(['uid' => $uid, 'cid' => $cid]);
        $courseStat->sign_time = date("Y-m-d H:i:s");
        $courseStat->save();

        // 计数器总计
        CounterService::courseRegAllIncrement($cid);
        // 计数器日计
        CounterService::courseRegDayIncrement($cid, date('Y-m-d'));

        // 套课报名人数统计
        $course = Course::find($cid);
        if ($course && $course->cid) {
            $catid = $course->cid;
            UserCourseCat::firstOrCreate(['catid' => $catid, 'uid' => $uid]);
        }

    }
}
