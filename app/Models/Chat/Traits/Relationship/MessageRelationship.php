<?php

namespace App\Models\Chat\Traits\Relationship;

use App\Models\Game\Game;
use App\Models\Auth\User;

trait MessageRelationship
{
    /**
     * @return mixed
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}