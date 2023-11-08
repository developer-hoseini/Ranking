<div
    wire:transition.in.opacity.duration.500ms
    wire:transition.out.opacity.duration.500ms
>
    <div class="online_games_container text-center">
        <h1 class="mt-3">{{ __('words.online_games') }}</h1>

        <h2 class="gtype_header mt-5 pr-4">{{ __('words.double_games') }}</h2>
        <hr>
        <div class="d-flex online_games_box mx-auto flex-wrap">
            @foreach ($this->games['two-player'] as $game)
                <x-games.online-game-card
                    href="{{ route('games.show.online', $game->id) }}"
                    :game="$game"
                    view="{{ $game?->onlineGames?->first()?->viewed }}"
                />
            @endforeach
        </div>

        <h2 class="gtype_header mt-5 pr-4">{{ __('words.multiplayer_games') }}</h2>
        <hr>
        <div class="d-flex online_games_box mx-auto flex-wrap">
            @foreach ($this->games['multiplayer'] as $game)
                <x-games.online-game-card
                    href="{{ route('games.show.online', $game->id) }}"
                    :game="$game"
                    view="{{ $game?->onlineGames?->first()?->viewed }}"
                />
            @endforeach
        </div>

        <h2 class="gtype_header mt-5 pr-4">{{ __('words.single_games') }}</h2>
        <hr>
        <div class="d-flex online_games_box mx-auto flex-wrap">
            @foreach ($this->games['one-player'] as $game)
                <x-games.online-game-card
                    href="{{ route('games.show.online', $game->id) }}"
                    :game="$game"
                    view="{{ $game?->onlineGames?->first()?->viewed }}"
                />
            @endforeach
        </div>

    </div>

</div>
