<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/20
 * Time: 下午3:23
 */

namespace App\CIService\Lib;

/**
 * Class Error
 *
 * @property-read array INVALID_APPKEY
 * @property-read array CURL_POST_ERROR
 * @property-read array INVALID_BACK_FORMAT
 */
class Error
{
    private $properties = array(
        'INVALID_APPKEY' => array(101, 'invalid appkey'),
        'CURL_POST_ERROR' => array(102, 'error exist in curl post'),
        'INVALID_BACK_FORMAT' => array(103, 'invalid back format'),
    );

    public function __get($key)
    {
        if (array_key_exists($key, $this->properties)) {
            $property = $this->properties[$key];

            return array('ret' => -1, 'code' => $property[0], 'msg' => $property[1]);
        }
    }

}

class CiApi
{
    const  ENV_TEST = 'test';
    const  ENV_PRODUCTION = 'production';

    const  API_ADMIN_CREATE_APP = '/shop/main.php/json/rpc_admin/create_app';
    const  API_ADMIN_GET_APP = '/shop/main.php/json/rpc_admin/get_app';
    const  API_STORE_GET = '/shop/main.php/json/rpc_store/get';
    const  API_STORE_ALL = '/shop/main.php/json/rpc_store/all';
    const  API_USER_GET = '/shop/main.php/json/rpc_user/get';
    const  API_USER_ALL = '/shop/main.php/json/rpc_user/all';

    const  API_ACCOUNT_THIRD_APP = '/account/main.php/json/login/third_app';
    const  API_GET_WEIXIN_ACCOUNT = '/shop/main.php/json/rpc_account/get_weixin_account';
    const  API_ACLI_BRIDGE = '/acl/main.php/json/rpc/acli_bridge';


    private $error;
    private $appkey;
    private $env;
    private $appsecret;
    private $domains = array(
        'test' => 'http://idg-zhangyi.tunnel.nibaguai.com',
        'zyx' => 'http://idg-zhuyixuan.tunnel.nibaguai.com',
        'production' => 'https://oneitfarm.com',
    );

    public function __construct($appkey, $appsecret, $env = 'production')
    {
        $this->error = new Error();
        $this->appkey = $appkey;
        $this->appsecret = $appsecret;
        $this->env = $env;
    }


    public function apiCall($api, $data)
    {
        return $this->call($data, $api);
    }

    private function call($data, $json_api)
    {
        $params = $this->get_params($data, $json_api);

        $domain = config('oneitfarm.domain');
        $result = $this->curl_post($domain . $json_api, $params);

//        var_dump($result);

        if (!$result) {
            return $this->error->CURL_POST_ERROR;
        }

        $ret = json_decode($result, true);

        if (!$ret || !is_array($ret)) {
            return $this->error->INVALID_BACK_FORMAT;
        }

        return $ret;
    }

    private function get_params($data, $json_api)
    {
        $token = $this->make_token($data, $this->appsecret);

        $platform_apis = array(
            self::API_ADMIN_CREATE_APP,
            self::API_ADMIN_GET_APP,
            self::API_STORE_ALL,
            self::API_STORE_GET,
            self::API_USER_ALL,
            self::API_USER_GET
        );

        if(in_array($json_api, $platform_apis)) {
            $params = array(
                'platform_key' => $this->appkey,
                'token' => $token
            );
        }else{
            $params = array(
                'appkey' => $this->appkey,
                'token' => $token
            );
        }

        return $params;
    }

    private function make_token($data, $secret)
    {
        $data['exp'] = time() + 60 * 5;
        return JWT::encode($data, $secret);
    }

    private function curl_post($url, array $post = null, array $options = array())
    {
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => http_build_query($post),
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36"
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (!$result = curl_exec($ch)) {
            if ($this->env == self::ENV_TEST) {
                trigger_error(curl_error($ch));
            }
            return null;
        }

        $flag = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($flag == 404) {
            return null;
        }

        curl_close($ch);
        return $result;
    }
}

