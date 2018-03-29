<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/11
 * Time: 下午2:04
 */

namespace App\Http\Controllers\Wyeth;


use App\Models\Order;
use App\Services\MqService;
use Illuminate\Http\Request;


//引入pay-sdk
require_once app_path('CIService/pay-sdk/autoload.php');

class PayController extends  WyethBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    //支付结果通知
    public function payNotify(Request $request){

        $this->log('request参数', $request->all());

        //验签
        $ret = \CIPay\Lib\Func::keyVerifySign($_POST, config('oneitfarm.appsecret'));
        if (!$ret){
            $this->error('验签失败');
        }

        if ($request->input('return_code') != 'SUCCESS'){
            $this->error('return_code fail');
        }

        //中台订单号
        $trade_no = $request->input('trade_no');
        if (!$trade_no){
            $this->error('no trade_no');
        }

        //业务单号
        $out_trade_no = $request->input('out_trade_no');
        $order = Order::where('order_no', $out_trade_no)
            ->where('status', Order::STATE_WAIT)
            ->first();
        if (!$order){
            $this->error("order_no {$out_trade_no} " . Order::STATE_WAIT . ' 不存在');
        }

        if ($order->total_fee != $request->input('total_fee')){
            $this->error("order_no {$out_trade_no} total_fee不一致");
        }

        //增加mq
        $res = MqService::increase($order->uid, MqService::ADD_TYPE_CASH, $order->mq);
        if ($res['ret'] != 1){
            $this->error('购买mq失败', $res);
        }

        //更新支付成功
        $order->status = Order::STATE_SUCCESS;
        $order->trade_no = $trade_no;
        $order->save();

        die('success');
    }

    //退款通知
    public function refundNotify(Request $request){
        $this->log('request参数', $request->all(), __FUNCTION__);

        //验签
        $ret = \CIPay\Lib\Func::keyVerifySign($_POST, config('oneitfarm.appsecret'));
        if (!$ret){
            $this->error('验签失败');
        }
    }

    private function log($m, $params = [], $function = 'payNotify'){
        \Log::info("$function: ".$m, $params);
    }

    private function error($m, $params = [], $function = 'payNotify'){
        \Log::error("$function: ".$m, $params);
        die('fail');
    }
}