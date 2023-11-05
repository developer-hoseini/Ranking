<!---------- Top Slider ---------->

<div class="w-100 tour-top-bg">
    <div class="w-100 tournament-top-slider">
        <div class="swiper-container tour-top-slider font-weight-bold text-center text-white">
            <div class="swiper-wrapper">
                <div class="swiper-slide">{{ __('words.match_and_win') }}
                    <div><i class="fa fa-gift mx-2"></i>{{ __('words.take_prize') }} !<i class="fa fa-gift mx-2"></i>
                    </div>
                </div>
                <div class="swiper-slide">
                    {{ __('words.start_tour_earn_money') }}<i class="fa fa-coins mx-2"></i>
                    <div class="pt-1">
                        {{-- TODO: add create tournoment route --}}
                        <a
                            class="btn btn-white-glass"
                            {{-- href="{{ route('my_tournament.create') }}" --}}
                        >{{ __('words.create_your_tour_now') }}<i class="fa fa-arrow-left mx-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
