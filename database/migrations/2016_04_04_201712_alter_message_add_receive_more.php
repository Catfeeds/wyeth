<?php

use Illuminate\Database\Migrations\Migration;

class AlterMessageAddReceiveMore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('message', function ($table) {

            $table->tinyInteger('receive_more')->after('state')->default(0);
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
    }
}
