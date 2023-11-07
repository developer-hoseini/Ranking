<div class="center_box">

    <div class="online_games pt-3 text-center">
        <img
            src="{{ asset('assets/img/family-playing-games.png') }}"
            width="250"
        >
    </div>

    <hr>

    <div
        class="btn-group btn-group-toggle change-games-part change_game_type mb-2"
        data-toggle="buttons"
    >
        <button
            class="btn btn-secondary btn-won btn-double-games {{ $gameType == 'double' ? 'active' : '' }} mb-1 mr-1 mt-2"
            style="cursor: pointer"
            wire:click="$set('gameType','double')"
            @if ($gameType == 'double') disabled @endif
        >
            {{ __('words.Double_Games') }}
        </button>
        <button
            class="btn btn-secondary btn-team-games btn-won {{ $gameType == 'team' ? 'active' : '' }} mb-1 mr-1 mt-2"
            style="cursor: pointer"
            wire:click="$set('gameType','team')"
            @if ($gameType == 'team') disabled @endif
        >
            {{ __('words.Team Games') }}
        </button>
        <button
            class="btn btn-secondary btn-online-games btn-won {{ $gameType == 'online' ? 'active' : '' }} mb-1 mr-1 mt-2"
            style="cursor: pointer"
            wire:click="$set('gameType','online')"
            @if ($gameType == 'online') disabled @endif
        >
            {{ __('words.online_games') }}
        </button>
    </div>

    @if ($gameType == 'double')
        <livewire:pages.games.index.double-games wire:key='double' />
    @endif

    @if ($gameType == 'team')
        <livewire:pages.games.index.team-games wire:key='team' />
    @endif

    @if ($gameType == 'online')
        <livewire:pages.games.index.online-games wire:key='online' />
    @endif
</div>
