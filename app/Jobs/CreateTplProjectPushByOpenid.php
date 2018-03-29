<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\TplProject;
use App\Services\WxWyeth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTplProjectPushByOpenid extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $pid;
    protected $openids;
    protected $need_check;
    protected $abtest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pid, $openids, $abtest = '', $need_check = true)
    {
        $this->pid = $pid;
        $this->openids = $openids;
        $this->abtest = $abtest;
        $this->need_check = $need_check;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tpl_project = TplProject::find($this->pid);
        if (!$tpl_project) {
            return;
        }

        $params = [
            'pid' => $tpl_project->id,
            'title' => $tpl_project->notify_title,
            'content' => $tpl_project->notify_content,
            'odate' => $tpl_project->notify_odate,
            'address' => $tpl_project->notify_address,
            'remark' => "\n" . $tpl_project->notify_remark,
            'url' => $tpl_project->notify_url,
            'abtest' => $this->abtest
        ];
        \Log::debug('CreateTplProjectPushByOpenid:start '.count($this->openids), $params);
        $wx_wyeth = new WxWyeth();
        foreach ($this->openids as $v) {
            $params['openid'] = $v;
            $wx_wyeth->pushpushCustomMessage($params, $tpl_project->notify_template_id, $this->need_check);
        }
        \Log::debug('CreateTplProjectPushByOpenid:end '.count($this->openids), $params);
    }
}
