<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticPageviewTable extends Migration
{
    /**
     * Run the migrations.
     * 保存的是每个页面访问的pv,uv
     * @return void
     */
    public function up()
    {
        Schema::create('pageview', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('页面名称');
            $table->string('platform')->comment('平台类型h5或小程序');
            $table->integer('pv');
            $table->integer('uv');
            $table->dateTime('start')->comment('开始时间');
            $table->dateTime('end')->comment('结束时间');
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
        Schema::drop('pageview');
    }
}
