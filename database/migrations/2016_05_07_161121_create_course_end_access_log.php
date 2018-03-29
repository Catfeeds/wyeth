<?php

/**
 *
 * CREATE TABLE `course_cat` (
 * `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT ,
 * `name` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8mb4_general_ci NULL ,
 * `description` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8mb4_general_ci NULL ,
 * `img` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8mb4_general_ci ,
 * `displayorder` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '10' ,
 * PRIMARY KEY (`id`)
 * ) ENGINE = InnoDB;
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseEndAccessLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_end_access_log', function ($table) {
            $table->increments('id');
            $table->integer('uid');
            $table->integer('cid');
            $table->timestamps();
            $table->index(['uid', 'cid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_end_access_log');
    }
}
