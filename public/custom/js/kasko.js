g_tab_kasko = 1;
function changeTransportTab(tab){
    g_tab_kasko = tab;
    $('#transport_model').val('');
    $('#transport_cost').val('');
    if(tab == 1){
        $('#btn_calculator').fadeIn(0);
        $('#btn_request').fadeOut(0);
    }
    else{
        $('#btn_calculator').fadeOut(0);
        $('#btn_request').fadeIn(0);
    }
}

function showKaskoStep2(){
    if($('#transport_model').val() == '') {
        $('#transport_model_error').html('Укажите корректные данные');
        $('#transport_model_info').addClass('has-error');
        return;
    }
    else {
        $('#transport_model_info').removeClass('has-error');
        $('#transport_model_error').html('');
    }

    if($('#transport_cost').val() == '') {
        $('#transport_cost_error').html('Укажите корректные данные');
        $('#transport_cost_info').addClass('has-error');
        return;
    }
    else {
        $('#transport_cost_info').removeClass('has-error');
        $('#transport_cost_error').html('');
    }

    if(!$('#filled-in-box').is(':checked')){
        showError('Чтобы получить рассчет Вам следует подтвердить согласие на сбор данных');
        return;
    }

    $('.kasko_step3').fadeOut(0);
    $('.kasko_step1').fadeOut(0);
    $('.kasko_step2').fadeIn(0);
}

function showKaskoStep1(){
    $('.kasko_step2').fadeOut(0);
    $('.kasko_step1').fadeIn(0);
}

g_procent = 2;
g_checked = 0;

function checkedGai(){
    if(g_checked == 0){
        g_checked = 1;
        g_vip = 1;
        $('#amort').prop('checked',true);
        $('#gai').prop('checked',true);
    }
    else {
        g_checked = 0;
        g_vip = 0;
        $('#amort').prop('checked',false);
        $('#gai').prop('checked',false);
    }
    calculateKASKO();
}

function showKaskoStep3(){

    check = true;

    if($('#phone').val() == '') {
        $('#phone_error').html('Укажите корректные данные');
        $('#phone_info').addClass('has-error');
        check = false;
    }
    else {
        $('#phone_info').removeClass('has-error');
    }

    if($('#email').val() == '') {
        $('#email_error').html('Укажите корректные данные');
        $('#email_info').addClass('has-error');
        check = false;
    }
    else {
        $('#email_info').removeClass('has-error');
    }

    if($('#user_name').val() == '') {
        $('#user_name_error').html('Укажите корректные данные');
        $('#user_name_info').addClass('has-error');
        check = false;
    }
    else {
        $('#user_name_info').removeClass('has-error');
    }

    if(check == false){
        return;
    }

    $('.kasko_step1').fadeOut(0);
    $('.kasko_step2').fadeOut(0);
    $('.kasko_step3').fadeIn(0);

    setFranchiseSlider();

    calculateKASKO();
}

g_vip = 0;
g_first = 0;
function calculateKASKO(){
    if(g_first > 1){
        $('#label_from').html('');
    }
    g_first++;

    if(g_vip == 0){
        if(g_procent == 2){
            var sum = 2.2 * $('#transport_cost').val();
        }
        else if(g_procent == 1) {
            var sum = 2.4 * $('#transport_cost').val();
        }
        else {
            var sum = 2.7 * $('#transport_cost').val();
        }
    }
    else {
        if(g_procent == 2){
            var sum = 3 * $('#transport_cost').val();
        }
        else if(g_procent == 1) {
            var sum = 3.3 * $('#transport_cost').val();
        }
        else {
            var sum = 3.5 * $('#transport_cost').val();
        }
    }

    sum = sum / 100;
    sum = Math.round(sum);
    $('#policy_cost').html(sum);

    sum = g_procent * $('#transport_cost').val();
    sum = sum / 100;
    sum = Math.round(sum);
    $('#fransh_cost').html(sum);
}

function addRequestKASKO() {
    check = true;

    if($('#phone').val() == '') {
        $('#phone_error').html('Укажите корректные данные');
        $('#phone_info').addClass('has-error');
        check = false;
    }
    else {
        $('#phone_info').removeClass('has-error');
    }

    if($('#email').val() == '') {
        $('#email_error').html('Укажите корректные данные');
        $('#email_info').addClass('has-error');
        check = false;
    }
    else {
        $('#email_info').removeClass('has-error');
    }

    if($('#user_name').val() == '') {
        $('#user_name_error').html('Укажите корректные данные');
        $('#user_name_info').addClass('has-error');
        check = false;
    }
    else {
        $('#user_name_info').removeClass('has-error');
    }

    if(check == false){
        return;
    }

    $('.ajax-loader').fadeIn(100);

    $.ajax({
        url:'/ajax/request/policy/kasko',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            user_name: $('#user_name').val(),
            phone: $('#phone').val(),
            email: $('#email').val(),
            transport_name: $('#transport_model').val(),
            transport_cost: $('#transport_cost').val(),
            driver_age: $('.driver-age:checked').val(),
            driver_experience: $('.driver-experience:checked').val(),
            is_exist_accident: $('.is_exist_accident:checked').val(),
            without_depreciation: $('#amort:checked').val(),
            is_call_gai: $('#gai:checked').val(),
            franchise_size: g_procent
        },
        success: function (data) {
            $('.ajax-loader').fadeOut(100);
            if(data.status == 0){
                showError(data.error);
                return;
            }

            $('#phone').val('');
            $('#user_name').val('');
            $('#email').val('');
            $('#modal-success').modal('show');

            $('#modal-success').modal('show');
        }
    });
}

g_is_first_time = 0;

function setFranchiseSlider(){
    if(g_is_first_time == 1){
        return;
    }

    g_is_first_time = 1;
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
            if(index == 2) index = 0;
            else if(index == 0) index = 2;
            g_procent = index;
            calculateKASKO();
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
}