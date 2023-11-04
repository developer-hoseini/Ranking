
<div class="col-12 col-md-12 col-lg-6 px-0 px-lg-2 pt-2 mt-1">
    <div class="bg-white rounded-1 pt-2 px-2 direction alignment border">
        <div class="border-bottom px-2"><h2 class="home-box-title-h2">{{__('words.Top Players Of Club')}}</h2></div>
        <div class="text-center d-flex flex-wrap" id="home-topplayer-box">
            @foreach ($topClubPlayers as $player)
                <div class="px-1 pt-3 mx-auto" style="width: 30%;">
                    <img src="{{ $player?->avatar }}" alt="{{ $player?->username.'-'.$player?->id }}" title="{{$player?->username}}" width="80%" class="rounded-circle">
                    <div class="font-weight-bold py-2" style="font-size: 14px;max-height: 40px;">
                        <a href="{{route('profile',['username'=>$player?->user?->username])}}" class="text-dark" title="{{ $player?->profile?->fullname }}">{{ $player?->profile?->fullname }}</a>
                    </div>
                    <div class="bg-light border text-ranking border-ranking rounded p-2 home-top-player-font">
                        
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>