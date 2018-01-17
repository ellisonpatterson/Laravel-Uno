<?php

namespace App\Models\Game\Traits\Relationship;

use App\Models\Game\Game;
use App\Models\Game\GameUserHand;
use App\Models\Auth\User;

trait GameUserRelationship
{
    /**
     * @return mixed
     */
    public function hand()
    {
        return $this->belongsTo(GameUserHand::class, 'game_user_id', 'game_user_id');
    }

    /**
     * @return mixed
     */
    public function hands()
    {
        return $this->belongsToMany(GameUserHand::class, 'game_user_id', 'game_user_id');
    }

    /**
     * @return mixed
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }

    /**
     * @return mixed
     */
    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_id', 'game_id');
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_id', 'user_id');
    }
}