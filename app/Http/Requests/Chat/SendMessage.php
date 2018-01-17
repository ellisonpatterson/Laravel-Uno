<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SendMessage extends FormRequest
{
    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        return [
            'game_id' => 'nullable|integer',
            'message' => 'required|filled|string|min:3|max:191',
        ];
    }
}