<?php
/**
 * 查询支付宝条码支付交易
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午3:58
 */

namespace CIPay\Request;


class QueryAlipayBarTrade extends AbstractPay
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/trade/queryAlipayBarTrade";
    }

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    protected function checkParams()
    {
        if(!$this->isTradeNoSet() && !$this->isOutTradeNoSet()) {
            throw new \Exception("trade_no和out_trade_no至少填一项");
        }
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
     * 设置out_trade_no
     * @param string $value 业务交易号
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