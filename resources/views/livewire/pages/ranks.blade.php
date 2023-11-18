<div class="container-fluid">
    <div class="change_rank_view">
        <div
            class="btn-group btn-group-toggle change-games-part mb-2"
            data-toggle="buttons"
        >
            {{-- <label
                class="btn btn-secondary btn-won active mb-1 mr-1 mt-2"
                v-show="isAuth"
            >
                <input type="radio" /> <i class="fas fa-globe"></i>
                {{ __('words.Global Rank') }}
            </label>
            <label
                class="btn btn-secondary btn-lost mb-1 mr-1 mt-2"
                v-show="isAuth"
            >
                <input type="radio" /> <i class="fas fa-flag"></i>
                {{ __('words.Country Rank') }}
            </label> --}}

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

                <label class="col-form-label"><i class="fas fa-users"></i>
                    {{ __('words.Members: ') }}
                    {{ $this->game['game_competitions_users_count'] }}
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
                            </th>
                            <th
                                class="photo-name"
                                scope="col"
                            >
                                {{ __('words.First Name') }}
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
                            <th
                                v-if="authId > 0"
                                scope="col"
                            ></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->rankUsers as $rank => $user)
                            <tr class="{{ $user->id == auth()->id() ? 'auth_selected' : '' }}">
                                <td>
                                    @if ($filters['rank'] && $filters['rank'] > 0)
                                        {{ $filters['rank'] }}
                                    @else
                                        {{ $rank + 1 }}
                                    @endif
                                </td>

                                <td class="photo-name">
                                    <a
                                        href="{{ route('profile.show', $user->id) }}"
                                        title="{{ $user?->profile?->fullname }}"
                                    >
                                        <img
                                            class="user_photo"
                                            src="{{ $user->avatar }}"
                                            alt="{{ $user?->profile?->fullname }}"
                                            width="40"
                                        />
                                        <span>
                                            {{ $user?->profile?->fullname }}
                                        </span>
                                        <span>
                                            {{ $user?->profile?->fullname ?? $user->username }}
                                        </span>
                                    </a>
                                </td>

                                <td>
                                    {{ $user->user_score_achievements_sum_count }}
                                </td>

                                <td class="text-center">
                                    {{ $user->user_coin_achievements_sum_count }}
                                </td>

                                <td>
                                    @auth

                                        @if (auth()->id() != $user->id)
                                            <a class="invitation">
                                                {{ __('words.Invitation_to_Compete') }}
                                            </a>
                                        @endif
                                    @endauth
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </section>

</div>
