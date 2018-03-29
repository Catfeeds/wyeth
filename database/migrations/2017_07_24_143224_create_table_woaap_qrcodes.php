<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWoaapQrcodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woaap_qrcodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source')->comment('来源');
            $table->string('params')->comment('参数的json字符串');
            $table->string('scene_str')->comment('微信场景值id');
            $table->string('ticket')->comment('微信ticket');
            $table->integer('expire')->comment('过期时间');
            $table->timestamps();
            $table->index('scene_str');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('woaap_qrcodes');
    }
}
