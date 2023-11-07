<div>

    <livewire:pages.tournaments.index.slider-tournaments />

    <livewire:pages.tournaments.index.search-tournaments />

    <div class="container">

        <livewire:pages.tournaments.index.solo-tournaments />
        <livewire:pages.tournaments.index.team-tournaments />

    </div>

    <livewire:pages.tournaments.index.galary-tournaments />

    <livewire:pages.tournaments.index.bracket-tournaments />

</div>

@push('styles')
    <style type="text/css">
        @media screen and (min-width: 1000px) {
            .menu-dark-icons {
                display: none;
            }

            .menu-white-icons {
                display: block;
            }
        }

        h2 {
            font-size: 18px !important;
            padding: 0 !important;
        }

        #main {
            margin-top: 10px !important;
        }

        @media screen and (max-width: 1000px) {
            #main {
                margin-top: 80px !important;
            }
        }

        .tournament-top-slider {
            height: 250px;
            background-color: rgba(0, 0, 0, 0.71);
            padding-top: 130px;
            font-size: 23px;
            line-height: 40px;
        }

        .tour-srch {
            width: max-content;
            border-radius: 50px;
        }

        .tour-srch-btn {
            width: 150px;
            height: 50px;
            line-height: 50px;
            text-align: center;
            background-color: #009973;
            border-radius: 50px;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        .tour-srch-btn:hover {
            background-color: #007e5b;
        }

        #tour-srch-btn-spn {
            margin: 0 10px;
            position: relative;
            bottom: 2px;
        }

        .tour-srch-box {
            display: none;
        }

        .tour-srch-input-box {
            width: 235px;
            text-align: center;
        }

        .tour-srch-input {
            height: 30px;
            border: none;
            margin-top: 10px;
            border-bottom: solid 2px #009973;
            padding: 0 5px;
            width: 70%;
            color: #777;
        }

        .tour-slide {
            width: auto !important;
        }

        .tournament-score {
            background-color: rgba(80, 80, 80, 0.7);
        }

        @media screen and (max-width: 1200px) {
            .tour-srch-input-box {
                width: 190px;
            }
        }

        @media screen and (max-width: 1000px) {
            .tournament-top-slider {
                height: 200px;
                padding-top: 60px;
            }

            .tour-srch-btn {
                width: 50px;
                border-radius: 50%;
                font-size: 20px;
            }

            #tour-srch-btn-spn {
                display: none;
            }

            .tour-srch {
                border-radius: 25px;
            }

            .tour-srch-input-box {
                width: 100%;
            }

            .tour-srch-input {
                width: 80%;
                height: 40px;
                margin-top: 10px;
            }

            .tour-srch-submit {
                background-color: #009973 !important;
                color: #fff !important;
                width: 80%;
                margin: 10px auto;
            }

            .tour-bracket-box {
                width: 100% !important;
            }
        }

        @media screen and (min-width: 1000px) {
            .tour-srch-submit {
                width: 150px;
                height: 40px;
                line-height: 40px;
                margin: 5px 0;
                text-align: center;
                background-color: #009973 !important;
                border-radius: 50px !important;
                color: #fff !important;
                font-size: 18px;
                cursor: pointer;
                transition: 0.3s;
            }
        }
    </style>

    <link
        href="{{ asset('assets/css/swiper.min.css') }}"
        rel="stylesheet"
    >
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/swiper.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.tour-srch-btn').on('click', function() {
                var srch = $('.tour-srch');
                var srch_box = $('.tour-srch-box');
                var search_btn = $('.tour-srch-btn');
                if (screen.width < 1000) {
                    search_btn.css('display', 'none');
                    srch.animate({
                        'width': '100%',
                        'height': '270px'
                    }, 500, function() {
                        srch_box.fadeIn();
                    });
                } else {
                    srch.animate({
                        'width': '100%'
                    }, 500, function() {
                        search_btn.css('display', 'none');
                        srch_box.fadeIn();
                    });
                }

            });
        });

        $(document).scroll(function() {
            tournament_scrollfun();
        });

        function tournament_scrollfun() {
            if (screen.width > 1000) {
                if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                    $(".menu-white-icons").css("display", "none");
                    $(".menu-dark-icons").css("display", "block");
                } else {
                    $(".menu-dark-icons").css("display", "none");
                    $(".menu-white-icons").css("display", "block");
                }
            }
        }

        function get_state(val) {
            // + "?country_id=" + val + "&state="
            /* $.get("" , function(data) {
                $('#state-input').html("<option value=''>{{ __('words.all_states') }}</option>" + data);
            }); */
        }

        var swiper = new Swiper('.tour-top-slider', {
            slidesPerView: 1,
            spaceBetween: 20,
            slidesPerGroup: 1,
            loop: true,
            loopFillGroupWithBlank: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: true,
            },
        });

        var swiper = new Swiper('.solo-tour-slider', {
            slidesPerView: 'auto',
            spaceBetween: 30,
            freeMode: true,
        });

        var swiper = new Swiper('.team-tour-slider', {
            slidesPerView: 'auto',
            spaceBetween: 30,
            freeMode: true,
        });

        var swiper = new Swiper('.gallery-slider', {
            slidesPerView: 'auto',
            spaceBetween: 30,
            slidesPerGroup: 1,
            centeredSlides: true,
            loop: true,
            loopFillGroupWithBlank: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: true,
            },
            pagination: {
                el: '.gallery-pagination',
                clickable: true,
                dynamicBullets: true,
            },
        });

        var swiper = new Swiper('.bracket-slider', {
            slidesPerView: 'auto',
            spaceBetween: 30,
            loop: true,
            loopFillGroupWithBlank: true,
        });

        var swiper = new Swiper('.score-slider', {
            slidesPerView: 'auto',
            spaceBetween: 30,
            loop: true,
            loopFillGroupWithBlank: true,
            on: {
                slideChange: function() {
                    $('#aaaa').css('background',
                        'url("{{ url('uploads/box-300x135-medium.jpg') }}") !important');
                }
            },
        });
    </script>
@endpush
