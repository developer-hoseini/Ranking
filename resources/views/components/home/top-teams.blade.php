
<div class="col-12 col-md-12 col-lg-6 px-0 px-lg-2 pt-2 mt-1">
    <div class="bg-white rounded-1 pt-2 px-2 direction alignment border">
        <div class="border-bottom px-2 pb-1 d-flex flex-wrap">
            <p class="home-box-title-a" title="{{__('words.Top Teams')}}"><h2 class="home-box-title-h2">{{__('words.Top Teams')}}</h2></p>
        </div>
        <div class="swiper-container" id="team-ranks-slider">
            <div class="swiper-wrapper pt-2 pb-4" id="home-teamrank-box">
                @foreach ($games as $game)
                    <div class="swiper-slide ranks-swiper-slide text-center">
                        <div class="w-100 shadow rounded">
                            <div class="py-2 rounded-top" style="background-color: #f2f2f2;">
                                <a href="{{route('games',['id'=>$game->id])}}" class="text-info font-weight-bold">{{ $game->name }}</a>
                            </div>
                            
                            @foreach ($game->gameCompetitionsTeams as $team)
                                <div class="py-2 border-bottom">
                                    <div class="d-inline-block align-middle" style="width: 20%;">
                                        <img src="{{ $team->avatar }}" class="rounded-circle" width="100%" alt="{{ $team->name }}" title="{{ $team->name }}">
                                    </div>
                                    <div class="d-inline-block align-middle text-truncate" style="width: 65%;">
                                        <a href="{{ route('teams.show',$team->id) }}" title="{{ $team->name }}" class="text-dark">
                                            {{ $team->name }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination" id="team-ranks-pagination"></div>
        </div>
    </div>
</div>
