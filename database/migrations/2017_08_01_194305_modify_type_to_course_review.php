<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTypeToCourseReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        Schema::table('course_review',function (Blueprint $table) {
            $table->integer('audio_duration')->after('audio')->comment('音频时长');
        });//
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_review',function (Blueprint $table){
            $table->dropColumn('audio_duration');
        });
    }
}
