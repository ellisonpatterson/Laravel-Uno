<?php

namespace App\Game;

use App\Models\Card\Card as CardModel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Collection;

class Card extends AbstractGame
{
    protected $cardSvgBack;
    protected $cardSvgMap;
    protected $cardSvgCache;
    protected $fileSystem;

    public function initialize()
    {
        $this->cardSvgMap = $this->getCardSvgMap();
        $this->fileSystem = new Filesystem;
    }

    public function getCardSvgDirectory($svg = null)
    {
        return resource_path() . '/assets/cards' . ($svg !== null ? '/' . $svg . '.svg' : '');
    }

    public function getCardSvgMap()
    {
        $map = [
            CardModel::SKIP_TYPE => $this->getCardSvgDirectory(CardModel::SKIP_TYPE),
            CardModel::REVERSE_TYPE => $this->getCardSvgDirectory(CardModel::REVERSE_TYPE),
            CardModel::DRAW2_TYPE => $this->getCardSvgDirectory(CardModel::DRAW2_TYPE),
            CardModel::WILD_TYPE => $this->getCardSvgDirectory(CardModel::WILD_TYPE),
            CardModel::WILDDRAW4_TYPE => $this->getCardSvgDirectory(CardModel::WILDDRAW4_TYPE),
        ];

        for ($number = 0; $number <= 9; $number++) {
            $map[CardModel::NORMAL_TYPE][$number] = $this->getCardSvgDirectory($number);
        }

        return $map;
    }

    public function performCardTypeAction($cardId)
    {
        $card = $this->card->getCardById($cardId);
        if ($card->type == CardModel::REVERSE_TYPE) {
            $this->manager->reverseGame();
        }

        if ($card->type == CardModel::SKIP_TYPE) {
            $this->game->active_game_user_id = $this->manager->getNextUser()->game_user_id;
        }

        if ($card->type == CardModel::DRAW2_TYPE) {
            $this->hand->addCardsToHand(2, $this->manager->getNextUser());
        }
    }

    public function getCardById($cardId)
    {
        if ($cardId instanceof Collection || $cardId instanceof CardModel) {
            return $cardId;
        }

        return $this->cards->where('card_id', $cardId)->first();
    }

    public function getCardsByIds($cardIds)
    {
        $cards = [];
        foreach ($cardIds as $cardId) {
            $cards[] = $this->getCardById($cardId);
        }

        return collect($cards)->keyBy('card_id');
    }

    public function getCardSvgUri($cardType, $cardNumber = null)
    {
        if (!empty($this->cardSvgMap[$cardType])) {
            $map = $this->cardSvgMap[$cardType];
            if ($cardNumber !== null && is_array($map)) {
                return $map[$cardNumber];
            }

            return $map;
        }

        return false;
    }

    public function getCardBackSvg()
    {
        if ($this->cardSvgBack === null) {
            $this->cardSvgBack = new HtmlString($this->fileSystem->get($this->getCardSvgDirectory('back')));
        }

        return $this->cardSvgBack;
    }

    public function getCardSvg($cardType, $cardNumber = null)
    {
        $cacheId = $cardType . ($cardNumber !== null ? $cardNumber : '');
        if (!empty($this->cardSvgCache[$cacheId])) {
            return $this->cardSvgCache[$cacheId];
        }

        if ($cardSvgUri = $this->getCardSvgUri($cardType, $cardNumber)) {
            return new HtmlString($this->cardSvgCache[$cacheId] = $this->fileSystem->get($cardSvgUri));
        }

        return false;
    }
}