<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColTDateToHuiyaoXxjp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('huiyao_xxjp', function (Blueprint $table) {
            $table->dateTime('t_date')->comment('自动下行的日期')->after('push_num');
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
            $table->dropColumn('t_date');
        });
    }
}
