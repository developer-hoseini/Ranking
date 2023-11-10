<form method="post" action="{{ route('games.page.invite') }}">
    {{ csrf_field() }}
    {{--

        <search-user random-search-route="{{ route('games.page.random-users') }}"
                     search-route="{{ route('games.page.search-user') }}"
                     select-route="{{ route('games.page.select-user') }}"
                     get-clubs="{{ route('games.page.get-clubs') }}"
                     profile-link="{{ route('profile.show', ['user'=> 0]) }}"
                     lbl-search="{{ __('words.search') }}"
                     lbl-suggested="{{ __('words.suggested:') }}"
                     lbl-score="{{ __('words.Score: ') }}"
                     lbl-rank="{{ __('words.Rank: ') }}"
                     lbl-btn="{{ __('words.Send Invite') }}"
                     lbl-required="{{ __('words.Enter Username') }}"
                     lbl-free-game="{{ __('words.Free_Game') }}"
                     lbl-inclub="{{ __('words.In Club') }}"
                     lbl-with-image="{{ __('words.With Referee') }}"
                     help-free-game="{{ __('words.free_game_tick') }}"
                     help-inclub="{{ __('words.in_club_tick') }}"
                     help-with-image="{{ __('words.with_image_tick') }}"
                     game-id="{{ $game->id }}"
                     select-club="{{ __('words.Choose the club you want to play...') }}"
                     opponent="{{ json_encode($opponent) }}"
                     get-country-route="{{route('games.page.get-clubs-country')}}"
                     get-states-route="{{route('games.page.get-clubs-states')}}"
                     locale-country="{{ json_encode(__('country')) }}"
                     locale-state="{{ json_encode(__('state')) }}"
                     lbl-select-country="{{__('words.Country')}}"
                     lbl-select-state="{{__('words.State')}}"
                     lbl-user-not-joined="{{__('words.user_not_joined_yet')}}"
                     lbl-only-can-send-joined="{{__('words.only_can_send_invite_for_joined')}}"
                     lbl-ok="{{__('words.ok')}}"
        ></search-user>
    --}}

    <div>

        <div class="invite-part">

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
                {{--
                                                <div v-if="user_results.length > 0" class="list-group user-results">
                                                    <button type="button" v-for="user in user_results" @click="select_user(user)"
                                                            :class="{'line_over' : user.scores.length==0}"
                                                            class="list-group-item list-group-item-action result">
                                                        <img :src="user.photo" class="user_photo" width="50">
                                                        {{ user.username + ' (' + user.profile.fullname + ')' }}
                                                    </button>
                                                </div>--}}

                <span class="loading" wire:loading="username"></span>
            </div>

            {{--
                        <div class="form-group row mb-1">
                            <div class="col-md-6 offset-md-4">
                                <div class="custom-control custom-checkbox">
                                    <button class="btn-tooltip" @click.prevent="" rel="tooltip" :title="helpInclub"></button>
                                    <input type="checkbox" name="in_club" value="1" class="custom-control-input" id="inclub"
                                           v-model="inclub_checked" @click="club_click($event)">
                                    <label class="custom-control-label" :class="{'line_over' : is_free_game}"
                                           for="inclub">{{ lblInclub }}</label>
                                </div>
                            </div>
                            <div class="form-group club-part" v-show="inclub_checked">

                                <select name="country_id" id="country" class="form-control" @change="get_states_click($event)">
                                    <option value="">{{ lblSelectCountry }}</option>
                                    <option v-for="country in countries" :value="country.id">{{
                                            get_locale_country(country.name)
                                        }}
                                    </option>
                                </select>

                                <select name="state_id" id="state" class="form-control" @change="get_clubs_click($event)">
                                    <option value="">{{ lblSelectState }}</option>
                                    <option v-for="state in states" :value="state.id">{{ get_locale_state(state.name) }}</option>
                                </select>

                                <select name="club" class="form-control">
                                    <option value="">{{ selectClub }}</option>
                                    <option v-for="club in clubs" :value="club.id">{{ club.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <div class="col-md-6 offset-md-4">
                                <div class="custom-control custom-checkbox">
                                    <button class="btn-tooltip" @click.prevent="" rel="tooltip" :title="helpWithImage"></button>
                                    <input type="checkbox" name="with_image" value="1" class="custom-control-input" id="withreferee"
                                           v-model="withimage_checked" @click="image_click($event)">
                                    <label class="custom-control-label lbl-withreferee" :class="{'line_over' : is_free_game}"
                                           for="withreferee">{{ lblWithImage }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="custom-control custom-checkbox">
                                    <button class="btn-tooltip" @click.prevent="" rel="tooltip" :title="helpFreeGame"></button>
                                    <input type="checkbox" name="free_game" value="1" class="custom-control-input" id="free_game"
                                           @click="free_game_click" v-model="free_game_checked" :disabled="free_game_checked">
                                    <label class="custom-control-label lbl-freegame" :class="{'line_over' : !is_free_game}"
                                           for="free_game">{{ lblFreeGame }}</label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button class="btn btn-danger">
                                    {{ lblBtn }}
                                </button>
                            </div>
                        </div>--}}
        </div>

        {{--

                <div class="userinfo-part" :style="userinfo_css" v-show="user_selected">
                    <img :src="selected_photo" class="user_photo userinfo_img" width="130">
                    <div class="userinfo_info">
                        <div>
                            <a :href="profileLink + '/' + selected_username" target="_blank"
                               :title="selected_username">{{ selected_username9 }}</a>
                        </div>
                        <div>{{ lblRank + selected_rank }}</div>
                        <div>{{ lblScore + selected_score }}</div>
                    </div>
                </div>
        --}}

    </div>

</form>
