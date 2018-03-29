<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCoursePush extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_push', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cid')->comment('课程id');
            $table->dateTime('push_time')->comment('推送时间');
            $table->integer('status')->comment('推送状态 0待推送 1已推送')->default('0');
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
        Schema::drop('course_push');
    }
}
