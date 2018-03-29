<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBuyCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_buy_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户id');
            $table->integer('type')->comment('购买类型：1 套课 2 单课');
            $table->integer('cid')->comment('套课ID或课程ID');
            $table->integer('mq')->comment('消耗MQ数量');
            $table->text('detail')->comment('购买时商品状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_buy_courses');
    }
}
