<div class="mx-auto mt-5">
    <div class="w-100 text-center"><h2 class="text-ranking font-weight-bold" style="font-size: 24px;">{{__('words.site_statistic')}}</h2></div>
    <div class="swiper-container pt-3 d-flex justify-content-center" id="statistic-slider">
        <div class="swiper-wrapper d-flex justify-content-center">
            <div class="swiper-slide statistic-swiper-slide">
                <div>
                    <div class="home-statistic-circle">{{ $statistics['users_count'] }}</div>
                    <div class="w-100 py-2 border-bottom">{{ __('words.Registered') }}</div>
                </div>
            </div>
            <div class="swiper-slide statistic-swiper-slide">
                <div>
                    <div class="home-statistic-circle">{{ $statistics['tournoments_count'] }}</div>
                    <div class="w-100 py-2 border-bottom">{{ __('words.matches') }}</div>
                </div>
            </div>
            <div class="swiper-slide statistic-swiper-slide">
                <div>
                    <div class="home-statistic-circle">{{ $statistics['games_count'] }}</div>
                    <div class="w-100 py-2 border-bottom">{{ __('words.Games played') }}</div>
                </div>
            </div>
            
            <div class="swiper-slide statistic-swiper-slide">
                <div>
                    <div class="home-statistic-circle">{{ $statistics['games_images_count'] }}</div>
                    <div class="w-100 py-2 border-bottom">{{ __('words.Games verified by image') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>