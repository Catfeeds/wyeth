<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CourseCounter;
use App\Models\Course;
use App\Models\CourseStat;

class CountLivingInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'count:living:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化表course_counts的字段course_living';

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
        foreach ($courses as $course) {
            $cid = $course->id;
            $courseLivingNum = CourseStat::where('cid', $cid)->where('in_class_time', '<>', '0000-00-00 00:00:00')->count();
            $courseCounter = CourseCounter::firstOrCreate(['item_id' => $cid, 'item_type' => 'course']);
            $courseCounter->course_living = $courseLivingNum;
            $courseCounter->save();
        }
    }
}
