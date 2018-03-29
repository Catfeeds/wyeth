<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessageAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('msg_id')->unsigned();
            $table->integer('yjt_qid')->unsigned();
            $table->string('answer_url');
            $table->timestamps();
            $table->index(['msg_id', 'yjt_qid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('message_answers');
    }
}
