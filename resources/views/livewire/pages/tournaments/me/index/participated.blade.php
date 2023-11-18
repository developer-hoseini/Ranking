<div>
    <table class="table-striped table-bordered table">
        <thead>
            <tr class="bg-info text-white">
                <td>{{ __('words.tournament_name') }}</td>
                <td>{{ __('words.sports_field') }}</td>
                <td>{{ __('words.competitors') }}</td>
                <td>{{ __('words.status') }}</td>
                <td>{{ __('words.tournament_bracket') }}</td>
                <td>{{ __('words.Top Players') }}</td>
            </tr>
        </thead>
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
                <td class="{{ $tournament->cupStatus?->colorClass }} align-middle">
                    {{ $tournament->cupStatus?->nameWithoutModelPrefix }}
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

                    {{-- TODO: add team cups detail --}}
                    {{-- @if ($tournament->is_team == $status['No']) --}}
                    {{--  @else
                        <td class="align-middle">
                            <a
                                href="{{ route('team_bracket', ['bracket_id' => $tournament->final_bracket_id, 'tour_name' => $tournament->link_name, 'bracket_title' => $tournament->team_final_bracket->link_title]) }}"
                                target="_blank"
                            ><img
                                    src="{{ asset('img/tournament_icon.png') }}"
                                    width="18px"
                                ></a>
                        </td>
                        <td class="alignment align-middle">
                            <div class="py-1"><a
                                    href="{{ route('team_profile', ['teamname' => $tournament->team_final_bracket->final_winner->name]) }}"
                                    target="_blank"
                                ><img
                                        class="rounded-circle mx-2"
                                        src="{{ $tournament->team_final_bracket->final_winner->photo }}"
                                        width="28px"
                                    >{{ $tournament->team_final_bracket->final_winner->name }}<img
                                        class="mx-2"
                                        src="/img/cup_1.png"
                                        width="16px"
                                    ></a></div>
                            <div class="py-1"><a
                                    href="{{ route('team_profile', ['teamname' => $tournament->team_final_bracket->second_winner->name]) }}"
                                    target="_blank"
                                ><img
                                        class="rounded-circle mx-2"
                                        src="{{ $tournament->team_final_bracket->second_winner->photo }}"
                                        width="28px"
                                    >{{ $tournament->team_final_bracket->second_winner->name }}<img
                                        class="mx-2"
                                        src="/img/cup_2.png"
                                        width="16px"
                                    ></a></div>
                            <div class="py-1"><a
                                    href="{{ route('team_profile', ['teamname' => $tournament->team_final_bracket->third_winner->name]) }}"
                                    target="_blank"
                                ><img
                                        class="rounded-circle mx-2"
                                        src="{{ $tournament->team_final_bracket->third_winner->photo }}"
                                        width="28px"
                                    >{{ $tournament->team_final_bracket->third_winner->name }}<img
                                        class="mx-2"
                                        src="/img/cup_3.png"
                                        width="16px"
                                    ></a></div>
                        </td>
                    @endif --}}
                @else
                    <td></td>
                    <td></td>
                @endif
            </tr>
        @endforeach
    </table>
    <div class="pagination-links">
        {{ $this->tournaments->links() }}
    </div>
    {{-- @if ($tournaments->count() == 0)
        <div class="w-100 py-3 text-center">{{ __('words.you_dont_registered_in_tour') }}</div>
    @endif
    <div class="pb-3">{{ $tournaments->links() }}</div> --}}
</div>
