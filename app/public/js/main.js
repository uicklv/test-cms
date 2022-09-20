import {
    SafariSwiper,
    isInViewport,
    getPosition,
    closest,
    MetaSwiper
} from './meta-settings.js';

import Swiper from 'https://unpkg.com/swiper@8/swiper-bundle.esm.browser.min.js';

$(document).ready(function($) {
        
//Слайдер на страничке Tech.html --------------------------------------------------------------------------------------------------------------
    $('.alerts-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
    });

//Слайдер на страничке  About_Us.html--------------------------------------------------------------------------------------------------------------
//     $('.un-slider').slick({
//         slidesToShow: 1,
//         slidesToScroll: 1,
//         arrows: true,
//         dots: true,
//     });

//Меню ------------------------------------------------------------------------------------
//     $(".menu-icon").click(function () {
//         $("#site").addClass('open-menu');
//     });
//
//     $(".close-menu").click(function () {
//         $("#site").removeClass('open-menu');
//     });


    $(".menu").accordion({
        accordion:true,
        speed: 500,
        closedSign: '',
        openedSign: ''
    });

// //Плавнаяч прокрутка--------------------------------------------------------------------
// var linkNav = document.querySelectorAll('[href^="#"]'),
//     V = 0.5;  // скорость, может иметь дробное значение через точку
// for (var i = 0; i < linkNav.length; i++) {
//   linkNav[i].addEventListener('click', function(e) {
//     e.preventDefault();
//     var w = window.pageYOffset,  // прокрутка
//         hash = this.href.replace(/[^#]*(.*)/, '$1');  // id элемента, к которому нужно перейти
//         t = document.querySelector(hash).getBoundingClientRect().top,  // отступ от окна браузера до id
//         start = null;
//     requestAnimationFrame(step);  // подробнее про функцию анимации [developer.mozilla.org]
//     function step(time) {
//       if (start === null) start = time;
//       var progress = time - start,
//           r = (t < 0 ? Math.max(w - progress/V, w + t) : Math.min(w + progress/V, w + t));
//       window.scrollTo(0,r);
//       if (r != w + t) {
//         requestAnimationFrame(step)
//       } else {
//         location.hash = hash  // URL с хэшем
//       }
//     }
//   }, false);
// }

// закладки на страничке Specialisms.html --------------------------------------------------------------------------------------------------------------

    // $('.sb-menu').on('click', 'li:not(.active)', function() {
    //     $(this).addClass('active').siblings().removeClass('active').parents('.specialist-block').find('.specialist-box').eq($(this).index()).fadeIn(150).siblings('.specialist-box').hide();
    // })
    // $(".sb-menu li").eq(0).click();

// Слайдер на главной страничке (первый экран) --------------------------------------------------------------------------------------------------------------
//     //Большой слайдер
//     $('.head-slider').slick({
//         dots: true,
//         fade: true,
//         arrows: false,
//         autoplay: true
//     });
//
//     $(".hc-slider-pic").each(function(){
//         var src = $(this).find('.hc-slider-img img').attr('src');
//         //alert(src );
//         $(this).css('backgroundImage', "url(" + src + ")");
//     });

// Слайдер на главной страничке --------------------------------------------------------------------------------------------------------------
//     $('.roles-slider').slick({
//         slidesToShow: 3,
//         slidesToScroll: 1,
//         arrows: true,
//         dots: false,
//         responsive: [
//             {
//                 breakpoint: 1024,
//                 settings: {
//                     slidesToShow: 2,
//                 }
//             },
//             {
//                 breakpoint: 650,
//                 settings: {
//                     slidesToShow: 1,
//                 }
//             }
//         ]
//     });

// Всплывалка --------------------------------------------------------------------------------------------------------------
//     $(".btn-open-popup").click(function(e){
//         e.preventDefault();
//         $("#site").addClass('popup-open');
//     });
//
//     $(".popup-fon, .close-popup").click(function(){
//         $("#site").removeClass('popup-open');
//     });

// закладки на страничке Edison.html--------------------------------------------------------------------------------------------------------------
    // закладки
    $('.ed-bookmark').on('click', 'li:not(.active)', function() {
        $(this).addClass('active').siblings().removeClass('active').parents('.ed-tabs').find('.ed-bookmarker-box').eq($(this).index()).fadeIn(150).siblings('.ed-bookmarker-box').hide();
    })
    $(".ed-bookmark li").eq(0).click();

// Замена стандартного селекта --------------------------------------------------------------------------------------------------------------
//     $( ".select" ).selectmenu();

// Добавление поля для ссылки Linked  на страничке Contact.html --------------------------------------------------------------------------------------------------------------
    $(".cf-linked-open").click(function () {
        $(".cf-linked").show(100);
    });

// Анимация при скроле--------------------------------------------------------------------------------------------------------------
    AOS.init({
        useClassNames: true,
        initClassName: false,
        animatedClassName: 'animated',
        once: true,
    });

// Анимация шапки----------------------------------------------------------------------------------------------------------------
    $(window).scroll(function(){
        if ($(this).scrollTop() > 20) {
            $('.header').addClass('scroll-active');
        } else {
            $('.header').removeClass('scroll-active');
        }
    });


// Всё для страниц Shop

    const controller = new ScrollMagic.Controller({
    });

    const shopHomeSlider = document.getElementsByClassName(`shop-home-slider`)[0];
    if (shopHomeSlider) {
        // const jobsArr = Array.from(jobSection.querySelectorAll('.swiper-slide'));

        MetaSwiper(".shop-home-slider", {
            observer: true,
            speed: 600,
            effect: "fade",
            // Autoplay
            // autoplay: {
            //     delay: 5000,
            //     disableOnInteraction: false,
            //     pauseOnMouseEnter: true,
            // },
            // Navigation
            slidesPerView: 1,
            spaceBetween: 30,
            loop: false,
            pagination: {
                el: ".shop-home-slider .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".shop-home-slider .swiper-button-next",
                prevEl: ".shop-home-slider .swiper-button-prev",
            },
        });
    }

    const popularProductsSlider = document.getElementsByClassName(`popular-products-slider`)[0];
    if (popularProductsSlider) {
        // const jobsArr = Array.from(jobSection.querySelectorAll('.swiper-slide'));

        console.log(popularProductsSlider)

        MetaSwiper(`.popular-products-slider`, {
            observer: true,
            speed: 600,
            // Autoplay
            // autoplay: {
            //     delay: 5000,
            //     disableOnInteraction: false,
            //     pauseOnMouseEnter: true,
            // },
            // Navigation
            slidesPerView: 4,
            spaceBetween: 20,
            loop: false,

            breakpoints: {
                1100: {
                    slidesPerView: 4,
                },
                769: {
                    slidesPerView: 3,
                },
                500: {
                    slidesPerView: 2,
                },
                300: {
                    slidesPerView: 1,
                },
            },
        });
    }

    const showProductsSlider = document.getElementsByClassName(`show-products-slider`)[0];
    if (showProductsSlider) {
        // const jobsArr = Array.from(jobSection.querySelectorAll('.swiper-slide'));

        MetaSwiper(`.show-products-slider`, {
            observer: true,
            speed: 600,
            // Autoplay
            // autoplay: {
            //     delay: 5000,
            //     disableOnInteraction: false,
            //     pauseOnMouseEnter: true,
            // },
            // Navigation
            slidesPerView: 4,
            spaceBetween: 20,
            loop: false,
            // pagination: {
            //     el: ".shop-home-slider .swiper-pagination",
            //     clickable: true,
            // },
            // navigation: {
            //     nextEl: ".shop-home-slider .swiper-button-next",
            //     prevEl: ".shop-home-slider .swiper-button-prev",
            // },
            breakpoints: {
                1100: {
                    slidesPerView: 4,
                },
                769: {
                    slidesPerView: 3,
                },
                500: {
                    slidesPerView: 2,
                },
                300: {
                    slidesPerView: 1,
                },
            },
        });
    }

    const thumbsProductsSliders = document.getElementsByClassName(`products-thumbs-slider`)[0];
    if (thumbsProductsSliders) {

        const thumbsSwiperBottom = new Swiper(".thumbs-slider-bottom", {
            spaceBetween: 20,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
            slideToClickedSlide: true,
        });
        SafariSwiper(thumbsSwiperBottom);

        const thumbsSwiperTop = new Swiper(".thumbs-slider-top", {
            spaceBetween: 20,
            thumbs: {
                swiper: thumbsSwiperBottom,
            },

        });
        SafariSwiper(thumbsSwiperTop);


        const bottomSlider = thumbsProductsSliders.querySelectorAll('.thumbs-slider-bottom .swiper-slide');
        bottomSlider.forEach((elem, index) => {
            elem.addEventListener('click', (e) => {
                thumbsSwiperTop.slideTo(index);
            })
        });
    }

    const sidebarProducts = () => {

        if (document.getElementsByClassName('page-with-sidebar__container')[0]) {
            const container = document.querySelector('.page-with-sidebar__container'),
                trigger = container.querySelector('.trigger');

            let sidebarDuration = (container.offsetHeight - (trigger.offsetTop * 2.5));

            new ScrollMagic.Scene({
                duration: sidebarDuration, // Продолжительность сцены в px
                // offset: `50vh`, // Отложить запуск сцены на 50 px
                triggerElement: `.trigger`, // Тригер элемент, где запускать сцену

            })
                .setPin(`.products-sidebar__container`) // закрепляет элемент на время сцены
                .addTo(controller) // добавить сцену в контроллер
                // .addIndicators();

        }
    };

    // sidebarProducts();

    // Аккордеон
    //
    // const accordionFiltersProducts = () => {
    //     const btn = document.querySelectorAll('.accordion-trigger'),
    //         item = document.querySelectorAll('.accordion-content');
    //
    //     if (document.querySelector('.products-sidebar__accordion')) {
    //         btn.forEach((elem, index) => {
    //             elem.addEventListener('click', (event) => {
    //                 let target = event.target;
    //                 target = target.closest('.accordion-trigger');
    //                 if (target.closest('.accordion-trigger.active')) {
    //                     elem.classList.remove('active')
    //                     item[index].classList.remove('active')
    //                 } else {
    //                     elem.classList.toggle('active')
    //                     item[index].classList.toggle('active')
    //                 }
    //             })
    //         })
    //     }
    // };
    // accordionFiltersProducts();
    //
    // OmRangeSlider.init();
    //
    // const doubleRangeDisplay = () => {
    //     const doubleRange = document.getElementsByClassName('double-range')[1];
    //
    //     if (doubleRange) {
    //         const displayBlock = document.querySelector('.om-sliderrange-display'),
    //             displaySpanArr = document.querySelectorAll('.om-sliderrange-display span');
    //
    //         const buttonStart = document.querySelector('.om-sliderrange-button-start'),
    //             buttonEnd = document.querySelector('.om-sliderrange-button-end');
    //
    //         displaySpanArr[0].style.left = buttonStart.offsetLeft + 'px';
    //         displaySpanArr[2].style.left = buttonEnd.offsetLeft + 'px';
    //
    //         doubleRange.addEventListener('mousemove', (e) => {
    //             displaySpanArr[0].style.left = buttonStart.offsetLeft + 'px';
    //             displaySpanArr[2].style.left = buttonEnd.offsetLeft + 'px';
    //         })
    //
    //         // console.log(doubleRange)
    //     }
    //
    // }
    // doubleRangeDisplay();
    // // setTimeout(doubleRangeDisplay, 1000)
    //
    // const cartPopup = () => {
    //
    //     if (document.getElementsByClassName('cart-popup')[0]) {
    //         const cartPopup = document.getElementsByClassName('cart-popup')[0],
    //             cartPopupBtn = document.querySelectorAll('.open-cart-popup'),
    //             cartPopupClose = cartPopup.querySelector('.cart-popup__close'),
    //             cartPopupBg = cartPopup.querySelector('.cart-popup__bg');
    //
    //         cartPopupBtn.forEach((elem) => {
    //             elem.addEventListener('click', (e) => {
    //                 e.preventDefault();
    //                 cartPopup.classList.add('active');
    //             })
    //         })
    //
    //         cartPopupClose.addEventListener('click', () => {
    //             cartPopup.classList.remove('active');
    //         })
    //
    //         cartPopupBg.addEventListener('click', () => {
    //             cartPopup.classList.remove('active');
    //         })
    //
    //         // calculator
    //
    //         const topItemsLength = cartPopup.querySelector(`.items-card-length`),
    //             cartList = cartPopup.querySelector(`.cart-list`),
    //             cartListItems = cartList.querySelectorAll(`.cart-list__item`),
    //             cartListPrice = cartPopup.querySelectorAll(`.cart-list__price`),
    //             visibleTotalPrice = cartPopup.querySelector(`.price-item span`);
    //
    //         topItemsLength.textContent = cartListItems.length + ` items`;
    //
    //         cartListItems.forEach((elem, i,) => {
    //             const counterBlock = elem.querySelector(`.counter-block`),
    //                 counterBtn = counterBlock.querySelectorAll(`.btn-shop`),
    //                 counterInput = counterBlock.querySelector(`.count-input`),
    //                 counterPrice = elem.querySelector(`.cart-list__price`);
    //
    //             let inputValue = +counterInput.value,
    //                 priceCounter = +counterPrice.textContent.replace(/[^0-9]/g,""),
    //                 firstPrice = +counterPrice.textContent.replace(/[^0-9]/g,"");
    //
    //             counterBtn[1].addEventListener(`click`, () => {
    //                 inputValue += 1;
    //                 counterInput.value = inputValue;
    //
    //                 priceCounter = firstPrice * inputValue;
    //                 counterPrice.textContent = priceCounter + ` $`;
    //                 priceTotalCount();
    //
    //             })
    //
    //             counterBtn[0].addEventListener(`click`, () => {
    //                 if (counterInput.value <= 1) return;
    //
    //                 inputValue -= 1;
    //                 counterInput.value = inputValue;
    //
    //                 priceCounter = firstPrice * inputValue;
    //                 counterPrice.textContent = priceCounter + ` $`;
    //                 priceTotalCount();
    //
    //             })
    //
    //             counterInput.addEventListener('input', (e) => {
    //                 counterInput.value = counterInput.value.replace(/[^\d.]/g, '');
    //
    //                 if (counterInput.value >= 1) {
    //                     inputValue = +counterInput.value;
    //
    //                     priceCounter = firstPrice * inputValue;
    //                     counterPrice.textContent = priceCounter + ` $`;
    //                     priceTotalCount();
    //
    //                 } else if (counterInput.value <= 0) {
    //                     counterInput.value = 1;
    //                     inputValue = +counterInput.value;
    //
    //                     priceCounter = firstPrice * inputValue;
    //                     counterPrice.textContent = priceCounter + ` $`;
    //                     priceTotalCount();
    //                 }
    //             })
    //
    //         })
    //
    //         const priceTotalCount = () => {
    //             let arrPrice = [];
    //             cartListPrice.forEach((elem, i) => {
    //                 arrPrice.push(+elem.textContent.replace(/[^0-9]/g,""));
    //                 visibleTotalPrice.textContent = summArrayElements(arrPrice) + ` $`;
    //             })
    //         }
    //
    //         function summArrayElements(arr){
    //             let x = 0;
    //             return arr.map(i=>x+=i, x).reverse()[0]
    //         }
    //
    //         priceTotalCount();
    //
    //     }
    // }
    //
    // cartPopup();
    //
    // // Products sidebar sticky
    // const productsSidebar = document.getElementsByClassName('products-sidebar')[0];
    // if (productsSidebar && window.innerWidth >= 769) {
    //
    //     if (document.getElementById('sidebar')) {
    //         var sidebar = new StickySidebar('#sidebar', {
    //             containerSelector: '.page-with-sidebar__container',
    //             innerWrapperSelector: '.products-sidebar__container',
    //             topSpacing: 40,
    //             bottomSpacing: 300,
    //             minWidth: 650,
    //         });
    //     }
    // }
    //
    // if (productsSidebar && window.innerWidth <= 769) {
    //
    //     const filtersBtnArr = document.querySelectorAll(`#filters-btn-shop`);
    //
    //     filtersBtnArr.forEach((elem) => {
    //         elem.addEventListener('click', (e) => {
    //
    //             productsSidebar.classList.toggle(`active`);
    //         })
    //     })
    //
    //     productsSidebar.addEventListener('click', (e) => {
    //         let target = e.target;
    //
    //         if(!target.closest('.products-sidebar__container')) {
    //             productsSidebar.classList.toggle(`active`);
    //         }
    //     })
    //
    // }
    //
    // Filters Dropdown

    const filtersDropdown = () => {
        if (document.getElementsByClassName(`filters-menu`)[0]) {
            const filtersMenu = document.getElementsByClassName(`filters-menu`)[0],
                filtersMenuBtn = filtersMenu.querySelector(`.filters-menu__active`),
                filtersMenuContainer = filtersMenu.querySelector(`ul`),
                filtersMenuItem = filtersMenu.querySelectorAll(`li`);

            filtersMenuBtn.addEventListener(`click`, () => {
                filtersMenuBtn.classList.toggle(`active`);
                filtersMenuContainer.classList.toggle(`active`);
            })

            filtersMenuItem.forEach((elem, i) => {
                elem.addEventListener(`click`, () => {
                    filtersMenuBtn.classList.remove(`active`);
                    filtersMenuContainer.classList.remove(`active`);
                    filtersMenuBtn.textContent = elem.textContent;

                    let sortValue = elem.getAttribute('data-value');
                    load('shop/search_process', 'form:#search_form', 'sort=' + sortValue);
                })
            })

            document.addEventListener('click', (e) => {
                let target = e.target;

                if(!target.closest('.filters-menu')) {
                    filtersMenuBtn.classList.remove(`active`);
                    filtersMenuContainer.classList.remove(`active`);
                }
            })

        }

    }
    filtersDropdown();

    const cartPopup = () => {
        const cartPopup = document.getElementsByClassName('cart-popup')[0];
        if (cartPopup) {
            function closeCartPopup () {
                cartPopup.classList.remove('active');
            }

            function sumArrayElements(arr){
                let x = 0;
                return arr.map(i=>x+=i, x).reverse()[0]
            }

            const priceTotalCount = () => {
                let arrPrice = [];
                cartListPrice.forEach((elem, i) => {
                    arrPrice.push(+elem.textContent.replace(/[^0-9]/g,""));
                    visibleTotalPrice.textContent = sumArrayElements(arrPrice) + ` $`;
                })
            }

            const cartPopupBtn = Array.from(document.getElementsByClassName('open-cart-popup'));
            const cartPopupBack = cartPopup.querySelector('.cart-popup__close');
            const cartPopupClose = cartPopup.querySelector('.cart-popup__close-btn');
            const cartPopupBg = cartPopup.querySelector('.cart-popup__bg');

            cartPopupBtn.forEach((elem) => {
                elem.addEventListener('click', (e) => {
                    e.preventDefault();
                    cartPopup.classList.add('active');
                })
            })

            cartPopupClose.addEventListener('click', closeCartPopup);
            cartPopupBack.addEventListener('click', closeCartPopup);
            cartPopupBg.addEventListener('click', closeCartPopup);

            // calculator
            const topItemsLength = cartPopup.querySelector(`.items-card-length`);
            const cartList = cartPopup.querySelector(`.cart-list`);
            const cartListItems = cartList.querySelectorAll(`.cart-list__item`);
            const cartListPrice = cartPopup.querySelectorAll(`.cart-list__price`);
            const visibleTotalPrice = cartPopup.querySelector(`.price-item span`);

            topItemsLength.textContent = cartListItems.length + ` items`;

            cartListItems.forEach((elem, i,) => {
                function recalculateCart() {
                    counterInput.value = inputValue;
                    priceCounter = firstPrice * inputValue;
                    counterPrice.textContent = priceCounter + ` $`;
                    priceTotalCount();
                }

                const counterBlock = elem.querySelector(`.counter-block`);
                const counterBtn = counterBlock.querySelectorAll(`.btn-shop`);
                // TODO:
                // const counterBtnPlus = counterBlock.querySelector(`.btn-shop`);
                // const counterBtnMinus = counterBlock.querySelector(`.btn-shop`);
                const counterInput = counterBlock.querySelector(`.count-input`);
                const counterPrice = elem.querySelector(`.cart-list__price`);

                let inputValue = +counterInput.value;
                let priceCounter = +counterPrice.textContent.replace(/[^0-9]/g,"");
                let firstPrice = +counterPrice.textContent.replace(/[^0-9]/g,"");

                // onclick Plus button
                counterBtn[1].addEventListener(`click`, () => {
                    inputValue >= 20 ? inputValue = 20 : inputValue += 1;
                    recalculateCart();
                })

                // onclick Minus button
                counterBtn[0].addEventListener(`click`, () => {
                    inputValue <= 1 ? inputValue = 1 : inputValue -= 1;
                    recalculateCart();
                })

                counterInput.addEventListener('input', (e) => {
                    counterInput.value = counterInput.value.replace(/[^\d.]/g, '');
                    if (counterInput.value <= 0) {
                        counterInput.value = 1;
                    } else if (counterInput.value >= 20) {
                        counterInput.value = 20;
                    }

                    recalculateCart();
                })
            })
            priceTotalCount();
        }
    }

    cartPopup();

    const searchHomeList = () => {
      const listHomeSearch = document.querySelector(`.list-search-block ul`);

      if (listHomeSearch && window.innerWidth >= 769) {
          const listItemArr = listHomeSearch.querySelectorAll(`.list-search-block ul li`);
          if (listItemArr.length <= 5) {
              listHomeSearch.classList.add('no-list-column')
          } else if (listItemArr.length > 5 && listItemArr.length <= 10) {
              listHomeSearch.classList.add('list-column-2')
          } else {
              listHomeSearch.classList.add('list-column-3')
          }
      }

    }
    searchHomeList();


    const cookies = () => {

        const cookiePopup = document.getElementsByClassName('cookies-popup')[0];

        if (cookiePopup) {
            const cookieBtn = cookiePopup.querySelector('.cookies-btn'),
                cookieCloseBtn = cookiePopup.querySelector('.cookies-popup__close'),
                cookieBg = cookiePopup.querySelector('.cookies-popup__bg');

            const cookieName = 'agree-with-cookies';

            if (!Cookies.get(cookieName)) {

                setTimeout(() => {
                    cookiePopup.classList.add('active');
                }, 500)

                cookieBtn.addEventListener('click', () => {
                    cookiePopup.classList.remove('active');

                    Cookies.set(cookieName, true, {
                        expires: 30,
                    })
                })

                cookieCloseBtn.addEventListener('click', () => {
                    cookiePopup.classList.remove('active');
                })

                cookieBg.addEventListener('click', () => {
                    cookiePopup.classList.remove('active');
                })
            }
        }

    }

    cookies();

});//конец ready
