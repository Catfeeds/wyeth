<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\User;
use App\Services\Crm;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\WxWyeth;

class SendTemplateMessage extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $params;

    protected $openid;

    protected $user_shop;

    protected $template_id;

    protected $need_check;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params, $openid, $user_shop, $template_id, $need_check = true)
    {
        $this->params = $params;
        $this->openid = $openid;
        $this->user_shop = $user_shop;
        $this->template_id = $template_id;
        $this->need_check = $need_check;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->openid) {
            $openid = $this->openid;

            //检查用户是否需要是否有主
            $crm = new Crm();
            if ($this->user_shop != User::SHOP_ALL) {
                $memberInfo = $crm->searchMemberInfo($openid);
                if ($memberInfo['Flag'] == 0) {
                    return;
                }

                $crm_hasShop = $memberInfo['IsHaveShop'];
                if (($this->user_shop == User::SHOP_HAS && $crm_hasShop == 0) || ($this->user_shop == User::SHOP_NO && $crm_hasShop == 1)) {
                    return;
                }
            }
            $data = array_merge(['openid' => $openid], $this->params);
            // \Log::debug('Do Send Template Message '. json_encode($data));

            $wxWyeth = new WxWyeth();
            $wxWyeth->pushpushCustomMessage($data, $this->template_id, $this->need_check);
        }
    }
}
