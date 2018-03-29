<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\User;
use App\Services\WxWyeth;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * 更新用户
 * Class UpdateUser
 * @package App\Jobs
 */
class UpdateUser extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $uid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->uid = $params['uid'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->uid) {
            return '';
        }
        try {
            $wxWyeth = new WxWyeth();
            //重新获取这些用户是否关注信息
            $user = User::find($this->uid);
            if (!$user || $user->type != User::OPENID_TYPE_WX) {
                return '';
            }
            $subscribeStatus = $wxWyeth->getSubscribeStatus($user->openid);
            if ($subscribeStatus != $user->subscribe_status) {
                $user->subscribe_status = $subscribeStatus;
                $user->save();
                \Log::info("User Update Subscribe openid {$user->openid} $subscribeStatus");
            }
        } catch (\Exception $e) {
            \Log::alert('User Update Subscribe Error '. $e->getMessage());
        }
    }
}
