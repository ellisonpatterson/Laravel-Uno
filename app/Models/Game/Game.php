<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;
use App\Models\Game\Traits\Relationship\GameRelationship;
use Illuminate\Support\Collection;
use App\Traits\Enums;
use GameEngine;

class Game extends Model
{
    use GameRelationship,
        Enums;

    const MINIMUM_PLAYERS = 2;

    /**
     * Game scope constants
     */
    const PUBLIC_SCOPE = 'public';

    /**
     * Game status constants
     */
    const PENDING_STATUS = 'pending';
    const ACTIVE_STATUS = 'active';
    const ENDED_STATUS = 'ended';
    const COMPLETE_STATUS = 'complete';

    /**
     * Game direction constants
     */
    const ASC_DIRECTION = 'asc';
    const DESC_DIRECTION = 'desc';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game';

    /**
     * The primary key of the database table.
     *
     * @var string
     */
    protected $primaryKey = 'game_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'scope',
        'status',
        'password',
        'draw',
        'discard'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'draw',
        'discard'
    ];

    /**
     * Gets the current draw and associated cards.
     *
     * @param  string $value
     * @return array
     */
    public function getDrawAttribute($value)
    {
        if ($value) {
            $value = GameEngine::create()->card->getCardsByIds(json_decode($value));
        }

        return collect($value);
    }

    /**
     * Encodes the draw to JSON.
     *
     * @param array $value
     * @return void
     */
    public function setDrawAttribute($value)
    {
        if ($value instanceof Collection) {
            $hasCardIds = $value->keyBy('card_id');
            if ($hasCardIds->count() > 1) {
                $value = $hasCardIds->keys();
            }
        }

        $this->attributes['draw'] = collect($value)->shuffle()->values()->toJson();
    }

    /**
     * Gets the discard and associated cards.
     *
     * @param  string $value
     * @return array
     */
    public function getDiscardAttribute($value)
    {
        if ($value) {
            $value = GameEngine::create()->card->getCardsByIds(json_decode($value));
        }

        return collect($value);
    }

    /**
     * Encodes the discard to JSON.
     *
     * @param array $value
     * @return void
     */
    public function setDiscardAttribute($value)
    {
        if ($value instanceof Collection) {
            $hasCardIds = $value->keyBy('card_id');
            if ($hasCardIds->count() > 1) {
                $value = $hasCardIds->keys();
            }
        }

        $this->attributes['discard'] = collect($value)->values()->toJson();
    }

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     *
     * @var array
     */
    protected $appends = [
        'color',
        'has_enough_players',
        'valid_player_count',
        'top_card',
        'draw_count',
        'discard_count',
        'can_modify',
        'status_message'
    ];

    public function getColorAttribute()
    {
        return '#' . substr(md5($this->title . $this->game_id . $this->chat_room_id), 0, 6);
    }

    public function getHasEnoughPlayersAttribute()
    {
        if ($this->valid_player_count >= self::MINIMUM_PLAYERS) {
            return true;
        }

        return false;
    }

    public function getValidPlayerCountAttribute()
    {
        return $this->gameUsers->where('forfeited', 0)->count();
    }

    public function getTopCardAttribute()
    {
        if ($this->discard->count()) {
            return GameEngine::discard()->getTopCard();
        }
    }

    public function getDrawCountAttribute()
    {
        if ($this->draw->count()) {
            return $this->draw->count();
        }
    }

    public function getDiscardCountAttribute()
    {
        if ($this->discard->count()) {
            return $this->discard->count();
        }
    }

    public function getCanModifyAttribute()
    {
        if ($this->status == self::ENDED_STATUS || $this->status == self::COMPLETE_STATUS) {
            return false;
        }

        return true;
    }

    public function getStatusMessageAttribute()
    {
        return GameEngine::manager()->getStatusMessage();
    }

    /**
     * Define all of the valid options in an array as a protected property that
     *
     * @var array
     */
    protected $enumScopes = [
        self::PUBLIC_SCOPE,
    ];

    /**
     * Define all of the valid options in an array as a protected property that
     *
     * @var array
     */
    protected $enumStatuses = [
        self::PENDING_STATUS,
        self::ACTIVE_STATUS,
        self::ENDED_STATUS,
        self::COMPLETE_STATUS
    ];

    /**
     * Define all of the valid options in an array as a protected property that
     *
     * @var array
     */
    protected $enumDirections = [
        self::ASC_DIRECTION,
        self::DESC_DIRECTION
    ];
}