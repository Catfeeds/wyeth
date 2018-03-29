<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseApply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_apply', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('account_id');
                $table->string('title', 50);
                $table->text('content');
                $table->date('start_day');
                $table->date('end_day');
                $table->time('start_time');
                $table->time('end_time');
                $table->string('stage', 50);
                $table->string('teacher_name', 20);
                $table->string('teacher_source', 80);
                $table->string('teacher_position', 80);
                $table->text('teacher_desc');
                $table->tinyInteger('status');
                $table->string('refuse_reason', 100);
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
        Schema::drop('course_apply');
    }
}
