<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAidToShareLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('share_log', function (Blueprint $table) {
            $table->integer('aid')->comment('活动ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('share_log', function (Blueprint $table) {
            $table->dropColumn('aid');
        });
    }
}
