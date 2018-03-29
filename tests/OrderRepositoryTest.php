<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderRepositoryTest extends TestCase
{
    /**
     * @var \App\Repositories\OrderRepository
     */
    private $orderRepository;
    
    private $order_no;

    /**
     * 执行每个测试方法前都会执行,类似于构造函数
     */
    public function setUp() {
        parent::setUp();

        $this->orderRepository = new \App\Repositories\OrderRepository();
    }

    public function testCreateOrder(){
        $return_url = config('app.url') . '/mobile/index';
        //测试错误价格
        $price_error_array = [0.1, 2, 4, 102, -10];
        foreach ($price_error_array as $price){
            $res = $this->orderRepository->createOrder($this->uid, $price, $return_url);
            $this->assertEquals($this->error->ORDER_INVALID_PRICE, $res);
        }

        $price = 1;

        $res = $this->orderRepository->createOrder($this->uid, $price, '');
        $this->assertEquals('no return_url', $res['msg']);

        $res = $this->orderRepository->createOrder($this->uid, $price, $return_url);
        $this->assertEquals(1, $res['ret']);

        $this->order_no = $res['data']['order_no'];
        //为testQueryOrder查询正确的订单号
        return $res['data']['order_no'];
    }

    /**
     * @param $order_no
     * @depends testCreateOrder
     */
    public function testQueryOrder($order_no){
        $order_no_error = '20170920143400463255';
        $res = $this->orderRepository->queryOrder($order_no_error);
        $this->assertEquals($this->error->ORDER_NOT_EXIST, $res);

        $res = $this->orderRepository->queryOrder($order_no);
        $this->assertEquals(1, $res['ret']);
    }

    /**
     * @param $order_no
     * @depends testCreateOrder
     */
    public function testDeleteOrder($order_no){
        $res = $this->orderRepository->deleteOrder($order_no);
        $this->assertEquals(1, $res['ret']);
    }
}
