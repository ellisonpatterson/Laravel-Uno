<?php

namespace App\Game;

use App\Models\Game\GameUserHand;

class Hand extends AbstractGame
{
    public function checkIfCardInHand($cardId)
    {
        if ($this->gameUser->hand->cards->get($cardId)) {
            return true;
        }

        return false;
    }

    public function isHandEmpty()
    {
        if ($this->gameUser->hand->cards->count()) {
            return false;
        }

        return true;
    }

    public function addCardToHand($cardId, $gameUser = null)
    {
        if ($gameUser === null) {
            $gameUser = $this->gameUser;
        }

        $cards = $gameUser->hand->cards;
        $cards->prepend($this->card->getCardById($cardId));
        $gameUser->hand->cards = $cards;
    }

    public function addCardsToHand($count, $gameUser = null)
    {
        if ($gameUser === null) {
            $gameUser = $this->gameUser;
        }

        for ($i = 0; $i < $count; $i++) {
            $this->addCardToHand($this->draw->removeTopCard(), $gameUser);
        }

        $gameUser->hand->save();
    }

    public function removeCardFromHand($cardId)
    {
        $this->gameUser->hand->cards = $this->gameUser->hand->cards->keys()->filter(function($card) use($cardId) {
            return $card != $cardId;
        });
    }
}