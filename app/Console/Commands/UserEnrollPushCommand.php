<?php

namespace App\Console\Commands;

use App\Jobs\UserEnrollPushJob;
use App\Models\CoursePush;
use App\Models\User;
use App\Models\UserEnrollPush;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UserEnrollPushCommand extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enroll:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '慧摇报名推送';

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
        $page = 0;
        $per_page = 1000;
        $i = 0;

        do {
            $list = UserEnrollPush::where('push_time', '<=', date('Y-m-d H:i:s'))
                ->where('status', 0)
                ->skip($page * $per_page)
                ->take($per_page)
                ->get();

            foreach ($list as $item){
                $i++;
                $this->dispatch(new UserEnrollPushJob($item));
            }
            $page++;
        }while(count($list));

        //记录到course_push 中
        $course_push = (new CoursePush())->fill([
            'cid' => 0,
            'type' => CoursePush::TYPE_ENROLL_PUSH,
            'push_time' => date('Y-m-d H:i:s'),
            'status' => CoursePush::COURSE_PUSH_SEND,
            'push_num' => $i,
            'remark' => '',
        ]);
        $course_push->save();
        \Log::info("每天慧摇报名定时推送{$i}条");
    }
}
