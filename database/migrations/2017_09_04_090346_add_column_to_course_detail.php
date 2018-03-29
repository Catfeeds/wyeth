<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToCourseDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_detail', function (Blueprint $table) {
            $table->integer('ask')->comment('提问数');
            $table->string('is_order')->comment('签约状态');
            $table->integer('share')->comment('转发');
            $table->string('ext')->comment('扩展');
            $table->integer('ext1')->comment('扩展2(int)');
            $table->renameColumn('now_mudu','ask_lask_week');
        });//
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_detail', function (Blueprint $table) {
            $table->dropColumn('ask','is_order','share','ext');
        });//
    }
}
