@php
    $sumCoinAchievements = auth()->user()->sumCoinAchievements;
@endphp

<div
    class="card"
    style="width: 30rem;"
>
    <div class="card-body">

        <div class="mb-3">
            <span>You have </span>
            <img
                src="{{ asset('assets/img/coin.png') }}"
                alt="coin"
                width="25px"
                height="25px"
            >
            <span class="font-weight-bold">{{ $sumCoinAchievements }}</span>
            <span>MGC coin.</span>
        </div>
        <form>
            <div class="mb-3">
                <label
                    class="form-label"
                    for="wallet-address"
                >
                    Your Wallet Address
                </label>
                <input
                    class="form-control"
                    id="wallet-address"
                    aria-describedby="walletHelp"
                    wire:model='form.wallet_address'
                >
                <div
                    class="form-text text-danger"
                    id="walletHelp"
                >
                    <p>
                        Please be careful when entering the wallet address, if a mistake is entered, the amount of coins
                        is
                        non-refundable
                    </p>
                </div>

                @error('form.wallet_address')
                    <div class="form-text text-danger">
                        {{ $message }}
                    </div>
                @enderror
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

            <button
                class="btn btn-primary"
                type="button"
                wire:click='submitForm'
                @if ($sumCoinAchievements == 0) disabled @endif
            >
                Submit
            </button>
        </form>
    </div>
</div>
