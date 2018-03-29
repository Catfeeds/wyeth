<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContentToCourseReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('course_review',function (Blueprint $table){
			$table->text('content')->after('section')->comment('文本内容');
		});        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('course_review',function (Blueprint $table) {
			//
		}); //
    }
}
