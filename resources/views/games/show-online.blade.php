@extends('layouts.app')

@section('title', __('words. - ') . __('words.online_games'))

@section('header')
    @parent
@endsection

@section('content')

    <div class="online_game_box text-center">
        <h1>{{ __('words.Game') . ' ' . $game?->name }}</h1>
        <h2 class="p-3 text-left">
            <a
                class="text-decoration-none"
                href="{{ route('games.index', ['gameType' => 'online']) }}"
            >
                {{ __('words.online_games') }}
                <i class="fa fa-arrow-left"></i>
            </a>
        </h2>

        {!! $game?->onlineGames?->first()?->content !!}

        <span
            class="mt-5"
            title="{{ __('words.viewed_count') }}"
        ><i class="fa fa-eye"></i> {{ $game?->onlineGames?->first()?->viewed + 1 }}</span>
        <div class="mt-5">
            {{-- TODO: submit result of online game --}}
            {{-- <a --}}
            {{-- class="btn-lg btn-success text-decoration-none" --}}
            {{-- href="{{ route('quick_submit') }}" --}}
            {{-- target="_blank" --}}
            {{-- > --}}
            {{-- <i class="fa fa-plus"></i> --}}
            {{-- {{ __('words.submit_result') }} --}}
            {{-- </a> --}}
        </div>
        <div class="ongame_desc mt-5 p-4">{!! $game?->onlineGames?->first()?->description !!}</div>
    </div>

    @push('scripts')
        <style type="text/css">
            @media screen and (max-width: 700px) {
                .menu-nav-btns {
                    padding-top: 0px;
                }

                .top-menu-nav {
                    height: 55px;
                }

                .menu-right-Corner {
                    display: none;
                }

                .menu-left-Corner {
                    display: none;
                }
            }
        </style>
    @endpush

@endsection
