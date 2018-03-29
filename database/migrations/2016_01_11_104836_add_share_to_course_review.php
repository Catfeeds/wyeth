<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShareToCourseReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_review', function (Blueprint $table) {
            //
            $table->string('firend_title', 250)->after('status');
            $table->text('firend_subtitle')->after('firend_title');
            $table->text('share_title')->after('firend_subtitle');
            $table->string('share_picture', 250)->after('share_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_review', function (Blueprint $table) {
            //
        });
    }
}
