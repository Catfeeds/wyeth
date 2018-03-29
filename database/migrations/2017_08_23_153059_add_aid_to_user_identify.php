<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAidToUserIdentify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_identify', function (Blueprint $table) {
            $table->integer('aid')->comment('活动id')->after('uid');
            $table->timestamps();
            $table->index('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_identify', function (Blueprint $table) {
            $table->dropColumn('aid');
        });
    }
}
