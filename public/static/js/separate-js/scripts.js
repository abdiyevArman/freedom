'use strict';

$(function() {

    /*
    |--------------------------------------------------------------------------
    | Mobile menu
    |--------------------------------------------------------------------------
    */

    $('.burger').click(function() {
        $(this).toggleClass('-opened');
        $('.m-menu').toggleClass('-opened');

        if ($(this).hasClass('-opened')) {
            $('body').css({"overflow": "hidden"});
        } else {
            $('body').css({"overflow": ""});
        }
    });

    /*
    |--------------------------------------------------------------------------
    | franchise Slider Bar
    |--------------------------------------------------------------------------
    */

    if(document.querySelector('.franchise-slider')){
        let franchiseSliderBar = document.querySelector('.franchise-slider__bar');
        let franchiseSlider = document.querySelector('.franchise-slider__bottom');
        let listCordsX = [];
        let franchiseSliderPoint = Array.from(franchiseSlider.querySelectorAll('.franchise-slider__text'));
        let calcTotal = document.querySelector('.calc__total-number');
        let calcPromoTitle = document.querySelector('.calc__promo-number');
        let handler = franchiseSliderBar.querySelector('.franchise-slider__handler');
        let flagMove = false;

        for(let i = 0; i < franchiseSliderPoint.length; i++){
            listCordsX.push([franchiseSliderBar.getBoundingClientRect().width/(franchiseSliderPoint.length-1), franchiseSliderPoint[i]]);
        }

        listCordsX.forEach((item, index)=>{
            if(index > 0){
                listCordsX[index][0] = listCordsX[index][0] + listCordsX[index-1][0];
            } else {
                listCordsX[index][0] = 0;
            }
        });

        listCordsX.forEach((item, index)=>{
            listCordsX[index].push(listCordsX[index][0] - franchiseSliderBar.getBoundingClientRect().width/(franchiseSliderPoint.length-1) / 2);
            listCordsX[index].push(listCordsX[index][0] + franchiseSliderBar.getBoundingClientRect().width/(franchiseSliderPoint.length-1) / 2);
        });

        fillingPrice(0);

        function transformationText(x){
            try {
                let text = x.split('');
                for(let i = text.length-4; i > 0; i -=3){
                    text[i] = text[i] + ' ';
                }
                text = text.join('');
                return text;
            } catch {
                return x;
            }
        }

        function fillingPrice(index){
            calcTotal.innerHTML = transformationText(listCordsX[index][1].dataset.value);
            calcPromoTitle.innerHTML = transformationText(String(Number(listCordsX[index][1].dataset.value) - Number(listCordsX[index][1].dataset.value)/100 * Number(calcPromoTitle.dataset.discount)));
        }

        function flagResolutionMove(event){
            if(event == 'down'){
                flagMove = true;
            }
            if(event == 'up'){
                flagMove = false;
            }
        }

        function move(e, device){
            if(flagMove){
                let x;
                if(device == 'PC'){
                    x = e.pageX;
                }
                if(device == 'MOB'){
                    x = e.touches[0].pageX;
                }
                listCordsX.forEach((item, index)=>{
                    if(x - franchiseSlider.getBoundingClientRect().left > listCordsX[index][2] && x- franchiseSlider.getBoundingClientRect().left < listCordsX[index][3]){
                        handler.style.left = listCordsX[index][0] + 'px';
                        fillingPrice(index);
                    }
                });
            }
        }

        document.addEventListener('mousedown', (e)=>{
            if(e.target.classList.contains('franchise-slider__handler')){
                flagResolutionMove('down');
            }
        });

        document.addEventListener('touchstart', (e)=>{
            if(e.target.classList.contains('franchise-slider__handler')){
                flagResolutionMove('down');
            }
        });

        document.addEventListener('mouseup', ()=>{
            flagResolutionMove('up');
        });

        document.addEventListener('touchend', ()=>{
            flagResolutionMove('up');
        });

        document.addEventListener('mousemove', (e)=>{
            move(e, 'PC');
        });

        document.addEventListener('touchmove', (e)=>{
            move(e, 'MOB');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Spoiler Text
    |--------------------------------------------------------------------------
    */

    let containerHeight = document.querySelectorAll(".spoiler__inner");
    let uncoverLink = document.querySelectorAll(".jsSpoilerMore");

    for(let i = 0; i < containerHeight.length; i++){
        let openData = uncoverLink[i].dataset.open;
        let closeData = uncoverLink[i].dataset.close;
        let curHeight = containerHeight[i].dataset.height;

        uncoverLink[i].innerHTML = openData;
        containerHeight[i].style.maxHeight = curHeight + "px";

        uncoverLink[i].addEventListener("click", function(){
            if(containerHeight[i].classList.contains("-open")){

                containerHeight[i].classList.remove("-open");

                uncoverLink[i].innerHTML = openData;

                containerHeight[i].style.maxHeight = curHeight + "px";

            } else {
                containerHeight[i].removeAttribute("style");

                containerHeight[i].classList.add("-open");

                uncoverLink[i].innerHTML = closeData;

            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Date Picker
    | https://github.com/uxsolutions/bootstrap-datepicker
    |--------------------------------------------------------------------------
    */

    (function($){
        $.fn.datepicker.dates['ru'] = {
            days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
            daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб"],
            daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
            months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
            today: "Сегодня",
            clear: "Очистить",
            format: "dd.mm.yyyy",
            weekStart: 1,
            monthsTitle: 'Месяцы'
        };
    }(jQuery));

    $('.jsBootstrapDatePicker').datepicker({
        language: 'ru',
    });

    /**
     * Russian translation for bootstrap-datepicker
     * Victor Taranenko <darwin@snowdale.com>
     */


    /*
    |--------------------------------------------------------------------------
    | Time Picker
    | https://timepicker.co
    |--------------------------------------------------------------------------
    */

    $('.jsTimepicker').timepicker({
        timeFormat: 'h:mm p',
        interval: 60,
        minTime: '10',
        maxTime: '6:00pm',
        defaultTime: '11',
        startTime: '10:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

    /*
    |--------------------------------------------------------------------------
    | Selectpicker
    |--------------------------------------------------------------------------
    */

    $('.jsSelectPicker').selectpicker();


    /*
    |--------------------------------------------------------------------------
    | Spidometr
    |--------------------------------------------------------------------------
    */

    function setSpeedometr(stage) {
        var $svg = $('.spidometr__wrapper .spidometr__front');
        var $overlay = $('.svg-overlay');
        var $value = $('.spidometr__value');

        var $filtered = $svg.find('path')
            .filter(function() {
                return this.id.match(/\d+/);
            });

        $filtered = $filtered.sort(function (a, b) {
            return getValue(a) - getValue(b);

            function getValue(item) {
                return parseInt(item.id.replace('Shape', ''))
            }
        });

        $filtered.removeClass('innactive');
        switch (stage) {
            case 0:
                $value.html('60%');
                $filtered.slice(5).addClass('innactive');
                break;
            case 1:
                $value.html('80%');
                $filtered.slice(19).addClass('innactive');
                break;
            case 2:
                $value.html('100%');
                break;
            default:
                $value.html('60%');
                $filtered.slice(5).addClass('innactive');
        }

        $overlay.attr('class', 'svg-overlay svg-overlay_' + stage);
    }

    setSpeedometr(0);

    $(function() {
        for (var i = 0; i < 3; i++) {
            $('.entry__card').eq(i).mouseenter(setSpeedometr.bind(null, i));
        }
    });

});
