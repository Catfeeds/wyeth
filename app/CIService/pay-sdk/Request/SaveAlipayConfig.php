<?php
/**
 * 插入/更新支付宝配置文件
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午6:22
 */

namespace CIPay\Request;


class SaveAlipayConfig extends AbstractPay
{
    const AlipayMobile = 'AlipayMobile';
    const AlipayQR = 'AlipayQR';
    const AlipayWap = 'AlipayWap';
    const AlipayBar = 'AlipayBar';

    const SIGN_TYPE_RSA = 'RSA';
    const SIGN_TYPE_RSA2 = 'RSA2';

    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/config/saveAlipayConfig";
    }

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    protected function checkParams()
    {
        if(!$this->isTypeSet()) {
            throw new \Exception("缺少必填参数type");
        } else if(!$this->isAppIdSet()) {
            throw new \Exception("缺少必填参数app_id");
        } else if(!$this->isPrivateRsaSet()) {
            throw new \Exception("缺少必填参数private_rsa");
        } else if(!$this->isAlipayPublicKeySet()) {
            throw new \Exception("缺少必填参数alipay_public_key");
        }
    }

    /**
     * 设置type
     * @param string $value 支付宝渠道
     **/
    public function setType($value)
    {
        $this->values['type'] = $value;
    }
    /**
     * 获取type
     * @return string
     **/
    public function getType()
    {
        return $this->values['type'];
    }
    /**
     * 判断type是否存在
     * @return bool
     **/
    public function isTypeSet()
    {
        return array_key_exists('type', $this->values);
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
     * 设置private_rsa
     * @param string $value 商户私钥
     **/
    public function setPrivateRsa($value)
    {
        $this->values['private_rsa'] = $value;
    }
    /**
     * 获取private_rsa
     * @return string
     **/
    public function getPrivateRsa()
    {
        return $this->values['private_rsa'];
    }
    /**
     * 判断private_rsa是否存在
     * @return bool
     **/
    public function isPrivateRsaSet()
    {
        return array_key_exists('private_rsa', $this->values);
    }


    /**
     * 设置alipay_public_key
     * @param string $value 支付宝公钥
     **/
    public function setAlipayPublicKey($value)
    {
        $this->values['alipay_public_key'] = $value;
    }
    /**
     * 获取alipay_public_key
     * @return string
     **/
    public function getAlipayPublicKey()
    {
        return $this->values['alipay_public_key'];
    }
    /**
     * 判断alipay_public_key是否存在
     * @return bool
     **/
    public function isAlipayPublicKeySet()
    {
        return array_key_exists('alipay_public_key', $this->values);
    }


    /**
     * 设置sign_type
     * @param string $value 默认RSA（SHA1WithRSA） RSA或RSA2 （RSA-SHA1WithRSA RSA2-SHA256WithRSA）
     **/
    public function setSignType($value)
    {
        $this->values['sign_type'] = $value;
    }
    /**
     * 获取sign_type
     * @return string
     **/
    public function getSignType()
    {
        return $this->values['sign_type'];
    }
    /**
     * 判断sign_type是否存在
     * @return bool
     **/
    public function isSignTypeSet()
    {
        return array_key_exists('sign_type', $this->values);
    }
}