<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_records', function ($table) {
            $table->increments('id');
            $table->integer('uid')->comment('搜索用户id');
            $table->string('keyword')->comment('搜索关键字');
            $table->string('result')->comment('搜索结果课程id 逗号隔开');
            $table->integer('click_type')->comment('1 搜索结果课程  2 推荐课程');
            $table->integer('click_id')->comment('点击课程id');
            $table->index(['id', 'keyword', 'created_at']);
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
    }
}
