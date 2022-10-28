
$(function () {
    $('.do-animate').scrolla({
        once: true
    });    
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
    $('.schedule').slick({
        arrows: true,
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: false,
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



    // Tabs
    $('.tabs-menu__item').on('click', function () {
        let menu = $(this).parent();
        menu.find('.tabs-menu__item.active').each(function() {
           $('#' + $(this).removeClass('active').data('tab')).removeClass('active');           
        });
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


    // Forms
    $.datetimepicker.setLocale('ru');
    $('#datetime').datetimepicker({
        format: 'd.m.Y H:i',
        formatTime: 'H:i',
        formatDate: 'd.m.Y',
        allowTimes: [
            '10:00', '10:15', '10:30', '10:45',
            '11:00', '11:15', '11:30', '11:45',
            '12:00', '12:15', '12:30', '12:45',
            '13:00', '13:15', '13:30', '13:45',
            '14:00', '14:15', '14:30', '14:45',
            '15:00', '15:15', '15:30', '15:45',
            '16:00', '16:15', '16:30', '16:45',
            '17:00', '17:15', '17:30', '17:45',
            '18:00', '18:15', '18:30', '18:45',
            '19:00', '19:15', '19:30', '19:45',
            '20:00', '20:15', '20:30', '20:45',
            '21:00', '21:15', '21:30', '21:45'
        ]
    });
    Inputmask().mask(document.querySelectorAll("input[data-inputmask]"));
    const checkAgreeCb = function (element) {
        let el = $(element);
        let id = el.data('btnid');
        let state = el.is(':checked');
        $('#' + id).attr('disabled', () => !state ? 'disabled' : null);
    }
    $('[data-btnid]').on('change', function () {
        checkAgreeCb(this);
    }).each((i, e) => checkAgreeCb(e));
    $('[data-link]').on('change', function () {
        let id = '#' + $(this).data('link');
        let val = $(this).val();
        $(id).prop('selectedIndex', 0);
        $(id + " option").each(function(){
            let el = $(this);
            let p = el.data('par');
            if (Array.isArray(p)) {
                if (p.includes(val)) {
                    el.removeAttr('hidden');
                } else {                    
                    el.attr('hidden', 'hidden');
                }
            }
        });
    });
    $('.ajax-form').on('submit', function (e) {
        let form = $(this);        
        e.preventDefault();
        grecaptcha.ready(function () {
            grecaptcha.execute(reCAPTCHA_site_key, { action: 'submit' }).then(function (token) {
                let data = form.serializeArray();
                data.push({
                    name: 'token',
                    value: token
                });
                $.post('/send.json', data, (resp) => {
                    if (resp.status == "ok") {
                        form.html(resp.message);
                    }
                }, 'json');
            });
        });
    });
    $(".show-popup").on('click', function(e) {
        e.preventDefault();
        $('#' + $(this).data("pid")).addClass("active")
        .find(".form").addClass("animate__animated");
    });
    $(".form__close").on('click', function(e) {
        e.preventDefault();
        $(this).parents('.form__popup').removeClass("active");
    });    
});