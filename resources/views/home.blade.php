@extends('layouts.app')



@section('header')
    @parent
@endsection

@section('content')
    <h1 id="ranking_rezvani_game">{{ __('words.ranking_rezvani_game') }}</h1>

    <div class="container">

        <!---------- Banners ---------->
        <x-home.banners />


        <!---------- Ranks ---------->
        <x-home.ranks />


        <!---------- Tournaments ---------->
        <x-home.tournaments />


        <!---------- Tournament Brackets ---------->
        <x-home.tournament-brackets />


        <!---------- Games ---------->
        <div class="container d-flex flex-wrap">
            <x-home.latest-played />
            
            <x-home.latest-photos />
        </div>
        
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ url('/assets/css/swiper.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ url('/assets/js/swiper.min.js') }}"></script>
        <script src="{{ url('/assets/js/custom/home.js') }}"></script>
    @endpush
@endsection
