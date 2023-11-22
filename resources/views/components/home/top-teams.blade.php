<div class="col-12 col-md-12 col-lg-6 px-lg-2 mt-1 px-0 pt-2">
    <div class="rounded-1 direction alignment border bg-white px-2 pt-2">
        <div class="border-bottom d-flex flex-wrap px-2 pb-1">
            <p
                class="home-box-title-a"
                title="{{ __('words.Top Teams') }}"
            >
            <h2 class="home-box-title-h2">{{ __('words.Top Teams') }}</h2>
            </p>
        </div>
        <div
            class="swiper-container"
            id="team-ranks-slider"
        >
            <div
                class="swiper-wrapper pb-4 pt-2"
                id="home-teamrank-box"
            >
                @foreach ($games as $game)
                    <div class="swiper-slide ranks-swiper-slide text-center">
                        <div class="w-100 rounded shadow">
                            <div
                                class="rounded-top text-truncate py-2"
                                style="background-color: #f2f2f2;width: 100%"
                            >
                                <a
                                    class="text-info font-weight-bold"
                                    href="{{ route('games.show', $game->id) }}"
                                    title="{{ $game->name }}"
                                >{{ $game->name }}</a>
                            </div>

                            @foreach ($game->gameCompetitionsTeams as $team)
                                <div class="border-bottom py-2">
                                    <div
                                        class="d-inline-block align-middle"
                                        style="width: 20%;"
                                    >
                                        <img
                                            class="rounded-circle"
                                            src="{{ $team->avatar }}"
                                            title="{{ $team->name }}"
                                            alt="{{ $team->name }}"
                                            width="100%"
                                        >
                                    </div>
                                    <div
                                        class="d-inline-block text-truncate align-middle"
                                        style="width: 65%;"
                                    >
                                        <a
                                            class="text-dark"
                                            href="{{ route('teams.show', $team->id) }}"
                                            title="{{ $team->name }}"
                                        >
                                            {{ $team->name }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div
                class="swiper-pagination"
                id="team-ranks-pagination"
            ></div>
        </div>
    </div>
</div>
