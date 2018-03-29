<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Crm;
use App\Services\MobileQQ;
use App\Models\User;
use App\Services\BLogger;

class UpdateUserInfo extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'user:update {start=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新用户CRM数据';

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
        $crm = new Crm();
        $mobileQQ = new MobileQQ();

        // $openid = "D593D4524D2EDD1855EBD700152A86F1x";
        // $info = $mobileQQ->searchMemberInfo($openid);
        // print_r($info);
        // exit;
        // $openid = "owtN6jp4MnDNgOq-fKA_oAaXLvNQ";
        // $info = $crm->searchMemberInfo($openid);
        // print_r($info);
        // exit;

        $this->log("job [{$this->signature}] is start");
        $start = $this->argument('start');
        $start = intval($start);
        User::where('id', '>', $start)->orderBy('id', 'asc')->chunk(3000, function ($users) use ($crm, $mobileQQ) {
            foreach ($users as $user) {
                $openid = $user->openid;
                $log = [
                    $user->id,
                    $openid
                ];
                if ($user->type == User::OPENID_TYPE_WX) {
                    $info = $crm->searchMemberInfo($openid);
                    if ($info['Flag']) {
                        $user->crm_hasShop = $info['IsHaveShop'];
                        $user->crm_province = $info['Province'];
                        $user->crm_city = $info['City'];
                        $user->baby_birthday = $info['MemberEDC'];
                        $user->crm_NeverBuyIMF = $info['NeverBuyIMF'];
                        $user->crm_status = 1;
                        $log[] = 'WX_TRUE';
                    } else {
                        $user->crm_status = 0;
                        $log[] = 'WX_FALSE';
                    }
                    $user->save();
                } else if ($user->type == User::OPENID_TYPE_SQ) {
                    $info = $mobileQQ->searchMemberInfo($openid);
                    if ($info['data']) {
                        // 有会员数据
                        $user->crm_province = $info['data']['province'];
                        $user->crm_city = $info['data']['city'];
                        $user->baby_birthday = $info['data']['babyBirthday'];
                        $user->mobile = $info['data']['phone'];
                        // $user->realname = $info['data']['name'];
                        $user->crm_status = 1;
                        $log[] = 'SQ_TRUE';
                    } else {
                        // 无会员数据
                        if ($user->crm_province && $user->crm_city && $user->baby_birthday) {
                            $mobileQQ->signUser($user);
                            $user->crm_status = 1;
                            $log[] = 'SQ_FALSE_NEW';
                        } else {
                            $user->crm_status = 0;
                            $log[] = 'SQ_FALSE';
                        }
                    }
                    $user->save();
                }
                $this->log(implode("\t", $log));
            }
        });
        $this->log("job [{$this->signature}] is end");
    }

    private function log($message) {
        $log = BLogger::getLogger('command');
        $log->info("[{$this->signature}]{$message}");
        echo $message. "\n";
    }

}
