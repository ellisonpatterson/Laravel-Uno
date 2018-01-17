<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Game\Game;

class CreateGame extends FormRequest
{
    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        return [
            'title' => 'required|filled|string|min:3|max:191',
            'scope' => 'required|in:' . implode(',', Game::getEnum('scope')),
        ];
    }
}