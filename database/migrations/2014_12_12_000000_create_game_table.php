<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Game\Game;
use App\Models\Game\GameUser;
use Illuminate\Support\Facades\DB;

class CreateGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('game', function (Blueprint $table) {
            $table->increments('game_id');
            $table->text('title');
            $table->string('scope')->default(Game::PUBLIC_SCOPE);
            $table->string('status')->default(Game::PENDING_STATUS);
            $table->string('direction')->default(Game::ASC_DIRECTION);
            $table->integer('active_game_user_id')->unsigned()->nullable();
            $table->integer('winner_game_user_id')->unsigned()->nullable();
            $table->binary('draw')->nullable();
            $table->binary('discard')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();

            $table->foreign('active_game_user_id')
                ->references('game_user_id')
                ->on('game_user');

            $table->foreign('winner_game_user_id')
                ->references('game_user_id')
                ->on('game_user');
        });

        // DB::statement('ALTER TABLE game ADD deck LONGBLOB DEFAULT NULL');
        // DB::statement('ALTER TABLE game ADD used LONGBLOB DEFAULT NULL');

        Schema::create('game_user', function (Blueprint $table) {
            $table->increments('game_user_id');
            $table->integer('game_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('order')->unsigned()->default(10);
            $table->string('position')->default(GameUser::PLAYER_POSITION);
            $table->boolean('forfeited')->default(0);

            $table->foreign('game_id')
                ->references('game_id')
                ->on('game')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('user_id')
                ->on('user');

            $table->unique(['game_id', 'user_id']);
        });

        Schema::create('game_user_hand', function (Blueprint $table) {
            $table->increments('game_user_hand_id');
            $table->integer('game_user_id')->unsigned();
            $table->binary('cards');
            $table->timestamp('updated_at');

            $table->foreign('game_user_id')
                ->references('game_user_id')
                ->on('game_user')
                ->onDelete('cascade');

            $table->unique('game_user_id');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('game_user_hand');
        Schema::dropIfExists('game_user');
        Schema::dropIfExists('game');

        Schema::enableForeignKeyConstraints();
    }
}
