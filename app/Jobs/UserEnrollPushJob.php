<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Course;
use App\Models\CoursePush;
use App\Models\UserEnrollPush;
use App\Services\WxWyeth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEnrollPushJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $user_enroll_push;


    public function __construct(UserEnrollPush $push)
    {
        $this->user_enroll_push = $push;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->user_enroll_push || $this->user_enroll_push->status != 0){
            return;
        }

        $cid = $this->user_enroll_push->cid;
        $course = Course::find($cid);
        if (!$course){
            return;
        }

        $channel = CoursePush::getTypeChannel()[CoursePush::TYPE_ENROLL_PUSH];
        $url = replaceUrlParams($course->notify_url, '_hw_c', $channel);

        $params = [
            'openid' => $this->user_enroll_push->openid,
            'title' => $course->notify_title,
            'content' => $course->notify_content,
            'odate' => $course->notify_odate ?: $course->start_date . ' ' . date("H:i", strtotime($course->start_time)),
            'address' => $course->notify_address,
            'remark' => "\n" . $course->notify_remark,
            'url' => $url,
            'type' => CoursePush::TYPE_ENROLL_PUSH
        ];

        $wx = new WxWyeth();
        $res = $wx->pushpushCustomMessage($params, 4, true);
        $this->user_enroll_push->status = $res ? 1 : -1;
        $this->user_enroll_push->save();

    }
}
