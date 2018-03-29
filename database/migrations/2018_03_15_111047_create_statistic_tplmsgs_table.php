<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticTplmsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_tplmsgs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cid')->comment('课程id');
            $table->integer('type')->comment('模板消息类型');
            $table->integer('send_pv');
            $table->integer('send_uv');
            $table->integer('open_pv');
            $table->integer('open_uv');
            $table->string('channel')->comment('模板消息渠道');
            $table->dateTime('date')->comment('日期');
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
        Schema::drop('statistic_tplmsgs');
    }
}
