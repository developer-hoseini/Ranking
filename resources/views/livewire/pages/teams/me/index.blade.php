<div class="gamepage_center_box">

    <div class="card invite_box">
        <div class="card-header text-center">
            <h5>{{ __('words.My Teams') }}</h5>
        </div>
        <div class="card-body">
            <div class="pb-2 text-left">
                <a
                    class="btn btn-success text-white"
                    href="{{ route('teams.me.create') }}"
                >
                    {{ __('words.Create Team') }}
                </a>

                <button
                    class="btn btn-outline-info {{ $type == 'created' ? 'btn-info text-white' : '' }} px-lg-5 px-md-3 mx-1 px-2 py-2"
                    wire:click='$set("type","created")'
                >
                    {{ __('words.My Teams') }}

                </button>

                <button
                    class="btn btn-outline-info {{ $type == 'joined' ? 'btn-info text-white' : '' }} px-lg-5 px-md-3 mx-1 px-2 py-2"
                    wire:click='$set("type","joined")'
                >
                    {{ __('words.Registered_Teams') }}

                </button>
            </div>

            @if ($type == 'created')
                <livewire:pages.teams.me.index.created />
            @endif

            @if ($type == 'joined')
                <livewire:pages.teams.me.index.joined />
            @endif

        </div>
    </div>

</div>
