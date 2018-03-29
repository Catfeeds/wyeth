<?php

/**
 * ALTER TABLE `course` ADD `cid` INT(11) UNSIGNED AFTER `id`;
 * 给课程列表加了一个cid,连接course_cat id
 */
use Illuminate\Database\Migrations\Migration;

class AlterTableCourseAddCid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('course', function ($table) {
            $table->smallInteger('cid')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course', function ($table) {
            $table->dropColumn('cid');
        });
    }
}
