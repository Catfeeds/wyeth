<?php
/**
 * V1 版本的接口，可以比较方便的使用groupBy
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/10
 * Time: 10:09
 */

namespace App\CIService;

class CIDataQuery extends  BaseCIService
{
    /**
     * 新的接口 访问线上,不在访问共享目录
     * group by查询
     * @param $beginTime
     * @param $endTime
     * @param $eventId
     * @param $groupBy string group by的字段
     * @param $groupByType string group by字段的类型: buildin | event_arg
     * @param $orderBy string order by类型
     * @param $orderByType string order by字段的类型: buildin | event_arg
     * @param $order string 排序 descending | ascending
     * @param int $limit 输出数量限制
     * @param array $common_filters
     * @param array $eventFilters
     * @param array $dimension_Filters
     * @return mixed
     */
    public function groupBy($beginTime, $endTime, $eventId, $groupBy, $groupByType, $orderBy, $orderByType, $order, $limit = 1000, $filters = null, $eventFilters = null, $userFilters = null)
    {
        // 暂时只能最大100
        if($limit >100){
            $limit = 100;
        }
        $url = "http://data.oneitfarm.com/cidata/main.php/query/v1/groupBy.json";
        $params = [
            'app_id' => $this->appkey,
            'begin_time'=>$beginTime,
            'end_time'=>$endTime,
            'event_id'=>$eventId,
            'group_by'=>$groupBy,
            //'group_by_type'=>$groupByType,
            'granularity'=>['type'=>"period",'period'=>"all"],
            'order_by'=>$orderBy,
            //'order_by_type'=>$orderByType,
            'order'=>$order,
            'limit'=>$limit,
            'common_filters' => $filters,
            'event_filters'=>$eventFilters,
            'dimension_filters'=>$userFilters
        ];
        $response = $this->jsonPost($url, $params, false);
        return $response;
    }

    /**
     * @param $beginTime integer 查询开始时间 时间戳
     * @param $endTime integer 查询结束时间 时间戳
     * @param $granularity string 按时间分段 可以是hour, day, all 等
     * @param $eventId string 事件ID
     * @param $filters array buildin filter 数组
     * @param $eventFilters array event args filter 数组
     * @param $userFilters array 自定义用户属性数组
     * @return array query result
     */
    public function timeSeries($beginTime, $endTime, $granularity, $eventId, $filters = null, $eventFilters = null, $userFilters = null)
    {
        $url = "http://data.oneitfarm.com/cidata/main.php/query/v1/timeseries.json";
        $params = [
            'app_id' => $this->appkey,
            'begin_time'=>$beginTime,
            'end_time'=>$endTime,
            'granularity'=>['type'=>"period",'period'=>"all"],
            'event_id'=>$eventId,
            'common_filters' => $filters,
            'event_filters'=>$eventFilters,
            'dimension_filters'=>$userFilters
        ];
        $response = $this->jsonPost($url, $params, false);
        return $response;
    }

}