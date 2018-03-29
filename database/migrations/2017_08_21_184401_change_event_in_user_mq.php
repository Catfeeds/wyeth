<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEventInUserMq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('user_mq', function (Blueprint $table) {
            $table->integer('type')->comment('增加或消费类型')->default(0)->after('uid');
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
        Schema::table('user_mq', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
