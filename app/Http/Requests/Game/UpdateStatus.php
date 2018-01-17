<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Game\Game;

class UpdateStatus extends FormRequest
{
    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        return [
            'action' => 'required|in:' . implode(',', array_merge(Game::getEnum('status'), ['forfeit', 'leave'])),
        ];
    }
}