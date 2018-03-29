<?php
/**
 * Created by PhpStorm.
 * User: tsy
 * Date: 2017/9/1
 * Time: 上午11:17
 */

namespace CIPay\Lib;


class Func
{
    /**
     * 签名
     * 算法:参数按照ASCILL排序后拼接key,然后md5,最后转为大写
     * @param $data mixed 参数数组
     * @param $secret string 签名密钥 app_secret
     * @return string
     */
    public static function keySign($data, $secret) {
        $unsign_str = Func::createLinkString(Func::argSort($data)) . "&secret=" . $secret;
        $sign = strtoupper(md5($unsign_str));

        return $sign;
    }

    /**
     * 签名验证
     * @param $data mixed 完整的参数数组
     * @param $secret string 签名密钥 app_secret
     * @return bool false-验证失败 true-验证成功
     */
    public static function keyVerifySign($data, $secret) {
        $para = array();
        foreach ($data as $key=>$val) {
            if($key == 'sign') {
                $sign = $val;
            } else {
                $para[$key] = $val;
            }
        }

        if(empty($sign)) {
            return false;
        }

        $unsign_str = Func::createLinkString(Func::argSort($para)) . "&secret=" . $secret;
        $sign_str = strtoupper(md5($unsign_str));

        if($sign === $sign_str) {
            return true;
        }

        return false;
    }

    /**
     * 数组排序 按照ASCII字典升序
     * @param $para mixed 排序前数组
     * @return mixed 排序后数组
     */
    public static function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para mixed 需要拼接的数组
     * @return string 拼接完成以后的字符串
     */
    public static function createLinkString($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            if($val === "") {
                continue;
            }
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        return $arg;
    }

    /**
     * 以post方式提交接口
     * @param string $url url
     * @param array $params 需要post的参数数据
     * @param int $second   url执行超时时间，默认30s
     * @return string
     * @throws \Exception
     */
    public static function postCurl($url, $params, $second = 30)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-SDK OAuth2.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        //运行curl
        $data = curl_exec($ch);

        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new \Exception("curl出错，错误码:$error");
        }
    }
}