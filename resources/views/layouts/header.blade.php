@auth
    @include('layouts.header.menu_auth')
@else
    @include('layouts.header.menu_guest')
@endauth
