<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\UserFriend;
use App\Models\UserFriendLog;
use App\Services\WxWyeth;
use Illuminate\Console\Command;
use App\Models\UserCourse;
use App\Models\User;

class InitFriendship extends Command
{
    /**
     * The name and signature of the console command.
     * cid int  课程id
     * test bool 是否测试
     * @var string
     */
    protected $signature = 'init:friendship';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert friendship data  into user_friend table';

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
     * cid ke
     * @return mixed
     */
    public function handle()
    {
        $logs = UserFriendLog::where('from_uid', '>', 0)
            ->where('to_uid', '>', 0)
            ->groupBy('from_uid','to_uid')
            ->get();

        if ($logs) {
            foreach ($logs as $log) {
                $from_uid = $log->from_uid;
                $to_uid = $log->to_uid;
                UserFriend::firstOrCreate(['from_uid' => $from_uid, 'to_uid' => $to_uid]);
                UserFriend::firstOrCreate(['from_uid' => $to_uid, 'to_uid' => $from_uid]);
            }
        }
    }
}
