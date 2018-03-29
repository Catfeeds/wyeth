<?php
/**
 * 微信企业付款
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午6:03
 */

namespace CIPay\Request;


class CreateWeixinPubTransfer extends AbstractPay
{
    const WeixinJSPay = 'WeixinJSPay';
    const WeixinNativePay = 'WeixinNativePay';

    const NO_CHECK = 'NO_CHECK';
    const FORCE_CHECK = 'FORCE_CHECK';
    const OPTION_CHECK = 'OPTION_CHECK';

    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/transfer/createWeixinPubTransfer";
    }

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    protected function checkParams()
    {
        if(!$this->isTypeSet()) {
            throw new \Exception("缺少必填参数type");
        } else if(!$this->isTransferFeeSet()) {
            throw new \Exception("缺少必填参数transfer_fee");
        } else if(!$this->isOutTransferNoSet()) {
            throw new \Exception("缺少必填参数out_transfer_no");
        } else if(!$this->isDescSet()) {
            throw new \Exception("缺少必填参数desc");
        } else if(!$this->isOpenidSet()) {
            throw new \Exception("缺少必填参数openid");
        } else if(!$this->isCheckNameSet()) {
            throw new \Exception("缺少必填参数check_name");
        } else if(($this->getCheckName() == CreateWeixinPubTransfer::FORCE_CHECK
            || $this->getCheckName() == CreateWeixinPubTransfer::OPTION_CHECK)
            && !$this->isUserNameSet()) {
            throw new \Exception("FORCE_CHECK选项下缺少必填参数username");
        }
    }

    /**
     * 设置type
     * @param string $value 使用扫码还是公众号支付配置
     *                          WeixinJSPay-公众号支付
     *                          WeixinNativePay-扫码支付
     **/
    public function setType($value)
    {
        $this->values['type'] = $value;
    }
    /**
     * 获取type
     * @return string
     **/
    public function getType()
    {
        return $this->values['type'];
    }
    /**
     * 判断type是否存在
     * @return bool
     **/
    public function isTypeSet()
    {
        return array_key_exists('type', $this->values);
    }


    /**
     * 设置transfer_fee
     * @param integer $value 转账总金额 单位分
     **/
    public function setTransferFee($value)
    {
        $this->values['transfer_fee'] = $value;
    }
    /**
     * 获取type
     * @return integer
     **/
    public function getTransferFee()
    {
        return $this->values['transfer_fee'];
    }
    /**
     * 判断transfer_fee是否存在
     * @return bool
     **/
    public function isTransferFeeSet()
    {
        return array_key_exists('transfer_fee', $this->values);
    }


    /**
     * 设置out_transfer_no
     * @param string $value [1, 32]业务交易号，需保持唯一
     **/
    public function setOutTransferNo($value)
    {
        $this->values['out_transfer_no'] = $value;
    }
    /**
     * 获取out_transfer_no
     * @return string
     **/
    public function getOutTransferNo()
    {
        return $this->values['out_transfer_no'];
    }
    /**
     * 判断out_transfer_no是否存在
     * @return bool
     **/
    public function isOutTransferNoSet()
    {
        return array_key_exists('out_transfer_no', $this->values);
    }


    /**
     * 设置desc
     * @param string $value 转账描述 [1, 50]
     **/
    public function setDesc($value)
    {
        $this->values['desc'] = $value;
    }
    /**
     * 获取desc
     * @return string
     **/
    public function getDesc()
    {
        return $this->values['desc'];
    }
    /**
     * 判断desc是否存在
     * @return bool
     **/
    public function isDescSet()
    {
        return array_key_exists('desc', $this->values);
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
     * 设置check_name
     * @param string $value 校验用户名选项
     *                  NO_CHECK：不校验真实姓名
     *                  FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账）
     *                  OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）
     **/
    public function setCheckName($value)
    {
        $this->values['check_name'] = $value;
    }
    /**
     * 获取check_name
     * @return string
     **/
    public function getCheckName()
    {
        return $this->values['check_name'];
    }
    /**
     * 判断check_name是否存在
     * @return bool
     **/
    public function isCheckNameSet()
    {
        return array_key_exists('check_name', $this->values);
    }


    /**
     * 设置user_name
     * @param string $value 如果check_name是FORCE_CHECK或OPTION_CHECK 该字段要填写真实姓名
     **/
    public function setUserName($value)
    {
        $this->values['user_name'] = $value;
    }
    /**
     * 获取user_name
     * @return string
     **/
    public function getUserName()
    {
        return $this->values['user_name'];
    }
    /**
     * 判断user_name是否存在
     * @return bool
     **/
    public function isUserNameSet()
    {
        return array_key_exists('user_name', $this->values);
    }
}