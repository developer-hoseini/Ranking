<div class="col-12 col-md-12 col-lg-6 px-lg-2 mt-1 px-0 pt-2">
    <div class="rounded-1 direction alignment border bg-white px-2 pt-2">
        <div class="border-bottom px-2">
            <h2 class="home-box-title-h2">{{ __('words.Top Players Of Club') }}</h2>
        </div>
        <div
            class="d-flex flex-wrap text-center"
            id="home-topplayer-box"
        >
            @foreach ($topClubPlayers as $player)
                <div
                    class="mx-auto px-1 pt-3"
                    style="width: 30%;"
                >
                    <img
                        class="rounded-circle"
                        src="{{ $player?->avatar }}"
                        title="{{ $player?->avatarName }}"
                        alt="{{ $player?->avatarName . '-' . $player?->id }}"
                        width="80%"
                    >
                    <div
                        class="font-weight-bold py-2"
                        style="font-size: 14px;max-height: 40px;"
                    >
                        <a
                            class="text-dark"
                            href="{{ route('profile', ['username' => $player?->user?->username]) }}"
                            title="{{ $player?->avatarName }}"
                        >{{ $player?->avatarName }}</a>
                    </div>
                    <div class="bg-light text-ranking border-ranking home-top-player-font rounded border p-2">

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
