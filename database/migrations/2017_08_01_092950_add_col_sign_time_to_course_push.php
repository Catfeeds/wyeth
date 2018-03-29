<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColSignTimeToCoursePush extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //course_push添加指定报名时间
        Schema::table('course_push', function (Blueprint $table) {
            $table->dateTime('sign_start')->comment('报名开始时间')->after('status');
            $table->dateTime('sign_end')->comment('报名结束时间')->after('sign_start');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_push', function (Blueprint $table) {
            $table->dropColumn('sign_start');
            $table->dropColumn('sign_end');
        });
    }
}
