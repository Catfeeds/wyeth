<?php

use Illuminate\Database\Migrations\Migration;

class AlterAdminAddCids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin', function ($table) {

            $table->string('cids', 200)->after('user_type')->default('');
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
