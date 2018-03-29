<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/14
 * Time: 上午10:17
 */

namespace App\Repositories;


use App\Helpers\SessionKey;
use App\Models\AppConfig;
use App\Models\CiAppConfig;
use App\Models\User;
use App\Models\UserMq;

use App\Services\Crm;
use App\Services\MqService;
use App\Services\Pregnotice;
use App\Services\WxWyeth;
use Auth;
use Illuminate\Support\Facades\Session;
use App\Repositories\UserTagRepository;
use App\Models\Task;
use App\Repositories\TaskRepository;

class UserRepository extends BaseRepository
{

    /**
     * @param string $platform 平台默认为公众号mp 小程序mini
     * @return array
     */
    public function getLoginInfo($platform = 'mp'){
        $user = Auth::user();

        $uid = Auth::id();

        $user_properties = $user->getUserProperties($user);

        $dateTime = new \DateTime();

        $time = $dateTime->format('Y-m-d');

        $task = Task::where('uid', $uid)
            ->where('type', Task::TYPE_SIGN)->where('created_at', '>', $time)->first();

        if($task){
            $is_sign = true;
        }else{
            $is_sign = false;
            $this->userSign();
        }

        $tags = (new UserTagRepository())->getUserTag();
        $tags = $tags['data'];
        //没有设置兴趣tag就不显示
        $tagIds = CiAppConfig::ci_focus_tags(true);

        if(count($tags) == 0 && count($tagIds) > 0){
            $chooseTag = false;
        }else{
            $chooseTag = true;
        }

        //孕期提醒的宝宝生日
        if (!$user->pregdate || $user->pregdate == '0000-00-00'){
            if ($user->baby_birthday && $user->baby_birthday != '0000-00-00 00:00:00'){
                $user->pregdate = date('Y-m-d', strtotime($user->baby_birthday));
                $user->save();
            }
        }

        //注册孕期提醒
        if ($platform == 'mini' && !$user->preg_id){
            $preg_id = (new Pregnotice())->getPregId($user->id, $user->nickname, $user->avatar, $user->pregdate);
            if ($preg_id){
                $user->preg_id = $preg_id;
                $user->save();
            }
        }

        $app_config = $this->getAppConfig();

        $data = [
            'id' => $user->id,
            'openid' => $user->openid,
            'mini_openid' => $user->mini_openid,
            'preg_id' => $user->preg_id,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'pregdate' => $user->pregdate,
            'channel' => $user->channel,
            'crm_status' => $user->crm_status,
            'user_properties' => $user_properties,
            'chooseTag' => $chooseTag,
            'is_sign' => $is_sign,
            'app_config' => $app_config
        ];
        return $data;
    }

    //配置
    public function getAppConfig(){
        //服务化的一些配置
        //随机取一个搜索框默认值
        $search_placeholder = trim(AppConfig::getOtherModuleDataByKey(AppConfig::KEY_OTHER_SEARCH_PLACEHOLDER));
        $arr = explode(' ', $search_placeholder);
        $search_placeholder = $arr[array_rand($arr)];
        $app_config = [
            'appkey' => config('oneitfarm.appkey'),
            'title' => AppConfig::getOtherModuleDataByKey(AppConfig::KEY_OTHER_TITLE),
            'search_placeholder' => $search_placeholder,
            'copyright' => AppConfig::getOtherModuleDataByKey(AppConfig::KEY_OTHER_COPYRIGHT),
            'crm_tip' => AppConfig::getOtherModuleDataByKey(AppConfig::KEY_OTHER_CRM_TIP),
            'crm_register' => AppConfig::getOtherModuleDataByKey(AppConfig::KEY_OTHER_CRM_REGISTER),
        ];
        return $app_config;
    }

    public function getUserInfo(){
        $user = Auth::user();

        $birth_info = $this->getBirth($user->baby_birthday);
        $data = [
            'id' => $user->id,
            'type' => $user->type,
            'openid' => $user->openid,
            'nickname' => $user->nickname,
            'sex' => $user->sex,
            'avatar' => $user->avatar,
            'mq' => $user->mq,
            'sign_days' => $user->sign_days,
            'baby_birth' => $birth_info
        ];
        return $data;
    }

    public function getBirth($datetime){
        $date = new \DateTime();

        $time = $date->format('Y-m-d');

        if(strtotime($datetime) < 0){
            return '';
        }elseif($datetime > $time){
            return '预产期:' . substr($datetime, 0, 10);
        }else{
            return '宝宝生日:' . substr($datetime, 0, 10);
        }
    }

    public function userSign(){
        $uid = Auth::id();
        $task = Task::where('uid', $uid)
            ->where('type', Task::TYPE_SIGN)->orderBy('created_at', 'desc')->first();
        $um = NULL;
        $update_time = NULL;
        if($task){
            $update_time = substr($task->created_at, 0, 10);
        }
        $user = User::where('id', $uid)->first();
        if(!$user){
            return [
                'ret' => -1,
                'msg' => '用户不存在'
            ];
        }
        $yesterday = date("y-m-d", strtotime("-1 day"));
        $today = date("y-m-d");
        if((!$update_time) || (strtotime($yesterday) > strtotime($update_time))){
            User::where('id', $uid)->update([
                'sign_days' => 1,
            ]);
            (new TaskRepository())->sign($uid);
            MqService::increase($uid, MqService::ADD_TYPE_SIGN);
        }elseif(strtotime($today) <= strtotime($update_time)){

        }else{
            $sign_days = $user->sign_days + 1;
            User::where('id', $uid)->update([
                'sign_days' => $sign_days,
            ]);
            (new TaskRepository())->sign($uid);
            MqService::increase($uid, MqService::ADD_TYPE_SIGN);
        }
        return [
            'ret' => 1
        ];
    }

    public function getUserType($uid){
//        $date = date('y-m-d');
//        $user = User::where('id', $uid)->first();
//        if($user->crm_shop == 1 && (strtotime($date) > strtotime($user->baby_birthday))){
//            return User::USER_CI_ON;
//        }elseif($user->crm_shop == 1 && (strtotime($date) < strtotime($user->baby_birthday))){
//            return User::USER_CI_OP;
//        }elseif($user->crm_shop == 0 && (strtotime($date) > strtotime($user->baby_birthday))){
//            return User::USER_CI_NN;
//        }else{
//            return User::USER_CI_NP;
//        }
        return User::USER_CI_NN;
    }


    //设置孕期提醒的宝宝生日
    public function setPregdate($pregdate){
        $user = Auth::user();
        if (!$user){
            return $this->error->NO_USER;
        }
        $pregdate = strtotime($pregdate);
        if (!$pregdate){
            return $this->returnError('pregdate不合法');
        }
        $user->pregdate = date('Y-m-d',$pregdate);
        $user->save();
        return $this->returnData([
            'pregdate' => $user->pregdate
        ]);
    }

}