<div
    class="card"
    style="width: 30rem;"
>
    <div class="card-body">
        <form>

            <div class="mb-3">
                <label
                    class="form-label"
                    for="wallet-address"
                >
                    Ranking Wallet Address
                </label>
                <input
                    class="form-control"
                    id="wallet-address"
                    value="000000000000L5SLmv7DivfNa"
                    aria-describedby="walletHelp"
                    disabled
                >
                <div
                    class="form-text"
                    id="walletHelp"
                >
                    Please deposit the desired amount into this wallet
                </div>
            </div>
            <div class="mb-3">
                <label
                    class="form-label"
                    for="count"
                >
                    Count
                </label>
                <input
                    class="form-control"
                    id="count"
                    type="number"
                    wire:model='form.count'
                >

                @error('form.count')
                    <div class="form-text text-danger">
                        {{ $message }}
                    </div>
                @enderror

            </div>
            <div class="mb-3">
                <label
                    class="form-label"
                    for="datetime"
                >
                    Time of deposit
                </label>
                <input
                    class="form-control"
                    id="datetime"
                    type="datetime-local"
                    aria-describedby="datetimeHelp"
                    wire:model='form.requested_at'
                >
                <div
                    class="form-text"
                    id="datetimeHelp"
                >
                    Please enter the date and time of deposit accurately
                </div>
                @error('form.requested_at')
                    <div class="form-text text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button
                class="btn btn-primary"
                type="button"
                wire:click='submitForm'
            >
                Submit
            </button>
        </form>
    </div>
</div>
