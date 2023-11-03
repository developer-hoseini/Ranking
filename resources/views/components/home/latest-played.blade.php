<div class="col-12 col-md-12 col-lg-6 px-0 px-lg-2 pt-2 mt-1">
    <div class="bg-white rounded-1 pt-2 px-2 direction alignment border">
        <div class="border-bottom px-2"><h2 class="home-box-title-h2">{{__('words.latest_played')}}</h2></div>
        <div class="swiper-container" id="games-slider">
            <div class="swiper-wrapper pt-2 pb-4" id="home-lastgame-box">
                @foreach ($competitions as $competition)
                   
                    <div class="swiper-slide tournament-swiper-slide text-center">
                        <div class="col-4 p-0">
                            <div>
                                <img src="{{ $competition?->loserUser?->avatar }}" alt="{{ $competition?->loserUser?->id }} {{ $competition?->loserUser?->name }}" style="max-width: 100px;" width="100%" class="rounded-circle">
                            </div>
                            <div><i class="fa fa-check-circle text-success" style="font-size: 22px;margin-top: 10px;"></i></div>
                            <div class="bg-success rounded-pill py-1 w-100">
                                <a href="{{ route('profile.show',$competition?->loserUser?->id ?? 0) }}" title="{{ $competition?->loserUser?->username }}" class="text-white">
                                    {{ $competition?->loserUser?->username }}
                                </a>
                            </div>
                        </div>
                        <div class="col-4 col-lg-3 p-0">
                            <a href="{{route('game.show',$competition?->game?->id ?? 0)}}" class="text-decoration-none">
                                <h4 class="text-dark">{{ __('games.'.$competition?->game?->name) }}</h4>
                            </a>
                        </div>
                        <div class="col-4 p-0">
                            <div>
                                <img src="{{ $competition?->winerUser?->avatar }}" alt="{{ $competition?->winerUser?->id }} {{ $competition?->winerUser?->name }}" style="max-width: 100px;" width="100%" class="rounded-circle">
                            </div>
                            <div><i class="fa fa-times-circle text-danger mx-2" style="font-size: 22px;margin-top: 10px;"></i></div>
                            <div class="bg-danger rounded-pill p-1 w-100">
                                <a href="{{ route('profile.show',$competition?->winerUser?->id ?? 0) }}" title="{{ $competition?->winerUser?->username }}" class="text-white">
                                    {{ $competition?->winerUser?->username }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination" id="games-slider-pagination"></div>
        </div>
    </div>
</div>
