<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemarkToCoursePush extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_push', function (Blueprint $table) {
            $table->text('remark')->comment('备注')->default('')->after('push_num');
            $table->string('action')->comment('cidata查询的action')->default('open_tplmsg')->after('remark');
            $table->text('ext')->comment('扩展字段')->default('')->after('action');
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
            $table->dropColumn('remark');
            $table->dropColumn('action');
            $table->dropColumn('ext');
        });
    }
}
