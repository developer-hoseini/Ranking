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

            {{--
              TODO:refactor for check reload page
              <check-page-invites
                  check-route="{{ route('games.page.check.invites') }}"
                  sent-count="{{ $currentUser->inviter->count() }}"
                  received-count="{{ $currentUser->invited->count() }}"
                  results-count="{{ $no_submit_results_count }}"
                  one-submit-results-count="{{ $one_submit_results_count }}"
                  game-id="{{ $game->id }}"
              ></check-page-invites>--}}

        </div>


        {{--

                <div id="app2" class="card result_box">
                    <a data-toggle="collapse" href="#Results" role="button" aria-expanded="false" aria-controls="Results"
                       class="collapsed">
                        <div class="card-header text-center">
                            <h5>
                                {{ __('words.Results').' ('.$game_results->count().')' }}
                                <i class="fas fa-chevron-up"></i>
                                <i class="fas fa-chevron-down"></i>
                            </h5>
                        </div>
                    </a>
                    <div class="card-body collapse" id="Results">

                        <ul class="list-group">
                            @foreach($game_results as $game_result)
                                <li class="list-group-item">
                                    <div class="col-md offset-md">
                                        <form action="{{ route('submit_result',['invite_id'=>$game_result->invite_id]) }}"
                                              method="post" enctype="multipart/form-data">

                                            {{ csrf_field() }}

                                            <div class="result-user-info">
                                                @php
                                                    if( $game_result->inviter_id == Auth::user()->id )
                                                      $user = \App\User::find($game_result->invited_id);
                                                    else
                                                      $user = \App\User::find($game_result->inviter_id);
                                                @endphp
                                                <a href="{{ route('profile',['username'=>$user->username]) }}" target="_blank"
                                                   title="{{$user->username}}">
                                                    <img src="{{ $user->photo }}" class="user_photo mb-2" width="80">
                                                    <div>{{ $user->username9 }}</div>
                                                </a>
                                                <div>{{__('words.Rank: ')}} {{ \App\User_Score::rank($user->id, $game['id']) }}</div>
                                                <div>{{__('words.Score: ')}} {{ \App\User_Score::get_score($user->id, $game['id']) }}</div>
                                            </div>

                                            <div class="result-info">

                                                <div class="mb-2">
                                                    @php
                                                        $inclub = \App\Invite::IsInclub($game_result->game_type);
                                                        $with_image = \App\Invite::IsWithImage($game_result->game_type);
                                                        $res_null = false;
                                                        if( ($game_result->inviter_id == Auth::user()->id && $game_result->inviter_res == null) ||  ($game_result->invited_id == Auth::user()->id && $game_result->invited_res == null) ) $res_null = true;
                                                    @endphp

                                                    <div>
                                                        @if($inclub)
                                                            <i class="fas fa-check txt-green"></i>
                                                        @else
                                                            <i class="fas fa-times txt-red"></i>
                                                        @endif
                                                        <b>{{ __('words.In Club') }}</b>
                                                        @if($inclub)
                                                            {{ '('.\App\Club::find($game_result->club_id)->name.')' }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @if($with_image)
                                                            <i class="fas fa-check txt-green"></i>
                                                        @else
                                                            <i class="fas fa-times txt-red"></i>
                                                        @endif
                                                        <b>{{ __('words.With Referee') }}</b>
                                                    </div>


                                                    @if($game_result->inviter_res != null && $game_result->invited_res != null)
                                                        <div>
                                                            @php
                                                                $result_dt = app()->getLocale()=='fa'?jdate($game_result->result_dt):$game_result->result_dt;
                                                                $dt = date("Y/m/d (H:i)", strtotime($result_dt));
                                                            @endphp
                                                            <i class="fas fa-calendar-check"></i> {{ $dt }}
                                                        </div>
                                                    @endif



                                                    @if( $res_null && $with_image )
                                                        <div class="upload-image mt-2" style="width: 89%;">
                                                            <div class="input-group mb-2">
                                                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="inputGroupFileAddon01">
                                    <i class="fas fa-image"></i>
                                  </span>
                                                                </div>
                                                                <div class="custom-file">
                                                                    <input type="file" name="image" accept="image/*"
                                                                           class="custom-file-input" id="inputGroupFile01"
                                                                           aria-describedby="inputGroupFileAddon01"
                                                                           @change="image_name_changed">
                                                                    <input type="hidden" name="with_referee" value="1">
                                                                    <label class="custom-file-label" for="inputGroupFile01">@{{
                                                                        image_file_name }}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif


                                                </div>

                                                @php
                                                    if( $game_result->status == config('status.Accepted') )
                                                    {
                                                      if( $res_null )
                                                      {
                                                @endphp


                                                <div class="btn-group btn-group-toggle mb-2" data-toggle="buttons">
                                                    <label class="btn btn-secondary mr-1 mb-1 mt-2 btn-won">
                                                        <input type="radio" name="result"
                                                               value="{{ config('result.Win') }}"> {{ __('words.I Won') }}
                                                    </label>
                                                    <label class="btn btn-secondary mr-1 mb-1 mt-2 btn-lost">
                                                        <input type="radio" name="result"
                                                               value="{{ config('result.Lose') }}"> {{ __('words.I Lost') }}
                                                    </label>
                                                    <br>
                                                    <label class="btn btn-secondary mr-1 btn-sm btn-heAbsent">
                                                        <input type="radio" name="result"
                                                               value="{{ config('result.He_Absent') }}"> {{ __('words.Opponent was absent / left the playing') }}
                                                    </label>
                                                    <label class="btn btn-secondary btn-sm btn-iAbsent">
                                                        <input type="radio" name="result"
                                                               value="{{ config('result.I_Absent') }}"> {{ __('words.I was absent / left the playing') }}
                                                    </label>
                                                </div>

                                                @php
                                                    $game_result->status = config('status.Submit_Result');
                                                  }
                                                  else{
                                                    $game_result->status = config('status.Wait_Opponent_Result');
                                                  }
                                                }
                                                @endphp


                                                <div class="form-group row mt-4">
                                                    @if( $res_null )
                                                        <div class="col-md-3.5 offset-md-2 result-btn-box">
                                                            <button class="btn btn-danger btn-submit-result">
                                                                {{ __('words.Submit') }}
                                                            </button>
                                                        </div>
                                                    @endif

                                                    <span class="result_status">
                              @if($game_result->status == config('status.End_True') ||
                                  $game_result->status == config('status.End_Absent'))
                                                            <b>{{ __('words.Final Result:') }}</b>
                                                        @else
                                                            <b>{{ __('words.Status:') }}</b>
                                                        @endif
                                                        {{ \App\Invite::statusMsg($game_result->status, $game_result->invite_id) }}
                            </span>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div>
        --}}

        {{--

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
                                    <li class="list-group-item">{{ __('words.Score: ').$score['score'] }}</li>
                                    @php
                                        $rank = \App\User_Score::rank($score['user_id'],$score['game_id']);
                                        $country_rank = \App\User_Score::country_rank($score['user_id'],$score['game_id']);
                                    @endphp
                                    <li class="list-group-item">{{ __('words.Global Rank: ').$rank }}</li>
                                    <li class="list-group-item">{{ __('words.Country Rank: ').$country_rank }}</li>
                                    <li class="list-group-item">{{ __('words.This_Game_Coin: ').$score->coin }}</li>
                                    <li class="list-group-item">
                                        <a href="{{ route('prizes') }}" class="btn_order_prize">{{ __('words.get_prize') }}</a>
                                    </li>
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
                                    <li class="list-group-item">{{ __('words.Fault: ').$score['fault'] }}</li>
                                    <li class="list-group-item">{{ __('words.Win: ').$score['win'] }}</li>
                                    <li class="list-group-item">{{ __('words.Lose: ').$score['lose'] }}</li>
                                    @php $total_played = $score['win']+$score['lose']; @endphp
                                    <li class="list-group-item">{{ __('words.all_competitions: ').$total_played }}</li>
                                </ul>
                            </div>
                        </div>


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


                    </div>
                </div>
        --}}


    </div>



    @push('scripts')
        <script>
            $(document).ready(function () {
                $("[rel=tooltip]").tooltip({placement: 'left'});
            });
        </script>
    @endpush

@endsection
