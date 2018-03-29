<?php
/**
 * 创建微信扫码支付交易
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午2:33
 */

namespace CIPay\Request;


class CreateWeixinNativeTrade extends AbstractPayCreateTrade
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/trade/createWeixinNativeTrade";
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
        } else if(!$this->isProductIdSet()) {
            throw new \Exception("缺少必填参数product_id");
        }
    }

    /**
     * 设置product_id
     * @param string $value 商品id，商户自行定义 长度[1, 32]
     **/
    public function setProductId($value)
    {
        $this->values['product_id'] = $value;
    }
    /**
     * 获取product_id
     * @return string
     **/
    public function getProductId()
    {
        return $this->values['product_id'];
    }
    /**
     * 判断product_id是否存在
     * @return bool
     **/
    public function isProductIdSet()
    {
        return array_key_exists('product_id', $this->values);
    }
}