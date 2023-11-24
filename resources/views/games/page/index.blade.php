@extends('layouts.app')

@section('title', __('words. - ').__($game->name).__('words. page'))

@section('header')
    @parent
@endsection


@section('content')

    <div class="card-header text-center gamepage-header">
        <h3>{{ __($game->name) }}</h3>
    </div>


    <div class="gamepage_center_box">


        <div id="app">
            <div class="card invite_box">
                <div class="card-header text-center">
                    <h5>{{ __('words.Select opponent') }}</h5>
                </div>
                <div class="card-body">
                    <livewire:pages.games.page.index.search-user :game="$game" :opponent="$opponent??null"/>
                </div>
            </div>

            <livewire:pages.games.page.index.send-received-box :game="$game"/>

        </div>

        <livewire:pages.games.page.index.game-results :game="$game"/>


        <div class="card stat_info_box player_info_box">
            <a data-toggle="collapse" href="#Info" role="button" aria-expanded="false" aria-controls="Info"
               class="collapsed kkkk">
                <div class="card-header text-center">
                    <h5>
                        {{ __('words.Stat_Info') }}
                        <i class="fas fa-chevron-up"></i>
                        <i class="fas fa-chevron-down"></i>
                    </h5>
                </div>
            </a>
            <div class="card-body collapse" id="Info">


                <div class="card gamepage_box player_info_box">
                    <div class="card-header text-center">
                        <h5>
                            {{ __('words.Your Scores') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                {{ __('words.Score: ').($user['user_score_achievements_sum_count']??0) }}
                            </li>
                            <li class="list-group-item">
                                {{ __('words.Global Rank: ').($user['rank']??0) }}
                            </li>
                            <li class="list-group-item">
                                {{ __('words.Country Rank: ').($user['country_rank']??0) }}
                            </li>
                            <li class="list-group-item">
                                {{ __('words.This_Game_Coin: ').($user['user_coin_achievements_sum_count']??0) }}
                            </li>
                            {{--                            <li class="list-group-item">--}}
                            {{--                                <a href="{{ route('prizes') }}" class="btn_order_prize">{{ __('words.get_prize') }}</a>--}}
                            {{--                            </li>--}}
                        </ul>
                    </div>
                </div>

                <div class="card gamepage_box player_info_box">
                    <div class="card-header text-center">
                        <h5>
                            {{ __('words.Your Play') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{ __('words.In Club: ').$score['in_club'] }}</li>
                            <li class="list-group-item">{{ __('words.With Referee: ').$score['with_image'] }}</li>
                            <li class="list-group-item">{{ __('words.Absent: ').$score['absent'] }}</li>
                            <li class="list-group-item">{{ __('words.Win: ').$score['win'] }}</li>
                            <li class="list-group-item">{{ __('words.Lose: ').$score['lose'] }}</li>
                            <li class="list-group-item">{{ __('words.all_competitions: ').$score['total'] }}</li>
                        </ul>
                    </div>
                </div>


                {{--

                    <div class="card gamepage_box player_info_box">
                        <div class="card-header text-center">
                            <h5>
                                {{ __('words.Game Info') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">{{ __('words.Total In Club: ').$game['in_club_count'] }}</li>
                                <li class="list-group-item">{{ __('words.Total With Referee: ').$game['with_image_count'] }}</li>
                                <li class="list-group-item">{{ __('words.Total Members: ').$game['scores_count'] }}</li>
                                <li class="list-group-item">{{ __('words.Total Played: ').$game['invites_count'] }}</li>
                            </ul>
                        </div>
                    </div>


                    <div class="card stars_box player_info_box">
                        <div class="card-header text-center">
                            <h5>
                                {{ __('words.Stars') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $inclub_per = $inclub_stars*100 / config('setting.star_club');
                                $image_per = $image_stars*100 / config('setting.star_image');
                                $team_played_per = $team_played_stars*100 / config('setting.star_team_played');
                                if($total_played>0){ // prevent divide by zero
                                  $div_played = $total_played/config('setting.warning_div');
                                  $remove_star = $score['warning']/$div_played;
                                  $warning_per = 100-$remove_star*20; // every 20% = one star
                                }
                                else
                                  $warning_per = 0;
                            @endphp
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="stars_textbox">{{ __('words.In Club: ') }}</div>
                                    <div class="stars_starbox gamepage_stars">
                                        <div class="star-ratings-sprite"><span style="width:{{$inclub_per}}%"
                                                                               class="star-ratings-sprite-rating"></span>
                                        </div>
                                        <span
                                            class="stars_value">{{ __('message.played_days_ago',['play_num'=>$inclub_stars, 'days_num'=>config('setting.days_ago')]) }}</span>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="stars_textbox">{{ __('words.With Referee: ') }}</div>
                                    <div class="stars_starbox gamepage_stars">
                                        <div class="star-ratings-sprite"><span style="width:{{$image_per}}%"
                                                                               class="star-ratings-sprite-rating"></span>
                                        </div>
                                        <span
                                            class="stars_value">{{ __('message.played_days_ago',['play_num'=>$image_stars, 'days_num'=>config('setting.days_ago')]) }}</span>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="stars_textbox">{{ __('words.Team_Game:') }}</div>
                                    <div class="stars_starbox gamepage_stars">
                                        <div class="star-ratings-sprite"><span style="width:{{$team_played_per}}%"
                                                                               class="star-ratings-sprite-rating"></span>
                                        </div>
                                        <span
                                            class="stars_value">{{ __('message.played_days_ago',['play_num'=>$team_played_stars, 'days_num'=>config('setting.days_ago')]) }}</span>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="stars_textbox">{{ __('words.Lawful:') }}</div>
                                    <div class="stars_starbox gamepage_stars">
                                        <div class="star-ratings-sprite"><span style="width:{{$warning_per}}%"
                                                                               class="star-ratings-sprite-rating"></span>
                                        </div>
                                        <span
                                            class="stars_value">{{ __('message.warnings_in_played',['warning_num'=>$score['warning'], 'play_num'=>$total_played]) }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <div class="card stars_box player_info_box">
                        <div class="card-header text-center">
                            <h5>
                                {{ __('words.Warnings') }}
                            </h5>
                        </div>
                        <div class="card-body">

                            <ul class="list-group list-group-flush warn-part-one">
                                <li class="list-group-item">{{ __('words.Fault Result: ').$false_result }}</li>
                                <li class="list-group-item">{{ __('words.No Submit: ').$no_submit }}</li>
                                <li class="list-group-item">{{ __('words.Absent: ').$you_absent }}</li>
                            </ul>

                            <ul class="list-group list-group-flush warn-part-two">
                                <li class="list-group-item"
                                    style="border-top: none;">{{ __('words.Unverified Image: ').$false_image }}</li>
                                <li class="list-group-item">{{ __('words.Unverified Play: ').$false_club }}</li>
                            </ul>

                        </div>
                    </div>
    --}}

            </div>
        </div>


    </div>


    @push('scripts')
        <script>
            $(document).ready(function () {
                $("[rel=tooltip]").tooltip({placement: 'left'});
            });
        </script>
    @endpush

@endsection
