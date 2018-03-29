<?php

namespace App\Jobs;

use App\Helpers\WyethUtil;
use App\Jobs\Job;
use App\Models\AppConfig;
use App\Models\Course;
use App\Models\CoursePush;
use App\Models\TplProject;
use App\Models\User;
use App\Models\UserEvent;
use App\Services\Crm;
use App\Services\Leqee;
use App\Services\WxWyeth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

//自动推送的福利模板
class CreateTplmsgFromAutoPushFuli extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    protected $users;
    protected $need_check;
    protected $draw_push;
    protected $draw_push_default;

    //上过河马课的人
    protected $hema_uids;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users, $need_check = true, $draw_push = [])
    {
        $this->users = $users;
        $this->need_check = $need_check;

        $this->draw_push_default = [
            'content' => "亲爱的妈妈：\n\t魔栗妈咪学院为你准备专业的育儿知识，更给宝宝提供更多贴心福利。MQ专享的魔栗转转乐最新上线啦，小栗子特为你献上今日份的听课福利，勤听课赚积分，更多惊喜就等你戳开！",
            'item' => '听课奖励',
            'remark' => ''
        ];
        if (!isset($draw_push['content'])) {
            $draw_push['content'] = $this->draw_push_default['content'];
        }
        if (!isset($draw_push['item'])) {
            $draw_push['item'] = $this->draw_push_default['item'];
        }
        if (!isset($draw_push['remark'])) {
            $draw_push['remark'] = $this->draw_push_default['remark'];
        }
        $this->draw_push = $draw_push;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $wx_wyeth = new WxWyeth();
        foreach ($this->users as $u) {
            $params = $this->getTplParams($u);
            if ($params) {
                $wx_wyeth->pushpushCustomMessage($params, 5, $this->need_check);
            }
        }
    }

    //判断推哪条模板消息
    private function getTplParams($user)
    {
        $uid = $user['uid'];
        $openid = $user['openid'];
        $nickname = $user['nickname'];

        //默认推送转转乐模板
        $data = [
            'notify_title' => $this->draw_push['content'],
            'notify_content' => '',
            'notify_odate' => $this->draw_push['item'],
            'notify_address' => '',
            'notify_remark' => $this->draw_push['remark'],
            'notify_url' => config('app.url') . '/mobile/index?defaultPath=/exchange&_hw_c=' . CoursePush::getTypeChannel()[CoursePush::TYPE_FULI_DRAW],
            'type' => CoursePush::TYPE_FULI_DRAW
        ];

        $brand = (new Crm())->getMemberBrand($openid);
        $user_info = User::where('openid', $openid)->first();

//        if ($brand <= 1 && $user_info->youzhu <= 0) {
//            //给无主无品牌的推
//            $url = 'http://yiqitao.pgjk.com/leqee?id=9';
//            $url = WyethUtil::getParamsLink($url, 'fuli_link', ['url' => $url]);
////            $data = [
////                'notify_title' => "亲爱的妈妈：\n\t魔栗妈咪学院为你贴心准备专业的育儿知识，更给宝宝提供专业的营养补充。小栗子为你献上今日份的听课惊喜福利，就等你戳开！",
////                'notify_content' => '',
////                'notify_odate' => '听课奖励',
////                'notify_address' => '',
////                'notify_remark' => '',
////                'notify_url' => $url,
////                'type' => CoursePush::TYPE_FULI_DTC
////            ];
//            $data['notify_url'] = $url;
//            $data['type'] = CoursePush::TYPE_FULI_DTC;
//        }

        $params = [
            'openid' => $openid,
            'title' => $data['notify_title'],         //预约标题
            'content' => $nickname,     //预约人
            'odate' => $data['notify_odate'],         //预约项目
            'address' => date('Y-m-d'),     //预约时间
            'remark' => $data['notify_remark'],       //预约备注
            'url' => $data['notify_url'],
            'type' => $data['type'],
        ];
        return $params;
    }
}
