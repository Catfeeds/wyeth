<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubheaderToCourseCat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_cat', function (Blueprint $table) {
            $table->string('subheader')->comment('副标题')->default('')->after('name');
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
            $table->dropColumn('subheader');
        });
    }
}
