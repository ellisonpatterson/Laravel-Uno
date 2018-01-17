<?php

namespace App\Models\Game\Traits\Relationship;

use App\Models\Game\GameUser;

trait GameUserHandRelationship
{
    /**
     * @return mixed
     */
    public function gameUser()
    {
        return $this->belongsTo(GameUser::class, 'game_user_id', 'game_user_id');
    }

    /**
     * @return mixed
     */
    public function gameUsers()
    {
        return $this->belongsToMany(GameUser::class, 'game_user_id', 'game_user_id');
    }
}