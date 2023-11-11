@component('mail::message')
    # Introduction
    {{__('message.sms_you_have_new_invite',['fullname' => $invite->invitedUser?->profile?->fullname,'game' => $game->name,])}}

    @component('mail::button', ['url' => route('games.page.index', ['game' => $game->id])])
        See
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
