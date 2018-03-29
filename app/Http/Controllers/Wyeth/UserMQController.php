<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/20
 * Time: 下午4:18
 */


namespace App\Http\Controllers\Wyeth;

use Illuminate\Http\Request;
use App\Repositories\UserMQRepository;
use Auth;
use App\Services\MqService;


class UserMQController extends WyethBaseController{

    public function getConsumeList(Request $request){
        $uid = Auth::id();
        $page = $request->input('page');
        $page_size = $request->input('page_size');
        if(!$page || !$page_size){
            return $this->returnError('参数不正确');
        }
        $data = MqService::getConsumeList($uid, $page, $page_size);
        return $this->returnData($data);
    }

    //增加补偿mq
    public function compensate(Request $request){
        $mq = $request->input('mq');
        $uid = $request->input('uid', $this->uid);
        return MqService::increase($uid, MqService::ADD_TYPE_COMPENSATE, $mq);
    }
}