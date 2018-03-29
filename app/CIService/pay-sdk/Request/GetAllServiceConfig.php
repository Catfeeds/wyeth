<?php
/**
 * Created by PhpStorm.
 * User: tsy
 * Date: 2017/9/1
 * Time: 下午7:06
 */

namespace CIPay\Request;


class GetAllServiceConfig extends AbstractPay
{
    /**
     * 获取接口地址
     * @return string
     */
    protected function getApiMethodName()
    {
        return "/config/getAllServiceConfig";
    }

    /**
     * 检查参数是否正确
     * @throws \Exception
     */
    protected function checkParams()
    {

    }

}