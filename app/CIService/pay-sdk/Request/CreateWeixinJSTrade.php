<?php
/**
 * 创建微信公众号支付交易
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午2:23
 */

namespace CIPay\Request;


class CreateWeixinJSTrade extends AbstractPayCreateTrade
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/trade/createWeixinJSTrade";
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
        } else if(!$this->isOpenidSet() && !$this->isSubOpenidSet()) {
            throw new \Exception("openid和sub_openid至少填一个");
        }
    }

    /**
     * 设置openid
     * @param string $value 微信公众号中获取的openid。ps：服务商模式下为服务商商户的openid
     **/
    public function setOpenid($value)
    {
        $this->values['openid'] = $value;
    }
    /**
     * 获取openid
     * @return string
     **/
    public function getOpenid()
    {
        return $this->values['openid'];
    }
    /**
     * 判断openid是否存在
     * @return bool
     **/
    public function isOpenidSet()
    {
        return array_key_exists('openid', $this->values);
    }


    /**
     * 设置sub_openid
     * @param string $value 服务商模式下使用。用户在子商户appid下的唯一标识。openid和sub_openid可以选传其中之一
     **/
    public function setSubOpenid($value)
    {
        $this->values['sub_openid'] = $value;
    }
    /**
     * 获取sub_openid
     * @return string
     **/
    public function getSubOpenid()
    {
        return $this->values['sub_openid'];
    }
    /**
     * 判断sub_openid是否存在
     * @return bool
     **/
    public function isSubOpenidSet()
    {
        return array_key_exists('sub_openid', $this->values);
    }
}