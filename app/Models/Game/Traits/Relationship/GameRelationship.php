<?php

namespace App\Models\Game\Traits\Relationship;

use App\Models\Chat\Room;
use App\Models\Game\GameUser;

trait GameRelationship
{
    /**
     * @return mixed
     */
   public function gameUser() 
   {
      return $this->hasOne(GameUser::class, 'game_id');
   }

    /**
     * @return mixed
     */
    public function gameUsers()
    {
        return $this->hasMany(GameUser::class, 'game_id');
    }
}