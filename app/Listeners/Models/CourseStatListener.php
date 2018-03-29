<?php

namespace App\Listeners\Models;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\CourseStat;
use Session;

class CourseStatListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function created(CourseStat $courseStat)
    {
        if (empty($courseStat->channel)) {
            $courseStat->channel = Session::get('channel');
            $courseStat->save();
        }
    }
}
