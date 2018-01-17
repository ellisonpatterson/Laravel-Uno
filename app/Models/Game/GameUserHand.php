<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;
use App\Models\Game\Traits\Relationship\GameUserHandRelationship;
use Illuminate\Support\Collection;
use GameEngine;

class GameUserHand extends Model
{
    use GameUserHandRelationship;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_user_hand';

    /**
     * The primary key of the database table.
     *
     * @var string
     */
    protected $primaryKey = 'game_user_hand_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_user_id',
        'cards',
        'updated_at'
    ];

    /**
     * Whether or not to store timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Gets the cards associated with the card data.
     *
     * @param  string $value
     * @return array
     */
    public function getCardsAttribute($value)
    {
        if ($value) {
            $value = GameEngine::create()->card->getCardsByIds(json_decode($value));
        }

        return collect($value);
    }

    /**
     * Encodes the cards to JSON.
     *
     * @param array $value
     * @return void
     */
    public function setCardsAttribute($value)
    {
        if ($value instanceof Collection) {
            $hasCardIds = $value->keyBy('card_id');
            if ($hasCardIds->count() > 1) {
                $value = $hasCardIds->keys();
            }
        }

        $this->attributes['cards'] = collect($value)->values()->toJson();
    }

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     *
     * @var array
     */
    protected $appends = [
        'can_uno',
        'card_count'
    ];

    public function getCanUnoAttribute()
    {
        return $this->card_count == 1 ? true : false;
    }

    public function getCardCountAttribute()
    {
        return $this->cards->count();
    }

    public function save(array $options = [])
    {
        if (!empty($this->gameUserHand)) {
            $this->gameUserHand->save();
        }

        return parent::save($options);
    }
}