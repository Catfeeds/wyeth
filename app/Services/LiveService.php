<?php
namespace App\Services;
use GuzzleHttp\Client;
use Cache;

// 直播, 奥点云接口
class LiveService
{

    private $appId = '';

    private $accessId = '';

    private $accessKey = '';


    public function __construct()
    {
        $this->appId = config('course.aodianyun_app_id');
        $this->accessId = config('course.aodianyun_access_id');
        $this->accessKey = config('course.aodianyun_access_key');
    }

    /**
     * 获取直播源的信息
     * @param  [type] $stream [description]
     * @return [type]         [description]
     */
    public function getPublishInfo($stream)
    {
        // curl -X POST http://openapi.aodianyun.com/v2/LSS.GetPublishInfo -d'{"access_id":"741665782372","access_key":"InDQ5783888AGcStF7XY3E9f8ypVIIfw","appid":"jimmy_test1","stream":"stream_t1"}'
        $cacheKey = "LiveService_info_{$stream}_v1";
        $info = Cache::get($cacheKey);
        var_dump($info);
        if ($info === null) {
            echo '0';
            $content = json_encode([
                'access_id' => $this->accessId,
                'access_key' => $this->accessKey,
                'appid' => $this->appId,
                'stream' => $stream
            ]);
            $client = new Client();
            $resultContents = $client->request('POST', 'http://openapi.aodianyun.com/v2/LSS.GetPublishInfo', [
                'body' => $content
            ])->getBody()->getContents();
            $result = json_decode($resultContents, true);
            if ($result['Flag'] == 100 && count($result['List'])) {
                $info = $result['List'][0];
            } else {
                $info = [];
            }
            Cache::put($cacheKey, $info, 0.1);
        } else {
            echo '1';
        }

        var_dump($info);
        exit;


        return $info;
    }

    public function getVodList($stream)
    {   
        // curl -X POST http://openapi.aodianyun.com/v2/LSS.GetPublishInfo -d'{"access_id":"741665782372","access_key":"InDQ5783888AGcStF7XY3E9f8ypVIIfw","appid":"jimmy_test1","stream":"stream_t1"}'
        
        $content = json_encode([
            'access_id' => $this->accessId,
            'access_key' => $this->accessKey,
            'appid' => $this->appId,
            'stream' => $stream,
            'num' => '100'
        ]);
        $client = new Client();
        $resultContents = $client->request('POST', 'http://openapi.aodianyun.com/v2/VOD.GetVodList', [
            'body' => $content
        ])->getBody()->getContents();
        $result = json_decode($resultContents, true);
        if ($result['Flag'] == 100 && count($result['List'])) {
            $info = $result['List'];
        } else {
            $info = [];     
        }
        return $info;
    }
}
