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
                        {{ __('words.Members: ') . $game->users_count }}</li>
                    <li class="list-group-item txt-size-12-bold">{{ __('words.Played: ') . $game->competitions_count }}
                    </li>
                </ul>

                <a
                    class="btn btn-primary btn-block text-white"
                    href="{{ route('teams.me.create', ['game_id' => $game?->id]) }}"
                    style="margin-top: 23px;"
                >{{ __('words.Create Team') }}</a>

            </div>
        </div>
    @endforeach
</div>
