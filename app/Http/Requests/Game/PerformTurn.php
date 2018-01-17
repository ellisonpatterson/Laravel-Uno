<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;
use GameEngine;

class PerformTurn extends FormRequest
{
    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        return [
            'card_id' => 'required|in:' . GameEngine::cards()->implode('card_id', ','),
        ];
    }
}