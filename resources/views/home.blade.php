@extends('layouts.app')



@section('header')
    @parent
@endsection

@section('content')


    <!---------- Banners ---------->


    <div class="container p-3">
        @if($sliders->count())
            <div class="swiper-container" id="top-slider">
                <div class="swiper-wrapper">
                    @foreach($sliders as $slider)
                        <div class="swiper-slide top-swiper-slide">
                            @if($slider->link)
                                <a href="{{'http://'.$slider->link}}" target="_blank"><img src="{{$slider->image}}" class="top-slider-img"></a>
                            @else
                                <img src="{{$slider->image}}" class="top-slider-img">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>



@endsection