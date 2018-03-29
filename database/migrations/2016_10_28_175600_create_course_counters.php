<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseCounters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_counters', function ($table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->comment('项目id，对应cid或者套课cid');
            $table->char('item_type', 32)->comment('项目类型：course普通课程 course_cat 套课');
            $table->integer('course_reg')->unsigned()->comment('报名')->default(0);
            $table->integer('course_cat_reg')->unsigned()->comment('套课报名')->default(0);
            $table->integer('course_review')->unsigned()->comment('回顾课程')->default(0);
            $table->integer('course_living')->unsigned()->comment('直播课程')->default(0);
            $table->timestamps();
            $table->unique(['item_id','item_type']);
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
