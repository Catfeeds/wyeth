<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\UserCourse;
use App\Services\CounterService;
use Cache;
use DB;
use Illuminate\Console\Command;

class CountUserCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'count:usercourse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '课程报名人数计数器初始化';

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
        // Cache::tags('counter')->flush();
        $courses = Course::get();
        foreach ($courses as $v) {
            $count = UserCourse::where('cid', '=', $v->id)->count();
            if ($count > 0) {
                CounterService::put(CounterService::TYPE_COURSE_REG, ['all', $v->id], $count);
            }

            // 按天进行计数
            $result = DB::table('user_course')
                ->where('cid', $v->id)
                ->groupBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))
                ->select('user_course.id', 'cid', DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') AS day, count(*) AS nums"))
                ->orderBy('user_course.id', 'asc')
                ->get();
            if ($result) {
                foreach ($result as $dayCount) {
                    CounterService::put(CounterService::TYPE_COURSE_REG, ['day', $v->id, $dayCount->day], $dayCount->nums);
                    echo "Done $v->id $dayCount->day $dayCount->nums \r\n";
                }
            }
            echo "Done $v->id \r\n";
        }
        echo "Done All \r\n";
    }
}
