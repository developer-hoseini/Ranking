    <!---------- Tournament Search ---------->

    <div
        class="position-relative container"
        style="bottom: 22px;z-index: 100;"
    >
        <div class="tour-srch d-flex flex-wrap bg-white shadow">
            <div class="tour-srch-btn"><span id="tour-srch-btn-spn">{{ __('words.search') }}</span><i
                    class="fa fa-search"></i></div>
            <div class="tour-srch-box">
                <form
                    method="GET"
                    {{-- action="{{ route('tournament.search') }}" --}}
                >
                    <div class="d-flex flex-wrap">
                        <div class="tour-srch-input-box">
                            <input
                                class="tour-srch-input"
                                name="name"
                                type="text"
                                placeholder="{{ __('words.tournament_title') }}"
                            >
                        </div>
                        <div class="tour-srch-input-box">
                            <select
                                class="tour-srch-input direction alignment"
                                name="game"
                            >
                                <option value="">{{ __('words.all_games') }}</option>
                                {{-- @foreach ($games as $game)
                                <option value="{{ $game->name }}">{{ __('games.' . $game->name) }}</option>
                            @endforeach --}}
                            </select>
                        </div>
                        <div class="tour-srch-input-box">
                            <select
                                class="tour-srch-input direction alignment"
                                name="country"
                            >
                                <option value="">{{ __('words.all_countries') }}</option>
                                {{-- @foreach ($countries as $country)
                                <option
                                    value="{{ $country->name }}"
                                    onclick="get_state({{ $country->id }})"
                                >
                                    @if (\Lang::has('country.' . $country->name))
                                        {{ __('country.' . $country->name) }}
                                    @else
                                        {{ $country->name }}
                                    @endif
                                </option>
                            @endforeach --}}
                            </select>
                        </div>
                        <div class="tour-srch-input-box">
                            <select
                                class="tour-srch-input direction alignment"
                                id="state-input"
                                name="state"
                            >
                                <option value="">{{ __('words.all_states') }}</option>
                            </select>
                        </div>
                        <button
                            class="btn tour-srch-submit py-2"
                            type="submit"
                        >{{ __('words.search') }}<i class="fa fa-search mx-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center">
            <h1
                class="pt-3"
                style="font-size: 28px !important;"
            >{{ __('words.ranking_tournaments') }}</h1>
        </div>
    </div>
