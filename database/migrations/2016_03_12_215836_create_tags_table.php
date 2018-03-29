<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 100);
            $table->timestamps();
            $table->unique('name', 'name');
        });

        Schema::create('course_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cid')->unsigned();
            $table->integer('tid')->unsigned();
            $table->timestamps();
            $table->index(['tid'], 'tid');
            $table->index(['cid'], 'cid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tags');
        Schema::drop('course_tags');
    }
}
