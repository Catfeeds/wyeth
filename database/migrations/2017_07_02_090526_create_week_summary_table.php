<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeekSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::create('week_summary',function ($table) {
		$table->increments('id');
		$table->date('start_day')->comment('时间（周）');
		$table->date('end_day');
		$table->integer('index_pv');
		$table->integer('index_uv');
		$table->integer('h5_sign_up')->comment('h5报名');
		$table->integer('h5_sign_up_online')->comment('h5报名-线上');
		$table->integer('h5_sign_up_offline')->comment('h5报名-线下');
		$table->integer('people_times')->comment('教育人次');
		$table->integer('people_number')->comment('教育人数');
		$table->integer('people_times_offline')->comment('教育人次-线下');
		$table->integer('people_number_offline')->comment('教育人数-线下');
		$table->integer('large_platform_people_times')->comment('大平台教育人次');
		$table->integer('community_people_times')->comment('社区教育人次');
		$table->integer('other')->comment('其他');
		$table->integer('listen_hours')->comment('听课时长');
		$table->integer('ask_times')->comment('提问人次');
		$table->integer('week_active')->comment('周活跃');
		$table->integer('week_active_online')->comment('线上周活跃');
		$table->integer('new_member')->comment('新会员');
	});
       //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	Schema::drop('week_summary');        //
    }
}
