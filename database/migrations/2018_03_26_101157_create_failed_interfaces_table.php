<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFailedInterfacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failed_interfaces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment('接口类型');
            $table->string('uri')->comment('接口URI');
            $table->string('method')->comment('接口请求方式');
            $table->text('params')->comment('接口参数json格式');
            $table->string('result')->comment('接口返回');
            $table->integer('try')->comment('接口是否重试成功');
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
        Schema::drop('failed_interfaces');
    }
}
