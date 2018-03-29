<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/17
 * Time: 15:55
 */

namespace App\CIService;

class CIDataOpenApi extends BaseCIService
{
    // 活跃、新增用户查询
    public function userCount($begin_time,$end_time,$user_type,$granularity="hour",$filters = null){
        $url = "http://oneitfarm.com/cidata/main.php/api/users/query/count.json";
        $params = [
            'appkey' => $this->appkey,
            'begin_time'=>$begin_time,
            'end_time'=>$end_time,
            'user_type'=>$user_type,
            'granularity'=>$granularity,
            'filters'=>$filters
        ];
        $response = $this->jsonPost($url, $params, false);
        return $response;
    }

    // 用户留存查询
    public function userRetain($begin_time,$retain_day,$user_type,$filters = null){
        $url = "http://oneitfarm.com/cidata/main.php/api/users/query/retain.json";
        $params = [
            'app_id' => $this->appkey,
            'begin_time'=>$begin_time,
            'user_type'=>$user_type,
            'retain_day'=>$retain_day,
            'filters'=>$filters
        ];
        $response = $this->jsonPost($url, $params, false);
        return $response;
    }

    // 事件查询
    public function eventCount($begin_time,$end_time,$user_type,$event_id,$granularity="day",$filters = null){
        $url = "https://oneitfarm.com/cidata/main.php/api/event/query/count.json";
        $params = [
            'app_id' => $this->appkey,
            'begin_time'=>$begin_time,
            'end_time'=>$end_time,
            'user_type'=>$user_type,
            'event_id'=>$event_id,
            'granularity'=>$granularity,
            'filters'=>$filters
        ];
        $response = $this->jsonPost($url, $params, true);
        return $response;
    }

    // 事件留存
    public function eventRetain($begin_time,$retain_day,$user_type,$event_id,$filters = null){
        $url = "http://oneitfarm.com/cidata/main.php/api/event/query/retain.json";
        $params = [
            'app_id' => $this->appkey,
            'begin_time'=>$begin_time,
            'user_type'=>$user_type,
            'event_id'=>$event_id,
            'retain_day'=>$retain_day,
            'filters'=>$filters
        ];
        $response = $this->jsonPost($url, $params, false);
        return $response;
    }

}