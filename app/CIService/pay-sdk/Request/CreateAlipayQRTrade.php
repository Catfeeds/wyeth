<?php
/**
 * 创建支付宝扫码支付交易
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午2:59
 */

namespace CIPay\Request;


class CreateAlipayQRTrade extends AbstractPayCreateTrade
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/trade/createAlipayQRTrade";
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