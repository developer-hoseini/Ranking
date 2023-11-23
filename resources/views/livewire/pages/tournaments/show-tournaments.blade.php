<div>
    @php
        $isCupForUsers = $cup?->cupCompetitionUsers?->count() > 0 && !$cup?->cupCompetitionTeams?->count() > 0;
        $isCupForTeams = $cup?->cupCompetitionTeams?->count() > 0 && !$cup?->cupCompetitionUsers?->count();

        if ($isCupForTeams) {
            dd('cups for teams coming soon ...');
        }
    @endphp

    <div
        class="w-100 tour-top-bg-size"
        style="background: url('{{ $cup?->game?->cover }}') center no-repeat;"
    >
        <div class="w-100 tournament-top-div">
            <div class="d-flex container flex-wrap text-white">
                <div class="tour-top-right-box text-center">
                    <div class="d-flex flex-wrap">
                        <img
                            class="rounded-circle tour-top-right-img mx-auto my-auto"
                            src="{{ url($cup?->game?->icon) }}"
                            title="{{ $cup?->game?->name }}"
                            alt="{{ $cup?->game?->name }}"
                        >
                        <div class="d-inline-block direction alignment tour-top-div-info">
                            <div class="font-weight-bold">{{ $cup?->game?->name }}</div>

                            <div class="font-weight-bold">
                                <h1
                                    class="m-0 text-white"
                                    style="font-size: 20px;"
                                >{{ $cup?->name }}</h1>
                            </div>
                            <div
                                class="pt-1"
                                style="font-size: 14px;"
                            >{{ __('words.contest_date') . ' : ' . $cup?->startAtDate }}</div>
                            <div
                                class="pt-1"
                                style="font-size: 14px;"
                            >
                                <img
                                    class="mx-1"
                                    src="{{ $cup?->cupCompetitionsState?->country?->icon }}"
                                    height="20px"
                                >

                                {{ $cup?->cupCompetitionsState?->name }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tour-top-left-box">
                    <div class="bg-dark-glass tour-bg-white-sm rounded-1 tour-top-info-div mx-auto">
                        <div class="d-flex flex-wrap">
                            <div
                                class="pt-2 text-center"
                                style="width: 65%;height: 85px;line-height: 25px;"
                            >
                                @if (!$cup?->isFinished)
                                    <div
                                        class="mb-2"
                                        style="font-size: 12px;"
                                    >
                                        <span>{{ __('words.reg_deadline') . ' : ' }}</span>
                                        <p class="mb-2">{{ $cup?->end_register_at_date }}</p>
                                    </div>
                                @else
                                    <div>{{ __('words.finished') }}</div>
                                @endif
                            </div>
                            <div
                                class="px-3"
                                style="width: 35%;"
                            >

                                <div
                                    class="text-center"
                                    style="line-height: 25px;"
                                >
                                    {{ $cup?->registeredUsers?->count() }}
                                </div>
                                @if ($cup?->capacity != 0)
                                    <div
                                        class="border-top text-center"
                                        style="line-height: 20px;"
                                    >
                                        {{ $cup?->capacity }}
                                    </div>
                                @endif

                                <div
                                    class="text-center"
                                    style="font-size: 12px;line-height: 15px;"
                                >{{ __('words.members') }}</div>

                            </div>
                        </div>
                        <div class="text-center">
                            @if ($cup?->isRegisterable)
                                @if ($cup?->isAuthUserParticipate)
                                    <p
                                        class="btn btn-outline-secondary text-white"
                                        style="width: 90%; cursor: default;"
                                    >
                                        You registered before !
                                    </p>
                                @else
                                    @if (!$cup->is_team)
                                        <a
                                            class="btn btn-success-glass"
                                            id="register-btn"
                                            href="{{ route('tournaments.register', $cup?->id) }}"
                                            style="width: 90%;"
                                        >{{ __('words.Register') }}</a>
                                    @else
                                        {{-- TODO: complete the register team with captain --}}
                                        <p
                                            class="fs-2 text-white"
                                            style="font-size: 12px;line-height: 15px;"
                                        >
                                            Only Captain could register the team
                                        </p>
                                    @endif

                                @endif
                            @else
                                <div
                                    class="btn btn-white-glass"
                                    style="width: 90%; cursor:default;"
                                >{{ __('words.reg_closed') }}!</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="container mt-5"
        id="tour-descript"
    >
        @if ($cup->is_team)
            <h2 class="mb-5 text-center">it's a team tournament</h2>
        @endif
        <div class="rounded-2 d-flex flex-wrap border bg-white p-3">
            <div class="tour-w50-box px-2">

                <div class="border-bottom pb-3">
                    <div
                        class="font-weight-bold text-dark"
                        style="font-size: 18px;"
                    >{{ __('words.organizer') }} :</div>
                    <div class="px-4 pt-2">
                        @if ($cup?->created_by_user_id != null)
                            <a
                                class="text-ranking"
                                href="{{ route('profile.show', $cup?->created_by_user_id) }}"
                                title="{{ $cup?->createdByUser->username }}"
                            >
                                <img
                                    class="rounded-circle"
                                    src="{{ $cup?->createdByUser?->avatar }}"
                                    title="{{ $cup?->createdByUser?->profile?->fullName }}"
                                    alt="{{ $cup?->createdByUser?->username }}"
                                    height="35px"
                                >
                                {{ $cup?->createdByUser?->profile?->fullName }}
                            </a>
                        @else
                            {{-- TODO: createdBy admin must check user has role admin --}}
                            {{-- <img
                                class="rounded-circle"
                                src="{{ url($cup?->creator_admin->club->Photo) }}"
                                title="{{ $cup?->creator_admin->club->name }}"
                                alt="{{ $cup?->creator_admin->club->name }}"
                                height="35px"
                            >
                            <span class="text-ranking">{{ $cup?->creator_admin->club->name }}</span> --}}
                        @endif
                    </div>
                </div>
                <div class="pt-3">
                    <div
                        class="font-weight-bold text-dark"
                        style="font-size: 18px;"
                    >{{ __('words.tour_prizes') }} :</div>
                    @if (!$cup?->isFinished)
                        <div class="px-4 pt-2">
                            <div>
                                {{ __('words.first_person') . ' : ' . $setting['tour_first_per'] . ' ' . __('words.percentage') }}
                            </div>
                            <div>
                                {{ __('words.second_person') . ' : ' . $setting['tour_second_per'] . ' ' . __('words.percentage') }}
                            </div>
                            <div>
                                {{ __('words.third_person') . ' : ' . $setting['tour_third_per'] . ' ' . __('words.percentage') }}
                            </div>
                            <div class="px-3 pt-1">{{ __('words.of_total_coins_collected') }}</div>
                        </div>
                    @else
                        {{-- TODO: calculate winer coin --}}
                        @php
                            $usersCount = $cup?->registeredUsers?->count();
                            $totalRegisteredCoin = $cup->register_cost_coin * $usersCount;
                            $totalRegisteredCoinPercent = $totalRegisteredCoin / 100;
                            $firstWinnerCoin = $totalRegisteredCoinPercent * $setting['tour_first_per'];
                            $secondWinnerCoin = $totalRegisteredCoinPercent * $setting['tour_second_per'];
                            $thirdWinnerCoin = $totalRegisteredCoinPercent * $setting['tour_third_per'];

                        @endphp
                        <div class="px-4 pt-2">
                            <div>{{ __('words.first_person') }} :
                                @if ($firstWinnerCoin)
                                    <img
                                        src="{{ asset('assets/img/coin.png') }}"
                                        height="20px"
                                    >
                                    {{ round($firstWinnerCoin) . ' ' . trans_choice('words.tour_coins', round($firstWinnerCoin)) }}
                                @else
                                    -
                                @endif
                            </div>
                            <div>{{ __('words.second_person') }} :
                                @if ($secondWinnerCoin)
                                    <img
                                        src="{{ asset('assets/img/coin.png') }}"
                                        height="20px"
                                    >
                                    {{ round($secondWinnerCoin) . ' ' . trans_choice('words.tour_coins', round($secondWinnerCoin)) }}
                                @else
                                    -
                                @endif
                            </div>
                            <div>{{ __('words.third_person') }} :
                                @if ($thirdWinnerCoin)
                                    <img
                                        src="{{ asset('assets/img/coin.png') }}"
                                        height="20px"
                                    >
                                    {{ round($thirdWinnerCoin) . ' ' . trans_choice('words.tour_coins', round($thirdWinnerCoin)) }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="tour-w50-box px-2">
                @if ($cup?->isFinished)
                    <div
                        class="font-weight-bold text-dark w-100 pt-3"
                        style="font-size: 18px;"
                    >{{ __('words.ranks_table') }} :</div>
                    <table class="table-striped mt-3 table">
                        <thead>
                            <tr>
                                <td class="alignment">{{ __('words.participant_name') }}</td>
                                <td>{{ __('words.Rank') }}</td>
                            </tr>
                        </thead>

                        @if ($cup->is_team)
                            @foreach ($winerTeams as $rank => $team)
                                @if ($team)
                                    <tr class="responsive-score">
                                        <td class="alignment">
                                            <a
                                                class="text-dark"
                                                href="{{ route('teams.show', $team->id) }}"
                                                title="{{ $team?->name }}"
                                            >
                                                <img
                                                    class="rounded-circle mx-1"
                                                    src="{{ $team->avatar }}"
                                                    height="30px"
                                                >
                                                {{ $team?->name }}
                                            </a>

                                        </td>
                                        <td>{{ trans_choice('words.tournament_score_place', $rank + 1) }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            @foreach ($winerUsers as $rank => $user)
                                @if ($user)
                                    <tr class="responsive-score">
                                        <td class="alignment">
                                            <a
                                                class="text-dark"
                                                href="{{ route('profile.show', $user->id) }}"
                                                title="{{ $user?->username }}"
                                            >
                                                <img
                                                    class="rounded-circle mx-1"
                                                    src="{{ $user->avatar }}"
                                                    height="30px"
                                                >
                                                {{ $user?->profile?->fullName }}
                                            </a>

                                        </td>
                                        <td>{{ trans_choice('words.tournament_score_place', $rank + 1) }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif

                    </table>
                @else
                    <div class="w-100">

                        <div
                            class="font-weight-bold text-dark"
                            style="font-size: 18px;"
                        >{{ __('words.description') }} :</div>
                        <div class="px-3 pt-2">
                            {!! $cup?->description !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($cup?->getMedia('images')?->count() > 0)
        <div class="w-100 mt-3 py-1">
            <div class="swiper-container gallery-slider container px-3 py-3">
                <div class="swiper-wrapper mb-3">
                    @foreach ($cup?->getMedia('images') ?? [] as $key => $image)
                        <div class="swiper-slide tour-slide">
                            <a
                                href="{{ $image->getFullUrl() }}"
                                target="_blank"
                            >
                                <img
                                    class="object-fit-contain rounded border"
                                    src="{{ $image->getFullUrl() }}"
                                    title="{{ $cup?->name }} {{ $key }}"
                                    alt="{{ $cup?->name }} {{ $key }}"
                                    width="200px"
                                    height="200px"
                                >
                            </a>
                        </div>
                    @endforeach
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination gallery-pagination"></div>
            </div>
        </div>
    @endif

    @if (!$cup->isRegisterable)
        <livewire:pages.cups.show-cup :id="$cup->id" />
    @endif

</div>

@push('styles')
    <link
        href="{{ asset('assets/css/swiper.min.css') }}"
        rel="stylesheet"
    />
    <style type="text/css">
        .alert {
            position: absolute;
            top: 110px;
            width: 100%;
        }

        @media screen and (min-width: 1000px) {
            .menu-dark-icons {
                display: none;
            }

            .menu-white-icons {
                display: block;
            }
        }

        @media screen and (max-width: 1000px) {
            .tour-bg-white-sm {
                background-color: #fff !important;
                color: #000 !important;
            }

            .btn-success-glass {
                background-color: #00873d !important;
                margin-top: 5px !important;
            }

            .btn-white-glass {
                background-color: #a0a0a0 !important;
                margin-top: 5px !important;
            }
        }

        #main {
            margin-top: 10px !important;
        }

        @media screen and (max-width: 1000px) {
            #main {
                margin-top: 80px !important;
            }
        }

        .tournament-top-div {
            height: 260px;
            background-color: rgba(0, 0, 0, 0.71);
            padding-top: 120px;
        }

        .tour-top-right-box {
            width: 70%;
        }

        .tour-top-left-box {
            width: 30%;
        }

        .tour-top-right-img {
            height: 90px;
            width: 90px;
        }

        .tour-top-div-info {
            width: 67%;
            font-size: 20px;
        }

        .tour-top-info-div {
            height: 100%;
            width: 75%;
        }

        .tour-w50-box {
            width: 50%;
        }

        .tour-top-info-div {
            height: 115px;
        }

        .tour-top-bg-size {
            background-size: 100% auto !important;
        }

        @media screen and (min-width: 1000px) {
            .tour-bracket-img {
                height: 600px;
            }
        }

        @media screen and (max-width: 1000px) {
            .tournament-top-div {
                height: 210px;
                padding-top: 45px;
            }

            .tour-top-right-box {
                width: 100%;
            }

            .tour-top-left-box {
                width: 100%;
                padding-top: 15px;
            }

            .tour-top-right-img {
                height: 60px;
                width: 60px
            }

            .tour-top-div-info {
                width: 67%;
                font-size: 20px;
            }

            .tour-top-info-div {
                height: 120px;
                width: 85%;
                border: solid 1px #ddd;
            }

            #tour-descript {
                margin-top: 100px !important;
            }

            .tour-w50-box {
                width: 100%;
                padding-top: 10px;
            }

            .tour-bracket-img {
                width: 100%;
            }

            .tour-top-bg-size {
                background-size: 100% 100% !important;
            }
        }
    </style>
@endpush
@push('scripts')
    <script src="/js/swiper.min.js"></script>
    <script type="text/javascript">
        $(document).scroll(function() {
            tournament_scrollfun();
        });

        function tournament_scrollfun() {
            if (screen.width > 1000) {
                if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                    $(".menu-white-icons").css("display", "none");
                    $(".menu-dark-icons").css("display", "block");
                } else {
                    $(".menu-dark-icons").css("display", "none");
                    $(".menu-white-icons").css("display", "block");
                }
            }
        }

        var swiper = new Swiper('.gallery-slider', {
            slidesPerView: 'auto',
            spaceBetween: 30,
            slidesPerGroup: 1,
            centeredSlides: true,
            loop: true,
            loopFillGroupWithBlank: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: true,
            },
            pagination: {
                el: '.gallery-pagination',
                clickable: true,
                dynamicBullets: true,
            },
        });

        var swiper = new Swiper('.bracket-slider', {
            slidesPerView: 'auto',
            spaceBetween: 30,
            loop: true,
            loopFillGroupWithBlank: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: true,
            },
            pagination: {
                el: '.bracket-pagination',
                clickable: true,
                dynamicBullets: true,
            },
        });
    </script>
@endpush
