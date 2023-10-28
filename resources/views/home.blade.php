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
                            @if($slider->href)
                                <a href="{{$slider->href}}" target="_blank"><img
                                        src="{{ \Storage::url($slider->path) }}" class="top-slider-img"></a>
                            @else
                                <img src="{{ \Storage::url($slider->path) }}" class="top-slider-img">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>



    @push('styles')
        <link rel="stylesheet" href="{{url('/assets/css/swiper.min.css')}}">
    @endpush

    @push('scripts')
        <script src="{{url('/assets/js/swiper.min.js')}}"></script>
        <script src="{{url('/assets/js/custom/home.js')}}"></script>
    @endpush

@endsection
