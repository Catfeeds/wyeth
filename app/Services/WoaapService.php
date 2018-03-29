<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/24
 * Time: 上午11:53
 */

namespace App\Services;

use App\Helpers\CacheKey;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;


//齐数微信公众号相关接口

class WoaapService
{
    protected $base_uri = 'http://API.woaap.com';
    protected $timeout = 5;

    protected $appid = 'wx7fd6725300a50716';
    protected $appkey = '29eb0f79fb7f1868f68ece4830d25540';

    protected $client;
    protected $log;


    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->base_uri,
            'timeout' => $this->timeout,
        ]);
        
        $this->log = BLogger::getLogger('api');
        
    }

    //获取ackey
    public function getAckey(){
        $ackey = Cache::get(CacheKey::WOAPP_ACKEY);
        if ($ackey){
            return $ackey;
        }
        
        $params = [
            'appid' => $this->appid,
            'appkey' => $this->appkey
        ];
        $res = $this->client->request('GET', '/api/ackey', ['query' => $params]);
        $res = json_decode($res->getBody(), true);

        //记录日志
        $this->log->info(__FUNCTION__, $res);

        if ($res['errcode'] == 0){
            $ackey = $res['ackey'];
            $expires_in = $res['expires_in']; //过期时间,单位秒,一般为7200s

            Cache::put(CacheKey::WOAPP_ACKEY, $ackey, intval($expires_in / 60) - 10);
            return $ackey;
        }else{
            return $res;
        }
    }

    //生成二维码接口
    public function qrcodeCreate($scene_str, $type = 0, $expire = 2592000){
        $ackey = $this->getAckey();
        if (is_array($ackey)){
            return $ackey;
        }

        try {
            $res = $this->client->request('GET', '/api/qrcode-create', [
                'query' => [
                    'ackey' => $ackey,
                    'type' => $type,
                    'scene_str' => $scene_str,
                    'expire' => $expire
                ]
            ]);
        }catch (RequestException $exception) {
            return false;
        }

        $res = json_decode($res->getBody(), true);
        return $res;
    }
}