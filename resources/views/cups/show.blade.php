@extends('layouts.app')

@section('header')
    @parent
@endsection

@php
    $playersCount = 16;
    $steps = [];

    while ($playersCount >= 1) {
        $playersCount = $playersCount / 2;
        $steps[] = $playersCount;
    }


    $marginTop = 0;
    $height = 50;
    $player1MarginBottom = 10;
    $marginBottom = 70;

@endphp

@section('content')
    <div class="container">

        <div class="dragscroll">
            <div class="bracket_holder" style="margin-top: 150px !important">
                @foreach ($steps as $key => $step)
                    @php
                        if ($key > 0) {
                            $marginTop += $height / 2;
                            $height += $marginBottom;
                            $player1MarginBottom += $marginBottom;
                            $marginBottom += $height / 2 - 10;
                        }

                        $style = "height: {$height}px; margin-top: {$marginTop}px; width: 190px; margin-bottom:{$marginBottom}px;";
                        $player1Style = "margin-bottom: {$player1MarginBottom}px;";
                        $isEnd = $step < 1 ? 1 : 0;
                    @endphp
                    <div class="round ">
                        @for ($i = 0; $i < $step; $i++)
                                <x-cups.show.players style="{{ $style }}" player1Style="{{ $player1Style }}"
                                    is-end="{{ $isEnd }}" />
                        @endfor

                    </div>
                @endforeach
              
            </div>
        </div>
    </div>
@endsection
