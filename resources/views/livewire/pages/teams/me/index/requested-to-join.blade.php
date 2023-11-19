<div class="my_teams">
    @foreach ($this->invitedToTeam as $invite)
        @php
            $team = $invite->inviteable;
        @endphp
        <div class="team d-flex">
            <div class="team_name text-center">
                <a
                    href="{{ route('teams.show', $team->id) }}"
                    target="_blank"
                >
                    <img
                        class="user_photo"
                        src="{{ $team->avatar }}"
                        width="100"
                    >
                    <div>{{ $team->name }}</div>
                </a>
                <div>
                    <span>
                        ({{ $team?->game?->name }})
                    </span>
                </div>
            </div>

            <div class="team_details">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>{{ __('words.Score') }}</th>
                            <th>{{ __('words.Rank') }}</th>
                            <th>status</th>
                            <th>confirm</th>
                        </tr>
                        <tr>
                            <td>
                                {{ $team?->team_score_achievements_sum_count }}
                            </td>
                            <td>
                                {{ $team?->team_rank }}
                            </td>
                            <td>
                                <span class="{{ $invite->confirmStatus?->colorClass }}">
                                    {{ $invite->confirmStatus?->nameWithoutModelPrefix }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $isPending = $invite->confirmStatus?->name == \App\Enums\StatusEnum::PENDING->value;
                                @endphp
                                @if ($isPending)
                                    <div class="d-flex">
                                        <div
                                            class="cursor-pointer"
                                            title="accept"
                                            x-on:click="$dispatch('change-status',{inviteId:'{{ $invite['id'] }}', type:'accept'})"
                                        >
                                            <svg
                                                style="color: green"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                width="25px"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </div>
                                        <div
                                            class="cursor-pointer"
                                            title="reject"
                                            x-on:click="$dispatch('change-status',{inviteId:'{{ $invite['id'] }}' ,type:'reject'})"
                                        >
                                            <svg
                                                style="color: red"
                                                width="25px"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>

                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <div>
        {{ $this->invitedToTeam->links() }}
    </div>

</div>
