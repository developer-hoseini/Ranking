<div class="container pt-3">
    <div class="text-center">
        <h2 class="mt-3 font-weight-bold" style="font-size: 20px;color: #ff4800;">
            <img src="{{asset('assets/img/tournament_icon.png')}}" width="20px">
            {{__('words.tournaments_bracket')}} :
        </h2>
    </div>
    <div class="swiper-container" id="tournament-bracket-slider">
        <div class="swiper-wrapper">
            @foreach($tournamentsBracket as $tournament)
                {{--                TODO:refactor bracket--}}
                {{--@if($tournament->latest_bracket)
                    @php $route = 'bracket'; $bracket = 'latest_bracket'; @endphp
                @elseif($tournament->latest_team_bracket)
                    @php $route = 'team_bracket'; $bracket = 'latest_team_bracket'; @endphp
                @endif
                <div class="swiper-slide tournament-bracket-swiper-slide">
                    <a href="{{route($route , ['bracket_id' => $tournament->$bracket->id, 'tour_name' => $tournament->link_name, 'bracket_title' => $tournament->$bracket->link_title])}}"
                       title="{{$tournament->$bracket->title.' - '.$tournament->name}}" class="text-ranking">
                        <div
                            class="pt-2 font-weight-bold">{{$tournament->$bracket->title.' - '.$tournament->name}}</div>
                        <img src="{{$tournament->$bracket->image}}"
                             alt="{{$tournament->$bracket->title.' - '.$tournament->name}}"
                             title="{{$tournament->$bracket->title.' - '.$tournament->name}}" class="home-bracket-img">
                    </a>
                </div>--}}
            @endforeach
        </div>
        <div class="swiper-pagination" id="tournament-bracket-pagination"></div>
    </div>
</div>
