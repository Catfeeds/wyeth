<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/20
 * Time: 下午4:17
 */


namespace App\Repositories;


use App\Models\User;
use App\Models\UserMq;

use Auth;
class UserMQRepository
{
    public function getConsumeList($page, $page_size){
        $uid = Auth::id();
        $page = $page ? $page : 1;
        $page_size = $page_size ? $page_size : 6;
        $page--;
        $offset = $page * $page_size;
        $userMq = UserMq::where('uid', $uid)->take($page_size)->offset($offset)->get()->toArray();
        $data = [];
        if(is_array($userMq) && (count($userMq) > 0)){
            foreach ($userMq as $um){
                $weekarray = array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
                $day = $weekarray[date("w", strtotime($um['created_at']))];
                $info = [
                    'uid' => $um['uid'],
                    'event' => $um['event'],
                    'mq' => $um['mq'],
                    'balance' => $um['balance'],
                    'created_at' => $um['created_at'],
                    'day' => $day
                ];
                $data[] = $info;
            }
        }
        return $data;
    }

    public function increase($num, $event){
        $uid = Auth::id();
        $user = User::where('id', $uid)->get();
        $mq = $user->mq;
        $mq = $mq + $num;
        User::where('id', $uid)->update([
            'mq' => $mq
        ]);
        $userMq = new UserMq();
        $userMq->uid = $uid;
        $userMq->event = $event;
        $userMq->mq = $num;
        $userMq->balance = $mq;
        $userMq->save();
        return '';
    }

    public function decrease($num, $event){
        $uid = Auth::id();
        $user = User::where('id', $uid)->get();
        $mq = $user->mq;
        $mq = $mq - $num;
        if($mq < 0){
            return '余额不足';
        }
        User::where('id', $uid)->update([
            'mq' => $mq
        ]);
        $userMq = new UserMq();
        $userMq->uid = $uid;
        $userMq->event = $event;
        $userMq->balance = $mq;
        $userMq->mq = 0 - $num;
        $userMq->save();
        return '';
    }
}