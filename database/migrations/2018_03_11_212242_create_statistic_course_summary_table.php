<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticCourseSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_summary', function (Blueprint $table) {
            $table->increments('id');
            $table->string('platform')->comment('平台类型h5或小程序');
            $table->integer('play_edu')->comment('听课人数');
            $table->integer('edc_0')->comment('孕期');
            $table->integer('edc_1')->comment('1-12m');
            $table->integer('edc_2')->comment('12m-24m');
            $table->integer('edc_3')->comment('24m+');
            $table->integer('1min')->comment('听课1分内');
            $table->integer('2min')->comment('1-2min');
            $table->integer('3min');
            $table->integer('4min');
            $table->integer('5min');
            $table->integer('6min');
            $table->integer('7min');
            $table->integer('over_7min')->comment('7分以上');
            $table->integer('over_time')->comment('完听人数');
            $table->integer('all_listen_time')->comment('总听课时长');
            $table->dateTime('start')->comment('开始时间');
            $table->dateTime('end')->comment('结束时间');
            $table->string('desc')->comment('备注');
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
        Schema::drop('course_summary');
    }
}
