<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/11
 * Time: 下午2:00
 */

namespace App\CIService;

//引入pay-sdk
require_once app_path('CIService/pay-sdk/autoload.php');

class Pay extends BaseCIService
{
    /**
     * 获取支付链接
     * @param $subject
     * @param $total_fee
     * @param $out_trade_no
     * @param $return_url
     * @param $iframe_url
     * @return string
     */
    public function getPayUrl($subject, $total_fee, $out_trade_no, $return_url, $iframe_url){

        //测试
        if (env('APP_ENV') == 'local'){
            $notify_url = config('app.url'). '/wyeth/pay/payNotify?hw_dev=xj';
        }else{
            $notify_url = config('app.url'). '/wyeth/pay/payNotify';
        }

        $params = [
            'appkey' => $this->appkey,
            'channel' => $this->channel,
            'subject' => $subject,
            'total_fee' => $total_fee,
            'notify_url' => $notify_url,
            'out_trade_no' => $out_trade_no,
            'return_url' => $return_url,
            'iframe_url' => $iframe_url,
            'title' => '魔栗妈咪学院'
        ];
        //获取签名
        $params['sign'] = \CIPay\Lib\Func::keySign($params, $this->appsecret);
        //要用http的
        $domain = get_http() . 'oneitfarm.com';
        return $domain . '/pay/main.php?action=oneitfarm_wx_h5_pay.html&' . http_build_query($params);
    }

    /**
     * 微信小程序支付
     * @param $openid
     * @param $subject
     * @param $total_fee
     * @param $out_trade_no
     * @return array|mixed
     */
    public function createWeixinMiniTrade($openid, $subject, $total_fee, $out_trade_no){
        //测试
        if (env('APP_ENV') == 'local'){
            $notify_url = config('app.url'). '/wyeth/pay/payNotify?hw_dev=xj';
        }else{
            $notify_url = config('app.url'). '/wyeth/pay/payNotify';
        }

        $params = [
            'app_key' => $this->appkey,
            'channel' => $this->channel,
            'subject' => $subject,
            'total_fee' => $total_fee,
            'notify_url' => $notify_url,
            'out_trade_no' => $out_trade_no,
            'openid' => $openid
        ];
        //获取签名
        $params['sign'] = \CIPay\Lib\Func::keySign($params, $this->appsecret);
        $res = $this->post('/pay/main.php/json/trade/createWeixinMiniTrade', $params, false);
        return $res;
    }

    /**
     * 退款
     * @param $out_refund_no
     * @param $trade_no
     * @param $refund_fee
     * @param $reason
     * @param string $refund_account
     * @return array|mixed
     */
    public function refund($out_refund_no, $trade_no, $refund_fee, $reason, $refund_account = 'REFUND_SOURCE_UNSETTLED_FUNDS'){
        //测试
        if (env('APP_ENV') == 'local'){
            $notify_url = config('app.url'). '/wyeth/pay/refundNotify?hw_dev=xj';
        }else{
            $notify_url = config('app.url'). '/wyeth/pay/refundNotify';
        }

        $params = [
            'app_key' => $this->appkey,
            'channel' => $this->channel,
            'out_refund_no' => $out_refund_no,
            'trade_no' => $trade_no,
            'refund_fee' => $refund_fee,
            'reason' => $reason,
            'notify_url' => $notify_url,
            'refund_account' => $refund_account,
        ];
        //获取签名
        $params['sign'] = \CIPay\Lib\Func::keySign($params, $this->appsecret);
        $res = $this->post('/pay/main.php/json/refund/doRefund', $params, false);
        return $res;
    }
}