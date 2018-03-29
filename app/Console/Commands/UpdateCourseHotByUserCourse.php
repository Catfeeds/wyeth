<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\CourseStat;
use App\Models\UserCourse;
use DB;
use Illuminate\Console\Command;

/**
 * 根据条件，定时更新course hot值
 * 热度值按照（报名人数*3+听课人数*5+回顾人数*6）/5，取整数吧，避免数字太大
 * 原名字 UpdateCourseHotByUserCourse，因为服务器很可能做了crontab,所以名字不改了。
 */
class UpdateCourseHotByUserCourse extends Command
{

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'course:UpdateCourseHotByUserCourse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'UpdateCourseHotByUserCourse';

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
     * cid ke
     * @return mixed
     */
    public function handle()
    {
        //报名人数   select cid, count(*) as c from user_course group by cid
        //备注 select cid, count(*) as c from course_stat where !isnull(sign_time) group by cid 应该用这个,但因为我写报名时漏写日志这块，所以用user_course
        $userCourses = UserCourse::select(DB::raw('cid, count(*) as number'))
            ->groupBy('cid')
            ->get();

        //听课人数   select cid, count(*) from course_stat where in_class_times > 0 group by cid
        $listeners = CourseStat::where('in_class_times', '>', 0)
            ->select(DB::raw('cid, count(*) as number'))
            ->groupBy('cid')
            ->get();

        //回顾人数   select cid, count(*) from course_stat where !is_null(in_review_time) group by cid
        //$reviews = CourseStat::whereNotNull('in_review_time')
        //$reviews = CourseStat::whereNull('in_review_time')
        $reviews = CourseStat::where('in_review_time', '<>', '0000-00-00 00:00:00')
            ->select(DB::raw('cid, count(*) as number'))
            ->groupBy('cid')
            ->get();

        $courses = []; //id,hot

        /* test
        $s = $reviews->toArray();
        $v = print_r($s,true);
        $this->info($v);
         */

        foreach ($userCourses as $userCourse) {
            $courses[$userCourse->cid] = $userCourse->number * 3;
        }
        foreach ($listeners as $listener) {
            if (isset($courses[$listener->cid])) {
                $courses[$listener->cid] += $listener->number * 5;
            } else {
                $courses[$listener->cid] = $listener->number * 5;
            }
        }
        foreach ($reviews as $review) {
            if (isset($courses[$review->cid])) {
                $courses[$review->cid] += $review->number * 6;
            } else {
                $courses[$review->cid] = $review->number * 6;
            }
        }

        $flag = [];
        foreach ($courses as $key => $course) {
            $course = round($course / 5);
            Course::where('id', $key)
                ->update([
                    'hot' => $course,
                ]);
            $flag[] = [
                'id' => $key,
                'hot' => $course,
            ];
        }

        $info = 'execution data: ' . print_r($flag, true);

        $this->info($info);

    }
}
