<?php

namespace App\Models\Auth\Traits\Relationship;

use App\Models\Chat\Message;

trait UserRelationship
{
    /**
     * @return mixed
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id', 'user_id');
    }
}