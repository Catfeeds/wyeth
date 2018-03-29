<?php
/**
 * Created by PhpStorm.
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午6:46
 */

namespace CIPay\Request;


class AddWeixinServiceConfig extends AbstractPay
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/config/addWeixinServiceConfig";
    }

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    protected function checkParams()
    {
        if(!$this->isNameSet()) {
            throw new \Exception("缺少必填参数name");
        } else if(!$this->isAppIdSet()) {
            throw new \Exception("缺少必填参数app_id");
        } else if(!$this->isMchidSet()) {
            throw new \Exception("缺少必填参数mchid");
        } else if(!$this->isKeySet()) {
            throw new \Exception("缺少必填参数key");
        } else if(!$this->isPemcertSet()) {
            throw new \Exception("缺少必填参数pemcert");
        } else if(!$this->isPemkeySet()) {
            throw new \Exception("缺少必填参数pemkey");
        }
    }

    /**
     * 设置name
     * @param string $value 微信渠道
     **/
    public function setName($value)
    {
        $this->values['name'] = $value;
    }
    /**
     * 获取name
     * @return string
     **/
    public function getName()
    {
        return $this->values['name'];
    }
    /**
     * 判断name是否存在
     * @return bool
     **/
    public function isNameSet()
    {
        return array_key_exists('name', $this->values);
    }


    /**
     * 设置app_id
     * @param string $value 支付宝appid
     **/
    public function setAppId($value)
    {
        $this->values['app_id'] = $value;
    }
    /**
     * 获取app_id
     * @return string
     **/
    public function getAppId()
    {
        return $this->values['app_id'];
    }
    /**
     * 判断app_id是否存在
     * @return bool
     **/
    public function isAppIdSet()
    {
        return array_key_exists('app_id', $this->values);
    }


    /**
     * 设置mchid
     * @param string $value 微信支付商户号
     **/
    public function setMchid($value)
    {
        $this->values['mchid'] = $value;
    }
    /**
     * 获取mchid
     * @return string
     **/
    public function getMchid()
    {
        return $this->values['mchid'];
    }
    /**
     * 判断mchid是否存在
     * @return bool
     **/
    public function isMchidSet()
    {
        return array_key_exists('mchid', $this->values);
    }


    /**
     * 设置key
     * @param string $value 微信支付API密钥 如果普通商户必填
     **/
    public function setKey($value)
    {
        $this->values['key'] = $value;
    }
    /**
     * 获取key
     * @return string
     **/
    public function getKey()
    {
        return $this->values['key'];
    }
    /**
     * 判断key是否存在
     * @return bool
     **/
    public function isKeySet()
    {
        return array_key_exists('key', $this->values);
    }


    /**
     * 设置pemcert
     * @param string $value 商户证书 如果普通商户必填
     **/
    public function setPemcert($value)
    {
        $this->values['pemcert'] = $value;
    }
    /**
     * 获取pemcert
     * @return string
     **/
    public function getPemcert()
    {
        return $this->values['pemcert'];
    }
    /**
     * 判断pemcert是否存在
     * @return bool
     **/
    public function isPemcertSet()
    {
        return array_key_exists('pemcert', $this->values);
    }


    /**
     * 设置pemkey
     * @param string $value 证书密钥 如果普通商户必填
     **/
    public function setPemkey($value)
    {
        $this->values['pemkey'] = $value;
    }
    /**
     * 获取pemkey
     * @return string
     **/
    public function getPemkey()
    {
        return $this->values['pemkey'];
    }
    /**
     * 判断pemkey是否存在
     * @return bool
     **/
    public function isPemkeySet()
    {
        return array_key_exists('pemkey', $this->values);
    }
}