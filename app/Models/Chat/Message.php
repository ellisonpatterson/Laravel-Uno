<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use App\Models\Chat\Traits\Relationship\MessageRelationship;

class Message extends Model
{
    use MessageRelationship;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chat_message';

    /**
     * The primary key of the database table.
     *
     * @var string
     */
    protected $primaryKey = 'chat_message_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id',
        'user_id',
        'message'
    ];
}