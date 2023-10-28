<!doctype html>
@php
    $app = config('app');
    $locale = app()->getLocale();
@endphp
<html lang="{{ str_replace('_', '-', $locale) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ $app['name'] }} @yield('title')
    </title>
    <meta name="description"
          content="Ranking - Global center of players ranking. Record your results of plays. Register in tournaments, get coins and spend it!">
    <meta name="keywords"
          content="ranking, metaverse, meta sports, meta coin, games coin, sports token, blockchane, rank, tournament, tournament brackets">
    <link rel="shortcut icon" href="{{Vite::image('favicon.png')}}" type="image/x-icon">

    <link rel="stylesheet" href="{{url('/assets/css/sweetalert2.min.css')}}">
    <script type="text/javascript" src="{{url('/assets/js/sweetalert2.min.js')}}"></script>
    <script src="{{url('/assets/js/app.js')}}" defer></script>
    <script src="{{url('/assets/js/jquery.min.js')}}"></script>
    <script src="{{url('/assets/js/slick.min.js')}}" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="{{url('/assets/js/bootstrap.js')}}"></script>
    <link rel="stylesheet" href="{{url('/assets/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{url('/assets/css/fontawesome.css')}}">
    <link rel="stylesheet" href="{{url('/assets/css/solid.css')}}">
    <link rel="stylesheet" href="{{url('/assets/css/slick.css')}}">
    <link rel="stylesheet" href="{{url('/assets/css/slick-theme.css')}}">
    <link rel="stylesheet" href="{{url('/assets/css/app.css')}}">

    @stack('styles')

</head>
<body>

@include('layouts.header')

@section('header')

    <div id="main">
        @yield('content')
    </div>

    @include('layouts.footer')

    @stack('scripts')
@endsection

</body>
</html>

