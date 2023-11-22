<div class="col-12 col-md-12 col-lg-6 px-lg-2 mt-1 px-0 pt-2">
    <div class="rounded-1 direction alignment border bg-white px-2 pt-2">
        <div class="border-bottom px-2">
            <h2 class="home-box-title-h2">{{ __('words.latest_played') }}</h2>
        </div>
        <div
            class="swiper-container"
            id="games-slider"
        >
            <div
                class="swiper-wrapper pb-4 pt-2"
                id="home-lastgame-box"
            >
                @foreach ($competitions as $competition)
                    <div class="swiper-slide tournament-swiper-slide text-center">
                        <div class="col-4 p-0">
                            <div>
                                <img
                                    class="rounded-circle"
                                    src="{{ $competition?->loserUser?->avatar }}"
                                    alt="{{ $competition?->loserUser?->id }} {{ $competition?->loserUser?->name }}"
                                    style="max-width: 100px;"
                                    width="100%"
                                >
                            </div>
                            <div><i
                                    class="fa fa-check-circle text-success"
                                    style="font-size: 22px;margin-top: 10px;"
                                ></i></div>
                            <div class="bg-success rounded-pill w-100 py-1">
                                <a
                                    class="text-white"
                                    href="{{ route('profile.show', $competition?->loserUser?->id ?? 0) }}"
                                    title="{{ $competition?->loserUser?->username }}"
                                >
                                    {{ $competition?->loserUser?->username }}
                                </a>
                            </div>
                        </div>
                        <div class="col-4 col-lg-3 p-0">
                            <a
                                class="text-decoration-none"
                                href="{{ route('games.show', $competition?->game?->id ?? 0) }}"
                            >
                                <h4 class="text-dark">{{ $competition?->game?->name }}</h4>
                            </a>
                        </div>
                        <div class="col-4 p-0">
                            <div>
                                <img
                                    class="rounded-circle"
                                    src="{{ $competition?->winerUser?->avatar }}"
                                    alt="{{ $competition?->winerUser?->id }} {{ $competition?->winerUser?->name }}"
                                    style="max-width: 100px;"
                                    width="100%"
                                >
                            </div>
                            <div><i
                                    class="fa fa-times-circle text-danger mx-2"
                                    style="font-size: 22px;margin-top: 10px;"
                                ></i></div>
                            <div class="bg-danger rounded-pill w-100 p-1">
                                <a
                                    class="text-white"
                                    href="{{ route('profile.show', $competition?->winerUser?->id ?? 0) }}"
                                    title="{{ $competition?->winerUser?->username }}"
                                >
                                    {{ $competition?->winerUser?->username }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div
                class="swiper-pagination"
                id="games-slider-pagination"
            ></div>
        </div>
    </div>
</div>
