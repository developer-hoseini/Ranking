<div class="mt-2">
    <div class="rounded-1 bg-white p-2 border">
        <div class="border-bottom px-2 pb-1 d-flex flex-wrap"><a
                href="@auth {{ route('ranks') }} @else {{ route('global_ranks') }} @endauth" class="home-box-title-a"
                title="{{ __('words.ranks_table') }}">
                <h2 class="home-box-title-h2">{{ __('words.ranks_table') }}</h2>
            </a></div>
        <div class="swiper-container" id="ranks-slider">
            <div class="swiper-wrapper pt-2 pb-5">

                @foreach ($games as $game)
                    <div class="swiper-slide ranks-swiper-slide rounded">
                        <div class="w-100 shadow bg-white">
                            <div class="w-100 pt-2 border-ranking bg-light" style="border-bottom: solid 3px;">
                                <div class="home-ranks-title col-5">
                                    <a href="{{ route('game.show', ['game' => $game->id]) }}"
                                        class="text-white text-truncate" title="{{ $game->name }}">
                                        {{ $game->name }}
                                    </a>
                                </div>
                            </div>
                            <div class="border-bottom d-flex flex-wrap py-2 bg-light">
                                <div class="d-inline-block align-middle mx-auto" style="width: 7%;">
                                    {{ __('words.Rank') }}</div>
                                <div class="d-inline-block align-middle mx-auto" style="width: 55%;">
                                    {{ __('words.First Name') }}</div>
                                <div class="d-inline-block align-middle mx-auto" style="width: 15%;">
                                    {{ __('words.Score') }}</div>
                                <div class="d-inline-block align-middle mx-auto" style="width: 15%;">
                                    {{ __('words.Coin') }}</div>
                            </div>

                            @foreach ($game->gameCompetitionsUsers as  $row => $user)
                                <div class="border-bottom w-100 py-2" style="height: 70px">
                                    <div class="d-inline-block align-middle" style="width: 7%;">{{ $row + 1 }}
                                    </div>
                                    <div class="d-inline-block align-middle" style="width: 8%;">
                                        <img img src="{{ $user?->avatar }}" width="100%"
                                            title="{{ $user?->username }}" alt="{{ $user?->profile?->fullName }}"
                                            class="rounded-circle">
                                    </div>
                                    <div class="d-inline-block align-middle" style="width: 47%;">
                                        <a @if($user) href="{{ route('profile.show',['user'=>$user?->id]) }}" @endif
                                        title="{{$user?->profile?->fullName}}" class="text-dark">
                                            {{ $user?->profile?->fullName??$user?->username }}
                                        </a>
                                    </div>
                                    <div class="d-inline-block align-middle" style="width: 15%;">
                                        {{ $user?->score_achievements_sum_count }}</div>
                                    <div class="d-inline-block align-middle" style="width: 15%;"><img
                                            src="{{asset('assets/img/coin.png')}}" width="20px"
                                            alt="{{__('words.rezvani_coin')}}" title="{{__('words.rezvani_coin')}}">
                                        <div>{{ $user?->coin_achievements_sum_count }}</div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination" id="ranks-slider-pagination"></div>
        </div>

    </div>
</div>
