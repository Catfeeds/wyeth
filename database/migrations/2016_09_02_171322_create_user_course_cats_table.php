<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCourseCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_course_cats', function ($table) {
            $table->increments('id');
            $table->integer('catid')->unsigned()->comment('套课id');
            $table->integer('uid')->unsigned()->comment('用户id');
            $table->timestamps();
            $table->unique(['catid', 'uid']);
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
