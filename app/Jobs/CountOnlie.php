<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Course;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CountOnlie extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $course;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Course $course)
    {
        //
        $this->course = $course;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $cid = $this->course->id;
        $this->callSilent('repaire:onlinestatistics', ['cid' => $cid]);
    }
}
