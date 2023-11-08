<div
    wire:transition.in.opacity.duration.500ms
    wire:transition.out.opacity.duration.500ms
>
    @foreach ($this->games as $game)
        <div class="card games_box select_games_box">
            <div class="card-header game_header text-truncate text-center">
                <a href="{{ route('games.show', $game->id) }}">
                    {{ $game->name }}
                </a>
            </div>
            <div class="card-body games-body">

                <ul class="list-group list-group-flush">
                    <li class="list-group-item txt-size-12-bold">
                        {{ __('words.Members: ') . $game?->competitions_count }}
                    </li>
                    <li class="list-group-item txt-size-12-bold">
                        {{ __('words.Played: ') . $game?->users_count }}
                    </li>
                </ul>

                {{-- TODO: add route --}}
                @php
                    // $routeHref = auth() ? route('')
                @endphp

                <a
                    class="btn btn-primary btn-block text-white"
                    style="margin-top: 23px; cursor: pointer;"
                    {{-- href="{{ route('select_game_login', ['game_id' => $game->id]) }}" --}}
                >{{ __('words.Join') }}</a>

            </div>
        </div>
    @endforeach
</div>
