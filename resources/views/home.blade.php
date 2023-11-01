@extends('layouts.app')



@section('header')
    @parent
@endsection

@section('content')

    <h1 id="ranking_rezvani_game">{{__('words.ranking_rezvani_game')}}</h1>

    <!---------- Banners ---------->
    <x-home.banners/>


    <!---------- Ranks ---------->
    <x-home.ranks/>


    <!---------- Tournaments ---------->
    <x-home.tournaments/>

    <!---------- Tournament Brackets ---------->
    <x-home.tournament-brackets/>



    @push('styles')
        <link rel="stylesheet" href="{{url('/assets/css/swiper.min.css')}}">
    @endpush

    @push('scripts')
        <script src="{{url('/assets/js/swiper.min.js')}}"></script>
        <script src="{{url('/assets/js/custom/home.js')}}"></script>
    @endpush

@endsection
