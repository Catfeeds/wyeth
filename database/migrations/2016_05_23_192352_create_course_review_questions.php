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

use Illuminate\Database\Migrations\Migration;

class CreateCourseReviewQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_review_questions', function ($table) {
            $table->increments('id');
            $table->integer('uid');
            $table->integer('cid');
            $table->string('content');
            $table->tinyInteger('is_send')->default(0);
            $table->integer('yjt_qid');
            $table->string('answer_url');
            $table->tinyInteger('is_close')->default(0);
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
        Schema::drop('course_review_questions');
    }
}
