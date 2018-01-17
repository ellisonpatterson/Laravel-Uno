<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;
use App\Models\Game\Traits\Relationship\GameUserRelationship;
use App\Traits\Enums;

class GameUser extends Model
{
    use GameUserRelationship,
        Enums;

    /**
     * User position constants
     */
    const CREATOR_POSITION = 'creator';
    const PLAYER_POSITION = 'player';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_user';

    /**
     * The primary key of the database table.
     *
     * @var string
     */
    protected $primaryKey = 'game_user_id';

    /**
     * Default relations to eagerly load.
     *
     * @var array
     */
    protected $with = [
        'user'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'game_id',
        'order',
        'position',
        'forfeited'
    ];

    /**
     * Whether or not to store timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     *
     * @var array
     */
    protected $appends = [
        'can_modify'
    ];

    public function getCanModifyAttribute()
    {
        if ($this->position == self::CREATOR_POSITION) {
            return true;
        }

        return false;
    }

    /**
     * Define all of the valid options in an array as a protected property that
     *
     * @var array
     */
    protected $enumPositions = [
        self::CREATOR_POSITION,
        self::PLAYER_POSITION
    ];

    public function save(array $options = [])
    {
        if (!empty($this->hand)) {
            $this->hand->save();
        }

        return parent::save($options);
    }
}