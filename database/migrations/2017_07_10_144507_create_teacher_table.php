<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('teacher',function ($table) {
            $table->increments('id');
            $table->string('name', 10)->comment('讲师姓名');
            $table->string('avatar', 200)->comment('讲师头像');
            $table->string('hospital', 100)->comment('讲师医院');
            $table->string('position', 50)->comment('讲师职位');
            $table->text('desc')->comment('讲师描述');
            $table->timestamps();
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
        Schema::drop('teacher');
    }
}
