<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\SendTemplateMessage;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTplmsgFromOpenids extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $params;

    protected $openids;

    protected $user_shop;

    protected $template_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params, $openids, $user_shop, $template_id)
    {
        $this->params = $params;
        $this->openids = $openids;
        $this->user_shop = $user_shop;
        $this->template_id = $template_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->params['url']) {
            return '';
        }

        foreach ($this->openids as $openid) {
            // \Log::debug("CreateTplmsgFromCourseOpenids: " . $openid);
            if ($openid) {
                $job = (new SendTemplateMessage($this->params, $openid, $this->user_shop, $this->template_id));
                $this->dispatch($job);
            }
        }

    }
}
