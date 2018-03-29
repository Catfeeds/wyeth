<?php
/**
 * 创建支付宝条码支付交易
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午3:15
 */

namespace CIPay\Request;


class CreateAlipayBarTrade extends AbstractPayCreateTrade
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/trade/createAlipayBarTrade";
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


    /**
     * 设置goods_detail
     * @param string $value 交易物品详情列表 格式为 GoodsDetail[], json字符串 格式见参数规则-7. GoodsDetail格式 https://gitlab.oneitfarm.com/idg/Pay/wikis/rule_param#7-goodsdetail-%E6%A0%BC%E5%BC%8F
     **/
    public function setGoodsDetail($value)
    {
        $this->values['goods_detail'] = $value;
    }
    /**
     * 获取goods_detail
     * @return string
     **/
    public function getGoodsDetail()
    {
        return $this->values['goods_detail'];
    }
    /**
     * 判断goods_detail是否存在
     * @return bool
     **/
    public function isGoodsDetailSet()
    {
        return array_key_exists('goods_detail', $this->values);
    }
}