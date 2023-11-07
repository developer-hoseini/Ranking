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
                                title="{{ $tournament->name }}"
                                style="color: #6f6f6f;"
                                {{-- href="{{ route('tournament.show', ['competition' => $tournament->id]) }}" --}}
                            >
                                <div class="rounded-2 tour-card bg-white pb-3 shadow">
                                    {{--                                    @if ($tournament?->teams->count()) --}}
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
                                                {{--                                                 title="{{__('games.'.$tournament->game->name)}}" --}}
                                                width="20px"
                                                height="20px"
                                            >
                                            {{--                                            {{__('games.'.$tournament->game->name)}} --}}
                                            {{ $tournament->game->name }}
                                        </div>
                                    </div>
                                    {{--                                    @else --}}
                                    {{--                                        @php --}}
                                    {{--                                            $team = $tournament->teams->first(); --}}
                                    {{--                                        @endphp --}}
                                    {{--                                        <img src="{{url($team->game->cover)}}" --}}
                                    {{--                                             alt="{{$team->game->name}}" --}}
                                    {{--                                             class="tour-card-img"> --}}
                                    {{--                                        <div class="position-relative d-flex flex-wrap" style="bottom: 10px;"> --}}
                                    {{--                                            <div class="bg-white py-1 px-2 rounded-pill text-center mx-auto"> --}}
                                    {{--                                                <img src="{{url($team->icon)}}" --}}
                                    {{--                                                     alt="{{$team->name}}" --}}
                                    {{--                                                     title="{{__('games.'.$team->name)}}" width="20px" --}}
                                    {{--                                                     height="20px" class="rounded-circle mx-1"> --}}
                                    {{--                                                {{__('games.'.$team->name)}} --}}
                                    {{--                                            </div> --}}
                                    {{--                                        </div> --}}
                                    {{--                                    @endif --}}
                                    <div class="text-dark text-center">
                                        {{ mb_substr($tournament->name, 0, 21, 'UTF-8') }}
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
                                        <div class="w-50 text-center">
                                            <img
                                                class="mx-1"
                                                src="{{ url('assets/img/flags/' . $tournament->state->country->name . '.png') }}"
                                                height="20px"
                                            >
                                            @if (\Lang::has('state.' . $tournament->state->name))
                                                {{ mb_substr(__('state.' . $tournament->state->name), 0, 10, 'UTF-8') }}
                                            @else
                                                {{ mb_substr($tournament->state->name, 0, 8, 'UTF-8') }}
                                            @endif
                                        </div>
                                        <div class="w-50 text-center">
                                            {{ trans_choice('words.tour_members', $tournament->users->count(), [
                                                'member' => $tournament->users->count(),
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
                            <a {{-- href="{{ route('tournament.show', ['competition' => $tournamentImage->id]) }}" --}}>
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
