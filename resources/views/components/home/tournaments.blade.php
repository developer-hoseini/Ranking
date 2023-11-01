<div class="container d-flex flex-wrap">


    <!---------- Tournaments Box ---------->


    <div class="col-12 col-md-12 col-lg-6 px-0 px-lg-2 pt-2 mt-1">
        <div class="bg-white rounded-1 pt-2 px-2 direction alignment border">
            <div class="border-bottom px-2 pb-1 d-flex flex-wrap">
                <a href="{{route('tournament.index')}}" class="home-box-title-a" title="{{__('words.matches')}}"><h2
                        class="home-box-title-h2">{{__('words.matches')}}</h2></a>
            </div>
            <div class="swiper-container" id="tournament-slider">
                <div class="swiper-wrapper pt-2 pb-4" id="home-tournament-box">
                    @foreach($tournaments as $tournament)
                        <div class="swiper-slide tour-slide">
                            <a href="{{route('tournament.show',['competition'=>$tournament->id])}}"
                               title="{{$tournament->name}}" class="text-decoration-none" style="color: #6f6f6f;">
                                <div class="rounded-2 bg-white shadow tour-card pb-3">
                                    {{--                                    @if($tournament?->teams->count())--}}
                                    <img src="{{url($tournament->game->cover)}}" alt="{{$tournament->game->name}}"
                                         class="tour-card-img">
                                    <div class="position-relative d-flex flex-wrap" style="bottom: 10px;">
                                        <div class="bg-white py-1 px-2 rounded-pill text-center mx-auto">
                                            <img src="{{url($tournament->game->icon)}}"
                                                 alt="{{$tournament->game->name}}"
                                                 {{--                                                 title="{{__('games.'.$tournament->game->name)}}"--}}
                                                 title="{{$tournament->game->name}}"
                                                 width="20px"
                                                 height="20px" class="rounded-circle mx-1">
                                            {{--                                            {{__('games.'.$tournament->game->name)}}--}}
                                            {{$tournament->game->name}}
                                        </div>
                                    </div>
                                    {{--                                    @else--}}
                                    {{--                                        @php--}}
                                    {{--                                            $team = $tournament->teams->first();--}}
                                    {{--                                        @endphp--}}
                                    {{--                                        <img src="{{url($team->game->cover)}}"--}}
                                    {{--                                             alt="{{$team->game->name}}"--}}
                                    {{--                                             class="tour-card-img">--}}
                                    {{--                                        <div class="position-relative d-flex flex-wrap" style="bottom: 10px;">--}}
                                    {{--                                            <div class="bg-white py-1 px-2 rounded-pill text-center mx-auto">--}}
                                    {{--                                                <img src="{{url($team->icon)}}"--}}
                                    {{--                                                     alt="{{$team->name}}"--}}
                                    {{--                                                     title="{{__('games.'.$team->name)}}" width="20px"--}}
                                    {{--                                                     height="20px" class="rounded-circle mx-1">--}}
                                    {{--                                                {{__('games.'.$team->name)}}--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    @endif--}}
                                    <div class="text-center text-dark">
                                        {{mb_substr($tournament->name, 0 , 21 ,'UTF-8')}}
                                        @if($tournament->status->name == \App\Enums\StatusEnum::FINISHED->value)
                                            <i class="fa fa-flag-checkered mx-1" style="font-size: 14px;"
                                               title="{{__('words.finished')}}"></i>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-wrap mt-1" style="font-size: 14px;">
                                        <div class="w-50 text-center">
                                            <img
                                                src="{{url('assets/img/flags/'.$tournament->state->country->name.'.png')}}"
                                                height="20px" class="mx-1">
                                            @if( \Lang::has('state.'.$tournament->state->name) )
                                                {{mb_substr( __('state.'.$tournament->state->name) , 0 , 10 ,'UTF-8')}}
                                            @else
                                                {{mb_substr($tournament->state->name, 0 , 8 ,'UTF-8')}}
                                            @endif
                                        </div>
                                        <div class="w-50 text-center">
                                            {{trans_choice('words.tour_members' , $tournament->users->count() , [
    'member'=>$tournament->users->count()] )}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination" id="tournament-slider-pagination"></div>
            </div>
        </div>
    </div>


    <!---------- Tournaments Gallery ---------->


    <div class="col-12 col-md-12 col-lg-6 px-0 px-lg-2 pt-2 mt-1">
        <div class="bg-white rounded-1 pt-2 px-2 direction alignment border">
            <div class="border-bottom px-2"><h2 class="home-box-title-h2">{{__('words.tournaments_gallery')}}</h2></div>

            <div class="swiper-container" id="tournament-gallery-slider">
                <div class="swiper-wrapper pt-2 pb-4" id="home-gallery-box">
                    @foreach($tournamentImages as $tournamentImage)
                        <div class="swiper-slide tour-slide">
                            <a href="{{route('tournament.show',['competition' => $tournamentImage->id])}}">
                                <img src="{{url($tournamentImage->game->cover)}}" alt="{{$tournamentImage->name}}"
                                     title="{{$tournamentImage->name}}" height="140px">
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination" id="tournament-gallery-slider-pagination"></div>
            </div>

        </div>
    </div>
</div>
