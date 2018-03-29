<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddListenToCourseStat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_stat', function (Blueprint $table) {
            $table->integer('listen')->after('channel')->comment('最多的一次听课时长');
            $table->tinyInteger('reward')->after('listen')->comment('是否已获得听课奖励');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_stat', function (Blueprint $table) {
            $table->dropColumn('listen');
            $table->dropColumn('reward');
        });
    }
}
