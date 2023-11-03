@props([
    'isEnd' => false,
    'style' => '',
    'player1Style' => '',
    'player2Style' => '',
])

<div class="{{ $isEnd ? "" :  "block" }}" style="{{ $style }}">
@if (!$isEnd)
    <div class="player_1" style="{{ $player1Style }}">
        <a href="http://ranking-orginal.test/profile/Jason">
            <img src="http://ranking-orginal.test/uploads/profile/default.png" class="user_photo" width="32">
            Jason Almond
        </a>
    </div>
    <div class="player_2" style="{{ $player2Style }}">
        <a href="http://ranking-orginal.test/profile/Jelin">
            <img src="http://ranking-orginal.test/uploads/profile/default.png" class="user_photo" width="32">
            Jelin Omg
        </a>
    </div>
    {{ $slot }}
@else
    <div class="d-flex">
        <div class="bracket_cup_icon"></div>
        <div class="player_1">
            <a href="http://ranking-orginal.test/profile/Jason">
                <img src="http://ranking-orginal.test/uploads/profile/default.png" class="user_photo" width="32">
                Jason Almond
            </a>
        </div>
    </div>

@endif
</div>