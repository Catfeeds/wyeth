<?php

use Illuminate\Database\Migrations\Migration;

class AlterQrcodesAddStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qrcodes', function ($table) {

            $table->smallInteger('stage')->after('imgurl')->default(1);
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
