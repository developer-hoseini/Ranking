<div class="w-100">
    <table class="table-striped table-bordered table">
        <thead>
            <tr class="bg-info text-white">
                <td>{{ __('words.tournament_name') }}</td>
                <td>{{ __('words.sports_field') }}</td>
                <td>{{ __('words.competitors') }}</td>
                <td>{{ __('words.status') }}</td>
                <td><img
                        src="{{ asset('assets/img/white-ranking.png') }}"
                        height="18px"
                    ></td>
                <td><i class="fa fa-image"></i></td>
                <td><i class="fa fa-edit"></i></td>
                <td><i class="fa fa-flag-checkered"></i></td>
                <td><i class="fa fa-trash"></i></td>
            </tr>
        </thead>
        <tbody id="tour_created_html">
            @foreach ($this->tournaments as $tournament)
                <tr>
                    <td class="align-middle">
                        <a
                            class="text-info"
                            href="{{ route('tournaments.show', $tournament->id) }}"
                        >
                            {{ $tournament->name }}
                        </a>
                    </td>
                    <td class="align-middle">
                        {{ $tournament->game?->name }}
                    </td>
                    <td class="align-middle">
                        <p>
                            {{ $tournament->registered_users_count . ' ' . __('words.member') }}
                            <i class="fa fa-users mx-2"></i>
                        </p>
                    </td>
                    <td class="{{ $tournament->status?->colorClass }} align-middle">
                        {{ $tournament->status?->nameWithoutModelPrefix }}
                    </td>
                    @if ($tournament->isFinished)
                        @php
                            $finalUsers = $tournament->cupFirstAndSecondUsers;
                            $semiFinalUsers = $tournament->cupFirstAndSecondUsers;
                            $topPlayers = [...$finalUsers, ...$semiFinalUsers];
                        @endphp
                        <td class="align-middle">
                            <a
                                href="{{ route('cup.show', $tournament->id) }}"
                                target="_blank"
                            >
                                <img
                                    src="{{ asset('assets/img/tournament_icon.png') }}"
                                    width="18px"
                                >
                            </a>
                        </td>
                        <td class="alignment align-middle">
                            @foreach ($topPlayers as $key => $user)
                                <div class="py-1">
                                    <a
                                        href="{{ route('profile.show', $user->id) }}"
                                        target="_blank"
                                    >
                                        <img
                                            class="rounded-circle mx-2"
                                            src="{{ $user->avatar }}"
                                            width="28px"
                                        >
                                        {{ $user->profile?->avatar_name }}
                                        @php
                                            $number = $key + 1;
                                            $cupImageSrc = $key < 3 ? asset("assets/img/cup_$number.png") : null;
                                        @endphp
                                        @if ($cupImageSrc)
                                            <img
                                                class="mx-2"
                                                src="{{ $cupImageSrc }}"
                                                width="16px"
                                            >
                                        @endif

                                    </a>
                                </div>
                            @endforeach

                        </td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div
        class="d-none pb-3 text-center"
        id="tournaments_more"
    >
        <a
            class="text-info"
            href="#"
            onclick="tour_created_load();"
        >{{ __('words.tournaments_more') }}<i class="fa fa-arrow-circle-down mx-2"></i></a>
    </div>
    {{-- <div
        class="d-none mx-auto pb-4 pt-3 text-center"
        id="tournaments_null"
    >
        {{ __('words.you_dont_created_tour') }} ... <a
            href="{{ route('my_tournament.create') }}">{{ __('words.create_new_tournament') }}</a>
    </div> --}}
</div>
