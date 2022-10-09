
$(function () {
    $.datetimepicker.setLocale('ru');
    $('#datetime').datetimepicker({        
        format:'d.m.Y H:i',
        formatTime:'H:i',
        formatDate:'d.m.Y'        
    });    
    Inputmask().mask(document.querySelectorAll("input[data-inputmask]"));
    let mainMenuOffset = $('.header').offset();
    $(window).on('scroll', function () {
        let st = $(this).scrollTop();
        $('.main-part').toggleClass('header--fixed', st > mainMenuOffset.top);
    });
    $('.index-banner__slider').slick({
        arrows: true,
        dots: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        fade: true,
        speed: 1000,
        cssEase: 'linear',
        autoplay: true,
        autoplaySpeed: 3000
    });
    $('.slider, .team-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        infinite: true,
        //autoplay: true,
        autoplaySpeed: 3000
    });


    $('.news-slider, .coop-slider').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: true,
        infinite: false,
        //autoplay: true,
        autoplaySpeed: 3000
    });
    $('.video-slider, .menu-slider').slick({
        arrows: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: false,
        //autoplay: true,
        autoplaySpeed: 3000
    });



    // Tabs
    $('.tabs-menu__item').on('click', function () {
        let menu = $(this).parent();
        menu.find('.tabs-menu__item').removeClass('active');
        menu.siblings().removeClass('active');
        let tid = '#' + $(this).addClass('active').data('tab');
        let el = $(tid).addClass('active');
        if (el.length) {
            setTabsHeigh();
            if (el.hasClass('needscroll')) {
                if ($(window).scrollTop() + $(window).height() < el.offset().top + el.height()) {
                    $('html,body').animate({ scrollTop: el.offset().top + el.height() + 80 - $(window).height() }, 1000);
                }
            }
        }
    });
    setTabsHeigh();
    function setTabsHeigh() {
        let childMenuHeigh = $('.parent-tabs>.tabs__item.active .tabs-menu').height();
        let childTabHeigh;
        if (childMenuHeigh != undefined) {
            childTabHeigh = $('.parent-tabs>.tabs__item.active .tabs__item.active').height();
        } else {
            childMenuHeigh = $('.child-tabs > .tabs-menu').height();
            childTabHeigh = $('.child-tabs .tabs__item.active').height();
        }
        $('.child-tabs').css('min-height', childMenuHeigh + childTabHeigh);
        let menuHeigh = $('.parent-tabs>.tabs-menu').height();
        let tabHeigh = $('.parent-tabs>.tabs__item.active').height();
        $('.parent-tabs').css('min-height', menuHeigh + tabHeigh + 140);
    }


    // Faq Accordion
    let faq = $('.faq__item');
    faq.on('click', function () {
        let el = $(this);
        if (!el.hasClass('active')) {
            faq.removeClass('active');
        }
        $(this).toggleClass('active');
    });


    $('.team-item__expand').on('click', function () {
        let p = $(this).parents('.team-item__description, .news-slide--expand').toggleClass('active');
        if (p.hasClass('active')) {
            p.addClass('no-shadow');
        }
    });
    $('.team-slider, .news-slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
        $(this).find('.team-item__description, .news-slide--expand').removeClass('active');
    });
    $('.news-slide__cnt').on('transitionend', function () {
        if (!$(this).hasClass('active')) {
            $(this).removeClass('no-shadow');
            console.log('stop');
        }
    });

});