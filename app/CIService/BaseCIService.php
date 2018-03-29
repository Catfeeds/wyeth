<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/20
 * Time: 下午3:31
 */

namespace App\CIService;

//调用中台服务

use App;
use App\CIService\Lib\CiApi;
use App\CIService\Lib\JWT;
use App\Helpers\WyethError;
use App\Services\BLogger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BaseCIService
{
    public $appkey;
    public $channel = 0;
    protected $appsecret;

    public $domain;
    protected $timeout = 2;

    protected $short_timeout = 0.8;

    protected $ciapi;
    protected $client;
    
    //写入日志文件ci
    protected $log;

    protected $error;
    
    public function __construct()
    {
        if (!isset($this->domain)){
            $this->domain = config('oneitfarm.domain');
        }
        if (!isset($this->appkey)){
            $this->appkey = config('oneitfarm.appkey');
        }
        if (!isset($this->appsecret)){
            $this->appsecret = config('oneitfarm.appsecret');
        }


        $this->ciapi = new CiApi($this->appkey, $this->appsecret);
        $this->client = new Client([
                'base_uri' => $this->domain,
                'timeout' => $this->timeout,
            ]
        );
        
        $this->log = BLogger::getLogger('ci');

        $this->error = new WyethError();
    }

    public function post($uri, $params = [], $has_appkey = true){
        if ($has_appkey && !isset($params['appkey'])){
            $params['appkey'] = $this->appkey;
        }
        //失败重试一次
        try{
            $ret = $this->client->request('POST', $uri, ['form_params' => $params]);
        }catch (RequestException $exception){
            try{
                $ret = $this->client->request('POST', $uri, ['form_params' => $params]);
            }catch (RequestException $exception){
                App\Services\Email::SendEmail('RequestException', "POST\n$uri\n".json_encode($params), App\Services\Email::EMAIL_BOX_PHP);
                return $this->returnError('请求失败');
            }
        }

        $status_code = $ret->getStatusCode();
        if ($status_code != 200){
            return $this->returnError('请求失败');
        }

        $body = $ret->getBody();
        $result = json_decode($body, true);
        if (!$result){
            return $this->returnError('json_decode失败');
        }else{
            return $result;
        }
    }

    // CIdata使用
    public function jsonPost($uri,$params =[],$has_appkey = true){
        if ($has_appkey && !isset($params['appkey'])){
            $params['appkey'] = $this->appkey;
        }
        //失败重试一次

        try{
            $ret = $this->client->request('POST', $uri, ['json' => $params]);
        }catch (RequestException $exception){
            try{
                $ret = $this->client->request('POST', $uri, ['json' => $params]);
            }catch (RequestException $exception){
                App\Services\Email::SendEmail('RequestException', "POST\n$uri\n".json_encode($params), App\Services\Email::EMAIL_BOX_PHP);
                return $this->returnError('请求失败');
            }
        }

        $status_code = $ret->getStatusCode();
        if ($status_code != 200){
            return $this->returnError('请求失败');
        }

        $body = $ret->getBody();
        $result = json_decode($body, true);
        if (!$result){
            return $this->returnError('json_decode失败');
        }else{
            return $result;
        }
    }

    public function get($uri, $params = [], $has_appkey = true){
        if ($has_appkey && !isset($params['appkey'])){
            $params['appkey'] = $this->appkey;
        }
        //失败重试一次
        try{
            $ret = $this->client->request('GET', $uri, ['query' => $params]);
        }catch (RequestException $exception){
            try{
                $ret = $this->client->request('GET', $uri, ['query' => $params]);
            }catch (RequestException $exception){
                App\Services\Email::SendEmail('RequestException', "GET\n$uri\n".json_encode($params), App\Services\Email::EMAIL_BOX_PHP);
                return $this->returnError('请求失败');
            }
        }

        $status_code = $ret->getStatusCode();
        if ($status_code != 200){
            return $this->returnError('请求失败');
        }

        $body = $ret->getBody();
        $result = json_decode($body, true);
        if (!$result){
            return $this->returnError('json_decode失败');
        }else{
            return $result;
        }
    }

    public function jsonGet($uri, $params = [], $has_appkey = true){
        if ($has_appkey && !isset($params['appkey'])){
            $params['appkey'] = $this->appkey;
        }
        //失败重试一次
        try{
            $ret = $this->client->request('GET', $uri, ['json' => $params]);
        }catch (RequestException $exception){
            try{
                $ret = $this->client->request('GET', $uri, ['json' => $params]);
            }catch (RequestException $exception){
                App\Services\Email::SendEmail('RequestException', "GET\n$uri\n".json_encode($params), App\Services\Email::EMAIL_BOX_PHP);
                return $this->returnError('请求失败');
            }
        }

        $status_code = $ret->getStatusCode();
        if ($status_code != 200){
            return $this->returnError('请求失败');
        }

        $body = $ret->getBody();
        $result = json_decode($body, true);
        if (!$result){
            return $this->returnError('json_decode失败');
        }else{
            return $result;
        }
    }

    public function short_post($uri, $params = [], $has_appkey = true){
        $short_client = new Client([
                'base_uri' => 'http://cidata-recommend.oneitfarm.com',
                'timeout' => $this->short_timeout,
            ]
        );

        if ($has_appkey && !isset($params['appkey'])){
            $params['appkey'] = $this->appkey;
        }

        $ret = $short_client->request('POST', $uri, [
            'form_params' => $params
        ]);
        $body = $ret->getBody();
        $result = json_decode($body, true);
        return $result;
    }

    public function returnError($msg = '', $code = -1){
        return [
            'ret' => -1,
            'code' => $code,
            'msg' => $msg
        ];
    }

    public function getAppkey(){
        return $this->appkey;
    }

    /**
     * 获取中台jwt加密参数
     * @param $data
     * @return array
     */
    public function getJWTParams($data){
        $data['exp'] = time() + 60 * 5;
        $token = JWT::encode($data, $this->appsecret);
        return [
            'appkey' => $this->appkey,
            'token' => $token
        ];
    }
}