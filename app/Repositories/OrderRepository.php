<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/13
 * Time: 下午3:51
 */

namespace App\Repositories;


use App\CIService\Pay;
use App\Helpers\WyethUtil;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderRepository extends BaseRepository
{
    //多少元买多少mq
    protected $price_mq = [
        1 => 50,
        6 => 300,
        10 => 500,
        20 => 1000,
        50 => 2500,
        100 => 5000,
        200 => 10000,
        500 => 25000,
    ];

    /**
     * 创建订单
     * @param $uid
     * @param $price
     * @param $return_url
     * @param $channel
     * @return array
     */
    public function createOrder($uid, $price, $return_url, $channel = null){
        if (!is_numeric($price) || !isset($this->price_mq[$price])){
            return $this->error->ORDER_INVALID_PRICE;
        }
        if (!$return_url){
            return $this->returnError('no return_url');
        }

        //购买mq
        $mq = $this->price_mq[$price];
        //业务单号
        $order_no = WyethUtil::generateTradeId();

        //支付的框iframe
        $iframe_url = get_http() . "wyeth-uploadsites.nibaguai.com/weex/pay/recharge.html?price={$price}";

        //测试1分钱
        if (env('APP_ENV') == 'local'){
            $price = 0.01;
        }

        $order = new Order();
        $order->uid = $uid;
        $order->order_no = $order_no;
        $order->subject = "购买{$mq}MQ";
        $order->mq = $mq;
        $order->total_fee = $price * 100;
        $order->status = Order::STATE_WAIT;
        if ($channel){
            $order->channel = $channel;
        }
        $order->save();


        //生成支付链接
        $pay_url = (new Pay())->getPayUrl($order->subject, $order->total_fee, $order->order_no, $return_url, $iframe_url);
        return $this->returnData([
            'pay_url' => $pay_url,
            'order_no' => $order_no
        ]);
    }

    public function createMiniOrder($price, $channel = 'mini'){
        if (!is_numeric($price) || !isset($this->price_mq[$price])){
            return $this->error->ORDER_INVALID_PRICE;
        }

        $user = Auth::user();
        if (!$user){
            return $this->error->NO_USER;
        }

        if (!$user->mini_openid){
            return $this->returnError('no mini_openid');
        }

        //购买mq
        $mq = $this->price_mq[$price];
        //业务单号
        $order_no = WyethUtil::generateTradeId();

        //测试1分钱
        if (env('APP_ENV') == 'local'){
            $price = 0.01;
        }

        $order = new Order();
        $order->uid = $user->id;
        $order->order_no = $order_no;
        $order->subject = "购买{$mq}MQ";
        $order->mq = $mq;
        $order->total_fee = $price * 100;
        $order->status = Order::STATE_WAIT;
        if ($channel){
            $order->channel = $channel;
        }
        $order->save();

        $res = (new Pay())->createWeixinMiniTrade($user->mini_openid, $order->subject, $order->total_fee, $order->order_no);
        if ($res['ret'] == 1){
            return $this->returnData([
                'order_no' => $order_no,
                'pay_param' => $res['pay_param'],
                'trade_no' => $res['trade_no']
            ]);
        }
        return $res;
    }

    /**
     * 查询订单
     * @param $order_no
     * @return array
     */
    public function queryOrder($order_no){
        $order = Order::where('order_no', $order_no)->first();
        if (!$order){
            return $this->error->ORDER_NOT_EXIST;
        }
        return $this->returnData($order->toArray());
    }

    /**
     * 删除订单
     * @param $order_no
     * @return array
     */
    public function deleteOrder($order_no){
        $order = Order::where('order_no', $order_no)->first();
        if (!$order){
            return $this->error->ORDER_NOT_EXIST;
        }
        $res = Order::where('order_no', $order_no)->delete();
        return $this->returnData();
    }
}