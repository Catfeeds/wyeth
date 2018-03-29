<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticCourseDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_course_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('platform')->comment('平台类型h5或小程序');
            $table->integer('edu_total_pv')->comment('累计教育pv');
            $table->integer('edu_total_uv')->comment('累计教育uv');
            $table->integer('play_edu')->comment('听课人数');
            $table->decimal('avg_listen_time',5,2)->comment('平均听课时长');
            $table->decimal('avg_listen_time_10',5,2)->comment('听课时长小于10s');
            $table->decimal('avg_listen_time_15',5,2)->comment('听课时长小于15s');
            $table->integer('stay_edu')->comment('停留人数');
            $table->decimal('avg_stay_time',6,2)->comment('平均停留时长');
            $table->decimal('avg_stay_time_10',6,2)->comment('停留时长小于10s');
            $table->decimal('avg_stay_time_15',6,2)->comment('停留时长小于15s');
            $table->decimal('repeat_rate',3,2)->comment('重复率');
            $table->decimal('retain_rate',3,2)->comment('留存率');
            $table->integer('1min')->comment('1分内');
            $table->integer('2min')->comment('1-2min');
            $table->integer('3min');
            $table->integer('4min');
            $table->integer('5min');
            $table->integer('6min');
            $table->integer('7min');
            $table->integer('over_7min')->comment('7分以上');
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
        Schema::drop('statistic_course_detail');
    }
}
