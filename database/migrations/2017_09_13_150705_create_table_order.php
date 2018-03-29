<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户id');
            $table->string('order_no', 20)->comment('订单号');
            $table->string('trade_no')->comment('中台订单号');
            $table->string('subject')->comment('交易名称');
            $table->integer('mq')->comment('购买多少mq');
            $table->integer('total_fee')->comment('总金额,分');
            $table->string('status')->comment('订单状态 wait待支付 success支付成功 fail支付失败');
            $table->timestamps();
            $table->unique('order_no');
            $table->index('uid');
            $table->index('order_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order');
    }
}
