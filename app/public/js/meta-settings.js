import {CountUp} from "./countUp.js";
import Swiper from 'https://unpkg.com/swiper@8/swiper-bundle.esm.browser.min.js';

/**
 * function to know what browser client is using [Application and version of it]
 * Used in fixForSwiperSafariOld function to toggle it only in Safari 11 and 12
 * where touch-action: pan-y || pan-x not supported
 * **/
navigator.sayswho= (function(){
    var ua= navigator.userAgent;
    var tem;
    var M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
        return 'IE '+(tem[1] || '');
    }
    if(M[1]=== 'Chrome'){
        tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
        if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
    }
    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
    return M.join(' ');
})();


/**
 * Function check for touchscreen device
 * **/
export function is_touch_enabled() {
    return ( 'ontouchstart' in window ) ||
        ( navigator.maxTouchPoints > 0 ) ||
        ( navigator.msMaxTouchPoints > 0 );
}


/**
 * Swiper функція яка прорахує чи потрібно включати loop на усіх breakpoint-ах, які ви задасте
 * Swiper function that will calc if it needed to turn on loop mode on all breakpoints that you had written
 **/
export function MetaSwiper(selector, swiperParams) {
    let variable = null;

    const swiperBlock = document.getElementsByClassName(selector.replace(/^\./, ""))[0];
    const swiperElementArr = Array.from(swiperBlock.querySelectorAll('.swiper-slide'));
    const swiperBreakpoints = swiperParams.breakpoints;
    const breakpointsArr = [];

    function swiperSearchMinMax(arr, windowWidth) {
        let min = 0;
        let max = 9999;
        let closestBreakpoint = closest(arr, windowWidth);

        if ((windowWidth - closestBreakpoint) >= 0) {
            if (closestBreakpoint === arr[arr.length - 1]) {
                min = arr[arr.length - 1];
            } else {
                min = closestBreakpoint;
                max = arr[arr.indexOf(closestBreakpoint) + 1];
            }
        } else {
            if (closestBreakpoint === arr[0]) {
                max = closestBreakpoint;
            } else {
                min = arr[arr.indexOf(closestBreakpoint) - 1];
                max = closestBreakpoint;
            }
        }

        return [min, max];
    }

    function CalcBreakpointLoop() {
        let currentBreakpoint = breakpointsArrReverse.find( num => num <= window.innerWidth );

        for (let windowWidth in swiperBreakpoints) {
            let obj = swiperBreakpoints[windowWidth];

            if (obj.slidesPerView === 'auto' && +windowWidth === currentBreakpoint && swiperParams.loop === undefined) {
                const swiperElementWidth = swiperElementArr[0].getBoundingClientRect().width;
                obj.loop = swiperElementArr.length >= Math.max(Math.floor(window.innerWidth / swiperElementWidth) + 1, 2);
                obj.loopedSlides = Math.floor(window.innerWidth / swiperElementWidth);
            } else if (obj.slidesPerView !== 'auto' && swiperParams.loop === undefined) {
                obj.loop = swiperElementArr.length > obj.slidesPerView;
                obj.loopedSlides = obj.slidesPerView + 1;
            }
        }
    }

    for (let windowWidth in swiperBreakpoints) {
        // skip loop if the property is from prototype
        if (!swiperBreakpoints.hasOwnProperty(windowWidth)) continue;

        let obj = swiperBreakpoints[windowWidth];

        breakpointsArr.push(+windowWidth);
    }


    const breakpointsArrReverse = breakpointsArr.slice().reverse();

    if (swiperParams.slidesPerView === 'auto' && swiperParams.loop === undefined) {
        const swiperElementWidth = swiperElementArr[0].getBoundingClientRect().width;
        swiperParams.loop = swiperElementArr.length >= Math.max(Math.floor(window.innerWidth / swiperElementWidth) + 1, 2);
        swiperParams.loopedSlides = Math.floor(window.innerWidth / swiperElementWidth);
    } else if (swiperParams.slidesPerView && swiperParams.loop === undefined) {
        swiperParams.loop = swiperElementArr.length > swiperParams.slidesPerView;
        swiperParams.loopedSlides = swiperParams.slidesPerView + 1;
    }

    CalcBreakpointLoop();

    let windowWidth = window.innerWidth;

    variable = new Swiper(selector, swiperParams);
    SafariSwiper(variable);

    let minMax = null;

    if (breakpointsArr.length > 0) {
        minMax = swiperSearchMinMax(breakpointsArr, windowWidth);
    }

    window.addEventListener('resize', function (e) {
        if (windowWidth === window.innerWidth) return;
        windowWidth = window.innerWidth;

        if(minMax && windowWidth >= minMax[0] && windowWidth <= minMax[1]) return;

        if (minMax) {
            minMax = swiperSearchMinMax(breakpointsArr, windowWidth);
        }

        variable.navigation.nextEl.classList.remove('swiper-button-lock', 'swiper-button-disable');
        variable.navigation.prevEl.classList.remove('swiper-button-lock', 'swiper-button-disable');

        variable.destroy();

        if (swiperParams.slidesPerView === 'auto') {
            const swiperElementWidth = swiperElementArr[0].getBoundingClientRect().width;
            swiperParams.loop = swiperElementArr.length >= Math.max(Math.floor(window.innerWidth / swiperElementWidth) + 1, 2);
            swiperParams.loopedSlides = Math.floor(window.innerWidth / swiperElementWidth);
        }

        CalcBreakpointLoop();

        variable = new Swiper(selector, swiperParams);
        variable.updateSlidesClasses();
        SafariSwiper(variable);
    })
}


/**
 * Function for Swiper correct working with 'swipe' in old Safari 11 and 12,
 * where touch-action: pan-y || pan-x not supported
 * **/
export function SafariSwiper(SwiperObjs) {
    if ((navigator.sayswho === `Safari 11` || navigator.sayswho === `Safari 12`) && !is_touch_enabled()) {

        let SwiperArr = null;

        if (Array.isArray(SwiperObjs)) {
            SwiperArr = SwiperObjs;
        } else {
            SwiperArr = [SwiperObjs];
        }

        SwiperArr.forEach(swiper => {
            const swiperParams = swiper.params;
            const swiperAmount = swiperParams.loop ? swiper.slides.length / 3 : swiper.slides.length;
            const swiperHTML = swiper.$el[0];
            const swiperHTMLPosLeft = swiperHTML.getBoundingClientRect().left;
            const swiperWrapper = swiper.$wrapperEl[0];
            console.log(swiperWrapper)
            let activeIndex = swiper.activeIndex;
            let realIndex = swiper.realIndex;

            let elWidth, margin;

            if (swiper.slides.length > 1) {
                elWidth = swiper.slidesGrid[1];
                margin = (elWidth - swiper.slides[1].offsetWidth);
            }

            let posLeft = null;

            $(swiperWrapper).draggable({
                    axis: 'x',
                    revert: true,
                    revertDuration: 0,

                    start: function (event, ui) {
                        activeIndex = swiper.activeIndex;
                        realIndex = swiper.realIndex;
                        posLeft = ui.offset.left;
                    },

                    drag: function(event, ui){
                        if (swiperParams.effect !== 'creative') {
                            swiper.setTranslate((ui.offset.left - swiperHTMLPosLeft), 0, 0);
                        }
                    },

                    stop: function (event, ui) {
                        const difference = posLeft - ui.offset.left;

                        if (difference > 0) {
                            swiper.slideTo(activeIndex + Math.ceil((difference) / elWidth), swiperParams.speed);
                        } else {
                            swiper.slideTo(activeIndex + Math.floor((difference) / elWidth), swiperParams.speed);
                        }

                        if (swiperParams.loop) {
                            if (swiper.activeIndex < swiperAmount / 2) {
                                setTimeout(function () {
                                    swiper.slideTo(swiper.activeIndex + swiperAmount, 0);
                                }, swiperParams.speed);
                            }

                            if (swiper.activeIndex / 2 >= swiperAmount) {
                                setTimeout(function () {
                                    swiper.slideTo(swiper.activeIndex - swiperAmount, 0);
                                }, swiperParams.speed);
                            }
                        }

                        swiper.slideReset(0);
                        swiper.updateProgress();
                        swiper.updateSlidesClasses();
                    },
                }
            );
        });
    }
}

/**
 * Function for Swiper running animation (like Marquee JS)
 * **/
export function runningSwiperFunction(swiper, options = {}) {

    let defaultOptions = {
        speed: 1,
        hrefClass: '',
        changeActiveClass: false,
        activeSlideLengthToggle: 1,
    };

    let mergeOptions = Object.assign(defaultOptions, options);

    let FLAG_FOR_ENTER = false;

    let startProgress = swiper.progress;
    let startTranslate = swiper.translate;
    const swiperParams = swiper.params;
    const swiperWrapper = swiper.wrapperEl;

    if (mergeOptions.hrefClass !== '' && typeof mergeOptions.hrefClass === 'string') {
        swiperWrapper.querySelectorAll(mergeOptions.hrefClass).forEach(elem => {
            elem.addEventListener('click', function() {
                window.location = elem.getAttribute('data-src');
            })
        })
    }

    swiperWrapper.addEventListener('mouseenter', () => {
        FLAG_FOR_ENTER = true;
    });

    swiperWrapper.addEventListener('mouseleave', () => {
        FLAG_FOR_ENTER = false;
    });

    let startTimeFlip = new Date().getTime();
    let lastTime = 0;
    let FlagOnSwitchActiveSlide = false;

    let activeIndex = Math.floor(swiperParams.loop ? swiper.slides.length / 3 : swiper.slides.length);
    const swiperAmount = activeIndex;

    function changeActiveSlide(startTranslateValue, translateValue) {
        console.log(123);
        let swiperSlide = swiper.slides[activeIndex];

        let swiperSlideWidth = swiperSlide.swiperSlideSize;

        if (startTranslateValue - translateValue >= swiperSlideWidth * mergeOptions.activeSlideLengthToggle && !FlagOnSwitchActiveSlide) {
            FlagOnSwitchActiveSlide = true;
            startTranslateForActiveSwitch -= swiperSlideWidth;
        }

        if (FlagOnSwitchActiveSlide) {
            let ariaLabelNumberOLD = activeIndex % swiperAmount + 1;

            activeIndex += 1;

            let ariaLabelNumber = activeIndex % swiperAmount + 1;

            swiper.slides.forEach(elem => {
                let ariaLabelFormat = Number(elem.getAttribute('aria-label').replace(` / ${swiperAmount}`, ''));

                if (ariaLabelNumber === ariaLabelFormat) {
                    if (elem.classList.contains('swiper-slide-duplicate')) {
                        elem.classList.add('swiper-slide-duplicate-active');
                    } else {
                        elem.classList.add('swiper-slide-active');
                    }
                }

                if (ariaLabelNumberOLD === ariaLabelFormat) {
                    if (elem.classList.contains('swiper-slide-duplicate')) {
                        elem.classList.remove('swiper-slide-duplicate-active');
                    } else {
                        elem.classList.remove('swiper-slide-active');
                    }
                }

            });

            FlagOnSwitchActiveSlide = false;
        }
    }

    let startTranslateForActiveSwitch = startTranslate;

    function SwiperAutoplaySmooth(timeStamp) {
        let timeInSecond = timeStamp / 1000;

        if (timeInSecond - lastTime >= 0.001) {
            lastTime = timeInSecond;

            if (!FLAG_FOR_ENTER) {
                swiper.translateTo(swiper.translate - mergeOptions.speed, 0.01);
                swiper.updateProgress();

                if (mergeOptions.changeActiveClass) {
                    changeActiveSlide(startTranslateForActiveSwitch, swiper.translate, swiper.activeIndex);
                }

                if (swiper.progress - startProgress >= startProgress) {
                    swiper.translateTo(startTranslate, 1);
                    swiper.updateProgress();

                    startTranslateForActiveSwitch = startTranslate;
                }
            }
        }

        requestAnimationFrame(SwiperAutoplaySmooth);
    }

    SwiperAutoplaySmooth();
}


/**
 * Function to getPosition of element in DOM
 * **/
export function getPosition(element) {
    var xPosition = 0;
    var yPosition = 0;

    while(element) {
        xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
        yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
        element = element.offsetParent;
    }

    return { x: xPosition, y: yPosition };
}



/** returns true if the element or one of its parents has the class classname **/
export function hasSomeParentTheClass(element, classname) {
    if (element.className.split(' ').indexOf(classname)>=0) return true;
    return element.parentNode && hasSomeParentTheClass(element.parentNode, classname);
}


/** find the closest number in array [first is arr, second is needed number] **/
export function closest(arr, needle) {
    return arr.reduce((a, b) => {
        return Math.abs(b - needle) < Math.abs(a - needle) ? b : a;
    })
}


/** Стара функція для перевірки чи елемент знаходиться в Viewport
 * (потрібно юзати з window.addEventListener('scroll'))
 *
 * Проте краще використовувати Intersection Observer API для меншої
 * нагрузки сайту https://developer.mozilla.org/ru/docs/Web/API/Intersection_Observer_API **/
export function isInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <=
        (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

/**
 * CountUp Metamorfosi function
 * (just add .count-up class anywhere and setup unique option in data attributes)
 * **/
const counts = Array.from(document.getElementsByClassName('count-up'));
if (counts) {
    const defaultOptions = {
        separator: '',
        enableScrollSpy: true,
        scrollSpyRunOnce: true,
    };

    let idNumber = 1;

    counts.forEach(count => {
        const id = `count-up-${idNumber}`,
            value = parseInt(count.innerHTML);

        let optionsFromDataAttr = $(count).data();

        for (const key in optionsFromDataAttr) {
            if (optionsFromDataAttr[key] === '') {
                optionsFromDataAttr[key] = true;
            }
        }

        count.id = id;
        new CountUp(id, value, Object.assign(Object.assign({}, defaultOptions), optionsFromDataAttr));
        idNumber++;
    })
}


/** Take index of element in HTMLList (Коли потрібно взяти index елемента з унікальним класом в массиві)
let index = [].indexOf.call(
  peopleArr,
  document.querySelector(`.people.active`)
); **/

/** AOS init
AOS.init({
  startEvent: "load",
  disableMutationObserver: false,
});
AOS.refresh(true); **/


/**
 * default window resize Listener
 * */
// let windowWidth = window.innerWidth;
// window.addEventListener('resize', function (e) {
//     if (windowWidth === window.innerWidth) return;
//
//     windowWidth = window.innerWidth;
//
//     // your code
// })