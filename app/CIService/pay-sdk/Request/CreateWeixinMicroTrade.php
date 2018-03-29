<?php
/**
 * 创建微信刷卡支付交易
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午2:41
 */

namespace CIPay\Request;


class CreateWeixinMicroTrade extends AbstractPayCreateTrade
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/trade/createWeixinMicroTrade";
    }

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    protected function checkParams()
    {
        if(!$this->isSubjectSet()) {
            throw new \Exception("缺少必填参数subject");
        } else if(!$this->isTotalFeeSet()) {
            throw new \Exception("缺少必填参数total_fee");
        } else if(!$this->isOutTradeNoSet()) {
            throw new \Exception("缺少必填参数out_trade_no");
        } else if(!$this->isAuthCodeSet()) {
            throw new \Exception("缺少必填参数auth_code");
        }
    }

    /**
     * 设置auth_code
     * @param string $value 扫码支付授权码，设备读取用户微信中的条码或者二维码信息
     **/
    public function setAuthCode($value)
    {
        $this->values['auth_code'] = $value;
    }
    /**
     * 获取auth_code
     * @return string
     **/
    public function getAuthCode()
    {
        return $this->values['auth_code'];
    }
    /**
     * 判断auth_code是否存在
     * @return bool
     **/
    public function isAuthCodeSet()
    {
        return array_key_exists('auth_code', $this->values);
    }
}