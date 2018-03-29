<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAdvertise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertise', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->comment('0:广告 1:品牌课 2:活动');
            $table->integer('brand_id')->comment('品牌ID');
            $table->integer('size')->comment('高 0：200，1：272，2：360');
            $table->string('link')->comment('链接');
            $table->string('img')->comment('图片');
            $table->integer('display')->comment('是否有效');
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
        Schema::drop('advertise');
    }
}
