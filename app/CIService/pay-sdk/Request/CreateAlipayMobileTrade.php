<?php
/**
 * 创建支付宝移动支付交易
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午2:57
 */

namespace CIPay\Request;


class CreateAlipayMobileTrade extends AbstractPayCreateTrade
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/trade/createAlipayMobileTrade";
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
        } else if(!$this->isNotifyUrlSet()) {
            throw new \Exception("缺少必填参数notify_url");
        } else if(!$this->isOutTradeNoSet()) {
            throw new \Exception("缺少必填参数out_trade_no");
        }
    }
}