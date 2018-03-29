<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClearUnuseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //删除无用表
        Schema::dropIfExists('pageview');
        Schema::dropIfExists('year_messages');
        Schema::dropIfExists('comment');
        Schema::dropIfExists('course_end_access_log');
        Schema::dropIfExists('job_media');
        Schema::dropIfExists('user_course_trace');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
