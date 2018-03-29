<?php
/**
 * Created by PhpStorm.
 * User: Jean
 * Date: 2018/1/30
 * Time: 上午10:49
 */

namespace App\CIService;

class CIDataFunnel extends BaseCIService
{
    // 获取漏斗列表
    public function index($page, $limit){
        $url = "http://oneitfarm.com/cidata/main.php/api/funnel/funnelList.json";
        $params = [
             'appkey' => $this->appkey,
            // 测试appkey
//            'appkey' => 'test-appkey-01',
            'page' => $page,
            'limit' => $limit
        ];
        $response = $this->get($url, $params, false);
        return $response;
    }

    // 新建漏斗
    public function create($name, $timeout, $steps = []){
        $url = "http://oneitfarm.com/cidata/main.php/api/funnel/create.json";
        $params = [
            'appkey' => $this->appkey,
            // 测试appkey
//            'appkey' => 'test-appkey-01',
            'name' => $name,
            'timeout' => $timeout,
            'steps' => $steps
        ];
        $response = $this->jsonPost($url, $params,false);
        return $response;
    }

    // 更新漏斗
    public function update($name, $timeout,$steps = [], $id){
        $url = "http://oneitfarm.com/cidata/main.php/api/funnel/update.json";
        $params = [
            'appkey' => $this->appkey,
            // 测试appkey
//            'appkey' => 'test-appkey-01',
            'name' => $name,
            'timeout' => $timeout,
            'steps' => $steps,
            'id' => $id
        ];
        $response = $this->jsonPost($url, $params, false);
        return $response;
    }

    // 漏斗详情
    public function detail($id){
        $url = "http://oneitfarm.com/cidata/main.php/api/funnel/detail.json";
        $params = [
            'appkey' => $this->appkey,
            // 测试appkey
//            'appkey' => 'test-appkey-01',
            'id' => $id
        ];
        $response = $this->get($url, $params, false);
        return $response;
    }

    // 一段时间内漏斗详情数据
    public function conversion($begin_time, $end_time, $id, $type){
        $url = "http://oneitfarm.com/cidata/main.php/api/funnel/conversion.json";
        $params = [
            'appkey' => $this->appkey,
            // 测试appkey
//            'appkey' => 'test-appkey-01',
            'begin_time' => $begin_time,
            'end_time' => $end_time,
            'id' => $id,
            'type' => $type
        ];
        $response = $this->get($url, $params, false);
        return $response;
    }

    // 删除漏斗
    public function delete($id){
        $url = "http://oneitfarm.com/cidata/main.php/api/funnel/delete.json";
        $params = [
            'appkey' => $this->appkey,
            // 测试appkey
//            'appkey' => 'test-appkey-01',
            'id' => $id
        ];
        $response = $this->post($url, $params, false);
        return $response;
    }
}