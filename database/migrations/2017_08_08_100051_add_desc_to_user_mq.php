<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescToUserMq extends Migration
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
            $table->text('desc')->comment('消费描述')->default('')->after('event');
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
            $table->dropColumn('desc');
        });
    }
}
