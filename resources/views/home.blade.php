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
                                <a href="{{$slider->href}}" target="_blank"><img src="{{ \Storage::url($slider->path) }}" class="top-slider-img"></a>
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
    <style type="text/css">
        .menu-nav-btns{position: relative;bottom: 150px;}
        .responsive-logo{opacity: 0;}
        #menu-nav-c-btn-1{position: relative;right: 102px;}
        #menu-nav-c-btn-2{position: relative;right: 55px;}
        .menu-nav-home-btn{position: relative;z-index: 999;}
        #menu-nav-c-btn-4{position: relative;left: 55px;}
        #menu-nav-c-btn-5{position: relative;left: 102px;}
    </style>
@endpush

@push('scripts')
    <script src="{{url('/assets/js/swiper.min.js')}}"></script>
    <script src="{{url('/assets/js/custom/home.js')}}"></script>
@endpush

@endsection