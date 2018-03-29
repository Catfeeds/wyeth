<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLinkToCat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_cat', function (Blueprint $table) {
            $table->string('link')->afetr('img')->comment('banner链接');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_cat', function (Blueprint $table) {
            $table->dropColumn('link');
        });
    }
}

