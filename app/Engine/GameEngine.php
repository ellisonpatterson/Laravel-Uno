<?php

namespace App\Engine;

use App\Models\Card\Card;
use App\Models\Game\Game;
use App\Models\Game\GameUser;
use Illuminate\Support\Facades\Auth;

class GameEngine
{
    protected $game;
    protected $gameUser;
    protected $cards;
    protected $components;

    public function __construct()
    {
        $this->cards = Card::all();
    }

    public function create($gameId = null)
    {
        if ($gameId) {
            $this->game = Game::where('game_id', $gameId)
            ->with(['gameUsers' => function($query) {
                return $query->with(['user', 'hand']);
            }])->firstOrFail();

            $this->findGameUser();
        }

        return $this;
    }

    public function findGameUser()
    {
        if (Auth::check()) {
            $this->gameUser = $this->game->gameUsers->where('user_id', Auth::user()->user_id)->first();
        }
    }

    public function refreshRelations()
    {
        if ($this->game) {
            $this->game->load(['gameUsers' => function($query) {
                return $query->with(['user', 'hand']);
            }]);
        }

        if ($this->gameUser) {
            $this->gameUser->load('user', 'hand');
        }
    }

    public function hideUserCards()
    {
        if ($this->game) {
            $gameUser = $this->gameUser;
            $this->game->gameUsers->each(function($item) use($gameUser) {
                if ($item->hand) {
                    if (!$gameUser || $item->game_user_id == $gameUser->game_user_id) {
                        $item->hand->makeHidden('cards');
                    }
                }

                return $item;
            });
        }
    }

    public function finalize()
    {
        if ($this->game) {
            $this->game->save();
        }

        if ($this->gameUser) {
            $this->gameUser->save();
        }

        $this->refreshRelations();
        $this->hideUserCards();
    }

    public function callComponent($component)
    {
        if (!empty($this->components[$component])) {
            return $this->components[$component];
        }

        $componentIdentity = 'App\Game\\' . ucfirst($component);
        return $this->components[$component] = (new $componentIdentity($this));
    }

    public function __call($name, $arguments)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if ($component = $this->callComponent($name)) {
            return $component;
        }
    }

    public function &__get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        if ($component = $this->callComponent($property)) {
            return $component;
        }

        return $this->$property;
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}