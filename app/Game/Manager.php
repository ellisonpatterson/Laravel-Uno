<?php

namespace App\Game;

use App\Models\Game\Game;
use App\Models\Game\GameUser;
use App\Models\Game\GameUserHand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\GeneralException;

class Manager extends AbstractGame
{
    public function createGame($title, $scope, $password = null)
    {
        $activeCreatedGame = Game::where([
            ['status', '!=', Game::COMPLETE_STATUS],
            ['status', '!=', Game::ENDED_STATUS]
        ])->with(['gameUser' => function($query) {
            return $query->where([
                ['user_id', '=', Auth::user()->user_id],
                ['position', '=', GameUser::CREATOR_POSITION]
            ]);
        }])->first();

        if ($activeCreatedGame) {
            throw new GeneralException('You cannot create another game until your current one has completed.');
        }

        $this->game = new Game([
            'title' => $title,
            'scope' => $scope,
            'password' => ($password !== null ? bcrypt($password) : null)
        ]);
        $this->game->save();

        $this->game->gameUsers()->save(new GameUser([
            'game_id' => $this->game->game_id,
            'user_id' => Auth::user()->user_id,
            'position' => GameUser::CREATOR_POSITION
        ]));
    }

    public function startGame()
    {
        $cards = $this->cards->shuffle()->keyBy('card_id')->keys()->chunk(10);
        $this->game->gameUsers->each(function($gameUser) use($cards) {
            $userCards = $cards->pop();

            $hand = new GameUserHand([
                'game_user_id' => $gameUser->game_user_id,
                'cards' => $userCards->shuffle()->values()
            ]);

            $gameUser->hand()->associate($hand);
            $hand->save();
        });

        $cards = $cards->collapse();
        $firstCard = $cards->pop();

        $this->game->active_game_user_id = $this->game->gameUsers()->get()->keyBy('game_user_id')->keys()->first();
        $this->game->discard = $firstCard;
        $this->game->draw = $cards;
    }

    public function completeGame()
    {
        $this->game->active_game_user_id = null;
        $this->game->winner_game_user_id = $this->gameUser->game_user_id;
        $this->game->status = Game::COMPLETE_STATUS;
    }

    public function endGame()
    {
        $this->game->active_game_user_id = null;
        $this->game->status = Game::ENDED_STATUS;
    }

    public function isTurn()
    {
        return ($this->game->active_game_user_id == $this->gameUser->game_user_id);
    }

    public function isEndingTurn()
    {
        if ($this->hand->isHandEmpty()) {
            return true;
        }

        return false;
    }

    public function gotoNextUser()
    {
        $this->game->active_game_user_id = $this->getNextUser()->game_user_id;
    }

    public function getNextUser()
    {
        $gameUsers = $this->game->gameUsers()->where('forfeited', 0)->orderBy('order', 'asc')->get();
        if ($this->game->direction == Game::ASC_DIRECTION) {
            return $gameUsers->next('game_user_id', $this->game->active_game_user_id);
        } else {
            return $gameUsers->previous('game_user_id', $this->game->active_game_user_id);
        }
    }

    public function getOrder()
    {
        $this->game->direction = ($this->game->direction == Game::ASC_DIRECTION ? Game::DESC_DIRECTION : Game::ASC_DIRECTION);
    }

    public function reverseGame()
    {
        $this->game->direction = ($this->game->direction == Game::ASC_DIRECTION ? Game::DESC_DIRECTION : Game::ASC_DIRECTION);
    }

    public function getStatusMessage()
    {
        if ($this->game) {
            if ($this->game->status == Game::PENDING_STATUS) {
                return 'The game is currently pending.';
            }

            if ($this->game->status == Game::ENDED_STATUS) {
                if ($forfeitedCount = $this->game->gameUsers->where('forfeited', 1)->count()) {
                    return 'The game was ended due to ' . $forfeitedCount . ' player(s) forfeiting.';
                }

                return 'The game was closed by the creator ' . $this->game->gameUsers->where('position', GameUser::CREATOR_POSITION)->first()->user->username . '.';
            }

            if ($this->game->status == Game::COMPLETE_STATUS) {
                return $this->game->gameUsers->where('game_user_id', $this->game->winner_game_user_id)->first()->user->username . ' has won the game!';
            }

            if ($this->game->status == Game::ACTIVE_STATUS) {
                if ($this->game->active_game_user_id == $this->game->gameUsers->where('user_id', Auth::user()->user_id)->first()->game_user_id) {
                    return 'It is currently your turn.';
                } else {
                    return $this->game->gameUsers->where('game_user_id', $this->game->active_game_user_id)->first()->user->username . ' is currently playing their turn.';
                }
            }
        }

        return 'There is currently no status.';
    }

    public function updateStatus($action)
    {
        if (!$this->gameUser || $this->gameUser->foreited) {
            throw new GeneralException('You do not have access to modifying this game.');
        }

        if ($this->game->status == $action) {
            throw new GeneralException('The game is already set to have a status of ' . $action . '.');
        }

        if (!$this->game->can_modify) {
            throw new GeneralException('The game can no longer be modified.');
        }

        if (in_array($action, Game::getEnum('status'))) {
            if ($action == Game::ACTIVE_STATUS && !$this->game->has_enough_players) {
                throw new GeneralException('There are not enough players to start the game.');
            }

            if (!$this->gameUser->can_modify) {
                throw new GeneralException('Only the game creator can change the game status.');
            }

            $this->game->status = $action;

            if ($action == Game::ACTIVE_STATUS) {
                $this->startGame();
            }
        }

        if ($action == 'forfeit') {
            if ($this->gameUser->foreited) {
                throw new GeneralException('The game creator cannot forfeit their own game.');
            }

            $this->gameUser->forfeited = true;

            if ($this->game->valid_player_count - 1 < Game::MINIMUM_PLAYERS) {
                $this->endGame();
            } elseif ($this->isTurn()) {
                $this->gotoNextUser();
            }
        }

        if ($action == 'leave') {
            if ($this->game->status != Game::PENDING_STATUS) {
                throw new GeneralException('You can only leave a pending game.');
            }

            $this->gameUser->destroy($this->gameUser->game_user_id);
        }
    }
}