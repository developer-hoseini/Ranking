<!doctype html>
@php
    $app = config('app');
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ $app['name'] }} @yield('title')
    </title>
    <meta name="description" content="Ranking - Global center of players ranking. Record your results of plays. Register in tournaments, get coins and spend it!">
    <meta name="keywords" content="ranking, metaverse, meta sports, meta coin, games coin, sports token, blockchane, rank, tournament, tournament brackets">
    <link rel="shortcut icon" href="{{url('/img/favicon.png')}}" type="image/x-icon">

</head>
<body>

@section('header')

<div id="main">
    @yield('content')
</div>

@include('layouts.footer')

@stack('scripts')

</body>
</html>

