<div class="d-flex flex-wrap p-0">

    <!---------- Tournaments Box ---------->

    <div class="col-12 col-md-12 col-lg-6 mt-1 pl-0 pt-2">
        <div class="rounded-1 direction alignment border bg-white px-2 pt-2">
            <div class="border-bottom d-flex flex-wrap px-2 pb-1">
                <a
                    class="home-box-title-a"
                    href="{{ route('tournaments.index') }}"
                    title="{{ __('words.matches') }}"
                >
                    <h2 class="home-box-title-h2">{{ __('words.matches') }}</h2>
                </a>
            </div>
            <div
                class="swiper-container"
                id="tournament-slider"
            >
                <div
                    class="swiper-wrapper pb-4 pt-2"
                    id="home-tournament-box"
                >
                    @foreach ($tournaments as $tournament)
                        <div class="swiper-slide tour-slide">
                            <a
                                class="text-decoration-none"
                                href="{{ route('tournaments.show', $tournament->id) }}"
                                title="{{ $tournament->name }}"
                                style="color: #6f6f6f;"
                            >
                                <div class="rounded-2 tour-card bg-white pb-3 shadow">
                                    <img
                                        class="tour-card-img"
                                        src="{{ url($tournament->game->cover) }}"
                                        alt="{{ $tournament->game->name }}"
                                    >
                                    <div
                                        class="position-relative d-flex flex-wrap"
                                        style="bottom: 10px;"
                                    >
                                        <div class="rounded-pill mx-auto bg-white px-2 py-1 text-center">
                                            <img
                                                class="rounded-circle mx-1"
                                                src="{{ url($tournament->game->icon) }}"
                                                title="{{ $tournament->game->name }}"
                                                alt="{{ $tournament->game->name }}"
                                                width="20px"
                                                height="20px"
                                            >
                                            {{ $tournament->game->name }}
                                        </div>
                                    </div>

                                    <div class="text-dark text-truncate text-center">
                                        {{ $tournament->name }}
                                        @if ($tournament->end_regesterd_at > now())
                                            <i
                                                class="fa fa-flag-checkered mx-1"
                                                title="{{ __('words.finished') }}"
                                                style="font-size: 14px;"
                                            ></i>
                                        @endif
                                    </div>

                                    <div
                                        class="d-flex mt-1 flex-wrap"
                                        style="font-size: 14px;"
                                    >
                                        <div class="w-50 text-truncate text-center">
                                            <img
                                                class="mx-1"
                                                src="{{ url('assets/img/flags/' . $tournament?->state?->country?->name . '.png') }}"
                                                height="20px"
                                            >
                                            {{ $tournament?->state?->name }}

                                        </div>
                                        <div class="w-50 text-center">
                                            {{ trans_choice('words.tour_members', $tournament?->registered_users_count, [
                                                'member' => $tournament?->registered_users_count,
                                            ]) }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div
                    class="swiper-pagination"
                    id="tournament-slider-pagination"
                ></div>
            </div>
        </div>
    </div>

    <!---------- Tournaments Gallery ---------->

    <div class="col-12 col-md-12 col-lg-6 mt-1 pl-0 pr-0 pt-2">
        <div class="rounded-1 direction alignment border bg-white px-2 pt-2">
            <div class="border-bottom px-2">
                <h2 class="home-box-title-h2">{{ __('words.tournaments_gallery') }}</h2>
            </div>

            <div
                class="swiper-container"
                id="tournament-gallery-slider"
            >
                <div
                    class="swiper-wrapper pb-4 pt-2"
                    id="home-gallery-box"
                >
                    @foreach ($tournamentImages as $tournamentImage)
                        {{-- TODO: add tournoment show route --}}
                        {{-- TODO: change image url to competetion images collection --}}
                        <div class="swiper-slide tour-slide">
                            <a>
                                <img
                                    src="{{ url($tournamentImage->game->cover) }}"
                                    title="{{ $tournamentImage->name }}"
                                    alt="{{ $tournamentImage->name }}"
                                    height="140px"
                                >
                            </a>
                        </div>
                    @endforeach
                </div>
                <div
                    class="swiper-pagination"
                    id="tournament-gallery-slider-pagination"
                ></div>
            </div>

        </div>
    </div>
</div>
