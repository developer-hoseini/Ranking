@extends('layouts.app')

@section('header')
    @parent
@endsection

@php
    $capacity = $cup?->capacity ?? 0;
    $steps = [];

    while ($capacity >= 1) {
        $capacity = $capacity / 2;
        $steps[] = $capacity;
    }

    $marginTop = 0;
    $height = 50;
    $player1MarginBottom = 10;
    $marginBottom = 70;

@endphp

@section('content')
    <div class="container">

        <div class="dragscroll">
            <div class="p-5 bracket_holder" style="margin-top: 150px !important">
                @foreach ($steps as $key => $step)
                    @php
                        
                        $winerPlayer = null;

                        if ($key > 0) {
                            $marginTop += $height / 2;
                            $height += $marginBottom;
                            $player1MarginBottom += $marginBottom;
                            $marginBottom += $height / 2 - 10;
                        }
                        $isEnd = $step < 1 ? 1 : 0;
                        
                        if($isEnd) {
                            $competition = $cup->competitions->where('pivot.step',$key)->first();
                            $users = $competition?->users;
                            $gameResultWiner = $competition?->gameResults?->where('gameResultStatus.name', \App\Enums\StatusEnum::GAME_RESULT_WIN->value)->first();
                            if($gameResultWiner) {
                                $winerPlayer = $users->where('id',$gameResultWiner->playerable_id)->first();
                            }
                            $height = 0;
                        }

                        $style = "height: {$height}px; margin-top: {$marginTop}px; width: 190px; margin-bottom:{$marginBottom}px;";
                        $player1Style = "margin-bottom: {$player1MarginBottom}px;";
                    @endphp
                    <div class="round ">
                        @if (!$isEnd)
                            @for ($i = 0; $i < $step; $i++)
                                @php
                                    $competition = $cup->competitions->where('pivot.step',$key+1)->values()?->get($i);
                                    $users = $competition?->users;
                                    $player1 = $users?->first();
                                    $player2 = $users?->values()?->get(1);
                                @endphp
                                <x-cups.show.players style="{{ $style }}" player1Style="{{ $player1Style }}" 
                                    is-end="{{ $isEnd }}" >
                                    <x-slot:player1>
                                        @if ($player1)
                                            <a href="http://ranking-orginal.test/profile/Jason">
                                                <img src="{{ $player1?->avatar != "" ? $player1?->avatar : asset('assets/images/default-profile.png') }}" class="user_photo" width="32">
                                                <span class="w-75 position-absolute text-truncate">{{ $player1?->name }} </span>
                                            </a>
                                        @else
                                            <x-icons.svg.question />
                                        @endif
                                    </x-slot>
                                    <x-slot:player2>
                                        @if ($player2)
                                            <a href="http://ranking-orginal.test/profile/Jelin">
                                                
                                                <img src="{{ $player2?->avatar != "" ? $player2?->avatar : asset('assets/images/default-profile.png') }}" class="user_photo" width="32">
                                                <span class="w-75 position-absolute text-truncate">{{ $player2?->name }}</span>
                                            </a>
                                        @else
                                            <x-icons.svg.question />
                                        @endif
                                        
                                    </x-slot>
                                
                                </x-cups.show.players>
                            @endfor 
                        @else
                            <x-cups.show.player-win-cup style="{{ $style }}" >
                                @if ($winerPlayer)
                                        <a href="http://ranking-orginal.test/profile/Jason">
                                            <img src="" class="user_photo" width="32">
                                            <span class="w-75 position-absolute text-truncate">
                                                {{ $winerPlayer?->name }}
                                            </span>
                                        </a>
                                @else
                                    <x-icons.svg.question />
                                @endif
                            </x-cups.show.player-win-cup>
                        @endif
                    </div>
                @endforeach
              
            </div>
        </div>
    </div>
@endsection
