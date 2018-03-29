<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseStatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_stat', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('uid');
                $table->integer('cid');
                $table->timestamp('sign_time');
                $table->tinyInteger('share_sign_page');
                $table->integer('share_sign_page_clicks');
                $table->timestamp('in_class_time');
                $table->timestamp('out_class_time');
                $table->integer('in_class_times');
                $table->integer('listen_time');
                $table->tinyInteger('share_living_page');
                $table->integer('share_living_page_clicks');
                $table->integer('speak_times');
                $table->integer('teacher_answer_times');
                $table->integer('anchor_answer_times');
                $table->tinyInteger('share_review_page');
                $table->integer('share_review_page_clicks');
                $table->timestamp('in_review_time');
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

    }
}
