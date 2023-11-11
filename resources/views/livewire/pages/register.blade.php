<div class="container">
    <div
        class="card mx-auto p-3"
        style="width: 25rem; margin-top: 8rem"
    >
        <h1>Register</h1>
        <div class="card-body">
            <form>
                <div class="form-group">
                    <label for="avatar_name">Avatar name</label>
                    <input
                        class="form-control @error('form.avatar_name') is-invalid @enderror"
                        id="avatar_name"
                        placeholder="Enter avatar name"
                        wire:model='form.avatar_name'
                    >
                    @error('form.avatar_name')
                        <div class="text-danger">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input
                        class="form-control @error('form.email') is-invalid @enderror"
                        id="email"
                        type="email"
                        aria-describedby="emailHelp"
                        placeholder="Enter email"
                        wire:model='form.email'
                    >
                    @error('form.email')
                        <div class="text-danger">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        class="form-control @error('form.password') is-invalid @enderror"
                        id="password"
                        type="password"
                        placeholder="Password"
                        wire:model='form.password'
                    >
                    @error('form.password')
                        <div class="text-danger">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                {{-- <div class="form-group">
                    <label for="country_id">Country</label>

                    <select
                        class="form-control @error('form.country_id') is-invalid @enderror"
                        id="country_id"
                        wire:model.live='form.country_id'
                    >
                        <option selected>please choose your country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country['id'] }}">
                                {{ $country['name'] }}
                            </option>
                        @endforeach

                    </select>
                    @error('form.country_id')
                        <div class="text-danger">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="state_id">State</label>

                    <select
                        class="form-control @error('form.state_id') is-invalid @enderror"
                        id="state_id"
                        wire:model='form.state_id'
                    >
                        <option selected>please choose your country</option>
                        @foreach ($states as $state)
                            <option value="{{ $state['id'] }}">
                                {{ $state['name'] }}
                            </option>
                        @endforeach

                    </select>
                    @error('form.state_id')
                        <div class="text-danger">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div> --}}

                <div class="d-flex mt-4 justify-items-center">
                    <button
                        class="btn btn-danger mr-1"
                        type="button"
                        wire:click='submitForm'
                        wire:loading.attr='disabled'
                    >
                        Register
                    </button>
                    <x-loading.spiner
                        wire:loading
                        wire:target="submitForm"
                    />
                </div>
            </form>
        </div>
    </div>
</div>
