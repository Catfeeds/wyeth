<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\SendTemplateMessage;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Course;
use App\Models\UserCourse;

/**
 *
 * Class CreateSQTplmsgByOpenid
 * @package App\Jobs
 */
class CreateSQTplmsgByOpenid extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    private $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->params['cid']) {
            return '';
        }
        $course = Course::find($this->params['cid']);
        if (!$course) {
            return '';
        }

        $cid = $course->id;

        //generating
        $params = [
            //'accessToken' => $cryptnStr,
            //'templateid' => '52c0727fbd1873fa', //temp
            'first' => $course->notify_content, //开头内容
            'keynote1' => $course->notify_title, //标题
            'keynote2' => $course->notify_odate, //开课时间
            'keynote3' => $course->notify_address, //开课地点
            'url' => $course->notify_url , //jump link
            //'source' => '微课堂', //来源
        ];

        $openids = $this->params['openids'];
        if (count($openids) <= 0) {
            return '';
        }
        foreach ($openids as $openid) {
            // \Log::debug("CreateTplmsgFromCourse: " . $openid);
            if ($openid) {
                $this->dispatch(new SendSQTemplateMessage($openid, $params, $cid));
            }
        }
    }
}
