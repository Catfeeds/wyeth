<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/8/23
 * Time: 下午2:34
 */

namespace App\Services;


class Leqee
{
    //云久的 是否领取会员卡接口
    public static function isMember($openid){
        //正式服
        $res = self::curlGet("http://shop.woaap.com/leqee/api/leqee-member-cards/$openid.json");
        //测试服
//        $res = self::curlGet("https://www.vchang8.com/leqee/api/leqee-member-cards/$openid.json");
        $res = json_decode($res, true);
        if ($res && $res['code'] == 1){
            return true;
        }
        return false;
    }

    public static function curlGet($url, $timeout = 3){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //权限验证的账号密码
        curl_setopt($ch, CURLOPT_USERPWD, 'wyethketangapi:CfzPnRTPZe92');
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}