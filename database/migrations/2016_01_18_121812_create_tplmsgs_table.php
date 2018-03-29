<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTplmsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tplmsgs', function (Blueprint $table) {
                $table->increments('id');
                $table->tinyInteger('type')->default(0);//1开课通知，2报名通知
                $table->integer('cid');
                $table->string('openid');
                $table->text('content');
                $table->tinyInteger('status')->default(0);
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
