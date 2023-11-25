<!doctype html>
@php
    $app = config('app');
    $locale = app()->getLocale();
@endphp
<html lang="{{ str_replace('_', '-', $locale) }}">

    <head>
        <meta charset="utf-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="csrf-token"
            content="{{ csrf_token() }}"
        >
        <title>
            {{ $app['name'] }} @yield('title')
        </title>
        <meta
            name="description"
            content="Ranking - Global center of players ranking. Record your results of plays. Register in tournaments, get coins and spend it!"
        >
        <meta
            name="keywords"
            content="ranking, metaverse, meta sports, meta coin, games coin, sports token, blockchane, rank, tournament, tournament brackets"
        >
        {{-- TODO: remove noindex after release --}}
        <meta
            name="robots"
            content="noindex"
        >
        <link
            type="image/x-icon"
            href="{{ Vite::image('favicon.png') }}"
            rel="shortcut icon"
        >

        <link
            href="{{ url('/assets/css/sweetalert2.min.css') }}"
            rel="stylesheet"
        >
        <script
            type="text/javascript"
            src="{{ url('/assets/js/sweetalert2.min.js') }}"
        ></script>
        <script
            src="{{ url('/assets/js/app.js') }}"
            defer
        ></script>
        <script src="{{ url('/assets/js/jquery.min.js') }}"></script>
        <script
            src="{{ url('/assets/js/slick.min.js') }}"
            type="text/javascript"
            charset="utf-8"
        ></script>
        <script
            type="text/javascript"
            src="{{ url('/assets/js/bootstrap.js') }}"
        ></script>
        <link
            href="{{ url('/assets/css/bootstrap.css') }}"
            rel="stylesheet"
        >
        <link
            href="{{ url('/assets/css/fontawesome.css') }}"
            rel="stylesheet"
        >
        <link
            href="{{ url('/assets/css/solid.css') }}"
            rel="stylesheet"
        >
        <link
            href="{{ url('/assets/css/slick.css') }}"
            rel="stylesheet"
        >
        <link
            href="{{ url('/assets/css/slick-theme.css') }}"
            rel="stylesheet"
        >
        <link
            href="{{ url('/assets/css/app.css') }}"
            rel="stylesheet"
        >
        <link
            href="{{ url('/assets/css/custom.css') }}"
            rel="stylesheet"
        >

        @livewireStyles
        @stack('styles')
    </head>
    <body>

        @include('layouts.header')

        @section('header')
        @endsection

        <div id="main">

            <x-alert.alert />

            <x-alert.alert-error />

            @yield('content')
        </div>

        @include('layouts.footer')

        @livewireScripts
        @stack('scripts')
    </body>
</html>
