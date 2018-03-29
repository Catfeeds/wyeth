<?php
/**
 * 退款
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午3:32
 */

namespace CIPay\Request;


class Refund extends AbstractPay
{
    const REFUND_SOURCE_UNSETTLED_FUNDS = 'REFUND_SOURCE_UNSETTLED_FUNDS';
    const REFUND_SOURCE_RECHARGE_FUNDS = 'REFUND_SOURCE_RECHARGE_FUNDS';

    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/refund/doRefund";
    }

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    protected function checkParams()
    {
        if(!$this->isOutRefundNoSet()) {
            throw new \Exception("缺少必填参数out_refund_no");
        } else if(!$this->isTradeNoSet()) {
            throw new \Exception("缺少必填参数trade_no");
        } else if(!$this->isRefundFeeSet()) {
            throw new \Exception("缺少必填参数refund_fee");
        } else if(!$this->isReasonSet()) {
            throw new \Exception("缺少必填参数reason");
        } else if(!$this->isNotifyUrlSet()) {
            throw new \Exception("缺少必填参数notify_url");
        }
    }

    /**
     * 设置out_refund_no
     * @param string $value 业务内部的退款单号，业务系统内部唯一，同一退款单号多次请求只退一笔 [1, 32]
     **/
    public function setOutRefundNo($value)
    {
        $this->values['out_refund_no'] = $value;
    }
    /**
     * 获取out_refund_no
     * @return string
     **/
    public function getOutRefundNo()
    {
        return $this->values['out_refund_no'];
    }
    /**
     * 判断out_refund_no是否存在
     * @return bool
     **/
    public function isOutRefundNoSet()
    {
        return array_key_exists('out_refund_no', $this->values);
    }


    /**
     * 设置trade_no
     * @param string $value 交易单号
     **/
    public function setTradeNo($value)
    {
        $this->values['trade_no'] = $value;
    }
    /**
     * 获取trade_no
     * @return string
     **/
    public function getTradeNo()
    {
        return $this->values['trade_no'];
    }
    /**
     * 判断trade_no是否存在
     * @return bool
     **/
    public function isTradeNoSet()
    {
        return array_key_exists('trade_no', $this->values);
    }


    /**
     * 设置refund_fee
     * @param integer $value 退款金额 单位分
     **/
    public function setRefundFee($value)
    {
        $this->values['refund_fee'] = $value;
    }
    /**
     * 获取refund_fee
     * @return integer
     **/
    public function getRefundFee()
    {
        return $this->values['refund_fee'];
    }
    /**
     * 判断refund_fee是否存在
     * @return bool
     **/
    public function isRefundFeeSet()
    {
        return array_key_exists('refund_fee', $this->values);
    }


    /**
     * 设置reason
     * @param string $value 退款理由 [1, 256]
     **/
    public function setReason($value)
    {
        $this->values['reason'] = $value;
    }
    /**
     * 获取reason
     * @return string
     **/
    public function getReason()
    {
        return $this->values['reason'];
    }
    /**
     * 判断reason是否存在
     * @return bool
     **/
    public function isReasonSet()
    {
        return array_key_exists('reason', $this->values);
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
     * 设置refund_account
     * @param string $value 退款资金来源（仅适用于微信）REFUND_SOURCE_UNSETTLED_FUNDS-未结算资金退款（不填的话默认是这个） REFUND_SOURCE_RECHARGE_FUNDS-可用余额退款
     **/
    public function setRefundAccount($value)
    {
        $this->values['refund_account'] = $value;
    }
    /**
     * 获取refund_account
     * @return string
     **/
    public function getRefundAccount()
    {
        return $this->values['refund_account'];
    }
    /**
     * 判断refund_account是否存在
     * @return bool
     **/
    public function isRefundAccountSet()
    {
        return array_key_exists('refund_account', $this->values);
    }
}