<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_event', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('事件名称');
            $table->string('desc')->comment('事件描述');
            $table->dateTime('online_date')->comment('埋点日期');
            $table->integer('status')->comment('状态');
            $table->integer('event_num');
            $table->string('event_uid');
            $table->string('ext_1');
            $table->integer('ext_2');
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
        Schema::drop('statistic_event');
    }
}
