<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLivingShareToCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course', function (Blueprint $table) {
            //
            $table->string('living_firend_title', 250)->after('status');
            $table->text('living_firend_subtitle')->after('living_firend_title');
            $table->text('living_share_title')->after('living_firend_subtitle');
            $table->string('living_share_picture', 250)->after('living_share_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course', function (Blueprint $table) {
            //
        });
    }
}
