<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableXxjp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('huiyao_xxjp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cid')->comment('课程id');
            $table->integer('push_num')->comment('推送数');
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
        Schema::drop('huiyao_xxjp');
    }
}
