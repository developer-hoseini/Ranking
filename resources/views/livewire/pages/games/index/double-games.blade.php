<div id="double_games"
     wire:transition.in.opacity.duration.500ms
     wire:transition.out.opacity.duration.500ms
>
    @foreach ($this->games as $game)
        <div class="card games_box">
            <div class="card-header game_header text-center">
                <a href="{{ route('games.show', $game->id) }}">
                    {{ $game->name }}
                </a>
            </div>
            <div class="card-body games-body">

                <ul class="list-group list-group-flush">

                    <li class="list-group-item txt-size-12-bold">
                        {{ __('words.Score: ').$game->user_score }}
                    </li>
                    <li class="list-group-item txt-size-12-bold">
                        {{ __('words.Rank: ').$game->user_rank }}
                    </li>
                    <li class="list-group-item txt-size-12-bold">
                        {{ __('words.Members: ') . $game?->competitions_count }}
                    </li>
                    <li class="list-group-item txt-size-12-bold">
                        {{ __('words.Played: ') . $game?->users_count }}
                    </li>
                </ul>


                @if($game->game_join_user_achievements_count)

                    <a href="{{ route('games.page.index',['game'=>$game->id]) }}"
                       class="btn bg-success btn-block text-white">
                        {{ __('words.Enter') }}
                    </a>
                    <a
                        href="{{ route('games.join.status', ['game' => $game->id ,'type'=>0]) }}"
                        class="btn bg-danger btn-block text-white">{{ __('words.Leave') }}
                    </a>
                @else
                    <a
                        class="btn btn-primary btn-block text-white"
                        style="margin-top: 23px; cursor: pointer;"
                        href="{{ route('games.join.status', ['game' => $game->id ,'type'=>true]) }}"
                    >{{ __('words.Join') }}</a>
                @endif

            </div>
        </div>
    @endforeach
</div>
