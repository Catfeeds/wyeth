<?php
/**
 * 创建支付宝手机网站支付交易
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午3:09
 */

namespace CIPay\Request;


class CreateAlipayWapTrade extends AbstractPayCreateTrade
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/trade/createAlipayWapTrade";
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
        } else if(!$this->isReturnUrlSet()) {
            throw new \Exception("缺少必填参数return_url");
        } else if(!$this->isProductCodeSet()) {
            throw new \Exception("缺少必填参数product_code");
        }
    }

    /**
     * 设置return_url
     * @param string $value 支付完成后前端回跳地址
     **/
    public function setReturnUrl($value)
    {
        $this->values['return_url'] = $value;
    }
    /**
     * 获取return_url
     * @return string
     **/
    public function getReturnUrl()
    {
        return $this->values['return_url'];
    }
    /**
     * 判断return_url是否存在
     * @return bool
     **/
    public function isReturnUrlSet()
    {
        return array_key_exists('return_url', $this->values);
    }


    /**
     * 设置product_code
     * @param string $value 商品id，商户自行定义 长度[1, 32]
     **/
    public function setProductCode($value)
    {
        $this->values['product_code'] = $value;
    }
    /**
     * 获取product_code
     * @return string
     **/
    public function getProductCode()
    {
        return $this->values['product_code'];
    }
    /**
     * 判断product_code是否存在
     * @return bool
     **/
    public function isProductCodeSet()
    {
        return array_key_exists('product_code', $this->values);
    }
}