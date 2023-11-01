<div class="container p-3">
    @if($sliders->count())
        <div class="swiper-container" id="top-slider">
            <div class="swiper-wrapper">
                @foreach($sliders as $slider)
                    <div class="swiper-slide top-swiper-slide">
                        @if($slider->href)
                            <a href="{{$slider->href}}" target="_blank"><img
                                    src="{{ url($slider->path) }}" class="top-slider-img"></a>
                        @else
                            <img src="{{ url($slider->path) }}" class="top-slider-img">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
