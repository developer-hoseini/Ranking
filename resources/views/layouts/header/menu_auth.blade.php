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

@php
    $authUser = auth()
        ->user()
        ->loadSum('userCoinAchievements', 'count');
@endphp

@php($eventsCount = 0)

<nav class="top-menu-nav px-0">
    <div class="w-100 d-flex flex-wrap">
        <div class="menu-nav-right mx-auto">
            <div
                class="text-decoration-none menu-toggler-btn d-inline-block mx-1"
                title="{{ __('words.menu') }}"
                onclick="menu_item();"
            >
                {{-- TODO: add notification badge --}}
                {{-- @if ($chats_count > 0 || $unconfirmed_quick_submitted > 0 || $team_invites_count > 0 || $support_new_ticket > 0 || $tournament_invite > 0) --}}
                {{-- <img
                    class="menu-white-icons"
                    src="{{ url('assets/img/menu/notify/menu.png') }}"
                    height="32px"
                >
                <img
                    class="menu-dark-icons"
                    src="{{ url('assets/img/menu/notify/menu-dark.png') }}"
                    height="32px"
                > --}}
                {{-- @else --}}
                <img
                    class="menu-white-icons"
                    src="{{ asset('assets/img/menu/menu.png') }}"
                    height="32px"
                >
                <img
                    class="menu-dark-icons"
                    src="{{ asset('assets/img/menu/menu-dark.png') }}"
                    height="32px"
                >
                {{-- @endif --}}
            </div>
            <a
                class="text-decoration-none d-inline-block mx-1"
                href="{{ route('game-results.quick-submit') }}"
                title="{{ __('words.quick_submit_result') }}"
            >
                <img
                    class="menu-white-icons"
                    src="{{ asset('assets/img/menu/submit_result.png') }}"
                    style="position: relative;bottom: 2px;"
                    height="28px"
                >
                <img
                    class="menu-dark-icons"
                    src="{{ asset('assets/img/menu/submit_result-dark.png') }}"
                    height="28px"
                >
            </a>
        </div>
        <div class="menu-nav-center mx-auto">
            <a
                href="{{ route('home') }}"
                title="{{ __('words.Ranking') }}"
            ><img
                    class="responsive-logo"
                    src="{{ asset('assets/img/white-ranking.png') }}"
                    height="40px"
                ></a>
        </div>
        <div class="menu-nav-left mx-auto">
            <div
                class="text-decoration-none menu-toggler-btn d-inline-block mx-1"
                title="{{ auth()?->user()?->profile?->full_name ?? auth()?->user()?->name }}"
                onclick="profile_item();"
            >
                <img
                    class="rounded-circle ml-1 bg-white"
                    src="{{ $authUser->avatar }}"
                    height="29px"
                >
            </div>
            <div
                class="text-decoration-none menu-toggler-btn d-inline-block mx-1"
                title="{{ __('words.coin_count') }}"
                onclick="coin_item();"
            >
                <img
                    src="{{ asset('assets/img/menu/coin.png?c=1') }}"
                    height="31px"
                >
            </div>
        </div>
        <div class="w-100 menu-nav-btns text-center">
            <a
                class="menu-nav-circle-btn"
                id="menu-nav-c-btn-1"
                href="{{ route('tournaments.index') }}"
                title="{{ __('words.matches') }}"
            >
                <img
                    src="{{ asset('assets/img/menu/tournament-red.png') }}"
                    width="20px"
                >
            </a>
            <a
                class="menu-nav-circle-btn"
                id="menu-nav-c-btn-2"
                href="{{ route('ranks.index') }}"
                title="{{ __('words.ranks_table') }}"
            >
                <img
                    src="{{ asset('assets/img/menu/ranks-red.png') }}"
                    width="20px"
                >
            </a>
            <a
                class="menu-nav-home-btn"
                href="{{ route('home') }}"
                title="{{ __('words.home') }}"
            >
                <img
                    src="{{ asset('assets/img/menu/home.png') }}"
                    height="25px"
                >
            </a>
            <a
                class="menu-nav-circle-btn"
                id="menu-nav-c-btn-4"
                href="{{ route('about') }}"
                title="{{ __('words.About_Ranking') }}"
            >
                <img
                    src="{{ asset('assets/img/menu/information-red.png') }}"
                    height="20x"
                >
            </a>
            <a
                class="menu-nav-circle-btn"
                id="menu-nav-c-btn-5"
                href="{{ route('events') }}"
                title="{{ __('words.Events') }}"
            >
                @if ($eventsCount > 0)
                    <img
                        src="{{ asset('assets/img/menu/notify/events-red.png') }}"
                        width="25px"
                    >
                @else
                    <img
                        src="{{ asset('assets/img/menu/events-red.png') }}"
                        width="22px"
                    >
                @endif
            </a>
        </div>
    </div>
</nav>

<div class="menu-right-Corner"></div>
<div class="menu-left-Corner"></div>

<div
    class="menu-list"
    id="menu-item"
>
    <div
        class="menu-list-box bg-white"
        id="profile-menu-box"
    >
        <a
            href="{{ route('game-results.index') }}"
            style="text-decoration: none;"
        >
            <div class="menu-list-item text-center">
                {{-- @if ($unconfirmed_quick_submitted > 0) --}}
                <img
                    class="mt-1"
                    src="{{ asset('assets/img/menu/notify/result_submit.png') }}"
                    width="45px"
                >
                {{-- @else --}}
                {{-- <img
                    class="mt-1"
                    src="{{ url('assets/img/menu/result_submit.png') }}"
                    width="45px"
                > --}}
                {{-- @endif --}}
                <div class="text-dark mt-1">{{ __('words.submitted_results') }}</div>
            </div>
        </a>
        <a
            href="{{ route('team_ranks') }}"
            style="text-decoration: none;"
        >
            <div class="menu-list-item text-center">
                <img
                    class="mt-1"
                    src="{{ asset('assets/img/menu/team_ranks.png') }}"
                    width="45px"
                >
                <div class="text-dark mt-1">{{ __('words.Team_Ranks') }}</div>
            </div>
        </a>
        <a
            href="{{ route('games.index') }}"
            style="text-decoration: none;"
        >
            <div class="menu-list-item text-center">
                <img
                    class="mt-1"
                    src="{{ asset('assets/img/menu/game.png') }}"
                    width="45px"
                >
                <div class="text-dark mt-1">{{ __('words.Games') }}</div>
            </div>
        </a>

        <a
            href="{{ route('tournaments.me.index') }}"
            style="text-decoration:none;"
        >
            <div class="menu-list-item text-center">
                <img
                    class="mt-1"
                    src="{{ url('assets/img/menu/my_tournament.png') }}"
                    width="45px"
                >
                <div class="text-dark mt-1">{{ __('words.my_tournament') }}</div>

                {{-- TODO: show notify badge --}}
                {{--  @if ($tournament_invite > 0)
                    <img
                        class="mt-1"
                        src="{{ url('assets/img/menu/notify/my_tournament.png') }}"
                        width="45px"
                    >
                @else
                @endif --}}
            </div>
        </a>
        <a
            href="{{ route('tickets.index') }}"
            style="text-decoration: none;"
        >
            <div class="menu-list-item text-center">
                <img
                    class="mt-1"
                    src="{{ asset('assets/img/menu/support.png') }}"
                    width="45px"
                >
                {{-- TODO: add badge new ticket --}}
                {{--  @if ($support_new_ticket > 0)
                    <img
                        class="mt-1"
                        src="{{ url('assets/img/menu/notify/support.png') }}"
                        width="45px"
                    >
                @else
                @endif --}}
                <div class="text-dark mt-1">{{ __('words.support') }}</div>
            </div>
        </a>

        {{-- TODO:  add Joined and My team --}}
        {{-- @if ($team_invites_count > 0) {{ route('joined_teams') }} @else {{ route('my_teams') }} @endif 
            @if ($team_invites_count > 0)
                    <img
                        class="mt-1"
                        src="{{ url('assets/img/menu/notify/team.png') }}"
                        width="45px"
                    >
                @else
        --}}
        <a
            href="{{ route('teams.me.index') }}"
            style="text-decoration: none;"
        >
            <div class="menu-list-item text-center">
                <img
                    class="mt-1"
                    src="{{ asset('assets/img/menu/team.png') }}"
                    width="45px"
                >
                <div class="text-dark mt-1">{{ __('words.My Teams') }}</div>
            </div>
        </a>

        {{-- 
        <a href="{{ route('chats') }}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                @if ($chats_count > 0)
                    <img src="{{ url('assets/img/menu/notify/chat.png') }}" width="45px" class="mt-1">
                @else
                    <img src="{{ url('assets/img/menu/chat.png') }}" width="45px" class="mt-1">
                @endif
                <div class="text-dark mt-1">{{ __('words.Chats') }}</div>
            </div>
        </a>
       
--}}
        <a
            href="{{ route('rules') }}"
            style="text-decoration: none;"
        >
            <div class="menu-list-item text-center">
                <img
                    class="mt-1"
                    src="{{ asset('assets/img/menu/rules.png') }}"
                    width="45px"
                >
                <div class="text-dark mt-1">{{ __('words.Rules') }}</div>
            </div>
        </a>
        <a
            href="{{ route('tutorial') }}"
            style="text-decoration: none;"
        >
            <div class="menu-list-item text-center">
                <img
                    class="mt-1"
                    src="{{ asset('assets/img/menu/tutorial.png') }}"
                    width="45px"
                >
                <div class="text-dark mt-1">{{ __('words.Tutorial') }}</div>
            </div>
        </a>
    </div>
</div>

<div
    class="menu-list"
    id="profile-item"
>
    <div class="menu-list-box menu-left-list-box bg-white">
        <div class="text-center">
            <img
                class="rounded-circle mt-2"
                src="{{ $authUser->avatar }}"
                width="150px"
                height="150px"
            >
            <div
                class="font-weight-bold mt-2"
                style="font-size: 20px;"
            >{{ auth()?->user()?->profile?->full_name ?? auth()?->user()?->name }}</div>
        </div>
        <div
            class="mt-3 py-2"
            style="width: 100%; border-top: solid 1px #bbb;"
        >
            <a
                href="{{ route('profile.show', $authUser?->id) }}"
                style="text-decoration: none;"
            >
                <div class="menu-list-item text-center">
                    <img
                        class="mt-1"
                        src="{{ asset('assets/img/menu/show_profile.png') }}"
                        width="45px"
                    >
                    <div class="text-dark mt-1">{{ __('words.View Profile') }}</div>
                </div>
            </a>
            <a
                href="{{ route('profile.complete-profile') }}"
                style="text-decoration: none;"
            >
                <div class="menu-list-item text-center">
                    <img
                        class="mt-1"
                        src="{{ asset('assets/img/menu/edit_profile.png') }}"
                        width="45px"
                    >
                    <div class="text-dark mt-1">{{ __('words.Edit Profile') }}</div>
                </div>
            </a>
            @if ($authUser->isAdmin)
                <a
                    href="{{ url('/admin') }}"
                    style="text-decoration: none;"
                >
                    <div class="menu-list-item text-center">
                        <img
                            class="mt-1"
                            src="{{ asset('assets/img/admin-panel.png') }}"
                            width="45px"
                        >
                        <div class="text-dark mt-1">Admin Panel</div>
                    </div>
                </a>
            @endif

        </div>
        <div class="text-center">
            <a
                class="mt-1"
                href="{{ route('auth.logout') }}"
                title="{{ __('words.Logout') }}"
                style="padding: 7px;"
            >
                <i class="fa fa-power-off btn btn-outline-danger rounded-circle mb-2 px-2 py-2"></i>
            </a>
        </div>
    </div>
</div>

<div
    class="menu-list"
    id="coin-item"
>
    <div class="menu-list-box menu-left-list-box bg-white">
        <div class="text-center">
            <a href="{{ route('charge') }}">
                <img
                    class="rounded-circle mt-2"
                    src="{{ asset('assets/img/menu/coin.png?c=1') }}"
                    width="130px"
                >
            </a>
            <div class="font-weight-bold mt-3">
                {{ __('words.cash:') }}
                {{ $authUser->sumCoinAchievements }}
                {{ __('words.Coin') }}
            </div>
        </div>
        <div
            class="mt-3 py-2"
            style="display: flex;width: 100%; border-top: solid 1px #bbb;"
        >
            <a
                href="{{ route('mgc-coin.index', ['type' => 'buy']) }}"
                style="text-decoration: none;margin: 0 auto;"
            >
                <div class="menu-list-item text-center">
                    <img
                        class="mt-1"
                        src="{{ asset('assets/img/menu/coin_buy.png?c=2') }}"
                        width="45px"
                    >
                    <div class="text-dark mt-1">{{ __('words.buy_coin') }}</div>
                </div>
            </a>
            <a
                href="{{ route('mgc-coin.index', ['type' => 'sell']) }}"
                style="text-decoration: none;margin: 0 auto;"
            >
                <div class="menu-list-item text-center">
                    <img
                        class="mt-1"
                        src="{{ asset('assets/img/menu/coin_buy.png?c=2') }}"
                        width="45px"
                    >
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

    <style type="text/css">.menu-nav-btns {
        position: relative;
        bottom: 150px;
    }

    .responsive-logo {
        opacity: 0;
    }

    #menu-nav-c-btn-1 {
        position: relative;
        right: 102px;
    }

    #menu-nav-c-btn-2 {
        position: relative;
        right: 55px;
    }

    .menu-nav-home-btn {
        position: relative;
        z-index: 999;
    }

    #menu-nav-c-btn-4 {
        position: relative;
        left: 55px;
    }

    #menu-nav-c-btn-5 {
        position: relative;
        left: 102px;
    }
</style>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).scroll(function() {
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

        $('document').ready(function() {
            $(document).mouseup(function(e) {
                var menu_list_box = $(".menu-list-box");
                var menu_toggler_btn = $(".menu-toggler-btn");
                var menu_list = $(".menu-list");

                if (!menu_list_box.is(e.target) && menu_list_box.has(e.target).length === 0 && !
                    menu_toggler_btn.is(e.target) && menu_toggler_btn.has(e.target).length === 0) {
                    menu_list.hide();
                }
            });

            setTimeout(function() {
                $('.menu-nav-btns').animate({
                    'bottom': 0
                }, 300, function() {
                    $('#menu-nav-c-btn-1').animate({
                        'right': 0
                    }, 400);
                    $('#menu-nav-c-btn-2').animate({
                        'right': 0
                    }, 400);
                    $('#menu-nav-c-btn-4').animate({
                        'left': 0
                    }, 400);
                    $('#menu-nav-c-btn-5').animate({
                        'left': 0
                    }, 400, function() {
                        $('.responsive-logo').animate({
                            'opacity': 1
                        }, 400);
                    });
                });

            }, 200);


            $("[rel=tooltip]").tooltip({
                placement: 'left'
            });
        });
    </script>
@endpush
