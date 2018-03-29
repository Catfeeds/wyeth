<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_mq',function ($table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户id');
            $table->string('event', 255)->comment('积分改变的事件');
            $table->integer('mq')->comment('增加或减少的积分值');
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
        //
        Schema::drop('user_mq');
    }
}
