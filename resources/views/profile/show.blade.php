@extends('layouts.app')

@section('title', __('words. - ').($user?->profile?->fullname??$user->name).' ('.$user->username.')')

@section('header')
    @parent
@endsection


@section('content')

    <div id="app">
        <div class="card">
            <div class="card-body">
                <div class="profile">
                    <div class="profile-photo">
                        <img src="{{ $user->avatar }}" class="user_photo" alt="{{ $user->username }}"
                             title="{{$user?->profile?->fullname??$user->name}}" width="180">
                    </div>

                    <div class="profile-details">
                        <div><h2 style="font-size: 24px;">{{ $user?->username }}</h2></div>
                        <div><h1 class="font-weight-bold"
                                 style="font-size: 20px;color: #000;">{{$user?->profile?->fullname??$user->name}}</h1>
                        </div>
                        <div>
                            @if($user->profile?->state_id!=null)
                                <img
                                    src="{{ $user->profile?->state?->country?->icon }}">
                                {{ $user->profile?->state?->country?->name }}
                            @endif
                        </div>
                        <div class="like_cnt">
                            @if(auth()->check())
                                {{--TODO: Change to livewire--}}
                                <profile like-route="{{route('profile.like')}}"
                                         report-route="{{route('profile.report')}}"
                                         is-liked="{{(bool)$user?->is_like}}"
                                         user-id="{{$user->id}}"
                                         like-count="{{$user->likes_count}}"
                                         coin-count="{{$user->coin_achievements_sum_count}}"
                                         lbl-like="{{__('')}}"
                                         lbl-likes="{{__('')}}"
                                         lbl-report="{{__('words.Report')}}"
                                         msg-report-profile="{{__('words.Report profile picture?')}}"
                                         msg-will-sent="{{__('words.Report will be sent for reviewing.')}}"
                                         lbl-yes-report="{{__('words.Yes, report it.')}}"
                                         lbl-cancel="{{__('words.Cancel')}}"></profile>
                            @else
                                <i class="fa fa-heart liked-color no-pointer"></i>
                                <span>{{ $user->likes_count }}</span>
                                <img src="{{asset('assets/img/coin.png')}}" class="profile_coin">
                                <span>{{$user->coin_achievements_sum_count}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="profile-bio">{{ $user->profile?->bio }}</div>
                </div>
            </div>


            <!-- Profile Menu Items -->
            <div class="w-100 border-bottom mb-2" style="border-top: solid 1px #636b6f;">
                <div class="container text-center">
                    <div class="p-3 profile-menu-item d-inline-block" btn_value="joined-games"
                         style="border-bottom: solid 1px #636b6f ;">
                        <img src="{{asset('assets/img/game-dark.png')}}" class="profile-menu-img">
                        <div class="profile-menu-responsive">{{__('words.Joined_Games')}}</div>
                    </div>
                    <div class="p-3 profile-menu-item d-inline-block" btn_value="competitions">
                        <img src="{{asset('assets/img/cup-dark.png')}}" class="profile-menu-img">
                        <div class="profile-menu-responsive">{{__('words.tournament_joined')}}</div>
                    </div>
                    <div class="p-3 profile-menu-item d-inline-block" btn_value="certificates">
                        <img src="{{asset('assets/img/certificate-dark.png')}}" class="profile-menu-img">
                        <div class="profile-menu-responsive">{{__('words.championship_certificates')}}</div>
                    </div>
                </div>
            </div>


            <div class="card-body">
                <div class="profile">
                    <div class="w-100 profile-options joined-games">
                        <div class="text-center"><h2>{{__('words.Joined_Games')}}</h2></div>
                        @foreach( $userGames as $row => $game)
                            <div class="profile_game_scores">
                                <h3 class="btn btn-light p-2 w-100 mt-2 border profile-game-btn"
                                    style="font-size: 20px;cursor: pointer;color: #555;"
                                    btn_value="{{$row}}">
                                    <span>
                                           {{ $game?->name}}
                                    </span>
                                    <i class="fa fa-chevron-down mx-2 game-btn-i-{{$row}}" rotate="down"></i></h3>
                                <div class="profile-game-div" id="profile-game-div-{{$row}}" style="display: none;">
                                    <div class="card profile_stars_box">
                                        <div class="card-header text-center"><h5>{{ __('words.Stars') }}</h5></div>
                                        <div class="card-body">
                                            {{--@php
                                                $game_id = $score->game->id;
                                                $inclub_stars = \App\Models\Invite::inclub_stars($user->id, $game_id, $status, $setting);
                                                $image_stars = \App\Models\Invite::image_stars($user->id, $game_id, $status, $setting);

                                                $team_played_stars = \App\Team_Played_User::whereHas('team' , function($query) use ($game_id){
                                                    $query->where('game_id', $game_id);
                                                })->where([
                                                    ['user_id','=',$user->id],
                                                    ['dt','>=', $days_ago],
                                                ])->count();

                                                $inclub_per = $inclub_stars*100 / $setting['star_club'];
                                                $image_per = $image_stars*100 / $setting['star_image'];
                                                $team_played_per = $team_played_stars*100 / config('setting.star_team_played');
                                                $total_played = $score['win']+$score['lose'];
                                                if($total_played>0){ // prevent divide by zero
                                                  $div_played = $total_played/$setting['warning_div'];
                                                  $remove_star = $score['warning']/$div_played;
                                                  $warning_per = 100-$remove_star*20; // every 20% = one star
                                                }
                                                else
                                                  $warning_per = 0;
                                            @endphp--}}

                                            {{-- TODO:List Stare --}}
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">
                                                    <div class="stars_textbox">{{ __('words.In Club: ') }}</div>
                                                    <div class="stars_starbox">
                                                        <div class="star-ratings-sprite"><span
                                                                style="width:{{0}}%"
                                                                class="star-ratings-sprite-rating"></span></div>
                                                        <button class="btn-tooltip" rel="tooltip"
                                                                title="{{ __('message.played_days_ago',['play_num'=>0, 'days_num'=>config('setting.days_ago')]) }}"></button>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="stars_textbox">{{ __('words.With Referee: ') }}</div>
                                                    <div class="stars_starbox">
                                                        <div class="star-ratings-sprite"><span
                                                                style="width:{{0}}%"
                                                                class="star-ratings-sprite-rating"></span>
                                                        </div>
                                                        <button class="btn-tooltip" rel="tooltip"
                                                                title="{{ __('message.played_days_ago',['play_num'=>0, 'days_num'=>config('setting.days_ago')]) }}"></button>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="stars_textbox">{{ __('words.Team_Game:') }}</div>
                                                    <div class="stars_starbox gamepage_stars">
                                                        <div class="star-ratings-sprite"><span
                                                                style="width:{{0}}%"
                                                                class="star-ratings-sprite-rating"></span></div>
                                                        <button class="btn-tooltip" rel="tooltip"
                                                                title="{{ __('message.played_days_ago',['play_num'=>0, 'days_num'=>config('setting.days_ago')]) }}"></button>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="stars_textbox">{{ __('words.Lawful:') }}</div>
                                                    <div class="stars_starbox">
                                                        <div class="star-ratings-sprite"><span
                                                                style="width:{{100}}%"
                                                                class="star-ratings-sprite-rating"></span></div>
                                                        <button class="btn-tooltip" rel="tooltip"
                                                                title="{{ __('message.warnings_in_played',['warning_num'=>5, 'play_num'=>5]) }}"></button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card profile_scores_box">
                                        <div class="card-header text-center"><h5>{{ __('words.Scores') }}</h5></div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">
                                                    {{ __('words.Score: ').$game?->game_competitions_score_occurred_model_sum_count??0 }}
                                                </li>
                                                @php
                                                    $rank = \App\Services\Actions\User\GetGameRank::handle($user->id,$game?->id);
                                                    $countryRank = \App\Services\Actions\User\GetCountryRank::handle($user->id,$user?->profile?->state?->country_id,$game?->id);
                                                @endphp
                                                <li class="list-group-item">{{ __('words.Global Rank: ').$rank }}</li>
                                                <li class="list-group-item">{{ __('words.Country Rank: ').$countryRank }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card profile_gamepage_box">
                                        <div class="card-header text-center"><h5>{{ __('words.competitions') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush warn-part-one">
                                                {{-- TODO:get in club & Referee --}}
                                                <li class="list-group-item">{{ __('words.In Club: ').'0' }}</li>
                                                <li class="list-group-item">{{ __('words.With Referee: ').'0' }}</li>
                                                <li class="list-group-item"
                                                    style="border-bottom: none;">{{ __('words.Fault: ').$game->win_absent }}</li>
                                            </ul>
                                            <ul class="list-group list-group-flush warn-part-two">
                                                <li class="list-group-item"
                                                    style="border-top: none;">{{ __('words.Win: ').$game->win_count }}</li>
                                                <li class="list-group-item">{{ __('words.Lose: ').$game->lose_count }}</li>
                                                <li class="list-group-item">{{ __('words.all_competitions: ').($game->win_count+$game->lose_count+$game->win_absent) }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{--@if($count == 0)
                            <div class="w-100 text-center pt-2">{{__('words.dont_yet_any_game')}} ...</div>
                        @endif--}}
                    </div>
                </div>


                <div class="profile profile-options competitions" style="display: none;">
                    <div class="text-center"><h2>{{__('words.tournament_registered')}}</h2></div>
                    <div class="w-100 pt-2" id="competitions-div">
                        <div class="w-100 text-center"><img src="{{asset('assets/img/loading_spinner.gif')}}"
                                                            width="100px">
                        </div>
                    </div>
                </div>


                <div class="w-100 profile-options certificates" style="display: none;">
                    <div class="text-center"><h2>{{__('words.championship_certificates')}}</h2></div>

                    <div class="mt-3 text-center">
                        <button
                            class="btn btn-outline-info active mx-2 btn_personal_certificates">{{__('words.personal_certificates')}}</button>
                        <button
                            class="btn btn-outline-info mx-2 btn_team_certificates">{{__('words.team_certificates')}}</button>
                    </div>

                    <div class="loading_spinner mx-auto mt-3" style="display:none;"></div>

                    <div id="certificates_content">
                        <div class="team_certificates_body pt-4 text-center"></div>

                        <div class="personal_certificates_body">
                            {{--@if($certificates->count() > 0)
                                <div class="w-100 text-center pt-2">
                                    @foreach($certificates as $certificate)
                                        <div class="p-3 d-inline-block">
                                            <a href="{{route('cert_info',['cert_id'=>$certificate->id, 'en_fullname'=>$user->profile->dashed_en_fullname])}}"
                                               target="_blank">
                                                <img src="{{url($certificate->small_image)}}"
                                                     alt="{{trans_choice('words.tournament_place_number', $certificate->place).' - '.$certificate->bracket->competition->name}}"
                                                     title="{{__('words.score_certificate').' '.$certificate->bracket->competition->name}}"
                                                     width="250" class="rounded">
                                            </a>
                                            <div class="pt-1"><img src="/img/cup_{{$certificate->place}}.png"
                                                                   height="30px">{{trans_choice('words.tournament_place_number', $certificate->place)}}
                                            </div>
                                            <div>{{$certificate->bracket->competition->name}}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="w-100 text-center pt-2">{{__('words.dont_yet_any_certificate')}} ...</div>
                            @endif--}}
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>


    @push('styles')
        <style type="text/css">
            .profile-menu-item {
                cursor: pointer;
                margin: 0 30px;
                margin-top: 1px;
            }

            .profile-menu-item:hover {
                background-color: #f5f5f5;
            }

            .profile-game-btn i {
                transition-duration: 0.5s;
            }

            .profile-menu-img {
                width: 50px;
            }

            @media screen and (max-width: 600px) {
                .profile-menu-responsive {
                    display: none;
                }
            }

            @media screen and (max-width: 770px) {
                .profile-menu-item {
                    margin: 0 10px;
                }
            }

            @media screen and (max-width: 450px) {
                .profile-menu-img {
                    width: 30px;
                }
            }

        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function () {

                $("[rel=tooltip]").tooltip({placement: 'left'});
                var competition_loading = 0;


                $('.profile-game-btn').on('click', function () {
                    var value = $(this).attr('btn_value');
                    var slider = $('#profile-game-div-' + value);
                    var rotate = $('.game-btn-i-' + value);
                    var game_btn = $('.profile-game-btn i');
                    var game_div = $('.profile-game-div');
                    var rotate_attr = rotate.attr('rotate');
                    var rotate_deg = '';

                    game_btn.removeClass('fa-chevron-up');
                    game_btn.addClass('fa-chevron-down');
                    game_btn.attr('rotate', 'down');
                    game_div.stop().slideUp();

                    if (rotate_attr === 'up') {
                        rotate_deg = 'down';
                    } else {
                        rotate_deg = 'up';
                    }
                    rotate.attr('rotate', rotate_deg);

                    rotate.removeClass('fa-chevron-' + rotate_attr);
                    rotate.addClass('fa-chevron-' + rotate_deg);
                    slider.stop().slideToggle();
                });


                $('.profile-menu-item').on('click', function () {
                    var value = $(this).attr('btn_value');
                    $('.profile-options').css('display', 'none');
                    $('.profile-menu-item').css('border', 'none');
                    $(this).css('border-bottom', 'solid 1px #636b6f');
                    $('.' + value).css('display', 'block');


                    if (value == 'competitions' && competition_loading == 0) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.post('{{ route('profile.competitions') }}', {'user_id': '{{$user->id}}'}, function (data) {
                            if (data) {
                                $('#competitions-div').html(data);
                                competition_loading++;
                            }
                        });
                    }
                });


                var team_certs_loaded = 0;
                var team_selected = 0;

                $('.btn_team_certificates').click(function () {
                    if (team_selected == 0) {
                        $('#certificates_content').find('.personal_certificates_body').fadeOut(function () {
                            if (team_certs_loaded == 0) {
                                $('.loading_spinner').fadeIn();
                                $('.team_certificates_body').load("{{route('profile_team_certificates')}}", function () {
                                    $('.loading_spinner').fadeOut();
                                    team_certs_loaded = 1;
                                });
                            } else {
                                $('#certificates_content').find('.team_certificates_body').fadeIn();
                            }
                        });

                        $('.btn_team_certificates').addClass('active');
                        $('.btn_personal_certificates').removeClass('active');

                        team_selected = 1;
                    }
                });


                $('.btn_personal_certificates').click(function () {
                    if (team_selected == 1) {
                        $('#certificates_content').find('.team_certificates_body').fadeOut(function () {
                            $('#certificates_content').find('.personal_certificates_body').fadeIn();
                        });
                        $('.btn_personal_certificates').addClass('active');
                        $('.btn_team_certificates').removeClass('active');

                        team_selected = 0;
                    }

                });


            });
        </script>
    @endpush

@endsection
