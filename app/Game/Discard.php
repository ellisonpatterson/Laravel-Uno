<?php

namespace App\Game;

use App\Models\Game\Game;
use App\Models\Game\GameUser;
use App\Models\Card\Card;

class Discard extends AbstractGame
{
    public function getTopCard()
    {
        if ($this->game) {
            return $this->game->discard->first();
        }
    }

    public function addTopCard($cardId)
    {
        $card = $this->card->getCardById($cardId);
        $discard = $this->game->discard;
        $discard->prepend($card);

        $this->game->discard = $discard;
    }

    public function removeBottomCard()
    {
        $bottomCard = $this->game->discard->pop();
        $this->game->used = $this->game->used->prepend($bottomCard);
        return $bottomCard;
    }

    public function isCardValidOnTurn($cardId)
    {
        $topCard = $this->getTopCard();
        $card = $this->card->getCardById($cardId);

        if ($card->type == Card::WILD_TYPE || $card->type == Card::WILDDRAW4_TYPE) {
            return true;
        }

        if ($card->type == Card::SKIP_TYPE || $card->type == Card::REVERSE_TYPE || $card->type == Card::DRAW2_TYPE) {
            if ($card->type == $topCard->type || $card->color == $topCard->color) {
                return true;
            }
        }

        if ($card->type == Card::NORMAL_TYPE) {
            if ($card->color == $topCard->color) {
                return true;
            }

            if ($card->number == $topCard->number) {
                return true;
            }
        }

        return false;
    }

    public function anyCardsValidOnTurn($cardIds)
    {
        $validCards = collect();

        foreach ($cardIds as $cardId) {
            if ($validCard = $this->isCardValidOnTurn($cardId)) {
                $validCards->push($validCard);
            }
        }

        return $validCards;
    }
}