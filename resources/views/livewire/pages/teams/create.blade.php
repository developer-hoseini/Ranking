<div class="editprofile mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h2> {{ __('words.Create Team') }}</h2>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="formSubmit">
                        @csrf

                        <div class="form-group row">
                            <label
                                class="col-md-4 col-form-label"
                                for="game"
                            >{{ __('words.Game') }}</label>

                            <div class="col-md-6">
                                <select
                                    class="form-control @error('form.game_id') is-invalid  @enderror"
                                    id="game"
                                    wire:model="form.game_id"
                                    required
                                >
                                    <option value="">{{ __('words.Select Game...') }}</option>
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

                        <div class="form-group row">
                            <label
                                class="col-md-4 col-form-label"
                                for="team_name"
                            >{{ __('words.Team Name') }}</label>

                            <div class="col-md-6">
                                <input
                                    class="form-control @error('form.name') is-invalid  @enderror"
                                    id="team_name"
                                    type="text"
                                    wire:model="form.name"
                                    required
                                >
                                @error('form.name')
                                    <div class="text-danger">
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-md-4 col-form-label"
                                for="about"
                            >{{ __('words.About_Team') }}</label>

                            <div class="col-md-6">
                                <textarea
                                    class="form-control @error('form.about') is-invalid  @enderror"
                                    id="about"
                                    type="text"
                                    style="height: 110px;"
                                    wire:model="form.about"
                                    maxlength="200"
                                >
                                </textarea>
                                @error('form.about')
                                    <div class="text-danger">
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-md-4 col-form-label"
                                for="avatar"
                            >Choose a team picture:</label>
                            <div class="col-md-6">
                                <input
                                    class="@error('form.avatar') is-invalid  @enderror"
                                    id="avatar"
                                    type="file"
                                    wire:model='form.avatar'
                                    accept="image/png, image/jpeg"
                                >
                                @error('form.avatar')
                                    <div class="text-danger">
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror

                                <div>
                                    @if (isset($form['avatar']))
                                        <img
                                            src="{{ $form['avatar']?->temporaryUrl() }}"
                                            width="150"
                                            height="150"
                                        >
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="form-group row mt-5">
                            <div class="col-md-6 offset-md-4">
                                <button
                                    class="btn btn-danger"
                                    type="button"
                                    wire:click='formSubmit'
                                    x-data="{
                                        isUploadingFile: false
                                    }"
                                    x-on:livewire-upload-start="isUploadingFile = true"
                                    x-on:livewire-upload-finish="isUploadingFile = false"
                                    x-on:livewire-upload-error="isUploadingFile = false"
                                    x-bind:class="{ 'enabled:opacity-70 enabled:cursor-wait': isUploadingFile }"
                                    wire:loading.attr="disabled"
                                    x-bind:disabled="isUploadingFile"
                                >

                                    <div
                                        class="spinner-border"
                                        role="status"
                                        x-show="isUploadingFile"
                                    >
                                    </div>
                                    <span x-show="! isUploadingFile">
                                        {{ __('words.Create Team') }}
                                    </span>
                                    <span
                                        style="display: none;"
                                        x-show="isUploadingFile"
                                    >
                                        Uploading file...
                                    </span>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
