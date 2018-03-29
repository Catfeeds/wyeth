<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecommendCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommend_course', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sign_up_course_id')->unsigned();
            $table->string('sign_up_course_stage', 20);
            $table->integer('recommend_course_id')->unsigned();
            $table->string('recommend_course_stage', 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recommend_course');
    }
}
