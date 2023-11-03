@props(['style' => ""])


<div class="" style="{{ $style }}">
    <div class="d-flex">
        <div class="bracket_cup_icon"></div>
        <div class="player_1">
            {{ $slot }}
        </div>
    </div>
</div>