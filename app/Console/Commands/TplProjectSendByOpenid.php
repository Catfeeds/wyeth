<?php

namespace App\Console\Commands;

use App\Jobs\CreateTplProjectPushByOpenid;
use App\Models\TplProject;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class TplProjectSendByOpenid extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tpl_project:send {tpl_project_id} {openid_path} {abtest?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '根据openid文件路径发送推送项目';

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
        $tpl_project_id = $this->argument('tpl_project_id');
        $tpl_project = TplProject::find($tpl_project_id);
        if (!$tpl_project){
            $this->exitError('no tpl_project');
        }

        $file_path = $this->argument('openid_path');
        if (!file_exists($file_path)){
            $this->exitError('文件不存在');
        }
        $content = file_get_contents($file_path);
        if (!$content){
            $this->exitError('文件没有内容');
        }

        $abtest = $this->argument('abtest') ?: '';

        $openid_list = explode("\n", $content);
        $openidArr = [];
        foreach ($openid_list as $k => $v) {
            $v = trim($v);
            if ($v && strlen($v) > 10 && strlen($v) < 60) {
                $openidArr[] = $v;
            }
        }

        $count = count($openidArr);
        $confirm = "推送pid:{$tpl_project->id} openid路径:{$file_path} 推送人数:{$count} abtest:{$abtest}";
        if (!$this->confirm($confirm . '确定吗?')) {
            exit();
        }

        $openidsChunks = array_chunk($openidArr, 500);
        if ($openidsChunks) {
            foreach ($openidsChunks as $openidArr) {
                $this->dispatch(new CreateTplProjectPushByOpenid($tpl_project->id, $openidArr, $abtest));
            }
        }
    }

    private function exitError($error){
        $this->error($error);
        exit();
    }
}
