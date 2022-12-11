
$(function () {
    $('.do-animate').scrolla({
        once: true
    });

    const hscrollCheck = function ($elem) {
        const newScrollLeft = $elem.scrollLeft(),
            width = $elem.outerWidth(),
            scrollWidth = $elem.get(0).scrollWidth,
            hc = $elem.hasClass("horizontal-scroll--hs");
        $elem.toggleClass("left", hc && !newScrollLeft);
        $elem.toggleClass("right", hc && newScrollLeft + width + 1 >= scrollWidth);
    };
    const hscroll = $(".horizontal-scroll").on("scroll", function () {
        hscrollCheck($(this));
    });
    let mousedownID = -1;
    const hsStartScroll = function(arrow, cnt) {
        if (mousedownID == -1) {
            console.log("start");
            mousedownID = setInterval(()=>{                
                if($(arrow).hasClass("horizontal-scroll__left")) {
                    cnt.scrollLeft-=5;
                } else {
                    cnt.scrollLeft+=5;
                }
            }, 50);                            
        } 
    }
    const hsStopScroll = function() {
        if (mousedownID != -1) {                            
            clearInterval(mousedownID);
            mousedownID = -1;
            console.log("stop");
        }
    }
    const hscrollSet = function () {
        hscroll.each((_, el) => {
            const $elem = $(el),
            hasScroll = el.scrollWidth > el.clientWidth,
            hasNav = $elem.next(".horizontal-scroll__nav-row");
            $elem.toggleClass("horizontal-scroll--hs", hasScroll);
            hscrollCheck($elem);
            if (hasScroll) {
                if (!hasNav.length) {
                    $('<div class="horizontal-scroll__nav-row"><button class="horizontal-scroll__left slider-arrow slick-prev"><</button><button class="horizontal-scroll__right slider-arrow slick-next">></button></div>')                    
                    .on("mousedown touchstart", function(e){                        
                        hsStartScroll(e.target, el);      
                    })
                    .on("mouseup mouseout touchend touchcancel", () => hsStopScroll())                    
                    .insertAfter($elem)
                }
            } else {
                hasNav.remove();
            }                        
        });
    };
    hscrollSet();
    

    let mainMenuOffset = $('.header').offset();
    $(window)
        .on('scroll', function () {
            let st = $(this).scrollTop();
            $('.main-part').toggleClass('header--fixed', st > mainMenuOffset.top);
        })
        .on('resize', function () {
            hscrollSet();
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
        autoplaySpeed: 3000,
        responsive: [
            {
                breakpoint: 576,
                settings: {
                    arrows: false
                }
            }
        ]
    });    
    $('.slider, .team-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        infinite: true,
        //autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });


    $('.news-slider, .tabs__item.active .coop-slider').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: true,
        infinite: false,
        //autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
    $('.video-slider, .menu-slider').slick({
        arrows: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: false,
        //autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    

    function mediaQueryHandler(query) {
        if (query.matches) {
            $(".visit-cond__cnt:not(.slick-initialized)").slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                dots: false,
                infinite: false
            });
            $('.schedule.slick-initialized').slick("unslick");                      
        } else {
            $(".visit-cond__cnt.slick-initialized").slick("unslick");
            $('.schedule:not(.slick-initialized)').slick({
                arrows: true,
                dots: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: false,
            });            
        }
    }
    let mq = window.matchMedia("(max-width:576px)");
    mediaQueryHandler(mq);
    mq.addListener(mediaQueryHandler);




    $(".main-menu__mbtn, .main-menu__close").on("click", function (e) {
        $(".main-menu__container").toggleClass("main-menu--show");
    });
    $(".main-menu__item.has-submenu>a").on("click", function (e) {
        if (mq.matches) {
            const sub = $(this).next();
            if (!sub.hasClass("active")) {
                sub.addClass("active");
                e.preventDefault();
                return false;
            }
        }
    });

    let expandedItem;    
    $(document).on("mouseup", function (e) {
        if (expandedItem && expandedItem.hasClass('active') && !expandedItem.is(e.target) && expandedItem.has(e.target).length === 0) {
            expandedItem.removeClass('active');
        }
        if ($(e.target).parents(".team-item__expand").length) {
            expandedItem = $(e.target).parents('.team-item__description, .news-slide--expand').toggleClass('active');
            if (expandedItem.hasClass('active')) {
                expandedItem.addClass('no-shadow');
            }
        }        
    });

    $('.news-slide__cnt').on('transitionend', function () {
        if (!$(this).hasClass('active')) {
            $(this).removeClass('no-shadow');            
        }
    });

    $(".text-expand__button").on("click", function () {
        const fl = $(this).toggleClass("active").hasClass("active");
        $(this).parent().prev().toggleClass("active", fl);
        $(this).find("span").text(fl ? "Свернуть" : "Читать всё");        
    });

    // Tabs    
    $('.tabs-menu__item').on('click', function () {
        let menu = $(this).parent();
        menu.find('.tabs-menu__item.active').each(function () {
            $('#' + $(this).removeClass('active').data('tab'))
            .removeClass('active')
            .find(".slick-initialized").slick("unslick");
        });
        let tid = '#' + $(this).addClass('active').data('tab');
        let el = $(tid).addClass('active');
        if (el.length) {            
            if (el.hasClass('needscroll')) {
                if ($(window).scrollTop() + $(window).height() < el.offset().top + el.height()) {
                    $('html,body').animate({ scrollTop: el.offset().top + el.height() + 80 - $(window).height() }, 1000);
                }
            }
            el.find(".coop-slider").slick({
                slidesToShow: 2,
                slidesToScroll: 1,
                arrows: true,
                infinite: false,
                //autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
            el.find(".team-slider").slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: true,
                infinite: true,
                //autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }
    });
    $(".tabs__item:not(.active) .slick-initialized").slick("unslick");
    

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
    /*
    $('#datePicker').datetimepicker({
        format: 'd.m.Y',        
        formatDate: 'd.m.Y',
        timepicker: false
    });
    */
    //$('#datePicker').on('input', e => e.target.dispatchEvent(new Event('input')));
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
        $(id + " option").each(function () {
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
    let options;
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
                if (options != undefined) {
                    for (const [key, value] of Object.entries(options)) {
                        data.push({
                            name: "option_" + key,
                            value: value
                        });
                    }
                }
                $.post('/send.json', data, (resp) => {
                    if (resp.status == "ok") {
                        form.html(resp.message);
                    }
                }, 'json');
            });
        });
    });
    $(".show-popup").on('click', function (e) {
        e.preventDefault();
        let btn = $(this);
        let formContainer = $('#' + btn.data("pid"))
        options = btn.data("options");
        if (options != undefined) {
            for (const [key, value] of Object.entries(options)) {
                formContainer.find(".option__" + key).text(value);
            }
        }
        formContainer.addClass("active")
            .find(".form").addClass("animate__animated");
    });
    $(".form__close").on('click', function (e) {
        e.preventDefault();
        options = undefined;
        $(this).parents('.form__popup').removeClass("active");
    });



});