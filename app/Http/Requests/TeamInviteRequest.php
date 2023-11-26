<?php

namespace App\Http\Requests;

use App\Rules\CountSendAndReceivedFullTeam;
use App\Rules\NoRepeatClubTeam;
use App\Rules\TeamInviteGame;
use Illuminate\Foundation\Http\FormRequest;

class TeamInviteRequest extends FormRequest
{
    public function rules(): array
    {
        $team = $this->route('team');

        return [
            'teamId' => [
                'required',
                'int',
                new TeamInviteGame($team),
                new CountSendAndReceivedFullTeam($team?->id),
            ],
            'club' => [
                'required_if:in_club,true',
                'int',
                'exists:clubs,id',
                new NoRepeatClubTeam($team?->id, $this->input('teamId', 0)),
            ],
        ];
    }

    public function prepareForValidation(): void
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
