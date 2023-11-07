<div
    wire:transition.in.opacity.duration.500ms
    wire:transition.out.opacity.duration.500ms
>
    @foreach ($this->games as $game)
        <div class="card games_box select_games_box">
            <div class="card-header game_header text-truncate text-center">
                <a href="{{ route('game.show', $game->id) }}">
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

                {{-- TODO: add route --}}
                <a
                    class="btn btn-primary btn-block text-white"
                    style="margin-top: 23px;"
                    {{-- href="{{ route('create_team') }}" --}}
                >{{ __('words.Create Team') }}</a>

            </div>
        </div>
    @endforeach
</div>
