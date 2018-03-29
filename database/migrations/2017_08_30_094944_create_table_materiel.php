<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMateriel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materiel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('position')->comment('点位');
            $table->string('brand')->comment('品牌');
            $table->string('group_t')->comment('分组');
            $table->string('date')->comment('日期');
            $table->string('link')->comment('推送链接');
            $table->string('key_word')->comment('关键词');
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
        Schema::drop('materiel');
    }
}
