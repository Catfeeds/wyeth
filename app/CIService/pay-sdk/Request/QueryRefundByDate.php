<?php
/**
 * 退款批量查询
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午6:01
 */

namespace CIPay\Request;


class QueryRefundByDate extends AbstractPay
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/refund/queryRefundByDate";
    }

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    protected function checkParams()
    {
        if(!$this->isDateSet()) {
            throw new \Exception("缺少必填参数date");
        }
    }

    /**
     * 设置date
     * @param string $value 查询日期 格式date('Y-m-d')
     **/
    public function setDate($value)
    {
        $this->values['date'] = $value;
    }
    /**
     * 获取date
     * @return string
     **/
    public function getDate()
    {
        return $this->values['date'];
    }
    /**
     * 判断date是否存在
     * @return bool
     **/
    public function isDateSet()
    {
        return array_key_exists('date', $this->values);
    }
}