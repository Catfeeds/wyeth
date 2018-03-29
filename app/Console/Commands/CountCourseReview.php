<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\CourseStat;
use App\Models\UserEvent;
use App\Services\CounterService;
use App\Services\UserEventService;
use Illuminate\Console\Command;

class CountCourseReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'count:course:review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '课程回顾 课程回顾点赞数 计数器初始化';

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
        $courses = Course::get();
        foreach ($courses as $v) {
            $cid = $v->id;
            // Cache::tags(['services', 'counter', $type])->forget($key, $num);
            // 课程回顾数
            $count = CourseStat::where('cid', $cid)->where('in_review_time', '<>', '0000-00-00 00:00:00')->count();
            if ($count > 0) {
                CounterService::put(CounterService::TYPE_COURSE_REVIEW, ['all', $v->id], $count);
            }
            // 课程回顾点赞数
            $reviewLikesNum = UserEvent::where('cid', $cid)->where('type', 'review_like')->where('data', 'give')->count();
            if ($reviewLikesNum) {
                CounterService::put(UserEventService::COUNTER_REVIEW_LIKE, ['all', $v->id], $reviewLikesNum);
            }

            echo "Done $v->id \r\n";
        }
        echo "Done All \r\n";
    }
}
