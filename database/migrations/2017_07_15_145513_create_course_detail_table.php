<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::create('course_detail',function(Blueprint $table){
		$table->increments('id');
		$table->integer('cid');
		$table->date('start_day');
		$table->date('end_day');
		$table->string('title');
		$table->integer('week_other')->comment('其他平台教育人次');
		$table->integer('week_mudu')->comment('目睹教育人次');
		$table->integer('month_other')->comment('本月其他平台教育人次');
		$table->integer('month_mudu')->comment('本月目睹教育人次');
		$table->integer('ytd_h5')->comment('ytd H5教育人次');
		$table->integer('ytd_other')->comment('ytd 其他平台教育人次');
		$table->integer('ytd_mudu')->comment('ytd 目睹教育人次');
		$table->integer('now_all_sign')->comment('上线至今总报名人次');
		$table->integer('now_all_edu')->comment('上线至今全平台教育人次');
		$table->integer('now_h5')->comment('上线至今h5教育人次');
		$table->integer('now_other')->comment('上线至今其他平台教育人次');
		$table->integer('now_mudu')->comment('上线至今目睹教育人次');
});        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
   	Schema::drop('course_detail');     //
    }
}
