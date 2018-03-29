<?php
/**
 * Created by PhpStorm.
 * User: tsy
 * Date: 2017/9/1
 * Time: 上午10:53
 */

namespace CIPay\Request;


abstract class AbstractPayCreateTrade extends AbstractPay
{
    /**
     * 设置subject
     * @param string $value 交易主题 长度[1, 128] 格式见参数规则-subject建议格式 https://gitlab.oneitfarm.com/idg/Pay/wikis/rule_param#6-subject%E5%BB%BA%E8%AE%AE%E6%A0%BC%E5%BC%8F
     **/
    public function setSubject($value)
    {
        $this->values['subject'] = $value;
    }
    /**
     * 获取subject
     * @return string
     **/
    public function getSubject()
    {
        return $this->values['subject'];
    }
    /**
     * 判断subject是否存在
     * @return bool
     **/
    public function isSubjectSet()
    {
        return array_key_exists('subject', $this->values);
    }


    /**
     * 设置total_fee
     * @param integer $value 交易总金额 单位分
     **/
    public function setTotalFee($value)
    {
        $this->values['total_fee'] = $value;
    }
    /**
     * 获取total_fee
     * @return integer
     **/
    public function getTotalFee()
    {
        return $this->values['total_fee'];
    }
    /**
     * 判断total_fee是否存在
     * @return bool
     **/
    public function isTotalFeeSet()
    {
        return array_key_exists('total_fee', $this->values);
    }


    /**
     * 设置notify_url
     * @param string $value 支付结果通知回调地址
     **/
    public function setNotifyUrl($value)
    {
        $this->values['notify_url'] = $value;
    }
    /**
     * 获取notify_url
     * @return string
     **/
    public function getNotifyUrl()
    {
        return $this->values['notify_url'];
    }
    /**
     * 判断notify_url是否存在
     * @return bool
     **/
    public function isNotifyUrlSet()
    {
        return array_key_exists('notify_url', $this->values);
    }


    /**
     * 设置out_trade_no
     * @param string $value 业务交易单号，业务系统内部唯一 [1, 32]
     **/
    public function setOutTradeNo($value)
    {
        $this->values['out_trade_no'] = $value;
    }
    /**
     * 获取out_trade_no
     * @return string
     **/
    public function getOutTradeNo()
    {
        return $this->values['out_trade_no'];
    }
    /**
     * 判断out_trade_no是否存在
     * @return bool
     **/
    public function isOutTradeNoSet()
    {
        return array_key_exists('out_trade_no', $this->values);
    }
}