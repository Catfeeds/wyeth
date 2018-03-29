<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeekDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::create('week_data_export',function(Blueprint $table){
		$table->increments('id');
		$table->date('start_day');
		$table->date('end_day');
		$table->string('all_course_week_url');
		$table->string('week_new_course_url');
		$table->string('week_summary_url');
		$table->string('all_course_year_url');
		$table->string('all_course_year_short_url');
		$table->string('signup_by_channel_url');
		$table->string('week_diversion_url');
	});        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('week_data_export'); //
    }
}
