<form
    method="post"
    action="{{ route('games.page.invite', ['game' => $game->id]) }}"
    x-data="{
        inClubSelect: false,
        withRefereeSelect: false,
        freeGameSelect: true,
    }"
    x-init="$watch('inClubSelect', value => value ? freeGameSelect = false : (!withRefereeSelect ? freeGameSelect = true : ''));
    $watch('withRefereeSelect', value => value ? freeGameSelect = false : (!inClubSelect ? freeGameSelect = true : ''));
    $watch('freeGameSelect', value => (value && (withRefereeSelect || inClubSelect)) ? withRefereeSelect = inClubSelect = false : '');"
>
    {{ csrf_field() }}

    <div
        class="invite-part"
        style="width: {{ $opponent ? 70 : 100 }}%"
    >

        <div class="form-group row">

            <div class="gamepage_username">
                <input
                    class="form-control username_search_box"
                    id="username"
                    wire:model.live="username"
                    autocomplete="off"
                    placeholder="{{ __('words.search') }}"
                >
            </div>

            @if ($usersRandom->count())
                <div style="width:100%;">
                    <div class="lbl-suggested">{{ __('words.suggested:') }}</div>
                    <ul class="suggested_users_result">
                        @foreach ($usersRandom as $userRandom)
                            <li
                                class="suggested_user_list"
                                wire:click="selectUser({{ $userRandom?->id }})"
                            >
                                <img
                                    class="user_photo suggested_user_photo"
                                    src="{{ $userRandom?->avatar }}"
                                >
                                <div>{{ $userRandom?->avatarName }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($usersResult)
                <div class="list-group user-results">
                    @foreach ($usersResult as $userResult)
                        <button
                            class="list-group-item list-group-item-action result {{ $userResult->user_score_achievements_sum_count == 0 ? 'line_over' : '' }}"
                            type="button"
                            wire:click="selectUser({{ $userResult?->id }})"
                        >
                            <img
                                class="user_photo"
                                src="{{ $userResult?->avatar }}"
                                width="50"
                            >
                            {{ $userResult->avatarName . ' (' . $userResult?->profile?->fullname . ')' }}
                        </button>
                    @endforeach
                </div>
            @endif

            <span
                class="loading"
                wire:loading="username"
            ></span>
        </div>
        <div class="form-group row mb-1">
            <div class="col-md-6 offset-md-4">
                <div class="custom-control custom-checkbox">
                    <button
                        class="btn-tooltip"
                        type="button"
                        title="{{ __('words.in_club_tick') }}"
                        rel="tooltip"
                    ></button>
                    <input
                        class="custom-control-input"
                        id="in-club"
                        name="in_club"
                        type="checkbox"
                        x-model="inClubSelect"
                    >
                    <label
                        class="custom-control-label"
                        for="in-club"
                        x-bind:class="{ 'line_over': !inClubSelect }"
                    >{{ __('words.In Club') }}</label>
                </div>
            </div>
            <div
                class="form-group club-part"
                x-show="inClubSelect"
            >

                <select
                    class="form-control"
                    wire:model.live="countryId"
                >
                    <option
                        value=""
                        disabled
                    >{{ __('words.Country') }}</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country['id'] }}}">
                            {{ $country['name'] }}
                        </option>
                    @endforeach
                </select>

                <select
                    class="form-control"
                    wire:model.live="stateId"
                >
                    <option
                        value=""
                        disabled
                    >{{ __('words.State') }}</option>
                    @foreach ($states as $state)
                        <option value="{{ $state['id'] }}">
                            {{ $state['name'] }}
                        </option>
                    @endforeach
                </select>

                <select
                    class="form-control"
                    name="club"
                >
                    <option
                        value=""
                        disabled
                    >{{ __('words.Choose the club you want to play...') }}</option>
                    @foreach ($clubs as $club)
                        <option value="{{ $club['id'] }}">
                            {{ $club['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row mb-1">
            <div class="col-md-6 offset-md-4">
                <div class="custom-control custom-checkbox">
                    <button
                        class="btn-tooltip"
                        type="button"
                        title="{{ __('words.with_image_tick') }}"
                        rel="tooltip"
                    ></button>
                    <input
                        class="custom-control-input"
                        id="with-referee"
                        name="with_image"
                        type="checkbox"
                        x-model="withRefereeSelect"
                    >
                    <label
                        class="custom-control-label lbl-withreferee"
                        for="with-referee"
                        x-bind:class="{ 'line_over': !withRefereeSelect }"
                    >{{ __('words.With Referee') }}</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <div class="custom-control custom-checkbox">
                    <button
                        class="btn-tooltip"
                        type="button"
                        title="{{ __('words.free_game_tick') }}"
                        rel="tooltip"
                    ></button>
                    <input
                        class="custom-control-input"
                        id="free_game"
                        name="free_game"
                        type="checkbox"
                        x-model="freeGameSelect"
                        x-bind:disabled="!withRefereeSelect && !inClubSelect"
                    >
                    <label
                        class="custom-control-label lbl-freegame"
                        for="free_game"
                        x-bind:class="{ 'line_over': !freeGameSelect }"
                    >{{ __('words.Free_Game') }}</label>
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

    @if ($opponent)
        <input
            name="userId"
            type="hidden"
            value="{{ $opponent->id }}"
        >
        <div
            class="userinfo-part"
            style="width: 30%"
        >
            <img
                class="user_photo userinfo_img"
                src="{{ $opponent?->avatar }}"
                width="130"
            >
            <div class="userinfo_info">
                <div>
                    <a
                        href="{{ route('profile.show', ['user' => $opponent?->id]) }}"
                        title="selected_username"
                        target="_blank"
                    >{{ $opponent?->avatarName }}</a>
                </div>
                <div>
                    {{ __('words.Rank: ') . \App\Services\Actions\User\GetGameRank::handle($opponent->id, $game->id) }}
                </div>
                <div>{{ __('words.Score: ') . $opponent->user_score_achievements_sum_count }}</div>
            </div>
        </div>
    @endif

</form>
