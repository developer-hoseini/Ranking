<div id="footer">
    <div class="rg-logo"><img src="{{ url('assets/img/coin.png') }}" width="60" align="center"></div>
    <div>{{ __('words.Global Center of Players Ranking') }}</div>
    {{-- <div class=" mt-2">|
        @foreach(LaravelLocalization::getLocalesOrder() as $localeCode => $properties)
            <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
               class="footer-link">{{ $properties['native'] }}</a> |
        @endforeach
    </div> --}}
    <div class="footer-link">
        <a href="{{ url('') }}" class="footer-link">Ranking of MetaGamesCoin</a>
    </div>
    <div>
        <p> Address: Canyon center, Internet City, sheikh Zayed highway, Dubai, UAE. <br>
            <a class="no-underline text-white" href="mailto:info@metagamescoin.io">E-Mail: info@metagamescoin.io</a> 
        </p>
    </div>
    <div class="reserved">{{ __('words.All rights reserved by Ranking of Rezvani Game.') }}</div>
</div>
