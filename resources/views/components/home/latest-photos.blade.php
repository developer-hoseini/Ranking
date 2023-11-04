
<div class="col-12 col-md-12 col-lg-6 px-0 px-lg-2 pt-2 mt-1">
    <div class="bg-white rounded-1 pt-2 px-2 direction alignment border">
        <div class="border-bottom px-2"><h2 class="home-box-title-h2">{{__('words.Latest Photos')}}</h2></div>
        <div class="swiper-container" id="games-gallery-slider">
            <div class="swiper-wrapper pt-2 pb-4" id="home-gamegallery-box">
                {{-- @foreach ($latest_photos as $invite)
                    @php
                        $date = date('Y/m', strtotime($invite->dt));
                        $show = $invite->game_result->selected_image==1 ? $invite->inviter_id : $invite->invited_id;
                    @endphp
                    <div class="swiper-slide tour-slide">
                        <img src="{{ url("/uploads/players/$date/{$invite->id}-{$show}.jpg") }}" class="rounded" alt="{{ $invite->game->name.'-'.$invite->id }}" height="130px">
                    </div>
                @endforeach --}}
            </div>
            <div class="swiper-pagination" id="games-gallery-pagination"></div>
        </div>

    </div>
</div>