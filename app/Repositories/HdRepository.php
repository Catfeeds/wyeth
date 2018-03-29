<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/22
 * Time: 下午3:31
 */

namespace App\Repositories;


use App\CIService\Account;
use App\CIService\Hd;
use App\Helpers\CacheKey;
use App\Models\Activity;
use App\Models\AppConfig;
use App\Models\CiAppConfig;
use App\Models\User;
use App\Services\MqService;
use Illuminate\Support\Facades\Auth;
use Cache;

class HdRepository extends BaseRepository
{
    protected $hd;

    protected $draw_aid; //抽奖活动aid 有主
    protected $draw_aid_no; //无主无品牌的活动aid
    protected $account_id;

    public function __construct()
    {
        parent::__construct();
        $this->hd = new Hd();
        $this->draw_aid = config('oneitfarm.draw_aid');
        $this->draw_aid_no = config('oneitfarm.draw_aid_no');
        $this->account_id = (new Account())->getAccountId();
    }

    //增加抽奖次数
    public function addChance($num){

        if(!preg_match('/^\d+$/', $num)){
            return $this->returnError('num 不合法');
        }


        //兑换次数
        $res = $this->hd->addChance($this->draw_aid, $this->account_id, $num);
        if ($res['ret'] != 1){
            return $res;
        }

        //消耗mq
        $mq = $num * MqService::getTypeArray()[MqService::CONSUME_TYPE_DRAW][0];
        $res = MqService::decrease(Auth::id(), MqService::CONSUME_TYPE_DRAW, $mq);
        return $res;
    }

    //获取抽奖次数
    public function getChance(){
        $res = $this->hd->getChance($this->draw_aid, $this->account_id);
        if ($res['ret'] != 1){
            return $res;
        }
        $left_times = $res['data']['left_times'];
        $user = Auth::user();
        $mq = MqService::getUserMq($user->id);
        return $this->returnData([
            'left_times' => $left_times,
            'mq' => $mq,
            'hd_url' => $this->getDrawUrl($user),
            'draw_bg' => AppConfig::getModuleKeyData(AppConfig::MODULE_OTHER_INDEX, AppConfig::KEY_OTHER_DRAW_BG)
        ]);
    }

    /**
     * 获取转转乐的地址 带token登录跳转
     * @param User $user
     * @return string
     */
    public function getDrawUrl($user = null)
    {
        if (!$user){
            $user = Auth::user();
        }
        if ($user->youzhu <= 0 && $user->brand <= 1){
            //无主无品牌和无主Club
            $aid = $this->draw_aid_no;
        }else{
            $aid = $this->draw_aid;
        }
        $token = (new Account())->login(false, $user);
        return (new Hd())->domain . "/hd/main.php?action=hs_login.html&aid=$aid&token=$token";
    }

    /**
     * 获取转转乐原始地址
     * @param null $user
     * @return string
     */
    public function getDrawRawUrl($user = null)
    {
        if (!$user){
            $user = Auth::user();
        }
        if ($user->youzhu <= 0 && $user->brand <= 1){
            //无主无品牌和无主Club
            $aid = $this->draw_aid_no;
        }else{
            $aid = $this->draw_aid;
        }
        return (new Hd())->domain . "/hd/hs/draw.html?act_id=$aid";
    }

    //获取活动页面
    public function getActivity(){
        $activity_data = CiAppConfig::where('module', 'activity')->where('key', 'spring_set')->get()->pluck('data')->first();
        $activities = explode(',', $activity_data['ids']);
        $data = [];
        foreach ($activities as $activity){
            $data['activities'][] = Activity::where('id', $activity)->first();
        }
        $data['background'] = $activity_data['bg'];
        $user_num = Cache::get(CacheKey::HD_CARD_USERS);
        if(!$user_num){
            $user_num = 45732;
        }
        $data['user_num'] = $user_num;
        return $this->returnData($data);
    }
}