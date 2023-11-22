<div class="container-fluid">
    <div class="change_rank_view">
        <div
            class="btn-group btn-group-toggle change-games-part mb-2"
            data-toggle="buttons"
        >
            @auth()
                <div
                    class="btn-group btn-group-toggle d-flex my-2 text-center"
                    data-toggle="buttons"
                >
                    <button
                        class="btn btn-secondary btn-won @if ($type == 'global') active @endif mb-1 mr-1 mt-2"
                        wire:click='$set("type","global")'
                    > <i class="fas fa-globe"></i>
                        {{ __('words.Global Rank') }}
                    </button>

                    <button
                        class="btn btn-secondary btn-lost @if ($type == 'country') active @endif mb-1 mr-1 mt-2"
                        wire:click='$set("type","country")'
                    > <i class="fas fa-flag"></i>
                        {{ __('words.Country Rank') }}
                    </button>
                </div>
            @endauth

            <div class="form-group mt-4">
                <select
                    class="form-control list-arrow-down"
                    wire:model="game.id"
                    wire:change='changeGame'
                    wire:loading.attr="disabled"
                >
                    @foreach ($this->games as $game)
                        <option value="{{ $game->id }}">
                            <span>
                                {{ $game->name }}
                            </span>
                        </option>
                    @endforeach
                </select>

                <label class="col-form-label"><i class="fas fa-teams"></i>
                    {{ __('words.Members: ') }}
                    {{-- {{ $this->game['game_competitions_teams_count'] }} --}}
                </label>
            </div>

            <div class="find-part">
                <label
                    class="col-md-4 col-form-label text-md-right"
                    for="rank"
                >
                    {{ __('words.Enter Rank:') }}
                </label>
                <input
                    class="form-control username_search_box"
                    id="rank"
                    type="number"
                    wire:model.live.debounce.250ms='filters.rank'
                    min="0"
                    autocomplete="off"
                />
                <span
                    class="loading loading-ranks"
                    wire:loading
                    wire:target="filters.rank"
                ></span>
            </div>
        </div>
    </div>

    <section class="ranks_table">
        <div class="card text-center">
            <div class="card-body">
                <div
                    class="loading loading-ranks"
                    wire:loading
                    wire:target="changeGame"
                >
                </div>
                <table class="table-striped table">
                    <thead>
                        <tr>
                            <th scope="col">
                                #
                            </th>
                            <td scope="col">
                            </td>
                            <th
                                class="photo-name"
                                scope="col"
                            >
                                {{ __('words.First Name') }}
                            </th>
                            <th scope="col">
                                {{ __('words.capitan') }}
                            </th>

                            <th scope="col">
                                {{ __('words.Score') }}
                            </th>
                            <th
                                class="text-center"
                                scope="col"
                            >
                                <img
                                    src="{{ asset('assets/img/coin.png') }}"
                                    width="16"
                                />
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($team)
                            <tr class="{{ 'auth_selected' }}">
                                <td>
                                    {{ $team->teamRank }}
                                </td>

                                <td class="photo-name">
                                    <img
                                        class="team_photo"
                                        src="{{ $team->avatar }}"
                                        alt="{{ $team?->name }}"
                                        width="40"
                                    />
                                </td>
                                <td>
                                    <a
                                        href="{{ route('profile.show', $team->id) }}"
                                        title="{{ $team?->name }}"
                                    >
                                        <span>
                                            {{ $team?->name }}
                                        </span>
                                        <span>
                                            {{ $team?->name }}
                                        </span>
                                    </a>
                                </td>

                                <td>
                                    {{ $team->capitan?->avatar_name }}
                                </td>

                                <td>
                                    {{ $team->team_score_achievements_sum_count }}
                                </td>

                                <td class="text-center">
                                    {{ $team->team_coin_achievements_sum_count }}
                                </td>

                            </tr>
                        @endif
                        @foreach ($this->rankTeams as $key => $rankTeam)
                            @php
                                $rank = $key + 1;

                                if ($this->rankTeams instanceof Illuminate\Pagination\LengthAwarePaginator) {
                                    if ($this->rankTeams?->currentPage() > 1) {
                                        $rank = ($this->rankTeams?->currentPage() - 1) * $this->rankTeams->perPage() + $rank;
                                    }
                                }
                            @endphp
                            <tr>

                                <td>
                                    @if ($filters['rank'] && $filters['rank'] > 0)
                                        {{ $filters['rank'] }}
                                    @else
                                        {{ $rank }}
                                    @endif
                                </td>

                                <td class="photo-name">
                                    <img
                                        class="team_photo"
                                        src="{{ $rankTeam->avatar }}"
                                        alt="{{ $rankTeam?->name }}"
                                        width="40"
                                    />

                                </td>
                                <td>
                                    <a
                                        href="{{ route('profile.show', $rankTeam->id) }}"
                                        title="{{ $rankTeam?->name }}"
                                    >
                                        <span>
                                            {{ $rankTeam?->name }}
                                        </span>
                                        <span>
                                            {{ $rankTeam?->name }}
                                        </span>
                                    </a>
                                </td>

                                <td>
                                    {{ $rankTeam->capitan?->avatar_name }}
                                </td>

                                <td>
                                    {{ $rankTeam->team_score_achievements_sum_count }}
                                </td>

                                <td class="text-center">
                                    {{ $rankTeam->team_coin_achievements_sum_count }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
                @if ($this->rankTeams instanceof Illuminate\Pagination\LengthAwarePaginator)
                    <div class="pagination-links">
                        {{ $this->rankTeams->withQueryString()->links() }}
                    </div>
                @endif

            </div>
        </div>
    </section>

</div>
