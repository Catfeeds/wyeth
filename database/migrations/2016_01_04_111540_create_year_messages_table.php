<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_messages', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('uid')->unsigned();
                $table->string('content', 500);
                $table->timestamps();
                $table->index(['uid']);
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
