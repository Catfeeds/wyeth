<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\User;
use App\Models\UserCourse;
use App\Lib\Timer;
use App\Jobs\UpdateUser;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;


class UserUpdateSubscribe extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新今日将直播课程报名用户关注状态';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $timer = Timer::start();
        \Log::info('User Update Subscribe S '. json_encode($timer->mark()));
        //获取今日要直播的课程
        $time = date('Y-m-d', time());
        $courses = Course::where('start_day', $time)->get();
        $countAll = 0;
        foreach ($courses as $course) {
            \Log::info('User Update Subscribe Course S '. $course->id . ' ' . json_encode($timer->mark()));
            //获取报名这节课的所有人
            $query = UserCourse::select('id', 'uid')->where('cid', $course->id);
            $countCourse = 0;
            $maxId = 0;
            $perPage = 10000;
            do {
                // 注意这里必需要进行clone
                $queryCloned = clone $query;
                $userCourses = $queryCloned->where('id', '>', $maxId)->limit($perPage)->get();
                if (!$userCourses->isEmpty()) {
                    $lastItem = $userCourses->last();
                    $maxId = $lastItem->id;
                    foreach ($userCourses as $userCourse) {
                        $job = (new UpdateUser(['uid' => $userCourse->uid]))->onQueue('hw-wyeth-low');
                        $this->dispatch($job);
                    }
                }
            } while (!$userCourses->isEmpty());
            \Log::info('User Update Subscribe Course E '. $course->id . ' ' . json_encode($timer->mark()));
        }
        \Log::info('Update User Subscribe E '. json_encode($timer->mark()));
    }
}
