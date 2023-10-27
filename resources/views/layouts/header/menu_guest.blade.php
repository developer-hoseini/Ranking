
<nav class="px-0 top-menu-nav">
    <div class="w-100 d-flex flex-wrap">
        <div class="mx-auto menu-nav-right">
            <div title="{{__('words.menu')}}" onclick="menu_item();" class="text-decoration-none menu-toggler-btn mx-1 d-inline-block">
                <img src="{{\Storage::url('assets/images/menu/menu.png')}}" height="32px" class="menu-white-icons">
                <img src="{{\Storage::url('assets/images/menu/menu-dark.png')}}" height="32px" class="menu-dark-icons">
            </div>
        </div>
        <div class="mx-auto menu-nav-center">
            <a href="{{ route('home') }}" title="{{__('words.Ranking')}}"><img src="{{\Storage::url('assets/images/white-ranking.png')}}" height="40px" class="responsive-logo"></a>
        </div>
        <div class="mx-auto menu-nav-left">
            <div title="{{__('words.Login / Register')}}" onclick="login_item();" class="text-decoration-none menu-toggler-btn d-inline-block mx-1">
                <img src="{{\Storage::url('assets/images/menu/user.png')}}" height="30px" class="menu-white-icons">
                <img src="{{\Storage::url('assets/images/menu/user-dark.png')}}" height="31px" class="menu-dark-icons">
            </div>
           
        </div>
        <div class="w-100 text-center menu-nav-btns">
            <a href="('tournament.index')" title="{{ __('words.matches') }}" class="menu-nav-circle-btn" id="menu-nav-c-btn-1">
                <img src="{{\Storage::url('assets/images/menu/tournament-red.png')}}" height="20x">
            </a>
            <a href="('global_ranks')}}" title="{{ __('words.ranks_table') }}" class="menu-nav-circle-btn" id="menu-nav-c-btn-2">
                <img src="{{\Storage::url('assets/images/menu/ranks-red.png')}}" height="20x">
            </a>
            <a href="{{ route('home') }}" title="{{__('words.home')}}" class="menu-nav-home-btn">
                <img src="{{\Storage::url('assets/images/menu/home.png')}}" height="25px">
            </a>
            <a href="('about_ranking')}}" title="{{ __('words.About_Ranking') }}" class="menu-nav-circle-btn" id="menu-nav-c-btn-4">
                <img src="{{\Storage::url('assets/images/menu/information-red.png')}}" height="20x">
            </a>
            {{-- <div class="menu-nav-circle-btn font-weight-bold" style="font-family: IranSans_Black;" onclick="language_btn();" title="{{__('words.Language')}}" id="menu-nav-c-btn-5">
                {{$locale}}
            </div> --}}
        </div>
    </div>
</nav>
<div>

<div class="menu-right-Corner"></div>
<div class="menu-left-Corner"></div>


<div class="menu-list" id="menu-item">
    <div class="menu-list-box bg-white">
        <a href="('select_game')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                <img src="{{\Storage::url('assets/images/menu/game.png')}}" width="45px" class="mt-1">
                <div class="text-dark mt-1">{{ __('words.Games') }}</div>
            </div>
        </a>
       <!-- <a href="('prizes')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                <img src="{{\Storage::url('assets/images/menu/prize.png')}}" width="45px" class="mt-1">
                <div class="text-dark mt-1">{{ __('words.store') }}</div>
            </div> -->
        </a>
        <a href="('global_team_ranks')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                <img src="{{\Storage::url('assets/images/menu/team_ranks.png')}}" width="45px" class="mt-1">
                <div class="text-dark mt-1">{{ __('words.Team_Ranks') }}</div>
            </div>
        </a>
        <a href="('tutorial')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                <img src="{{\Storage::url('assets/images/menu/tutorial.png')}}" width="45px" class="mt-1">
                <div class="text-dark mt-1">{{ __('words.Tutorial') }}</div>
            </div>
        </a>
        <a href="('rules')}}" style="text-decoration: none;">
            <div class="menu-list-item text-center">
                <img src="{{\Storage::url('assets/images/menu/rules.png')}}" width="45px" class="mt-1">
                <div class="text-dark mt-1">{{ __('words.Rules') }}</div>
            </div>
        </a>
    </div>
</div>
<div class="menu-list" id="login-item">
    <div class="menu-list-box bg-white" id="login-box">
        <div class="text-center">
            <img src="{{\Storage::url('assets/images/menu/login.png')}}" width="70px" class="mt-1 mx-auto">
            <div class="font-weight-bold mt-1 pb-2" style="font-size: 20px;border-bottom: solid 1px #ddd;">{{ __('words.login') }}</div>
        </div>
        <form method="POST" action="">
            @csrf
            <div class="login-menu-item mt-2">
                <i class="fa fa-user login-menu-item-icon" style="float:right;"></i>
                <input type="text" name="username" style="width: 82%;filter: none;" class="login-menu-input" placeholder="{{__('words.Username')}}" oninput="setCustomValidity('')">
            </div>
            <div class="login-menu-item mt-2">
                <i class="fa fa-key login-menu-item-icon" style="float:right;"></i>
                <input type="password" name="password" style="width: 82%;filter: none;" class="login-menu-input" placeholder="******" oninput="setCustomValidity('')">
            </div>
            <button type="submit" class="btn btn-success mt-2 rounded-pill" style="width: 100%;height: 40px;">{{ __('words.Enter') }}</button>
        </form>
        <div class="px-1 mt-2" style="border-top: solid 1px #ccc;height: 40px;line-height: 40px;">
            <a href="('register')}}" style="float: right;">{{ __('words.Register') }}</a>
            <a href=""  style="float: left;">{{ __('words.forget_password') }}</a>
        </div>
    </div>
</div>

<style type="text/css">
    @media (max-width: 1000px) {
        .responsive-logo{height: 35px;margin-top: 3px;}
    }
    @media (max-width: 340px) {
        .responsive-logo{height: 30px;margin-top: 6px;}
    }
</style>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).scroll(function () {scrollfun()});
        });

        function scrollfun() {
            if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                $(".top-menu-nav").css("background-color","#ffffffc4");
            } else {
                $(".top-menu-nav").css("background-color","");
            }
        }

        function menu_item() {
            $('#login-item').css('display','none');
            $('#menu-item').toggle();
        }
        function login_item() {
            $('#menu-item').css('display','none');
            $('#login-item').toggle();
        }

        function language_btn() {
            Swal.fire({
                title: "{{ __('words.language_select') }}"+' :',
                html: '<br>'
                    ,
                showCancelButton: true,
                showConfirmButton: false,

                cancelButtonColor: '#d33',
                cancelButtonText: "{{ __('words.Cancel') }}",
            })
        }

        $('document').ready(function () {
            $(document).mouseup(function(e)
            {
                var menu_list_box = $(".menu-list-box");
                var menu_toggler_btn = $(".menu-toggler-btn");
                var menu_list = $(".menu-list");

                if (!menu_list_box.is(e.target) && menu_list_box.has(e.target).length === 0 && !menu_toggler_btn.is(e.target) && menu_toggler_btn.has(e.target).length === 0) {
                    menu_list.hide();
                }
            });
        })
    </script>
@endpush

