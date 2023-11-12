@component('mail::message')

    {{__('message.sms_your_invite_has_accepted',['fullname' => $invite->invitedUser?->profile?->fullname])}}


    @component('mail::button', ['url' => route('games.page.index', ['game' => $game->id])])
        See
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
