<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigninItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signin_items', function ($table) {
            $table->increments('id');
            $table->integer('start_uid')->unsigned()->comment('游戏发起人ID');
            $table->integer('cid')->unsigned()->comment('课程ID');
            $table->integer('signin_num')->comment('签到数量');
            $table->index(['start_uid', 'signin_num', 'created_at']);
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

    }
}
