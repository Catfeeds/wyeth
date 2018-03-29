<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Services\MobileQQ;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSQTemplateMessage extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $openid;
    private $message;
    private $courseId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($openid, $message, $courseId)
    {
        $this->openid = $openid;
        $this->message = $message;
        $this->courseId = $courseId;
    }

    /**
     * 发送一条SQ模板消息 sendTemplateMessage
     *
     * @return void
     */
    public function handle()
    {
        $sq = new MobileQQ();
        $sq->sendTemplateMessage($this->openid, $this->message, $this->courseId);
    }
}
