<div class="row mt-3">
    <div class="col-10 col-md-7 col-lg-5 mx-auto mt-3 rounded bg-white py-4 shadow">
        <div class="text-center">
            <h1 style="font-size: 22px;">{{ __('words.create_tournament') }}</h1>
        </div>

        <form
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            <div
                class="d-flex col-12 justify-content-between mx-auto py-3"
                {{-- class="row justify-content-around p-2 " --}}
            >
                <button
                    class="btn btn-outline-info col @if ($type == 'solo') active @endif col mr-2"
                    type="button"
                    wire:click='$set("type","solo")'
                >
                    {{ __('words.tournament_only') }}
                </button>
                <button
                    class="btn btn-outline-info col @if ($type == 'team') active @endif"
                    type="button"
                    wire:click='$set("type","team")'
                >
                    {{ __('words.tournament_team') }}
                </button>

            </div>
            <div class="row px-2 pt-1">
                <div class="col-sm-12 col-md-5 col-lg-4 mx-auto mt-1 px-1 py-1">
                    {{ __('words.sports_field') }} :
                </div>
                <select
                    class="form-control vodiapicker col-sm-12 col-md-6 col-lg-8 new_tournament_input @error('form.game_id') is-invalid  @enderror mx-auto ml-2 mt-1 py-1"
                    id="game_select"
                    type="text"
                    wire:model="form.game_id"
                >
                    <option value=""></option>
                    @foreach ($this->games as $game)
                        <option value="{{ $game->id }}">
                            {{ $game->name }}
                        </option>
                    @endforeach
                </select>
                @error('form.game_id')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror

            </div>

            <div class="row px-2 pt-1">
                <div class="col-sm-12 col-md-5 col-lg-4 mx-auto mt-1 px-1 py-1">
                    {{ __('words.tournament_title') }} :
                </div>
                <input
                    class="form-control col-sm-12 col-md-6 col-lg-8 new_tournament_input @error('form.name') is-invalid  @enderror mx-auto ml-2 mt-1 py-1"
                    type="text"
                    wire:model="form.name"
                    placeholder="{{ __('words.tournament_name') }}"
                    required
                >
                @error('form.name')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="row px-2 pt-1">
                <div class="col-sm-12 col-md-5 col-lg-4 mx-auto mt-1 px-1 py-1">
                    {{ __('words.tournament_entrance_coin') }} :
                </div>
                <input
                    class="form-control col-sm-12 col-md-6 col-lg-8 new_tournament_input @error('form.register_cost_coin') is-invalid  @enderror mx-auto ml-2 mt-1 py-1"
                    type="number"
                    wire:model="form.register_cost_coin"
                >
                @error('form.register_cost_coin')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div
                class="w-100 px-2 py-1"
                style="font-size: 14px;color: #568056;"
            >
                {{ __('words.tournament_quota_alert') }}
            </div>

            <div class="row px-2 pt-1">
                <div class="col-sm-12 col-md-5 col-lg-4 mx-auto mt-1 px-1 py-1">{{ __('words.tournament_capacity') }} :
                </div>
                <input
                    class="form-control col-sm-12 col-md-6 col-lg-8 new_tournament_input @error('form.capacity') is-invalid  @enderror mx-auto ml-2 mt-1 py-1"
                    type="text"
                    wire:model="form.capacity"
                >
                @error('form.capacity')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div
                class="w-100 px-2 py-1"
                style="font-size: 14px;color: #568056;"
            >
                {{ __('words.tournament_capacity_alert') }}
            </div>

            <div class="row px-2 pt-1">
                <div class="col-sm-12 col-md-5 col-lg-4 mx-auto mt-1 px-1 py-1">
                    {{ __('words.tournament_end') }} :
                </div>
                <input
                    class="form-control col-sm-12 col-md-6 col-lg-8 new_tournament_input @error('form.end_register_at') is-invalid  @enderror mx-auto ml-2 mt-1 py-1"
                    type="datetime-local"
                    wire:model="form.end_register_at"
                >
                @error('form.end_register_at')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="row px-2 pt-1">
                <div class="col-sm-12 col-md-5 col-lg-4 mx-auto mt-1 px-1 py-1">
                    {{ __('words.tournament_start') }} :
                </div>
                <input
                    class="form-control col-sm-12 col-md-6 col-lg-8 new_tournament_input @error('form.start_at') is-invalid  @enderror mx-auto ml-2 mt-1 py-1"
                    type="datetime-local"
                    wire:model="form.start_at"
                >
                @error('form.start_at')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="row px-2 pt-1">
                <div class="col-sm-12 col-md-5 col-lg-4 mx-auto mt-1 px-1 py-1">{{ __('words.Country') }} :</div>
                <select
                    class="form-control col-sm-12 col-md-6 col-lg-8 new_tournament_input @error('form.country_id') is-invalid  @enderror mx-auto ml-2 mt-1 py-1"
                    id="countries"
                    wire:model.live="form.country_id"
                >
                    <option value=""></option>
                    @foreach ($countries as $country)
                        <option value="{{ $country['id'] ?? '' }}">
                            {{ $country['name'] ?? '' }}
                        </option>
                    @endforeach
                </select>
                @error('form.country_id')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="row px-2 pt-1">
                <div class="col-sm-12 col-md-5 col-lg-4 mx-auto mt-1 px-1 py-1">{{ __('words.State') }} :</div>
                <select
                    class="form-control col-sm-12 col-md-6 col-lg-8 new_tournament_input @error('form.state_id') is-invalid  @enderror mx-auto ml-2 mt-1 py-1"
                    id="states"
                    wire:model="form.state_id"
                >
                    <option value=""></option>
                    @foreach ($states as $state)
                        <option value="{{ $state['id'] ?? '' }}">
                            {{ $state['name'] ?? '' }}
                        </option>
                    @endforeach
                </select>
                @error('form.state_id')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="row px-2 pt-1">
                <div class="col-sm-12 col-md-5 col-lg-4 mx-auto mt-1 px-1 py-1">
                    {{ __('words.tournament_description') }} :
                </div>
                <textarea
                    class="form-control col-sm-12 col-md-6 col-lg-8 new_tournament_input @error('form.description') is-invalid  @enderror mx-auto ml-2 mt-1 py-1"
                    style="min-height: 100px;"
                    wire:model.live="form.description"
                    placeholder="{{ __('words.tournament_description_example') }}"
                >
                </textarea>
                @error('form.description')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div
                class="row justify-content-center align-content-center mx-auto mt-3 gap-5"
                style="gap:1rem"
            >
                <a
                    class="btn btn-success"
                    href="#"
                    role="button"
                    wire:click='submitForm'
                >
                    {{ __('words.create_tournament') }}
                    <i class="fa fa-plus mx-2"></i>
                </a>
                <a
                    class="btn btn-light"
                    href="{{ route('tournaments.me.index') }}"
                    role="button"
                >
                    {{ __('words.back_to_my_tournament') }}
                </a>
            </div>
        </form>
    </div>
</div>
@push('styles')
    <link
        href="{{ url('css/kamadatepicker.css') }}"
        rel="stylesheet"
    >
    <style type="text/css">
        .new_tournament_input {
            background-color: #f5f5f5;
            border: solid 1px #eee;
            border-radius: 5px;
        }

        #bd-root-date1 {
            margin: 0 10px;
        }

        #bd-root-date2 {
            margin: 0 10px;
        }
    </style>
@endpush
