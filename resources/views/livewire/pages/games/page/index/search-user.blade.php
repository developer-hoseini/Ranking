<form method="post" action="{{ route('games.page.invite') }}" x-data="{
             inClubSelect:false,
             withRefereeSelect:false,
             freeGameSelect:true,
             }" x-init="$watch('inClubSelect', value => value?freeGameSelect=false:(!withRefereeSelect?freeGameSelect=true:''));
             $watch('withRefereeSelect', value =>  value?freeGameSelect=false:(!inClubSelect?freeGameSelect=true:''));
             $watch('freeGameSelect', value =>  (value && (withRefereeSelect || inClubSelect))?withRefereeSelect=inClubSelect=false:'');
             ">
    {{ csrf_field() }}

    <div class="invite-part" style="width: {{$opponent?70:100}}%">

        <div class="form-group row">


            <div class="gamepage_username">
                <input id="username" class="form-control username_search_box" wire:model.live="username"
                       autocomplete="off"
                       placeholder="{{ __('words.search') }}">
            </div>

            @if($usersRandom->count())
                <div style="width:100%;">
                    <div class="lbl-suggested">{{ __('words.suggested:') }}</div>
                    <ul class="suggested_users_result">
                        @foreach($usersRandom as $userRandom)
                            <li wire:click="selectUser({{$userRandom?->id}})" class="suggested_user_list">
                                <img src="{{$userRandom?->avatar}}" class="user_photo suggested_user_photo">
                                <div>{{$userRandom?->username}}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($usersResult)
                <div class="list-group user-results">
                    @foreach($usersResult as $userResult)
                        <button type="button" wire:click="selectUser({{$userResult?->id}})"
                                class="list-group-item list-group-item-action result {{$userResult->score_achievements_sum_count==0?'line_over':''}}">
                            <img src="{{$userResult?->avatar}}" class="user_photo" width="50">
                            {{ $userResult->username . ' (' . $userResult?->profile?->fullname . ')' }}
                        </button>
                    @endforeach
                </div>
            @endif

            <span class="loading" wire:loading="username"></span>
        </div>
        <div class="form-group row mb-1">
            <div class="col-md-6 offset-md-4">
                <div class="custom-control custom-checkbox">
                    <button type="button" class="btn-tooltip" rel="tooltip"
                            title="{{__('words.in_club_tick')}}"></button>
                    <input type="checkbox" name="in_club" class="custom-control-input"
                           id="in-club" x-model="inClubSelect">
                    <label class="custom-control-label" x-bind:class="{'line_over' : !inClubSelect}"
                           for="in-club">{{ __('words.In Club') }}</label>
                </div>
            </div>
            <div class="form-group club-part" x-show="inClubSelect">

                <select class="form-control" wire:model.live="countryId">
                    <option value="" disabled>{{ __('words.Country') }}</option>
                    @foreach($countries as $country)
                        <option value="{{$country['id']}}}">
                            {{$country['name']}}
                        </option>
                    @endforeach
                </select>

                <select class="form-control" wire:model.live="stateId">
                    <option value="" disabled>{{ __('words.State') }}</option>
                    @foreach($states as $state)
                        <option value="{{$state['id']}}">
                            {{$state['name']}}
                        </option>
                    @endforeach
                </select>

                <select name="club" class="form-control">
                    <option value="" disabled>{{ __('words.Choose the club you want to play...') }}</option>
                    @foreach($clubs as $club)
                        <option value="{{$club['id']}}">
                            {{$club['name']}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row mb-1">
            <div class="col-md-6 offset-md-4">
                <div class="custom-control custom-checkbox">
                    <button type="button" class="btn-tooltip" rel="tooltip"
                            title="{{__('words.with_image_tick')}}"></button>
                    <input type="checkbox" name="withRefereeSelect" class="custom-control-input"
                           id="with-referee"
                           x-model="withRefereeSelect">
                    <label class="custom-control-label lbl-withreferee"
                           x-bind:class="{'line_over' : !withRefereeSelect}"
                           for="with-referee">{{ __('words.With Referee') }}</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <div class="custom-control custom-checkbox">
                    <button type="button" class="btn-tooltip" rel="tooltip"
                            title="{{__('words.free_game_tick')}}"></button>
                    <input type="checkbox" name="free_game" class="custom-control-input" id="free_game"
                           x-model="freeGameSelect" x-bind:disabled="!withRefereeSelect && !inClubSelect">
                    <label class="custom-control-label lbl-freegame" x-bind:class="{'line_over' : !freeGameSelect}"
                           for="free_game">{{ __('words.Free_Game') }}</label>
                </div>
            </div>
        </div>


        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button class="btn btn-danger">
                    {{ __('words.Send Invite') }}
                </button>
            </div>
        </div>
    </div>

    @if($opponent)
        <div class="userinfo-part" style="width: 30%">
            <img src="{{$opponent?->avatar}}" class="user_photo userinfo_img" width="130">
            <div class="userinfo_info">
                <div>
                    <a href="{{route('profile.show',['user'=> $opponent?->id])}}" target="_blank"
                       title="selected_username">{{ $opponent?->username  }}</a>
                </div>
                <div>{{ __('words.Rank: ').\App\Services\Actions\User\GetGameRank::handle($opponent->id,$game->id) }}</div>
                <div>{{  __('words.Score: ') . $opponent->score_achievements_sum_count }}</div>
            </div>
        </div>
    @endif

</form>
