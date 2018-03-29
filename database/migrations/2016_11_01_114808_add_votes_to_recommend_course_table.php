<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToRecommendCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recommend_course', function (Blueprint $table) {
            $table->integer('uid', 11)->unsigned()->after('id');
            $table->integer('id', 11)->unsigned()->change();
            $table->integer('sign_up_course_id', 11)->unsigned()->change();
            $table->integer('recommend_course_id', 11)->unsigned()->change();
            $table->index(['sign_up_course_id', 'id']);
            $table->index('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recommend_course', function (Blueprint $table) {
            //
        });
    }
}
