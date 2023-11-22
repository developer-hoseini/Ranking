<div class="card ranks_content">
    <div class="card-body">
        <section class="ranks_table">
            <div class="card text-center">
                <div class="card-body">
                    <table
                        class="table-striped quick_list_table table"
                        id="app"
                    >
                        <thead>
                        <tr>
                            <th scope="col">{{ __('words.opponent') }}</th>
                            <th scope="col">{{ __('words.Game') }}</th>
                            <th scope="col">{{ __('words.result') }}</th>
                            <th scope="col">{{ __('words.status') }}</th>
                        </tr>
                        </thead>

                        @foreach ($this->gameResults as $result)
                            @php
                                $competition = $result?->gameresultable;
                                $game = $competition?->game;
                                $opponentUser = $competition?->opponentUsers?->first();
                                $canChangeStatus = \Auth::id() != $competition->created_by_user_id && $result?->gameResultAdminStatus?->name != \App\Enums\StatusEnum::ACCEPTED->value;

                            @endphp
                            <tr>
                                <td class="text-center">
                                    <a
                                        class="photo_fullname"
                                        href="{{ route('profile.show', ['user'=>$opponentUser?->id]) }}"
                                    >
                                        <img
                                            class="user_photo"
                                            src="{{ $opponentUser?->avatar }}"
                                            width="40"
                                        >
                                        <div class="bottom_fullname">{{ $opponentUser?->profile?->avatar_name }}</div>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('games.show', $game->id) }}">
                                        {{ $game->name }}
                                    </a>

                                    @if ($competition->image)
                                        <br><br>
                                        <div>
                                            <img
                                                class="zoomify-photo"
                                                src="{{ $competition->image }}"
                                                width="40"
                                            >
                                        </div>
                                    @endif
                                </td>
                                <td class="{{ $result?->gameResultStatus?->colorClass }}">
                                    {{ $result?->gameResultStatus?->nameWithoutModelPrefix }}
                                </td>
                                <td>

                                    <div class="row justify-content-center gap-5">
                                        <span class="{{ $result?->gameResultUserStatus?->colorClass }} mr-2">
                                            {{ $result?->gameResultUserStatus?->name }}
                                        </span>

                                        @if ($canChangeStatus && $result?->gameResultUserStatus?->name == \App\Enums\StatusEnum::PENDING->value)
                                            <div
                                                class="cursor-pointer"
                                                title="accept"
                                                x-on:click="$dispatch('change-status',{resultId:'{{ $result['id'] }}', type:'accept'})"
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
                                                x-on:click="$dispatch('change-status',{resultId:'{{ $result['id'] }}' ,type:'reject'})"
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
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </section>

        <div class="pagination-links">
            {{ $this->gameResults->links() }}
        </div>

    </div>
</div>
