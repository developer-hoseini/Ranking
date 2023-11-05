@extends('layouts.app')

@section('title', $title ?? __('words. - ') . __('words.About_Ranking'))

@section('header')
    @parent
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    {{ $slot }}
@endsection
