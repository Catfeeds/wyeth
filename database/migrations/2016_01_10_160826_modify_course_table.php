<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course', function ($table) {

                $table->string('notify_title', '255')->after('speak_chance');

                $table->string('notify_odate', '20')->after('notify_content');

                $table->string('notify_address', '255')->after('notify_odate');

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
