<nav class="top-menu-nav px-0">
    <div class="w-100 d-flex flex-wrap">
        <div class="menu-nav-right mx-auto">
            <div
                class="text-decoration-none menu-toggler-btn d-inline-block mx-1"
                title="{{ __('words.menu') }}"
                onclick="menu_item();"
            >
                <img
                    class="menu-white-icons"
                    src="{{ url('assets/img/menu/menu.png') }}"
                    height="32px"
                >
                <img
                    class="menu-dark-icons"
                    src="{{ url('assets/img/menu/menu-dark.png') }}"
                    height="32px"
                >
            </div>
        </div>
        <div class="menu-nav-center mx-auto">
            <a
                href="{{ route('home') }}"
                title="{{ __('words.Ranking') }}"
            ><img
                    class="responsive-logo"
                    src="{{ url('assets/img/white-ranking.png') }}"
                    height="40px"
                ></a>
        </div>
        <div class="menu-nav-left mx-auto">
            <div
                class="text-decoration-none menu-toggler-btn d-inline-block mx-1"
                title="{{ __('words.Login / Register') }}"
                onclick="login_item();"
            >
                <img
                    class="menu-white-icons"
                    src="{{ url('assets/img/menu/user.png') }}"
                    height="30px"
                >
                <img
                    class="menu-dark-icons"
                    src="{{ url('assets/img/menu/user-dark.png') }}"
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
                    src="{{ url('assets/img/menu/tournament-red.png') }}"
                    height="20x"
                >
            </a>
            <a
                class="menu-nav-circle-btn"
                id="menu-nav-c-btn-2"
                href="{{ route('ranks.index') }}"
                title="{{ __('words.ranks_table') }}"
            >
                <img
                    src="{{ url('assets/img/menu/ranks-red.png') }}"
                    height="20x"
                >
            </a>
            <a
                class="menu-nav-home-btn"
                href="{{ route('home') }}"
                title="{{ __('words.home') }}"
            >
                <img
                    src="{{ url('assets/img/menu/home.png') }}"
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
                    src="{{ url('assets/img/menu/information-red.png') }}"
                    height="20x"
                >
            </a>

        </div>
    </div>
</nav>
<div>

    <div class="menu-right-Corner"></div>
    <div class="menu-left-Corner"></div>

    <div
        class="menu-list"
        id="menu-item"
    >
        <div class="menu-list-box bg-white">
            <a
                href="{{ route('games.index') }}"
                style="text-decoration: none;"
            >
                <div class="menu-list-item text-center">
                    <img
                        class="mt-1"
                        src="{{ url('assets/img/menu/game.png') }}"
                        width="45px"
                    >
                    <div class="text-dark mt-1">{{ __('words.Games') }}</div>
                </div>
            </a>
            <a
                href="{{ route('prizes') }}"
                style="text-decoration: none;"
            >
                <div class="menu-list-item text-center">
                    <img
                        class="mt-1"
                        src="{{ url('assets/img/menu/prize.png') }}"
                        width="45px"
                    >
                    <div class="text-dark mt-1">{{ __('words.store') }}</div>
                </div>
            </a>
            <a
                href="{{ route('ranks.team') }}"
                style="text-decoration: none;"
            >
                <div class="menu-list-item text-center">
                    <img
                        class="mt-1"
                        src="{{ url('assets/img/menu/team_ranks.png') }}"
                        width="45px"
                    >
                    <div class="text-dark mt-1">{{ __('words.Team_Ranks') }}</div>
                </div>
            </a>
            <a
                href="('tutorial')}}"
                style="text-decoration: none;"
            >
                <div class="menu-list-item text-center">
                    <img
                        class="mt-1"
                        src="{{ url('assets/img/menu/tutorial.png') }}"
                        width="45px"
                    >
                    <div class="text-dark mt-1">{{ __('words.Tutorial') }}</div>
                </div>
            </a>
            <a
                href="('rules')}}"
                style="text-decoration: none;"
            >
                <div class="menu-list-item text-center">
                    <img
                        class="mt-1"
                        src="{{ url('assets/img/menu/rules.png') }}"
                        width="45px"
                    >
                    <div class="text-dark mt-1">{{ __('words.Rules') }}</div>
                </div>
            </a>
        </div>
    </div>
    <div
        class="menu-list"
        id="login-item"
    >
        <div
            class="menu-list-box bg-white"
            id="login-box"
        >
            <div class="text-center">
                <img
                    class="mx-auto mt-1"
                    src="{{ url('assets/img/menu/login.png') }}"
                    width="70px"
                >
                <div
                    class="font-weight-bold mt-1 pb-2"
                    style="font-size: 20px;border-bottom: solid 1px #ddd;"
                >
                    {{ __('words.login') }}</div>
            </div>
            <form
                method="POST"
                action="{{ route('auth.login', ['callback' => request()->path()]) }}"
            >
                @csrf
                <div class="login-menu-item mt-2">
                    <i
                        class="fa fa-user login-menu-item-icon"
                        style="float:right;"
                    ></i>
                    <input
                        class="login-menu-input"
                        name="avatar-name"
                        type="text"
                        style="width: 82%;filter: none;"
                        placeholder="Avatar name"
                    >
                </div>
                <div class="login-menu-item mt-2">
                    <i
                        class="fa fa-key login-menu-item-icon"
                        style="float:right;"
                    ></i>
                    <input
                        class="login-menu-input"
                        name="password"
                        type="password"
                        style="width: 82%;filter: none;"
                        placeholder="******"
                    >
                </div>
                <button
                    class="btn btn-success rounded-pill mt-2"
                    type="submit"
                    style="width: 100%;height: 40px;"
                >{{ __('words.Enter') }}</button>
            </form>
            <div
                class="mt-2 px-1"
                style="border-top: solid 1px #ccc;height: 40px;line-height: 40px;"
            >
                <a
                    href="{{ route('auth.show-register') }}"
                    style="float: right;"
                >{{ __('words.Register') }}</a>
                <a
                    href="{{ route('password.email') }}"
                    style="float: left;"
                >{{ __('words.forget_password') }}</a>
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
                $('#login-item').css('display', 'none');
                $('#menu-item').toggle();
            }

            function login_item() {
                $('#menu-item').css('display', 'none');
                $('#login-item').toggle();
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
            })
        </script>
    @endpush
