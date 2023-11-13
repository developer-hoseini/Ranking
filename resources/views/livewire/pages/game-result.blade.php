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

                            @endphp
                            <tr>
                                <td class="text-center">
                                    <a
                                        class="photo_fullname"
                                        href="{{ route('profile.show', $opponentUser?->id) }}"
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
                                <td class="{{ $result?->status?->colorClass }}">
                                    {{ $result?->status?->name }}
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
