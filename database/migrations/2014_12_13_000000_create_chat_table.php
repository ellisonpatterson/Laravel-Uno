<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_message', function (Blueprint $table) {
            $table->increments('chat_message_id');
            $table->integer('game_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->text('message');
            $table->timestamps();

            $table->foreign('game_id')
                ->references('game_id')
                ->on('game');
            $table->foreign('user_id')
                ->references('user_id')
                ->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_message');
    }
}
