<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CourseCounter;
use App\Models\Course;
use App\Models\CourseCat;
use App\Services\CourseReviewService;
use App\Services\CounterService;

class CountToDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'count:to:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新数据表course_counts的字段[\'course_reg\',\'course_cat_reg\',\'course_review\']';

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
        $courseCats = CourseCat::get();
        foreach ($courses as $course) {
            $cid = $course->id;
            $courseRegNum = CounterService::courseRegAllGet($cid);
            $courseReviewNum = CourseReviewService::countAllGet($cid);
            $courseCounter = CourseCounter::firstOrCreate(['item_id' => $cid, 'item_type' => 'course']);
            $courseCounter->course_reg = $courseRegNum;
            $courseCounter->course_review = $courseReviewNum;
            $courseCounter->save();
        }
        foreach ($courseCats as $courseCat) {
            $courseCatId = $courseCat->id;
            $courseCatRegNum = CounterService::courseCatRegAllGet($courseCatId);
            $courseCounter = CourseCounter::firstOrCreate(['item_id' => $courseCatId, 'item_type' => 'course_cat']);
            $courseCounter->course_cat_reg = $courseCatRegNum;
            $courseCounter->save();
        }
    }
}
