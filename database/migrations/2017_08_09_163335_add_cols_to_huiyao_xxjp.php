<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToHuiyaoXxjp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('huiyao_xxjp', function (Blueprint $table) {
            $table->integer('sign_num')->after('t_date')->comment('线下报名人数');
            $table->integer('pv')->after('sign_num')->comment('打开pv');
            $table->integer('uv')->after('pv')->comment('打开uv');
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
            $table->dropColumn('sign_num');
            $table->dropColumn('pv');
            $table->dropColumn('uv');
        });
    }
}
