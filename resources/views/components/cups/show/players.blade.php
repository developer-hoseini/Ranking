@props([
    'style' => '',
    'player1Style' => '',
    'player2Style' => '',
    'player1',
    'player2',
])



<div class="block" style="{{ $style }}">
    <div class="player_1" style="{{ $player1Style }}">
        {{ $player1 }}
    </div>
    <div class="player_2" style="{{ $player2Style }}">
        {{ $player2 }}
    </div>
</div>