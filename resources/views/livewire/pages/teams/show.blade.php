<div>
    <div class="card-header text-center gamepage-header">
        <h3>{{ __('words.Team').' '.$team->name }}</h3>
    </div>


    <div class="gamepage_center_box">

        <div id="app">
            <div class="card invite_box">
                <div class="card-header text-center">
                    <h5>{{ __('words.Select opponent team') }}</h5>
                </div>
                <div class="card-body">
                    <livewire:pages.teams.search-team :team="$team"/>
                    {{--<search-team random-search-route="{{ route('random_teams') }}"
                                 search-route="{{ route('search_team') }}"
                                 select-route="{{ route('select_team') }}"
                                 get-clubs="{{ route('get_clubs') }}"
                                 profile-link="{{ route('team_profile', ['teamname'=>'']) }}"
                                 lbl-username="{{ __('words.Team Name') }}"
                                 lbl-suggested="{{ __('words.suggested:') }}"
                                 lbl-score="{{ __('words.Score: ') }}"
                                 lbl-rank="{{ __('words.Rank: ') }}"
                                 lbl-btn="{{ __('words.Invite team to compete') }}"
                                 lbl-required="{{ __('words.Enter Team Name') }}"
                                 lbl-free-game="{{ __('words.Free_Game') }}"
                                 lbl-inclub="{{ __('words.In Club') }}"
                                 lbl-with-image="{{ __('words.With Referee') }}"
                                 help-free-game="{{ __('words.free_game_tick') }}"
                                 help-inclub="{{ __('words.in_club_tick') }}"
                                 help-with-image="{{ __('words.with_image_tick') }}"
                                 game-id="{{ $team->game_id }}"
                                 team-id="{{ $team->id }}"
                                 select-club="{{ __('words.Choose the club you want to play...') }}"
                                 get-country-route="{{route('get_clubs_country')}}"
                                 get-states-route="{{route('get_clubs_states')}}"
                                 locale-country="{{ json_encode(__('country')) }}"
                                 locale-state="{{ json_encode(__('state')) }}"
                                 lbl-select-country="{{__('words.Country')}}"
                                 lbl-select-state="{{__('words.State')}}"
                    ></search-team>--}}
                </div>
            </div>

            {{--

                        <div class="card box sent_box">
                            <a data-toggle="collapse" href="#Sent_Invites" role="button" aria-expanded="false"
                               aria-controls="Sent_Invites" class="collapsed">
                                <div class="card-header text-center">
                                    <h5>
                                        {{ __('words.Sent_Invites').' ('.$sent->count().')' }}
                                        <i class="fas fa-chevron-up"></i>
                                        <i class="fas fa-chevron-down"></i>
                                    </h5>
                                </div>
                            </a>
                            <div class="card-body collapse" id="Sent_Invites">

                                <ul class="list-group">
                                    @foreach($sent as $invite)
                                        <li class="list-group-item">

                                            <div class="sent-user-info">
                                                <a href="{{ route('team_profile',['teamname'=>$invite->invited->name]) }}"
                                                   target="_blank" title="{{$invite->invited->name}}">
                                                    <img src="{{ $invite->invited->photo }}" class="user_photo mb-2" width="80">
                                                    <div>{{ $invite->invited->name9 }}</div>
                                                </a>
                                                <div>{{__('words.Rank: ')}} {{ \App\Team_Score::rank($invite->invited->id, $team->game_id) }}</div>
                                                <div>{{__('words.Score: ')}} {{ \App\Team_Score::get_score($invite->invited->id, $team->game_id) }}</div>
                                            </div>


                                            <div class="sent-info">
                                                <div>
                                                    @if($invite->is_inclub)
                                                        <i class="fas fa-check txt-green"></i>
                                                    @else
                                                        <i class="fas fa-times txt-red"></i>
                                                    @endif
                                                    <b>{{ __('words.In Club') }}</b>
                                                    @if($invite->is_inclub)
                                                        {{ '('.$invite->club->name.')' }}
                                                    @endif
                                                </div>
                                                <div>
                                                    @if($invite->is_with_image)
                                                        <i class="fas fa-check txt-green"></i>
                                                    @else
                                                        <i class="fas fa-times txt-red"></i>
                                                    @endif
                                                    <b>{{ __('words.With Referee') }}</b>
                                                </div>

                                                @php
                                                    $date = app()->getLocale()=='fa'?jdate($invite->dt):$invite->dt;
                                                    $dt = date("Y/m/d (H:i)", strtotime($date));
                                                @endphp
                                                <div class="invite-dt"><span class="btn-dark btn-sm"><i class="fas fa-clock"></i> {{ $dt }}</span>
                                                </div>

                                                <div class="mb-3"><b>{{ __('words.Status:') }}</b> {{ $invite->status_msg }}</div>
                                                <div class="gamepage-buttons">
                                                    <a href="{{ route('chatpage',['user_id'=>$invite->invited->capitan_id]) }}"
                                                       class="btn btn-primary btn-sm" target="_blank"><i
                                                            class="fa fa-comments"></i> {{ __('words.chat_with_capitan') }}</a>
                                                    <a href="{{ route('team_cancel',['team_id'=>$invite->inviter_id, 'invite_id'=>$invite->id]) }}"
                                                       class="btn btn-danger btn-sm">
                                                        <i class="fa fa-window-close"></i> {{ __('words.Cancel') }}</a>
                                                </div>
                                            </div>

                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>


                        <div class="card box received_box">
                            <a data-toggle="collapse" href="#Received_Invites" role="button" aria-expanded="false"
                               aria-controls="Received_Invites" class="collapsed">
                                <div class="card-header text-center">
                                    <h5>
                                        {{ __('words.Received_Invites').' ('.$received->count().')' }}
                                        <i class="fas fa-chevron-up"></i>
                                        <i class="fas fa-chevron-down"></i>
                                    </h5>
                                </div>
                            </a>
                            <div class="card-body collapse" id="Received_Invites">

                                <ul class="list-group">
                                    @foreach($received as $invite)
                                        <li class="list-group-item">

                                            <div class="receive-user-info">
                                                <a href="{{ route('team_profile',['teamname'=>$invite->inviter->name]) }}"
                                                   target="_blank" title="{{$invite->inviter->name}}">
                                                    <img src="{{ $invite->inviter->photo }}" class="user_photo mb-2" width="80">
                                                    <div>{{ $invite->inviter->name9 }}</div>
                                                </a>
                                                <div>{{__('words.Rank: ')}} {{ \App\Team_Score::rank($invite->inviter->id, $team->game_id) }}</div>
                                                <div>{{__('words.Score: ')}} {{ \App\Team_Score::get_score($invite->inviter->id, $team->game_id) }}</div>
                                            </div>

                                            <div class="receive-info">
                                                <div>
                                                    <div>
                                                        @if($invite->is_inclub)
                                                            <i class="fas fa-check txt-green"></i>
                                                        @else
                                                            <i class="fas fa-times txt-red"></i>
                                                        @endif
                                                        <b>{{ __('words.In Club') }}</b>
                                                        @if($invite->is_inclub)
                                                            {{ '('.$invite->club->name.')' }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @if($invite->is_with_image)
                                                            <i class="fas fa-check txt-green"></i>
                                                        @else
                                                            <i class="fas fa-times txt-red"></i>
                                                        @endif
                                                        <b>{{ __('words.With Referee') }}</b>
                                                    </div>
                                                </div>

                                                @php
                                                    $date = app()->getLocale()=='fa'?jdate($invite->dt):$invite->dt;
                                                    $dt = date("Y/m/d (H:i)", strtotime($date));
                                                @endphp
                                                <div class="invite-dt"><span class="btn-dark btn-sm"><i class="fas fa-clock"></i> {{ $dt }}</span>
                                                </div>

                                                <div class="mb-3 txt-black mobile-number">
                                                    @if($invite->inviter->capitan->profile->show_mobile==1)
                                                        <i class="fas fa-mobile-alt"></i>
                                                        {{ $invite->inviter->capitan->profile->mobile }}
                                                    @endif
                                                </div>

                                                <div class="gamepage-buttons">
                                                    <a href="{{ route('team_accept',['team_id'=>$invite->invited_id, 'invite_id'=>$invite->id]) }}"
                                                       class="btn btn-success btn-sm">
                                                        <i class="fa fa-check"></i> {{ __('words.Accept') }}</a>
                                                    <a href="{{ route('team_reject',['team_id'=>$invite->invited_id, 'invite_id'=>$invite->id]) }}"
                                                       class="btn btn-danger btn-sm">
                                                        <i class="fa fa-ban"></i> {{ __('words.Reject') }}</a>
                                                    <a href="{{ route('chatpage',['user_id'=>$invite->inviter->capitan_id]) }}"
                                                       class="btn btn-primary btn-sm ml-2" target="_blank"><i
                                                            class="fa fa-comments"></i> {{ __('words.chat_with_capitan') }}</a>
                                                </div>
                                            </div>

                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                        <check-page-invites
                            check-route="{{ route('check_teampage_invites') }}"
                            sent-count="{{ $sent->count() }}"
                            received-count="{{ $received->count() }}"
                            results-count="{{ $no_submit_results_count }}"
                            one-submit-results-count="{{ $one_submit_results_count }}"
                            game-id="{{ $team->id }}"
                        ></check-page-invites>
            --}}

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
                                <form
                                    action="{{ route('team_submit_result',['team_id'=>$team->id, 'invite_id'=>$game_result->invite_id]) }}"
                                    method="post" enctype="multipart/form-data">

                                    {{ csrf_field() }}

                                    <div class="result-user-info">
                                        @php
                                            if( $game_result->inviter_id == $team->id )
                                              $opponent_team = \App\Team::find($game_result->invited_id);
                                            else
                                              $opponent_team = \App\Team::find($game_result->inviter_id);
                                        @endphp
                                        <a href="{{ route('team_profile',['teamname'=>$opponent_team->name]) }}"
                                           target="_blank" title="{{$opponent_team->name}}">
                                            <img src="{{ $opponent_team->photo }}" class="user_photo mb-2" width="80">
                                            <div>{{ $opponent_team->name9 }}</div>
                                        </a>
                                        <div>{{__('words.Rank: ')}} {{ \App\Team_Score::rank($opponent_team->id, $team->game_id) }}</div>
                                        <div>{{__('words.Score: ')}} {{ \App\Team_Score::get_score($opponent_team->id, $team->game_id) }}</div>
                                    </div>

                                    <div class="result-info">

                                        <div class="mb-2">
                                            @php
                                                $inclub = \App\Team_Invite::IsInclub($game_result->game_type);
                                                $with_image = \App\Team_Invite::IsWithImage($game_result->game_type);
                                                $res_null = false;
                                                if( ($game_result->inviter_id == $team->id && $game_result->inviter_res == null) ||  ($game_result->invited_id == $team->id && $game_result->invited_res == null) ) $res_null = true;
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
                                                {{ \App\Team_Invite::statusMsg($game_result->status, $game_result->invite_id) }}
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


        <div class="card stat_info_box player_info_box">
            <a data-toggle="collapse" href="#Info" role="button" aria-expanded="false" aria-controls="Info"
               class="collapsed">
                <div class="card-header text-center">
                    <h5>
                        {{ __('words.Stat_Info') }}
                        <i class="fas fa-chevron-up"></i>
                        <i class="fas fa-chevron-down"></i>
                    </h5>
                </div>
            </a>
            <div class="card-body collapse" id="Info">


                <div class="card gamepage_box player_info_box teampage_scores_box">
                    <div class="card-header text-center">
                        <h5>
                            {{ __('words.Your Scores') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{ __('words.Score: ').$score['score'] }}</li>
                            @php
                                $rank = \App\Team_Score::rank($score['team_id'],$score['game_id']);
                                $country_rank = \App\Team_Score::country_rank($score['team_id'],$score['game_id']);
                            @endphp
                            <li class="list-group-item">{{ __('words.Global Rank: ').$rank }}</li>
                            <li class="list-group-item">{{ __('words.Country Rank: ').$country_rank }}</li>
                            <li class="list-group-item">{{ __('words.This_Team_Coin: ').$score->coin }}</li>
                        </ul>
                    </div>
                </div>


                <div class="card gamepage_box player_info_box teampage_scores_box">
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


</div>
