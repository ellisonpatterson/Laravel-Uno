<?php

namespace App\Models\Card;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Enums;
use GameEngine;

class Card extends Model
{
    use Enums;

    /**
     * Card type constants
     */
    const NORMAL_TYPE = 'normal';
    const SKIP_TYPE = 'skip';
    const REVERSE_TYPE = 'reverse';
    const DRAW2_TYPE = 'draw2';
    const WILD_TYPE = 'wild';
    const WILDDRAW4_TYPE = 'wilddraw4';

    /**
     * Card color constants
     */
    const RED_COLOR = 'red';
    const YELLOW_COLOR = 'yellow';
    const GREEN_COLOR = 'green';
    const BLUE_COLOR = 'blue';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'card';

    /**
     * The primary key of the database table.
     *
     * @var string
     */
    protected $primaryKey = 'card_id';

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     *
     * @var array
     */
    protected $appends = [
        'svg',
        'element',
        'hex_color'
    ];

    public function getSvgAttribute()
    {
        return GameEngine::create()->card->getCardSvg($this->type, $this->number);
    }

    public function getElementAttribute()
    {
        return '<div class="game-card" ' . ($this->hex_color ? 'style="fill: ' . $this->hex_color . ';"' : '') . '">' . $this->svg . '</div>';
    }

    public function getHexColorAttribute()
    {
        if (!empty($this->color)) {
            return $this->colorsHex[$this->color];
        }

        return false;
    }

    protected $colorsHex = [
        self::RED_COLOR => '#ff5555',
        self::YELLOW_COLOR => '#ffaa00',
        self::GREEN_COLOR => '#55aa55',
        self::BLUE_COLOR => '#5555ff'
    ];

    /**
     * Define all of the valid options in an array as a protected property that
     *
     * @var array
     */
    protected $enumTypes = [
        self::NORMAL_TYPE,
        self::SKIP_TYPE,
        self::REVERSE_TYPE,
        self::DRAW2_TYPE,
        self::WILD_TYPE,
        self::WILDDRAW4_TYPE
    ];

    /**
     * Define all of the valid options in an array as a protected property that
     *
     * @var array
     */
    protected $enumColors = [
        self::RED_COLOR,
        self::YELLOW_COLOR,
        self::GREEN_COLOR,
        self::BLUE_COLOR
    ];
}