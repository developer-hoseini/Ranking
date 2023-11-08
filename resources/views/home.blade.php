@extends('layouts.app')



@section('header')
    @parent
@endsection

@section('content')
    <h1 id="ranking_rezvani_game">{{ __('words.ranking_rezvani_game') }}</h1>

    <div class="container">

        <!---------- Banners ---------->
        <x-home.banners/>


        <!---------- Ranks ---------->
        <x-home.ranks/>


        <!---------- Tournaments ---------->
        <x-home.tournaments/>


        <!---------- Tournament Brackets ---------->
        <x-home.tournament-brackets/>


        <!---------- Games ---------->
        <div class="container d-flex flex-wrap">

            <x-home.latest-played/>
            <x-home.latest-photos/>

        </div>

        <!---------- Team Ranks && Top Club Players ---------->
        <div class="container d-flex flex-wrap">

            <x-home.top-teams/>
            <x-home.top-club-players/>

        </div>

        <x-home.statistics/>


    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('/assets/css/swiper.min.css') }}">

        {{-- transition menus --}}
        <style type="text/css">
            .menu-nav-btns {
                position: relative;
                bottom: 150px;
            }

            #menu-nav-c-btn-1 {
                position: relative;
                right: 102px;
            }

            #menu-nav-c-btn-2 {
                position: relative;
                right: 55px;
            }

            .menu-nav-home-btn {
                position: relative;
                z-index: 999;
            }

            #menu-nav-c-btn-4 {
                position: relative;
                left: 0px;
            }

            #menu-nav-c-btn-5 {
                position: relative;
                left: 102px;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ url('/assets/js/swiper.min.js') }}"></script>
        <script src="{{ url('/assets/js/custom/home.js') }}"></script>
    @endpush
@endsection
