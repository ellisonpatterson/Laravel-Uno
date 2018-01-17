<?php

namespace App\Game;

use App\Models\Game\Game;
use App\Models\Game\GameUser;
use Illuminate\Support\Facades\Auth;

class Games extends AbstractGame
{
    public function fetchAvailableGames()
    {
        return Game::where([
            ['status', '=', Game::PENDING_STATUS],
            ['scope', '=', Game::PUBLIC_SCOPE]
        ])
        ->with(['gameUsers' => function($query) {
            return $query->with('user');
        }])->get();
    }

    public function fetchParticipatingGames()
    {
        if (!Auth::check()) {
            return false;
        }

        return Game::where([
            ['status', '!=', Game::COMPLETE_STATUS],
            ['status', '!=', Game::ENDED_STATUS]
        ])->whereHas('gameUsers', function($query) {
            return $query->where([
                ['user_id', '=', Auth::user()->user_id],
            ])->with('user');
        })->get();
    }

    public function fetchIfHasActiveOwnedGames()
    {
        if (!Auth::check()) {
            return false;
        }

        return Game::where([
            ['status', '!=', Game::COMPLETE_STATUS],
            ['status', '!=', Game::ENDED_STATUS]
        ])->whereHas('gameUsers', function($query) {
            return $query->where([
                ['user_id', '=', Auth::user()->user_id],
                ['position', '=', GameUser::CREATOR_POSITION]
            ])->with('user');
        })->first();
    }

    public function fetchPreviousGames()
    {
        if (!Auth::check()) {
            return false;
        }

        return Game::where('status', '=', Game::ENDED_STATUS)
        ->orWhere('status', '=', Game::COMPLETE_STATUS)
        ->with(['gameUsers' => function($query) {
            return $query->with('user');
        }])->get();
    }
}