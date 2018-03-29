<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPushCountToHuiyaoXxjp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('huiyao_xxjp', function (Blueprint $table) {
            $table->integer('push_num')->comment('推送人数')->change();
            $table->integer('push_count')->comment('推送条数')->after('push_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('huiyao_xxjp', function (Blueprint $table) {
            $table->dropColumn('push_count');
        });
    }
}
