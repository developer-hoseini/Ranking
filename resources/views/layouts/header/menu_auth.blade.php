{{-- @php
   $status = config('status');

    // Notifications
    $events_count = \App\Event::where(
        ['user_id'=>$auth_user->id, 'seen'=>$status['No']])->count();
    $chats_count = \App\Chat::where(
        ['to_id'=>$auth_user->id, 'seen'=>$status['No']])->count();
    $unconfirmed_quick_submitted = \App\Invite::where(['invited_id'=>$auth_user->id,
        'status'=>$status['Quick_Wait_Opponent'], 'quick'=>$status['Yes']])->count();
    $team_invites_count = \App\Team_Member::where(['user_id'=>$auth_user->id,
        'status'=>$status['Pending']])->count();
    $support_new_ticket = \App\Ticket::where(['user_id'=>$auth_user->id,
        'status' => $status['New_Message']])->count();
    $teams = \App\Team::where('capitan_id',$auth_user->id)->get('id');
    $team_arr = array();
    foreach ($teams as $team){$team_arr[] = $team->id;}
    $tournament_invite = \App\Competition_Invite::where('user_id',$auth_user->id)->orwhereIn('team_id',$team_arr)->count();


@endphp --}}

<nav class="px-0 top-menu-nav">
    <div class="w-100 d-flex flex-wrap">
        <div class="menu-nav-right mx-auto">
            <div title="{{__('words.menu')}}" onclick="menu_item();"
                 class="text-decoration-none menu-toggler-btn mx-1 d-inline-block">
                @if($chats_count>0 || $unconfirmed_quick_submitted>0 || $team_invites_count>0 || $support_new_ticket>0 || $tournament_invite>0)
                    <img src="{{url('assets/img/menu/notify/menu.png')}}" height="32px" class="menu-white-icons">
                    <img src="{{url('assets/img/menu/notify/menu-dark.png')}}" height="32px" class="menu-dark-icons">
                @else
                    <img src="{{url('assets/img/menu/menu.png')}}" height="32px" class="menu-white-icons">
                    <img src="{{url('assets/img/menu/menu-dark.png')}}" height="32px" class="menu-dark-icons">
                @endif
            </div>
            <a href="{{route('quick_submit')}}" title="{{__('words.quick_submit_result')}}"
               class="text-decoration-none d-inline-block mx-1">
                <img src="{{url('assets/img/menu/submit_result.png')}}" height="28px" class="menu-white-icons"
                     style="position: relative;bottom: 2px;">
                <img src="{{url('assets/img/menu/submit_result-dark.png')}}" height="28px" class="menu-dark-icons">
            </a>
        </div>
        <div class="menu-nav-center mx-auto">
            <a href="{{ route('home') }}" title="{{__('words.Ranking')}}"><img
                    src="{{url('assets/img/white-ranking.png')}}"
                    height="40px"
                    class="responsive-logo"></a>
        </div>
        <div class="menu-nav-left mx-auto">
            <div title="{{session('fullname')}}" onclick="profile_item();"
                 class="text-decoration-none menu-toggler-btn d-inline-block mx-1">
                <img src="{{url(session('photo'))}}" height="29px" class="rounded-circle bg-white ml-1">
            </div>
            <div title="{{ __('words.coin_count') }}" class="text-decoration-none menu-toggler-btn d-inline-block mx-1"
                 onclick="coin_item();">
                <img src="{{url('assets/img/menu/coin.png?c=1')}}" height="31px">
            </div>
        </div>
        <div class="w-100 text-center menu-nav-btns">
            <a href="{{route('tournament.index')}}" title="{{ __('words.matches') }}" class="menu-nav-circle-btn"
               id="menu-nav-c-btn-1">
                <img src="{{url('assets/img/menu/tournament-red.png')}}" width="20px">
            </a>
            <a href="{{route('ranks')}}" title="{{ __('words.ranks_table') }}" class="menu-nav-circle-btn"
               id="menu-nav-c-btn-2">
                <img src="{{url('assets/img/menu/ranks-red.png')}}" width="20px">
            </a>
            <a href="{{ route('home') }}" title="{{__('words.home')}}" class="menu-nav-home-btn">
                <img src="{{url('assets/img/menu/home.png')}}" height="25px">
            </a>
            <a href="{{route('events')}}" title="{{ __('words.Events') }}" class="menu-nav-circle-btn"
               id="menu-nav-c-btn-4">
                @if($events_count>0)
                    <img src="{{url('assets/img/menu/notify/events-red.png')}}" width="25px">
                @else
                    <img src="{{url('assets/img/menu/events-red.png')}}" width="22px">
                @endif
            </a>
            <div class="menu-nav-circle-btn font-weight-bold" style="font-family: IranSans_Black;"
                 onclick="language_btn();" title="{{__('words.Language')}}" id="menu-nav-c-btn-5">{{$locale}}</div>
        </div>
    </div>
</nav>

<div class="menu-right-Corner"></div>
<div class="menu-left-Corner"></div>


<div class="menu-list" id="menu-item">
    <div class="menu-list-box bg-white" id="profile-menu-box">
        <a href="{{route('quick_submitted_list')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                @if($unconfirmed_quick_submitted>0)
                    <img src="{{url('assets/img/menu/notify/result_submit.png')}}" width="45px" class="mt-1">
                @else
                    <img src="{{url('assets/img/menu/result_submit.png')}}" width="45px" class="mt-1">
                @endif
                <div class="text-dark mt-1">{{__('words.submitted_results')}}</div>
            </div>
        </a>
        <a href="{{route('team_ranks')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                <img src="{{url('assets/img/menu/team_ranks.png')}}" width="45px" class="mt-1">
                <div class="text-dark mt-1">{{ __('words.Team_Ranks') }}</div>
            </div>
        </a>
        <a href="{{route('games')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                <img src="{{url('assets/img/menu/game.png')}}" width="45px" class="mt-1">
                <div class="text-dark mt-1">{{ __('words.Games') }}</div>
            </div>
        </a>
        <a href="{{route('my_tournament.index')}}@if($tournament_invite>0)#invited @endif"
           style="text-decoration: none;">
            <div class="menu-list-item text-center">
                @if($tournament_invite>0)
                    <img src="{{url('assets/img/menu/notify/my_tournament.png')}}" width="45px" class="mt-1">
                @else
                    <img src="{{url('assets/img/menu/my_tournament.png')}}" width="45px" class="mt-1">
                @endif
                <div class="text-dark mt-1">{{ __('words.my_tournament') }}</div>
            </div>
        </a>
        <a href="@if($team_invites_count>0){{route('joined_teams')}} @else {{route('my_teams')}} @endif"
           style="text-decoration: none;">
            <div class="menu-list-item text-center">
                @if($team_invites_count>0)
                    <img src="{{url('assets/img/menu/notify/team.png')}}" width="45px" class="mt-1">
                @else
                    <img src="{{url('assets/img/menu/team.png')}}" width="45px" class="mt-1">
                @endif
                <div class="text-dark mt-1">{{ __('words.My Teams') }}</div>
            </div>
        </a>
        <a href="{{route('chats')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                @if($chats_count>0)
                    <img src="{{url('assets/img/menu/notify/chat.png')}}" width="45px" class="mt-1">
                @else
                    <img src="{{url('assets/img/menu/chat.png')}}" width="45px" class="mt-1">
                @endif
                <div class="text-dark mt-1">{{ __('words.Chats') }}</div>
            </div>
        </a>
        <a href="{{route('tickets.index')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                @if($support_new_ticket >0)
                    <img src="{{url('assets/img/menu/notify/support.png')}}" width="45px" class="mt-1">
                @else
                    <img src="{{url('assets/img/menu/support.png')}}" width="45px" class="mt-1">
                @endif
                <div class="text-dark mt-1">{{ __('words.support') }}</div>
            </div>
        </a>

        <a href="{{route('rules')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                <img src="{{url('assets/img/menu/rules.png')}}" width="45px" class="mt-1">
                <div class="text-dark mt-1">{{ __('words.Rules') }}</div>
            </div>
        </a>
        <a href="{{route('tutorial')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                <img src="{{url('assets/img/menu/tutorial.png')}}" width="45px" class="mt-1">
                <div class="text-dark mt-1">{{ __('words.Tutorial') }}</div>
            </div>
        </a>
    </div>
</div>
<div class="menu-list" id="profile-item">
    <div class="menu-list-box bg-white menu-left-list-box">
        <div class="text-center">
            <img src="{{url(session('photo'))}}" width="150px" height="150px" class="mt-2 rounded-circle">
            <div class="font-weight-bold mt-2" style="font-size: 20px;">{{session('fullname')}}</div>
        </div>
        <div class="mt-3 py-2" style="width: 100%; border-top: solid 1px #bbb;">
            <a href="{{route('profile',['username'=>$auth_user->username])}}" style="text-decoration: none;">
                <div class="menu-list-item text-center">
                    <img src="{{url('assets/img/menu/show_profile.png')}}" width="45px" class="mt-1">
                    <div class="text-dark mt-1">{{ __('words.View Profile') }}</div>
                </div>
            </a>
            <a href="{{route('edit_profile')}}" style="text-decoration: none;">
                <div class="menu-list-item text-center">
                    <img src="{{url('assets/img/menu/edit_profile.png')}}" width="45px" class="mt-1">
                    <div class="text-dark mt-1">{{ __('words.Edit Profile') }}</div>
                </div>
            </a>
            <a href="{{route('set_qrcode')}}" style="text-decoration: none;">
                <div class="menu-list-item text-center">
                    <img src="{{url('assets/img/menu/set_card.png')}}" width="45px" class="mt-1">
                    <div class="text-dark mt-1">{{ __('words.set_card') }}</div>
                </div>
            </a>
        </div>
        <div class="text-center">
            <a href="{{route('logout')}}" title="{{ __('words.Logout') }}" class="mt-1" style="padding: 7px;">
                <i class="fa fa-power-off btn btn-outline-danger rounded-circle py-2 px-2 mb-2"></i>
            </a>
        </div>
    </div>
</div>
<div class="menu-list" id="coin-item">
    <div class="menu-list-box bg-white menu-left-list-box">
        <div class="text-center">
            <a href="{{route('charge')}}">
                <img src="{{url('assets/img/menu/coin.png?c=1')}}" width="130px" class="mt-2 rounded-circle">
            </a>
            <div class="font-weight-bold mt-3">
                {{ __('words.cash:') }}
                {{number_format($auth_user->coin)}}
                {{ __('words.Coin') }}
            </div>
        </div>
        <div class="mt-3 py-2" style="display: flex;width: 100%; border-top: solid 1px #bbb;">
            <a href="https://ranking.metagamescoin.io/forms/?page_id=130" style="text-decoration: none;margin: 0 auto;">
                <div class="menu-list-item text-center">
                    <img src="{{url('assets/img/menu/coin_buy.png?c=2')}}" width="45px" class="mt-1">
                    <div class="text-dark mt-1">{{ __('words.buy_coin') }}</div>
                </div>
            </a>
            <a href="https://ranking.metagamescoin.io/forms/?page_id=140" style="text-decoration: none;margin: 0 auto;">
                <div class="menu-list-item text-center">
                    <img src="{{url('assets/img/menu/coin_buy.png?c=2')}}" width="45px" class="mt-1">
                    <div class="text-dark mt-1">{{ __('words.card_charge') }}</div>
                </div>
            </a>
        </div>
    </div>
</div>

<style type="text/css">
    @media (max-width: 1000px) {
        .responsive-logo {
            height: 35px;
            margin-top: 3px;
        }
    }

    @media (max-width: 340px) {
        .responsive-logo {
            height: 30px;
            margin-top: 6px;
        }
    }
</style>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).scroll(function () {
                scrollfun()
            });
        });

        function scrollfun() {
            if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                $(".top-menu-nav").css("background-color", "#ffffffc4");
            } else {
                $(".top-menu-nav").css("background-color", "");
            }
        }

        function menu_item() {
            $('#profile-item').css('display', 'none');
            $('#coin-item').css('display', 'none');
            $('#menu-item').toggle();
        }

        function profile_item() {
            $('#menu-item').css('display', 'none');
            $('#coin-item').css('display', 'none');
            $('#profile-item').toggle();
        }

        function coin_item() {
            $('#menu-item').css('display', 'none');
            $('#profile-item').css('display', 'none');
            $('#coin-item').toggle();
        }

        function language_btn() {
            Swal.fire({
                title: "{{ __('words.language_select') }}" + ' :',
                html: '<br>' +
                    '@foreach(LaravelLocalization::getLocalesOrder() as $localeCode => $properties)' +
                    '<a class="btn btn-outline-primary" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">{{ $properties['native'] }}</a> ' +
                    '@endforeach',
                showCancelButton: true,
                showConfirmButton: false,

                cancelButtonColor: '#d33',
                cancelButtonText: "{{ __('words.Cancel') }}",
            })
        }

        $('document').ready(function () {
            $(document).mouseup(function (e) {
                var menu_list_box = $(".menu-list-box");
                var menu_toggler_btn = $(".menu-toggler-btn");
                var menu_list = $(".menu-list");

                if (!menu_list_box.is(e.target) && menu_list_box.has(e.target).length === 0 && !menu_toggler_btn.is(e.target) && menu_toggler_btn.has(e.target).length === 0) {
                    menu_list.hide();
                }
            });
        });
    </script>
@endpush
