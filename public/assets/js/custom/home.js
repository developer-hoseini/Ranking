$(document).ready(function () {

    setTimeout(function() {
        $('.menu-nav-btns').animate({'bottom' : 0}, 300 , function () {
            $('#menu-nav-c-btn-1').animate({'right' : 0} , 400);
            $('#menu-nav-c-btn-2').animate({'right' : 0} , 400);
            $('#menu-nav-c-btn-4').animate({'left' : 0} , 400);
            $('#menu-nav-c-btn-5').animate({'left': 0} , 400 , function () {
                $('.responsive-logo').animate({'opacity':1},400);
            });
        });

    }, 200 );


    $("[rel=tooltip]").tooltip({placement: 'left'});
});

var swiper = new Swiper('#top-slider', {
    slidesPerView: 1,
    spaceBetween: 30,
    slidesPerGroup: 1,
    loop: true,
    loopFillGroupWithBlank: true,
    effect: 'fade',
    autoplay: {
        delay: 4000,
        disableOnInteraction: true,
    },
});

var swiper = new Swiper('#ranks-slider', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    loopFillGroupWithBlank: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: true,
    },
    pagination: {
        el: '#ranks-slider-pagination',
        clickable: true,
        dynamicBullets: true,
    },
    breakpoints: {
        770: {slidesPerView: 2,},
    }
});

var swiper = new Swiper('#tournament-slider', {
    slidesPerView: 'auto',
    spaceBetween: 25,
    freeMode: true,
});

var swiper = new Swiper('#tournament-gallery-slider', {
    slidesPerView: 'auto',
    spaceBetween: 20,
    slidesPerGroup: 1,
    centeredSlides: true,
    loop: true,
    loopFillGroupWithBlank: true,
    autoplay: {
        delay: 4500,
        disableOnInteraction: true,
    },
    pagination: {
        el: '#tournament-gallery-slider-pagination',
        clickable: true,
        dynamicBullets: true,
    },
});

var swiper = new Swiper('#tournament-bracket-slider', {
    slidesPerView: 1,
    spaceBetween: 30,
    slidesPerGroup: 1,
    loop: true,
    loopFillGroupWithBlank: true,
    autoplay: {
        delay: 5500,
        disableOnInteraction: true,
    },
    pagination: {
        el: '#tournament-bracket-pagination',
        clickable: true,
        dynamicBullets: true,
    },
});

var swiper = new Swiper('#games-slider', {
    slidesPerView: 1,
    spaceBetween: 30,
    slidesPerGroup: 1,
    loop: true,
    loopFillGroupWithBlank: true,
    autoplay: {
        delay: 4500,
        disableOnInteraction: true,
    },
    pagination: {
        el: '#games-slider-pagination',
        clickable: true,
        dynamicBullets: true,
    },
});

var swiper = new Swiper('#games-gallery-slider', {
    slidesPerView: 'auto',
    spaceBetween: 20,
    slidesPerGroup: 1,
    centeredSlides: true,
    loop: true,
    loopFillGroupWithBlank: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: true,
    },
    pagination: {
        el: '#games-gallery-pagination',
        clickable: true,
        dynamicBullets: true,
    },
});

var swiper = new Swiper('#team-ranks-slider', {
    slidesPerView: 2,
    spaceBetween: 30,
    loop: true,
    loopFillGroupWithBlank: true,
    autoplay: {
        delay: 5500,
        disableOnInteraction: true,
    },
    pagination: {
        el: '#team-ranks-pagination',
        clickable: true,
        dynamicBullets: true,
    },
    breakpoints: {
        770: {slidesPerView: 3,},
    }
});

var swiper = new Swiper('#statistic-slider', {
    slidesPerView: 2,
    spaceBetween: 30,
    slidesPerGroup: 2,
    autoplay: {
        delay: 4500,
        disableOnInteraction: true,
    },
    breakpoints: {
        770: {
            slidesPerView: 5,
        },
    }
});
