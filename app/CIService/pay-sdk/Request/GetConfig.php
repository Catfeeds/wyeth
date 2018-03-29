<?php
/**
 * Created by PhpStorm.
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午6:43
 */

namespace CIPay\Request;


class GetConfig extends AbstractPay
{
    const WeixinMobile = 'WeixinMobile';
    const WeixinJSPay = 'WeixinJSPay';
    const WeixinNativePay = 'WeixinNativePay';
    const WeixinMicroPay = 'WeixinMicroPay';
    const WeixinMiniPay = 'WeixinMiniPay';
    const AlipayMobile = 'AlipayMobile';
    const AlipayQR = 'AlipayQR';
    const AlipayWap = 'AlipayWap';
    const AlipayBar = 'AlipayBar';

    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/config/getConfig";
    }

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    protected function checkParams()
    {
        if(!$this->isTypeSet()) {
            throw new \Exception("缺少必填参数type");
        }
    }

    /**
     * 设置type
     * @param string $value 渠道
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
}