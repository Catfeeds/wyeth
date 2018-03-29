<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportOpenid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:openid {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导出openid文件';

    protected $file_path;

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
        $action = $this->argument('action');
        $this->file_path = '/opt/ci123/www/wyeth_dev/wyeth_xj/storage/users/';

        switch ($action) {
            case 'course':
                $this->course();
                break;
            case 'edc':
                $this->edc();
                break;
            default :
                $this->error('action不合法');
        }
    }

    //导出活跃用户（来上过课的）
    private function course()
    {
        ini_set('memory_limit', '500M');

        $start = $this->ask('输入start时间');
        $end = $this->ask('输入end时间');
        if (!$end) {
            $end = date('Y-m-d', strtotime('+1 day'));
        }

        $file = $this->file_path . "{$start}~{$end}course.txt";

        $user_events = DB::connection('mysql_read')->table('user_events')
            ->select(DB::raw('DISTINCT uid'))
            ->where('created_at', '>', $start)
            ->where('created_at', '<', $end)
            ->where('type', 'review_in')
            ->get();
        if (!$user_events) {
            $this->error('no user');
            exit();
        }

        $bar = $this->output->createProgressBar(count($user_events));

        foreach ($user_events as $item) {
            $uid = $item->uid;
            $user = DB::connection('mysql_read')->table('user')
                ->select('openid')
                ->where('id', $uid)
                ->first();
            if ($user) {
                file_put_contents($file, $user->openid . "\n", FILE_APPEND);
            }
            $bar->advance();
        }
        $bar->finish();
    }

    //根据宝宝生日导出用户openid
    private function edc()
    {
        ini_set('memory_limit', '800M');

        $start = $this->ask('输入start时间');

        $file = $this->file_path . "{$start}~edc.txt";


        $users = DB::connection('mysql_read')->table('user')
            ->select('openid')
            ->where('baby_birthday', '>', $start)
            ->get();

        $bar = $this->output->createProgressBar(count($users));

        foreach ($users as $item) {
            file_put_contents($file, $item->openid . "\n", FILE_APPEND);
            $bar->advance();
        }
        $bar->finish();
    }
}
