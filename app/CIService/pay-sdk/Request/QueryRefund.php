<?php
/**
 * 查询退款
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午5:51
 */

namespace CIPay\Request;


class QueryRefund extends AbstractPay
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/refund/queryRefund";
    }

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    protected function checkParams()
    {
        if(!$this->isRefundNoSet()) {
            throw new \Exception("缺少必填参数refund_no");
        }
    }

    /**
     * 设置refund_no
     * @param string $value 退款号
     **/
    public function setRefundNo($value)
    {
        $this->values['refund_no'] = $value;
    }
    /**
     * 获取refund_no
     * @return string
     **/
    public function getRefundNo()
    {
        return $this->values['refund_no'];
    }
    /**
     * 判断refund_no是否存在
     * @return bool
     **/
    public function isRefundNoSet()
    {
        return array_key_exists('refund_no', $this->values);
    }
}