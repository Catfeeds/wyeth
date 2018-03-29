<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToCoursePush extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_push', function (Blueprint $table) {
            $table->integer('type')->after('cid')->comment('推送类型');
            $table->integer('push_num')->after('status')->comment('预计推送人数');
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
            $table->dropColumn('type');
            $table->dropColumn('push_num');
        });
    }
}
