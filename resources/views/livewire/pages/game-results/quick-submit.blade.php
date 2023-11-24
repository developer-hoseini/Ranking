<div class="quick_submit_result container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h2> {{ __('words.quick_submit_result') }}</h2>
                </div>

                <div class="card-body">
                    <form>
                        @csrf

                        <div class="form-group row">
                            <label
                                class="col-md-3 col-form-label"
                                for="opponent"
                            >{{ __('words.opponent') }}</label>

                            <div
                                class="col-md-7"
                                wire:ignore.self
                                x-data="{ show: {{ $searchUser != '' ? 'true' : 'false' }} }"
                            >

                                <input
                                    class="form-control @error('form.user_id') is-invalid  @enderror"
                                    type="text"
                                    placeholder="search user ..."
                                    wire:model.live.debounce.250ms='searchUser'
                                    x-on:input="show=true"
                                >
                                @error('form.user_id')
                                    <div class="text-danger">
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror

                                <ul
                                    class="position-absolute w-100 form-select overflow-auto border"
                                    style="z-index: 10; background: white; max-height: 150px; max-width: 92%; padding-top: 10px; padding-bottom: 10px;
                                    "
                                    x-show="show"
                                >
                                    <li wire:loading>
                                        loading...
                                    </li>
                                    @forelse($users as $key => $user)
                                        @php
                                            $avatarName = isset($user['profile']['avatar_name']) ? $user['profile']['avatar_name'] : '';
                                        @endphp

                                        <li
                                            style="cursor: pointer"
                                            wire:loading.remove
                                            x-on:click="show=false;$dispatch('user-selected',{userId:'{{ $user['id'] }}',avatarName:'{{ $avatarName }}'})"
                                        >
                                            {{ $avatarName }}
                                        </li>
                                        <br>
                                    @empty
                                        <li wire:loading.remove>not found.</li>
                                    @endforelse
                                </ul>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-md-3 col-form-label"
                                for="game"
                            >{{ __('words.Game') }}</label>

                            <div class="col-md-7">
                                <select
                                    class="form-control @error('form.user_id') is-invalid  @enderror"
                                    id="game"
                                    name="game"
                                    wire:model='form.game_id'
                                >
                                    <option value="">{{ __('words.select_game...') }}</option>
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
                        </div>

                        <div
                            class="form-group row"
                            id="app2"
                        >
                            <label
                                class="col-md-3 col-form-label"
                                for="image"
                            >{{ __('words.image') }}</label>

                            <div class="col-md-7">
                                <div class="upload-image mt-2">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span
                                                class="input-group-text"
                                                id="inputGroupFileAddon01"
                                            >
                                                <i class="fas fa-image"></i>
                                            </span>
                                        </div>
                                        <input
                                            class="@error('form.image') is-invalid  @enderror"
                                            type="file"
                                            placeholder="Game Picture"
                                            wire:model='form.image'
                                        />
                                        @error('form.image')
                                            <div class="text-danger">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror

                                        @if (isset($form['image']))
                                            <img
                                                src="{{ $form['image']?->temporaryUrl() }}"
                                                width="150"
                                                height="150"
                                            >
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row result_quick">
                            <label
                                class="col-md-3 col-form-label"
                                for="result"
                            >{{ __('words.result') }}</label>

                            <div
                                class="col-md-7"
                                x-data="{ selected: '' }"
                            >
                                <div class="btn-group btn-group-toggle">
                                    <label
                                        class="btn btn-secondary btn-won mb-1 mr-1 mt-2"
                                        :class="selected == 'win' ? 'active' : ''"
                                    >
                                        <input
                                            class="@error('form.image') is-invalid  @enderror"
                                            type="radio"
                                            value="win"
                                            wire:model='form.result'
                                            x-on:click="selected='win'"
                                        > {{ __('words.I Won') }}
                                    </label>
                                    <label
                                        class="btn btn-secondary btn-lost mb-1 mr-1 mt-2"
                                        :class="selected == 'lost' ? 'active' : ''"
                                    >
                                        <input
                                            class="@error('form.image') is-invalid  @enderror"
                                            type="radio"
                                            value="lost"
                                            wire:model='form.result'
                                            x-on:click="selected='lost'"
                                        > {{ __('words.I Lost') }}
                                    </label>

                                    @error('form.result')
                                        <div class="text-danger">
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label
                                class="col-md-3 col-form-label"
                                for="agent_user_id"
                            >
                                Agent
                            </label>

                            <div class="col-md-7">
                                <select
                                    class="form-control"
                                    id="agent_user_id"
                                    wire:model='form.agent_user_id'
                                >
                                    <option value="">select agent</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent['id'] ?? '' }}">
                                            {{ $agent['username'] ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('form.agent_user_id')
                                    <div class="text-danger">
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                                <span style="font-size: 0.75rem">
                                    if you know which agent must confirm this competition, please fill this
                                    selectbox, otherwise skip that.
                                </span>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button
                                    class="btn btn-success"
                                    type="button"
                                    wire:click='submitForm'
                                >
                                    <i class="fa fa-check"></i>
                                    {{ __('words.submit_result') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
