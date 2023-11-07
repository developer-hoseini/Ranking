<div class="mt-2">
    <div class="rounded-1 border bg-white p-2">
        <div class="border-bottom d-flex flex-wrap px-2 pb-1"><a
                class="home-box-title-a"
                href="@auth {{ route('ranks') }} @else {{ route('global_ranks') }} @endauth"
                title="{{ __('words.ranks_table') }}"
            >
                <h2 class="home-box-title-h2">{{ __('words.ranks_table') }}</h2>
            </a></div>
        <div
            class="swiper-container"
            id="ranks-slider"
        >
            <div class="swiper-wrapper pb-5 pt-2">

                @foreach ($games as $game)
                    <div class="swiper-slide ranks-swiper-slide rounded">
                        <div class="w-100 bg-white shadow">
                            <div
                                class="w-100 border-ranking bg-light pt-2"
                                style="border-bottom: solid 3px;"
                            >
                                <div class="home-ranks-title col-5">
                                    <a
                                        class="text-truncate text-white"
                                        href="{{ route('game.show', ['game' => $game->id]) }}"
                                        title="{{ $game->name }}"
                                    >
                                        {{ $game->name }}
                                    </a>
                                </div>
                            </div>
                            <div class="border-bottom d-flex bg-light flex-wrap py-2">
                                <div
                                    class="d-inline-block mx-auto align-middle"
                                    style="width: 7%;"
                                >
                                    {{ __('words.Rank') }}</div>
                                <div
                                    class="d-inline-block mx-auto align-middle"
                                    style="width: 55%;"
                                >
                                    {{ __('words.First Name') }}</div>
                                <div
                                    class="d-inline-block mx-auto align-middle"
                                    style="width: 15%;"
                                >
                                    {{ __('words.Score') }}</div>
                                <div
                                    class="d-inline-block mx-auto align-middle"
                                    style="width: 15%;"
                                >
                                    {{ __('words.Coin') }}</div>
                            </div>

                            @foreach ($game->gameCompetitionsUsers as $row => $user)
                                <div
                                    class="border-bottom w-100 py-2"
                                    style="height: 70px"
                                >
                                    <div
                                        class="d-inline-block align-middle"
                                        style="width: 7%;"
                                    >{{ $row + 1 }}
                                    </div>
                                    <div
                                        class="d-inline-block align-middle"
                                        style="width: 8%;"
                                    >
                                        <img
                                            class="rounded-circle"
                                            src="{{ $user?->avatar }}"
                                            title="{{ $user?->username }}"
                                            alt="{{ $user?->profile?->fullName }}"
                                            img
                                            width="100%"
                                        >
                                    </div>
                                    <div
                                        class="d-inline-block align-middle"
                                        style="width: 47%;"
                                    >
                                        <a
                                            class="text-dark"
                                            title="{{ $user?->profile?->fullName }}"
                                            @if ($user) href="{{ route('profile.show', ['user' => $user?->id]) }}" @endif
                                        >
                                            {{ $user?->profile?->fullName ?? $user?->username }}
                                        </a>
                                    </div>
                                    <div
                                        class="d-inline-block align-middle"
                                        style="width: 15%;"
                                    >
                                        {{ $user?->score_achievements_sum_count }}</div>
                                    <div
                                        class="d-inline-block align-middle"
                                        style="width: 15%;"
                                    ><img
                                            src="{{ asset('assets/img/coin.png') }}"
                                            title="{{ __('words.rezvani_coin') }}"
                                            alt="{{ __('words.rezvani_coin') }}"
                                            width="20px"
                                        >
                                        <div>{{ $user?->coin_achievements_sum_count }}</div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </div>
            <div
                class="swiper-pagination"
                id="ranks-slider-pagination"
            ></div>
        </div>

    </div>
</div>
