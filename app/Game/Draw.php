<?php

namespace App\Game;

class Draw extends AbstractGame
{
    public function getTopCard()
    {
        return $this->game->draw->first();
    }

    public function removeTopCard()
    {
        $draw = $this->game->draw;
        $topCard = $draw->shift();
        $this->game->draw = $draw;
        return $topCard;
    }

    public function removeBottomCard()
    {
        $bottomCard = $this->game->draw->pop();
        return $bottomCard;
    }

    public function checkIfDrawEmpty()
    {
        //////
    }
}