<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/13
 * Time: 下午3:54
 */

namespace App\Http\Controllers\Wyeth;


use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends WyethBaseController
{
    protected $orderRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderRepository = new OrderRepository();
    }

    public function createOrder(Request $request){
        return $this->orderRepository->createOrder($this->uid, $request->input('price'), $request->input('return_url'), $request->input('channel'));
    }

    public function queryOrder(Request $request){
        return $this->orderRepository->queryOrder($request->input('order_no'));
    }

    //小程序支付订单
    public function createMiniOrder(Request $request){
        return $this->orderRepository->createMiniOrder($request->input('price'));
    }
}