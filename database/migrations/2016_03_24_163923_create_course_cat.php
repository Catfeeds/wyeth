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

class CreateCourseCat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_cat', function ($table) {
           $table->increments('id');
           $table->string('name', 200);
           $table->string('description', 1000);
           $table->string('img', 200);
           $table->mediumInteger('displayorder')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_cat');
    }
}
