const mobileTestimonial = new Swiper(".testimonial-mobile-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: true,
    navigation: {
        nextEl: ".testimonial-nav-next",
        prevEl: ".testimonial-nav-prev",
    },

    breakpoints: {
        600: {
            slidesPerView: 2,
        },
    }
});

const pdpThumbsSwiper = new Swiper('.pdp-image-thumbnails', {
    direction: 'horizontal', 
    slidesPerView: 5,
    spaceBetween: 5,
    // grabCursor: true,
    // watchSlidesProgress: true,
    // slideToClickedSlide: true,

    breakpoints: {
        425: {
            direction: 'vertical',
        },
    }
});

const pdpMainSwiper = new Swiper('.pdp-image-carousel', {
    slidesPerView: 1,
    centeredSlides: true,
    loop: true,
    speed: 400,
    spaceBetween: 8,
    grabCursor: true,
    preloadImages: false,
    lazy: {
        loadPrevNext: true,
    },

    thumbs: {
        swiper: pdpThumbsSwiper,
    },
    breakpoints: {
        425: {
            slidesPerView: 1
        },
        550: {
            slidesPerView: 1.2
        },
        700: {
            slidesPerView: 1.4
        },
        1000: {
            slidesPerView: 1
        },
    }
});






