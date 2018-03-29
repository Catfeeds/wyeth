<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigninWinRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signin_win_records', function ($table) {
            $table->increments('id');
            $table->integer('signin_item_id')->unsigned()->comment('游戏ID');
            $table->integer('mobile')->comment('所属 client id');
            $table->string('realname', 50)->comment('真实姓名');
            $table->string('address', 100)->comment('收货地址');
            $table->string('remark', 100)->comment('备注');
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
