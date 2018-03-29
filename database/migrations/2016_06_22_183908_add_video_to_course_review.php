<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVideoToCourseReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_review',function ($table) {
           //重命名字段
           // $table->renameColumn('video','audio');

            //增加字段
            $table->boolean('video_display')->after('audio')->default(false);
            $table->integer('video_position')->after('video_display');
            $table->string('video', 200)->after('video_position');
            $table->string('video_cover', 250)->after('video');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
