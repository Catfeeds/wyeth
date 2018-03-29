<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScanNumToTagQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tag_question', function (Blueprint $table) {
            $table->integer('scan_num')->after('answer')->comment('浏览数');
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
        Schema::table('tag_question', function (Blueprint $table) {
            $table->dropColumn('scan_num');
        });
    }
}
