<div class="d-flex container flex-wrap py-1">
    <div
        class="tour-bracket-box mt-3 px-2 text-center"
        style="width: 60%;"
    >
        <div class="text-center">
            <h2
                class="pt-3"
                style="font-size: 23px !important;"
            >
                <img
                    class="mx-2"
                    src="{{ asset('assets/img/tournament_icon.png') }}"
                    width="20px"
                >{{ __('words.tournaments_bracket') }}
            </h2>
        </div>

        <div class="swiper-container gallery-slider w-50 px-2 pb-3">
            <div class="swiper-wrapper">
                @foreach ($this->cups as $cup)
                    <div class="swiper-slide tour-slide mb-4">
                        <a
                            class="text-ranking"
                            href="{{ route('cup.show', $cup->id) }}"
                            title="{{ $cup->name }}"
                        >
                            <x-icons.svg.cup
                                with="30"
                                height="30"
                            />
                            <span class="font-weight-bold pt-2">{{ $cup->name }}</span>
                        </a>
                    </div>
                @endforeach

            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination gallery-pagination"></div>
        </div>

    </div>
    <div
        class="tour-bracket-box rounded-2 mt-4 border bg-white"
        id="aaaa"
        style="width: 40%;background: url('/img/tour_bg.jpg') center no-repeat;background-size: auto 100%;"
    >
        <div
            class="w-100 tournament-score rounded-2 p-2 pb-4 text-white"
            style="height: 100%;"
        >
            <div class="px-2 py-1">
                <div class="d-flex flex-wrap">
                    <h2 class="text-white">{{ __('words.ranks_table') }}</h2>
                </div>
            </div>

            <div class="swiper-container score-slider px-2 py-3">
                <div class="swiper-wrapper">
                    {{-- @foreach ($tour_scores as $tour)
                    <div class="swiper-slide">
                        <div class="pb-4 pt-1 text-center">
                            <div>
                                @if ($tour->is_team == $status['No'])
                                    <a
                                        class="text-white"
                                        href="{{ route('gameinfo', ['game_name' => $tour->game->name]) }}"
                                        title="{{ $tour->game->name }}"
                                    >
                                        <img
                                            class="rounded-circle mx-1"
                                            src="{{ $tour->game->icon }}"
                                            title="{{ __('games.' . $tour->game->name) }}"
                                            alt="{{ $tour->game->name }}"
                                            width="20px"
                                            height="20px"
                                        >
                                        {{ __('games.' . $tour->game->name) }}
                                    </a>
                                @else
                                    <a
                                        class="text-white"
                                        href="{{ route('gameinfo', ['game_name' => $tour->team_game->name]) }}"
                                        title="{{ $tour->team_game->name }}"
                                    >
                                        <img
                                            class="rounded-circle mx-1"
                                            src="{{ $tour->team_game->icon }}"
                                            title="{{ __('games.' . $tour->team_game->name) }}"
                                            alt="{{ $tour->team_game->name }}"
                                            width="20px"
                                            height="20px"
                                        >
                                        {{ __('games.' . $tour->team_game->name) }}
                                    </a>
                                @endif
                            </div>
                            <div><a
                                    class="text-white"
                                    href="{{ route('tournament.show', ['tour_id' => $tour->id, 'tour_name' => $tour->name]) }}"
                                    title="{{ $tour->name }}"
                                >( {{ $tour->name }} )</a></div>
                        </div>
                        <div
                            class="w-100 d-flex border-top flex-wrap py-2"
                            style="background-color: #ffffff30;"
                        >
                            <div
                                class="pr-2"
                                style="width: 60%;"
                            >{{ __('words.participant_name') }}</div>
                            <div
                                class="text-center"
                                style="width: 20%;"
                            >{{ __('words.Score') }}</div>
                            <div
                                class="text-center"
                                style="width: 20%;"
                            >{{ __('words.Rank') }}</div>
                        </div>
                        @php
                            $a = null;
                            $b = null;
                            if ($tour->is_team == $status['No']) {
                                $final_winner = $tour->competition_user->where('user_id', $tour->final_bracket->final_winner->id);
                                $second_winner = $tour->competition_user->where('user_id', $tour->final_bracket->second_winner->id);
                                $third_winner = $tour->competition_user->where('user_id', $tour->final_bracket->third_winner->id);
                                $fourth_winner = $tour->competition_user->where('user_id', $tour->final_bracket->fourth_winner->id);

                                $top_users = $final_winner
                                    ->merge($second_winner)
                                    ->merge($third_winner)
                                    ->merge($fourth_winner);
                                $users = $tour->competition_user->whereNotIn('user_id', $top_users->pluck('id'));
                            } else {
                                $final_winner = $tour->competition_user->where('team_id', $tour->team_final_bracket->final_winner->id);
                                $second_winner = $tour->competition_user->where('team_id', $tour->team_final_bracket->second_winner->id);
                                $third_winner = $tour->competition_user->where('team_id', $tour->team_final_bracket->third_winner->id);
                                $fourth_winner = $tour->competition_user->where('team_id', $tour->team_final_bracket->fourth_winner->id);

                                $top_users = $final_winner
                                    ->merge($second_winner)
                                    ->merge($third_winner)
                                    ->merge($fourth_winner);
                                $users = $tour->competition_user->whereNotIn('team_id', $top_users->pluck('id'));
                            }

                            $all_users = $top_users->merge($users);

                            $score_count = 1;
                        @endphp
                        @foreach ($all_users as $user)
                            @if ($score_count <= 10)
                                <div
                                    class="w-100 d-flex border-top {{ $score_count > 6 ? 'responsive-score' : '' }} flex-wrap py-2">
                                    @if ($tour->is_team == $status['No'])
                                        <div
                                            class="pr-2"
                                            style="width: 60%;"
                                        >{{ $user->user->profile->fullname }}</div>
                                    @else
                                        <div
                                            class="pr-2"
                                            style="width: 60%;"
                                        >{{ $user->team->name }}</div>
                                    @endif
                                    <div
                                        class="text-center"
                                        style="width: 20%;"
                                    >{{ $user->score }}</div>
                                    <div
                                        class="text-center"
                                        style="width: 20%;"
                                    >{{ trans_choice('words.tournament_score_place', $score_count) }}</div>
                                </div>
                                @php $score_count++; @endphp
                            @endif
                        @endforeach
                    </div>
                @endforeach --}}

                </div>
            </div>
        </div>
    </div>
</div>
