<?php

namespace App\Http\Requests;

use App\Rules\CountSendAndReceivedFull;
use App\Rules\NoRepeatClub;
use App\Rules\UserInviteGame;
use Illuminate\Foundation\Http\FormRequest;

class GamePageInviteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'userId' => [
                'required',
                'int',
                new UserInviteGame($this->route('game')?->id),
                new CountSendAndReceivedFull($this->route('game')?->id),
            ],
            'club' => [
                'required_if:in_club,true',
                'int',
                'exists:clubs,id',
                new NoRepeatClub($this->route('game')?->id, $this->input('userId', 0)),
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            ...['in_club' => $this->has('in_club') && $this->input('in_club') === 'on'],
            ...['with_image' => $this->has('with_image') && $this->input('with_image') === 'on'],
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
