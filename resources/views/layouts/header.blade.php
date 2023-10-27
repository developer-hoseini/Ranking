@auth
    {{-- @php
        $auth_user = \App\User::with('profile')->find(Auth::user()->id);
        $curr_route = Route::currentRouteName();

        if(!Session::has('fullname'))
        {
            session(
                [
                    'fullname' => $auth_user->profile->fullname,
                    'photo' => $auth_user->photo,
                ]
            );
        }

        if( $auth_user->username==null && $curr_route!='set_user_pass' )
        {
            echo '<script>window.location="'.route('set_user_pass').'";</script>';
        }
    @endphp --}}

    @include('layouts.header.menu_auth')
@else
    {{-- @php
        $auth_user = collect([ 'id'=>time(), 'is_guest'=>true ]);
    @endphp --}}
    @include('layouts.header.menu_guest')
@endauth