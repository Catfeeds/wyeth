<?php

namespace App\Console\Commands;

use File;
use App\Models\Course;
use App\Models\User;
use Illuminate\Console\Command;
use App\Services\WxWyeth;
use App\Jobs\SendTemplateMessage;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SendTpls extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:tpls {cid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送模板消息';

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
        $cid = $this->argument("cid");

        $template_id = 4;

        $course = Course::find($cid);
        if (!$course) {
            return 'course error';
        }
        $params = [
            'title' => $course->notify_title,
            'content' => $course->notify_content,
            'odate' => $course->notify_odate ?: $course->start_date . ' ' . date("H:i", strtotime($course->start_time)),
            'address' => $course->notify_address,
            'remark' => "\n" . $course->notify_remark,
            'url' => $course->notify_url,
        ];

        $logPath = storage_path() . "/app/tpls/$cid.txt";
        if (!File::exists($logPath)) {
            echo 'file not exists';
            exit;
        }
        $handle = @fopen($logPath, "r");
        $i = 0;
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $openid = trim($buffer);
                if ($openid) {
                    $job = (new SendTemplateMessage($params, $openid, User::SHOP_ALL, $template_id));
                        $this->dispatch($job);
                }
                \Log::debug("Send Tpls $i\t$openid");
                $i++;
            }
            if (!feof($handle)) {
                $this->debug("Error: unexpected fgets() fail");
            }
            fclose($handle);
        }
    }
}
