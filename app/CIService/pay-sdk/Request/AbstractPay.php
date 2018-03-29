<?php
/**
 * Created by PhpStorm.
 * User: tsy
 * Date: 2017/9/1
 * Time: 上午10:53
 */

namespace CIPay\Request;


use CIPay\Lib\Func;
use CIPay\PayConfig;

abstract class AbstractPay
{
    protected $values = array();
    protected $base_url = "http://idg-tangsiyuan.tunnel.nibaguai.com/pay/main.php/json";

    /**
     * 获取接口地址
     * @return string
     */
    abstract protected function getApiMethodName();

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    abstract protected function checkParams();

    /**
     * 获取所有参数
     * @return array
     */
    public function getValues() {
        return $this->values;
    }

    /**
     * 执行请求
     * @param $config PayConfig
     * @return array
     * @throws \Exception
     */
    public function execute($config) {
        $this->checkParams();
        
        $this->setAppKey($config->getAppkey());
        $this->setChannel($config->getChannel());
        $this->setSign($config->getAppsecret());

        $url = $this->base_url . $this->getApiMethodName();
        $data = Func::postCurl($url, $this->getValues());
        $data = json_decode($data, true);

        return $data;
    }

    /**
     * 设置appkey
     * @param string $value
     **/
    private function setAppKey($value)
    {
        $this->values['app_key'] = $value;
    }
    /**
     * 获取appkey
     * @return string
     **/
    private function getAppKey()
    {
        return $this->values['app_key'];
    }
    /**
     * 判断appkey是否存在
     * @return bool
     **/
    private function isAppKeySet()
    {
        return array_key_exists('app_key', $this->values);
    }


    /**
     * 设置channel 默认0
     * @param integer $value
     **/
    private function setChannel($value)
    {
        $this->values['channel'] = $value;
    }
    /**
     * 获取channel
     * @return integer
     **/
    private function getChannel()
    {
        return $this->values['channel'];
    }
    /**
     * 判断channel是否存在
     * @return bool
     **/
    private function isChannelSet()
    {
        return array_key_exists('channel', $this->values);
    }


    /**
     * 设置签名，详见签名生成算法
     * @param String $secret
     **/
    private function setSign($secret)
    {
        $sign = Func::keySign($this->values, $secret);
        $this->values['sign'] = $sign;
        return $sign;
    }

    /**
     * 获取签名，详见签名生成算法的值
     * @return string 值
     **/
    private function getSign()
    {
        return $this->values['sign'];
    }

    /**
     * 判断签名，详见签名生成算法是否存在
     * @return bool true 或 false
     **/
    private function isSignSet()
    {
        return array_key_exists('sign', $this->values);
    }
}