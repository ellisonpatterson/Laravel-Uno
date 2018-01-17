<?php

namespace App\Game;

use App\Events\Game\DashboardUpdate;
use App\Events\Game\GameUpdate;
use App\Models\Game\GameUser;
use Illuminate\Support\Facades\Auth;

class Events extends AbstractGame
{
    public function dashboardUpdate()
    {
        broadcast(new DashboardUpdate($this->games->fetchAvailableGames(), Auth::user()))->toOthers();

        return $this;
    }

    public function gameUpdate()
    {
        $game = $this->game; $gameUser = $this->gameUser;
        $this->game->gameUsers->each(function($eachGameUser) use($game, $gameUser) {
            if ($eachGameUser->game_user_id != $gameUser->game_user_id) {
                broadcast(new GameUpdate($game->toArray(), $eachGameUser->toArray()));
            }
        });

        return $this;
    }
}