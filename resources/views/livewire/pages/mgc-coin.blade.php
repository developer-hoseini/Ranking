<div class="container">
    <div class="row justify-content-center text-center">
        <div class="btn-group align-self-center mb-5">
            <button
                class="col-4 btn btn-secondary btn-won {{ $type == 'buy' ? 'active' : '' }} mb-1 mr-1 mt-2"
                wire:click="$set('type','buy')"
                @if ($type == 'buy') disabled @else style="cursor: pointer" @endif
            >
                Buy MGC Coin
            </button>
            <button
                class="col-4 btn btn-secondary btn-won {{ $type == 'sell' ? 'active' : '' }} mb-1 mr-1 mt-2"
                wire:click="$set('type','sell')"
                @if ($type == 'sell') disabled @else style="cursor: pointer" @endif
            >
                Sell MGC Coin
            </button>

        </div>

    </div>

    <div class="row justify-content-center">
        @if ($type == 'buy')
            <livewire:pages.mgc-coin.buy>
        @endif

        @if ($type == 'sell')
            <livewire:pages.mgc-coin.sell>
        @endif
    </div>

</div>
