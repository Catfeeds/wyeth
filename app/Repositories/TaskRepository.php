<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/7
 * Time: 下午3:15
 */

namespace App\Repositories;


use App\Models\Task;
use App\Services\MqService;

class TaskRepository extends BaseRepository
{
    //Task类型与表中int值对照表
    protected $type_table = [
        Task::TYPE_SIGN   => MqService::ADD_TYPE_SIGN,
        Task::TYPE_SCAN   => MqService::ADD_TYPE_ENTER_COURSE,
        Task::TYPE_SHARE  => MqService::ADD_TYPE_TRANSMIT,
        Task::TYPE_SHARE_CMS => MqService::ADD_TYPE_TRANSMIT_CMS
    ];

    //签到任务,默认已领取
    public function sign($uid){
        $task = Task::where('uid', $uid)
            ->where('type', Task::TYPE_SIGN)
            ->where('created_at', '>', date('Y-m-d'))
            ->first();
        if ($task){
            return false;
        }
        $mq = MqService::getTypeArray()[MqService::ADD_TYPE_SIGN][0];
        $task = new Task();
        $task->uid = $uid;
        $task->type = Task::TYPE_SIGN;
        $task->mq = $mq;
        $task->get = 1;
        $task->save();
        return true;
    }

    //浏览课程任务,最多5次,每次3mq
    public function scan($uid, $cid, $max = 5){
        $mq = MqService::getTypeArray()[MqService::ADD_TYPE_ENTER_COURSE][0];

        $count = Task::where('uid', $uid)
            ->where('type', Task::TYPE_SCAN)
            ->where('created_at', '>', date('Y-m-d'))
            ->count();
        if ($count >= $max){
            return false;
        }
        //是否浏览重复的课程
        $task = Task::where('uid', $uid)
            ->where('cid', $cid)
            ->where('type', Task::TYPE_SCAN)
            ->where('created_at', '>', date('Y-m-d'))
            ->first();
        if ($task){
            return false;
        }
        $task = new Task();
        $task->uid = $uid;
        $task->cid = $cid;
        $task->type = Task::TYPE_SCAN;
        $task->mq = $mq;
        $task->save();
        return $mq;
    }

    //分享任务,最多5次,每次5mq
    public function share($uid, $cid = 0, $max = 5, $mq = 5){
        $count = Task::where('uid', $uid)
            ->where('type', Task::TYPE_SHARE)
            ->where('created_at', '>', date('Y-m-d'))
            ->count();
        if ($count >= $max){
            return false;
        }
        $task = new Task();
        $task->uid = $uid;
        $task->cid = $cid;
        $task->type = Task::TYPE_SHARE;
        $task->mq = $mq;
        $task->save();
        return true;
    }

    //分享图文任务,最多5次,每次5mq
    public function shareCms($uid, $article_id, $max = 5){
        $mq = MqService::getTypeArray()[MqService::ADD_TYPE_TRANSMIT_CMS][0];

        $count = Task::where('uid', $uid)
            ->where('type', Task::TYPE_SHARE_CMS)
            ->where('created_at', '>', date('Y-m-d'))
            ->count();
        if ($count >= $max){
            return false;
        }
        //是否浏览重复的课程
        $task = Task::where('uid', $uid)
            ->where('cid', $article_id)
            ->where('type', Task::TYPE_SHARE_CMS)
            ->where('created_at', '>', date('Y-m-d'))
            ->first();
        if ($task){
            return false;
        }
        $task = new Task();
        $task->uid = $uid;
        $task->cid = $article_id;
        $task->type = Task::TYPE_SHARE_CMS;
        $task->mq = $mq;
        $task->save();
        return $mq;
    }

    //领取任务奖励mq
    public function getMq($uid, $type){
        $type_array = [Task::TYPE_SCAN, Task::TYPE_SHARE, Task::TYPE_SHARE_CMS];
        if (!in_array($type, $type_array)){
            return $this->error->TASK_TYPE_INVALID;
        }

        //未领取的奖励
        $task_array = Task::where('uid', $uid)
            ->where('type', $type)
            ->where('get', 0)
            ->where('created_at', '>', date('Y-m-d'))
            ->get();
        if (count($task_array) == 0){
            return $this->error->TASK_NOT_COMPLETE;
        }

        $total_mq = 0;
        foreach ($task_array as &$task){
            $task->get = 1;
            $task->save();
            $total_mq += $task->mq;
        }

        //TODO 增加MQ
        MqService::increase($uid, $this->type_table[$type], $total_mq);

        return $this->returnData([
            'type' => $type,
            'mq' => $total_mq
        ]);
    }

    //查看每日任务
    public function getTask($uid){
        //初始化
        $data = [];
        $type_array = [Task::TYPE_SIGN  , Task::TYPE_SCAN, Task::TYPE_SHARE, Task::TYPE_SHARE_CMS];
        foreach ($type_array as $type){
            $data[$type] = [];
            $data[$type]['mq'] = 0;
            $data[$type]['total_mq'] = 0;
            $data[$type]['get_num'] = 0;
            $data[$type]['not_get_num'] = 0;
        }

        $task_array = Task::where('uid', $uid)
            ->where('created_at', '>', date('Y-m-d'))
            ->get();
        foreach ($task_array as $task){

            $data[$task->type]['mq'] = $task->mq;
            if ($task->get == 1){
                $data[$task->type]['get_num'] ++;
                $data[$task->type]['total_mq'] += $task->mq;
            }else{
                $data[$task->type]['not_get_num'] ++;
            }
        }
        $result = [];
        foreach ($data as $key => $d){
            $d['type'] = $key;
            $result[] = $d;
        }
        return $this->returnData($result);
    }
}