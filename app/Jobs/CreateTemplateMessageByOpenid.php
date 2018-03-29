<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\SendTemplateMessageByOpenid;
use App\Models\Course;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTemplateMessageByOpenid extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $cid;
    protected $openids;
    protected $template_id;
    protected $need_check;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cid, $openids, $template_id, $need_check = true)
    {
        $this->cid = $cid;
        $this->openids = $openids;
        $this->template_id = $template_id;
        $this->need_check = $need_check;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $course = Course::where('id', $this->cid)->first();
        $params = [
            'tpl_params' => [
                'title' => $course->notify_title,
                'content' => $course->notify_content,
                'odate' => $course->notify_odate ?: $course->start_date . ' ' . date("H:i", strtotime($course->start_time)),
                'address' => $course->notify_address,
                'remark' => "\n" . $course->notify_remark,
                'url' => $course->notify_url,
            ],
            'need_check' => $this->need_check,
            'template_id' => $this->template_id,
        ];
        foreach ($this->openids as $v) {
            $params['tpl_params']['openid'] = $v;
            $job = (new SendTemplateMessageByOpenid($params));
            $this->dispatch($job);
        }
    }
}
