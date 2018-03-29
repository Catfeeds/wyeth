<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Services\WxWyeth;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTemplateMessageByOpenid extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $tpl_params;
    protected $need_check;
    protected $template_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->tpl_params = $params['tpl_params'];
        $this->need_check = $params['need_check'];
        $this->template_id = $params['template_id'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $wxWyeth = new WxWyeth();
        $wxWyeth->pushpushCustomMessage($this->tpl_params, $this->template_id, $this->need_check);
    }
}
