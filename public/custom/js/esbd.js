$('#iin').change(function(){
    $('#is_input_iin').val(0);
    g_exist_iin = 0;
    var check = checkValidIIN();
    if(check == true){
        clearBtn();
        getInfoByINN($('#iin').val());
    }
});

function checkValidIIN(){
    if($('#iin').val().length != 12 || $.isNumeric($('#iin').val()) == false){
        $('#iin_error').html('Некорректный ИИН');
        $('#iin_info').addClass('has-error');
        $('#iin_message').html('');
        $('#iin_message').fadeOut(0);
        return false;
    }
    else if($('#iin').val().length == 12 && $.isNumeric($('#iin').val()) == true) {
        $('#iin_info').removeClass('has-error');
        $('#iin_message').fadeOut(0);
        return true;
    }
    return false;
}

v_grade = 0;
g_ogpo = 0;

function getInfoByINN(iin) {
    if(iin.length != 12 || $.isNumeric(iin) == false){
        return;
    }

    $('#iin_message').addClass('loader');
    $('#iin_message').html('');
    $('#iin_message').fadeIn(0);
    $('#iin').prop('disabled', true);

    $.ajax({
        url:'/ajax/get-info-by-iin',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            iin: iin,
            is_ogpo: g_ogpo
        },
        success: function (data) {
            g_user_name = '';

            $('#iin_message').removeClass('loader');
            $('#iin').prop('disabled', false);
            if(data.status == 0){
                if(data.error != ''){
                    $('#iin_error').html(data.error);
                }
                if($('#iin_error').html() == '') {
                    $('#iin_error').html(data.status_code.message );
                }
                $('#iin_info').addClass('has-error');
                return;
            }
            $('#iin_info').removeClass('has-error');
            $('#iin_message').html(data.message);

            g_user_name = data.message;

            v_grade = data.grade;
            $('#iin_message').fadeIn(0);
            g_exist_iin = 1;
            g_iin = iin;

            if(g_is_page_limit_kasko == 1){
                $('#message_content').html('');
                $('.calc__agree').fadeIn(0);

                if(v_grade < 4){
                    $('.calc__agree').fadeOut(0);
                    var content = '<div class="calc__total">Ваш класс не подходит, чтобы купить этот полис</div>';
                    $('#message_content').html(content);
                    return;
                }
            }
            else if(g_ogpo == 1){
                if(data.unfinished_policy == 1){

                    var content = '<div class="calc__total">Стоимость полиса для вашего авто: <strong class="total_policy_cost">' + data.total_cost + ' тенге</strong></div>';
                    $('#second_message_content').html(content);

                    content = parseInt(data.ogpo_price)  + ' тг.';
                    $('#ogpo_cost').html(content);

                    content = data.total_cost + ' тг.';
                    $('.total_policy_cost').html(content);

                    g_cost = data.total_cost;
                    before_total_cost = data.total_cost;
                    g_transport_name = data.transport_name;

                    checked_is_kasko = data.kasko;
                    checked_is_is_need_dgpo = data.ogpo_plus;

                    $('#cancel_additional').prop('checked', true);
                    $('#is_need_kasko').prop('checked', false);
                    $('#is_need_dgpo').prop('checked', false);
                    $('#kasko_cost_label').css('display','none');
                    $('#dgpo_cost_label').css('display','none');

                    if(checked_is_kasko == 1){
                        $('#is_need_kasko').prop('checked', true);
                        $('#kasko_cost_label').css('display','block');
                        $('#cancel_additional').prop('checked', false);
                    }

                    if(checked_is_is_need_dgpo == 1){
                        $('#is_need_dgpo').prop('checked', true);
                        $('#dgpo_cost_label').css('display','block');
                        $('#cancel_additional').prop('checked', false);
                    }

                    $('#modal-process2').modal('show');
                }
            }
        }
    });
}

function clearOGPOModal(){
    checked_is_kasko = 1;
    checked_is_is_need_dgpo = 0;

    $('#cancel_additional').prop('checked', false);
    $('#is_need_dgpo').prop('checked', false);

    $('#is_need_kasko').prop('checked', true);
    $('#kasko_cost_label').css('display','block');
    $('#dgpo_cost_label').css('display','none');
}

var g_exist_iin = 0;
var g_exist_auto = 0;

g_policy_id = 0;
g_transport_name = '';

g_is_click_calculate_btn = 0;

var before_total_cost = 0;

function calculateOGPO(){
    g_cost = 0;
    g_is_click_calculate_btn = 1;

    clearOGPOModal();

    if(!$('#filled-in-box').is(':checked')){
        showError('Чтобы получить рассчет Вам следует подтвердить согласие на сбор данных');
        return;
    }
    else {
        $('.calc__agree').fadeOut();
    }

    $('#auto_info').removeClass('has-error');
    $('#iin_info').removeClass('has-error');

    var message_iin = $('#iin_message').html();
    var message_auto = $('#auto_input_message').html();

    var check = checkValidIIN();
    if(check == true){
        $('#iin_message').html(message_iin);
        $('#iin_message').fadeIn(0);
    }

    var check_auto = checkValidCarNumber();
    if(check_auto == true){
        $('#auto_input_message').html(message_auto);
        $('#auto_input_message').fadeIn(0);
    }

    if(check == false || check_auto == false){
        return;
    }


    if(g_exist_iin == 0){
        showError('Некорректные данные водителя');
        return;
    }
    if(g_exist_auto == 0){
        showError('Некорректные данные транспорта');
        return;
    }

    var iins = [];
    $(g_iin_array).each(function(){
        iins.push(this.iin);
    });

    var transport_number = [];
    $(g_transport_array).each(function(){
        transport_number.push(this.transport_number);
    });

    var transport_model = [];
    $(g_transport_array).each(function(){
        transport_model.push(this.transport_model);
    });


    var transport_year = [];
    $(g_transport_array).each(function(){
        transport_year.push(this.transport_year);
    });


    var transport_region = [];
    $(g_transport_array).each(function(){
        transport_region.push(this.transport_region);
    });


    var transport_vin = [];
    $(g_transport_array).each(function(){
        transport_vin.push(this.transport_vin);
    });

    $('.ajax-loader').fadeIn();

    $.ajax({
        url:'/ajax/calc/agpo',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            iin: $('#iin').val(),
            iins: iins,
            transport_numbers: transport_number,
            transport_models: transport_model,
            transport_years: transport_year,
            transport_regions: transport_region,
            transport_vins: transport_vin,
            is_need_kasko: $('#is_need_kasko').is(':checked'),
            is_need_ogpo_plus: $('#is_need_dgpo').is(':checked'),
            is_has_discount: $('#is_has_discount').is(':checked')
        },
        success: function (data) {
            $('.ajax-loader').fadeOut();

            if(data.status == 0){
                var content = '<div class="calc__total">' + data.error + '</div>';
                $('#message_content').html(content);
                $('#second_message_content').html(content);
            }
            else {
                var content = '<div class="calc__total">Стоимость полиса для вашего авто: <strong class="total_policy_cost">' + data.total_cost + ' тенге</strong></div>';
                $('#message_content').html('');
                $('#second_message_content').html(content);

                content = parseInt(data.total_cost)  + ' тг.';
                $('.total_policy_cost').html(content);

                content = parseInt(data.ogpo_price)  + ' тг.';
                $('#ogpo_cost').html(content);

                content = parseInt(data.kasko_price)  + ' тг.';
                $('.kasko_cost').html(content);

                content = parseInt(data.ogpo_plus_price)  + ' тг.';
                $('.ogpo_plus_cost').html(content);

                kasko_price = data.kasko_price;
                ogpo_plus_price = data.ogpo_plus_price;
                g_cost = data.total_cost;
                before_total_cost = data.total_cost;

                console.log(data.total_cost);

                content = '<button type="button" onclick="showPayContent()" class="button -green -md">Купить</button>';
                $('#btn_content_modal').html(content);

                $('#modal-order').modal('show');

            }
            g_is_change_new_car = 0;
            g_transport_name = data.transport_name;
        }
    });
}

function checkValidDriverIIN(){
    if($('#iin_new').val().length != 12 || $.isNumeric($('#iin_new').val()) == false){
        $('#new_driver_message').html('Некорректный ИИН');
        $('#new_driver_message').fadeIn(0);
        return false;
    }
    else if($('#iin_new').val().length == 12 && $.isNumeric($('#iin_new').val()) == true) {
        $('#new_driver_message').fadeOut(0);
        return true;
    }
    return false;
}

function addDriver(is_main_button) {
    if(is_main_button == 1 && $('#iin_new').val() == ''){
        if(g_is_change_new_driver == 1){
            $('#btn_content').find('a').click();
        }
        $('#modal-driver').modal('hide');
        return;
    }

    var check = checkValidDriverIIN();

    if(check == false){
        return;
    }

    if($('#iin').val() == $('#iin_new').val()){
        $('#new_driver_message').html("Вы уже добавили этого водителя");
        $('#new_driver_message').fadeIn(0);
        check = false;
        return;
    }

    check = true;
    $('.iin-driver').each(function(){
        if($(this).val() == $('#iin_new').val()){
            $('#new_driver_message').html("Вы уже добавили этого водителя");
            $('#new_driver_message').fadeIn(0);
            check = false;
            return;
        }
    });

    if(check == false){
        return;
    }

    $('.ajax-loader').fadeIn();
    $.ajax({
        url:'/ajax/add-driver',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            iin: $('#iin_new').val()
        },
        success: function (data) {
            $('.ajax-loader').fadeOut();
            if(data.status == 0){
                $('#new_driver_message').html(data.error);
                $('#new_driver_message').fadeIn(0);
                return;
            }
            $('#driver_list').prepend(data);
            setDriverLabel();
            clearBtn();
            $('#iin_new').val('');
            g_is_change_new_driver = 1;
        }
    });
}

g_is_change_new_driver = 0;

function deleteDriver(ob){
    var iin = $(ob).closest('.driver-item').find('.iin-driver').val();
    $(ob).closest('.driver-item').remove();
    setDriverLabel();
    clearBtn();

    g_is_change_new_driver = 1;

    var key = -1;
    $(g_iin_array).each(function(index){
        if(this.iin == iin){
            delete g_iin_array[index];
        }
    });
}

function deleteCar(ob){
    var transport_number = $(ob).closest('.car-item').find('.car-number').val();
    $(ob).closest('.car-item').remove();
    setCarLabel();
    clearBtn();
    g_is_change_new_car = 1;

    var key = -1;
    $(g_transport_array).each(function(index){
        if(this.transport_number == transport_number){
            delete g_transport_array[index];
        }
    });
}

var g_transport_key = 0;
var g_iin_key = -1;
var g_iin_array = [];
var g_iin = '';

function setCarLabel(){
    var message = 'Добавить авто';
    message = 'Добавлено ' + $('.car-number').length + ' авто';
    $('#add_car_label').html(message);
}

function setDriverLabel(){
    var message = 'Добавить водителя';
    if($('.iin-driver').length == 1){
        message = 'Добавлено ' + $('.iin-driver').length + ' водитель';
    }
    else if($('.iin-driver').length > 0){
        message = 'Добавлено ' + $('.iin-driver').length + ' водителя';
    }
    $('#add_driver_label').html(message);
}

function clearBtn(){
    var action_name = $('#btn_type').val();
    var content = '<a href="javascript:void(0)" onclick="' + action_name + '()" class="button -green -md calc__button">Рассчитать</a>';
    $('#btn_content').html(content);
    $('#message_content').html('');
}

var g_is_change_new_car = 0;

function addCar(is_main_button) {
    $('#new_car_message').fadeOut(0);

    if(is_main_button == 1 && $('#transport_number_new').val() == ''){
        if(g_is_change_new_car == 1){
            $('#btn_content').find('a').click();
        }
        $('#modal-auto').modal('hide');
        return;
    }

    if($('#transport_number_new').val() == ''){
        $('#new_car_message').html("Укажите номер транспорта");
        $('#new_car_message').fadeIn(0);
        return;
    }

    if($('#auto_number').val() == $('#transport_number_new').val()){
        $('#new_car_message').html("Вы уже добавили это авто");
        $('#new_car_message').fadeIn(0);
        check = false;
        return;
    }

    check = true;
    $('.car-number').each(function(){
        if($(this).val() == $('#transport_number_new').val()){
            $('#new_car_message').html("Вы уже добавили это авто");
            $('#new_car_message').fadeIn(0);
            check = false;
            return;
        }
    });

    if(check == false){
        return;
    }

    $('.ajax-loader').fadeIn();
    $.ajax({
        url:'/ajax/add-car',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            transport_number: $('#transport_number_new').val()
        },
        success: function (data) {
            $('.ajax-loader').fadeOut();
            if(data.status == 0){
                $('#new_car_message').html(data.error);
                $('#new_car_message').fadeIn(0);
                return;
            }
            $('#car_list').prepend(data);
            setCarLabel();
            clearBtn();
            $('#transport_number_new').val('');
            g_is_change_new_car = 1;
        }
    });
}

function showNewDriverModal(){
    $('#auto_info').removeClass('has-error');
    $('#iin_info').removeClass('has-error');

    var message_iin = $('#iin_message').html();
    var message_auto = $('#auto_input_message').html();

    var check = checkValidIIN();
    if(check == true){
        $('#iin_message').html(message_iin);
        $('#iin_message').fadeIn(0);
    }

    var check_auto = checkValidCarNumber();
    if(check_auto == true){
        $('#auto_input_message').html(message_auto);
        $('#auto_input_message').fadeIn(0);
    }

    if(check == false || check_auto == false){
        return;
    }

    if($('.car-number').length > 0){
        showError('Сначала Вам следует удалить дополнительные авто');
        return;
    }

    $('#modal-driver').modal('show');
}

function showNewCarModal(){
    $('#auto_info').removeClass('has-error');
    $('#iin_info').removeClass('has-error');

    var message_iin = $('#iin_message').html();
    var message_auto = $('#auto_input_message').html();

    var check = checkValidIIN();
    if(check == true){
        $('#iin_message').html(message_iin);
        $('#iin_message').fadeIn(0);
    }

    var check_auto = checkValidCarNumber();
    if(check_auto == true){
        $('#auto_input_message').html(message_auto);
        $('#auto_input_message').fadeIn(0);
    }

    if(check == false || check_auto == false){
        return;
    }

    if($('.iin-driver').length > 0){
        showError('Сначала Вам следует удалить дополнительных водителей');
        return;
    }

    $('#modal-auto').modal('show');
}

var g_is_page_limit_kasko = 0;

function calculateLimitKASKO(){
    if(!$('#filled-in-box').is(':checked')){
        showError('Чтобы получить рассчет Вам следует подтвердить согласие на сбор данных');
        return;
    }
    else {
        $('.calc__agree').fadeOut();
    }

    $('#auto_info').removeClass('has-error');
    $('#iin_info').removeClass('has-error');

    var message_iin = $('#iin_message').html();
    var message_auto = $('#auto_input_message').html();

    var check = checkValidIIN();
    if(check == true){
        $('#iin_message').html(message_iin);
        $('#iin_message').fadeIn(0);
    }

    var check_auto = checkValidCarNumber();
    if(check_auto == true){
        $('#auto_input_message').html(message_auto);
        $('#auto_input_message').fadeIn(0);
    }

    if(check == false || check_auto == false){
        return;
    }


    if(g_exist_iin == 0){
        showError('Некорректные данные водителя');
        return;
    }
    if(g_exist_auto == 0){
        showError('Некорректные данные транспорта');
        return;
    }

    var driver_list = [];

    $('.iin-driver').each(function(){
        driver_list.push($(this).val());
    });

    var driver_grade = [];

    $('.grade-driver').each(function(){
        driver_grade.push($(this).val());
    });

    if(v_grade < 4){
        var content = '<div class="calc__total">Ваш класс не подходит, чтобы купить этот полис</div>';
        $('#message_content').html(content);
        return;
    }

    $('.ajax-loader').fadeIn();
    $.ajax({
        url:'/ajax/calc/kasko-express',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            iin: $('#iin').val(),
            grade: v_grade,
            driver_list: driver_list,
            money: $("input[name=money]:checked").val(),
            driver_grade: driver_grade
        },
        success: function (data) {
            $('.ajax-loader').fadeOut();

            if(data.status == 0){
                var content = '<div class="calc__total">' + data.error + '</div>';
                $('#message_content').html(content);
            }
            else {
                var content = '<div class="calc__total">Стоимость полиса для вашего авто: <strong>' + data.cost + ' тенге</strong></div>';
                $('#message_content').html(content);

                 content = '<a href="javascript:void(0)" onclick="addRequestLimitKASKO()" class="button -green -md calc__button">Оставить заявку</a>';
                 $('#btn_content').html(content);
            }
        }
    });
}

$('.input-by-passport').click(function(){
    $(this).fadeOut(0);
    $(this).closest('.input-car').find('.input-number').val('');
    $(this).closest('.input-car').find('.input-number').fadeOut(0);
    $(this).closest('.input-car').find('.input-by-number').fadeIn(0);
    $(this).closest('.input-car').find('.input-passport').fadeIn(0);
    g_by_car_number = 0;
    $('#auto_input_error').fadeOut(0);
});

$('.input-by-number').click(function(){
    $(this).fadeOut(0);
    $(this).closest('.input-car').find('.input-passport').val('');
    $(this).closest('.input-car').find('.input-passport').fadeOut(0);
    $(this).closest('.input-car').find('.input-by-passport').fadeIn(0);
    $(this).closest('.input-car').find('.input-number').fadeIn(0);
    g_by_car_number = 1;
    $('#auto_input_error').fadeOut(0);
});

$('#auto_number').change(function(){
    var check = checkValidCarNumber();
    g_exist_auto = 0;
    if(check == true){
        clearBtn();
        $(this).closest('.input-car').find('.input-by-passport').fadeOut(0);
        $(this).closest('.input-car').find('.input-by-number').fadeOut(0);
        $('#auto_number').prop('disabled', true);
        getCarInfo();
    }
});

$('#passport_number').change(function(){
    var check = checkValidCarNumber();
    g_exist_auto = 0;
    if(check == true){
        clearBtn();
        $(this).closest('.input-car').find('.input-by-passport').fadeOut(0);
        $(this).closest('.input-car').find('.input-by-number').fadeOut(0);
        $('#passport_number').prop('disabled', true);
        getCarInfo();
    }
});

var g_by_car_number = 1;

function checkValidCarNumber(){
    if(($('#auto_number').val() == '' && g_by_car_number == 1) || ($('#passport_number').val() == '' && g_by_car_number == 0)) {
        $('#auto_input_error').html('Укажите корректные данные');
        $('#auto_info').addClass('has-error');
        $('#auto_input_message').html('');
        $('#auto_input_message').fadeOut(0);
        return false;
    }
    else {
        $('#auto_info').removeClass('has-error');
        $('#auto_input_error').fadeOut(0);
        $('#auto_input_message').fadeOut(0);
        return true;
    }

    return false;
}

var g_transport_array = [];

function getCarInfo(){
    $('#auto_input_message').addClass('loader');
    $('#auto_input_message').html('');
    $('#auto_input_message').fadeIn(0);

    $.ajax({
        url:'/ajax/get-car-info',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            auto_number: $('#auto_number').val(),
            passport_number: $('#passport_number').val()
        },
        success: function (data) {
            $('#auto_input_message').removeClass('loader');
            $('#passport_number').prop('disabled', false);
            $('#auto_number').prop('disabled', false);

            if(data.status == 0){
                if(g_by_car_number == 1){
                    $('#auto_info').closest('.input-car').find('.input-by-passport').fadeIn(0);
                }
                else {
                    $('#auto_info').closest('.input-car').find('.input-by-number').fadeIn(0);
                }
                $('#auto_input_error').html(data.error);
                $('#auto_info').addClass('has-error');
                return;
            }

            $('#auto_info').removeClass('has-error');
            $('#auto_input_message').html(data.message);
            $('#auto_input_message').fadeIn(0);

            g_exist_auto = 1;

            g_transport_array[0] = [];
            g_transport_array[0]['transport_number'] = data.auto.transport_number;
            g_transport_array[0]['transport_model'] = data.auto.transport_model;
            g_transport_array[0]['transport_year'] = data.auto.transport_year;
            g_transport_array[0]['transport_region'] = data.auto.transport_region;
            g_transport_array[0]['transport_vin'] = data.auto.transport_vin;

            if(g_is_click_calculate_btn == 1){
                calculateOGPO();
            }
        }
    });
}

var g_cost = 0;
var g_user_name = '';

function showPayContent(){
    if(v_check_correct_date == 0){
        showError('Укажите корректную дату');
        return;
    }
    $('#main_content').remove();
    $('#modal-order').modal('hide');
    $('#pay_content').fadeIn(0);
    $('#email').focus();
}

function payPolice(){
    var check = true;
    if($('#email').val() == '') {
        $('#email_error').html('Укажите корректные данные');
        $('#email_info').addClass('has-error');
        $('#email_message').fadeOut(0);
        check = false;
    }
    else {
        $('#email_info').removeClass('has-error');
        $('#email_error').fadeOut(0);
        $('#email_message').fadeIn(0);
    }

    if($('#phone').val() == '') {
        $('#phone_error').html('Укажите корректные данные');
        $('#phone_info').addClass('has-error');
        $('#phone_message').fadeOut(0);
        check = false;
    }
    else {
        $('#phone_info').removeClass('has-error');
        $('#phone_error').fadeOut(0);
        $('#phone_message').fadeIn(0);
    }

    if(check == false){
        return;
    }

    if(g_cost == 0 || g_iin == ''){
        showError('Произошла ошибка, обновите страницу и попробуйте сначала');
        return;
    }

    var iins = [];
    $(g_iin_array).each(function(){
        iins.push(this.iin);
    });

    $('.ajax-loader').fadeIn();
    $.ajax({
        url:'/ajax/pay-police',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            iin: g_iin,
            cost: g_cost,
            iins: iins,
            before_cost: before_total_cost,
            transport_name: g_transport_name,
            user_name: g_user_name,
            start_date: $('#policy_start_date').val(),
            policy_period: $('#policy_period').val(),
            phone: $('#phone').val(),
            email: $('#email').val(),
            is_need_kasko: $('#is_need_kasko').is(':checked'),
            is_need_ogpo_plus: $('#is_need_dgpo').is(':checked')
        },
        success: function (data) {
            $('.ajax-loader').fadeOut();
            if(data.status == 0){
                showError(data.error);
                return;
            }
            else if(data.status == true){
                $('.ajax-loader').fadeIn();
                window.location.href = data.href;
            }
        }
    });
}

function addRequestLimitKASKO() {
    $('#auto_info').removeClass('has-error');
    $('#iin_info').removeClass('has-error');

    var message_iin = $('#iin_message').html();
    var message_auto = $('#auto_input_message').html();

    var check = checkValidIIN();
    if(check == true){
        $('#iin_message').html(message_iin);
        $('#iin_message').fadeIn(0);
    }

    var check_auto = checkValidCarNumber();
    if(check_auto == true){
        $('#auto_input_message').html(message_auto);
        $('#auto_input_message').fadeIn(0);
    }

    if(check == false || check_auto == false){
        return;
    }

    if(g_exist_iin == 0){
        showError('Некорректные данные водителя');
        return;
    }
    if(g_exist_auto == 0){
        showError('Некорректные данные транспорта');
        return;
    }

    if($('#phone').val() == '') {
        $('#phone_error').html('Укажите корректные данные');
        $('#phone_info').addClass('has-error');
        check = false;
        return;
    }
    else {
        $('#phone_info').removeClass('has-error');
        $('#phone_input_error').fadeOut(0);
    }

    if(v_grade < 4){
        var content = '<div class="calc__total">Ваш класс не подходит, чтобы купить этот полис</div>';
        $('#message_content').html(content);
        return;
    }

    $('.ajax-loader').fadeIn(100);

    $.ajax({
        url:'/ajax/request/policy/kasko-express',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            iin: $('#iin').val(),
            user_name: $('#iin_message').html(),
            transport_name: $('#auto_input_message').html(),
            phone: $('#phone').val(),
            transport_number: $('#auto_number').val(),
            insurance_cost: $('.insurance-cost:checked').val()
        },
        success: function (data) {
            $('.ajax-loader').fadeOut(100);
            if(data.status == 0){
                showError(data.error);
                return;
            }

            $('#phone').val('');
            $('#modal-success').modal('show');
        }
    });


}


function rejectPolicyModal(policy_id){
    g_policy_id = policy_id;

    $('#reject-policy').modal('show');
}

function rejectPolicy(){
    $('.ajax-loader').fadeIn();
    $.ajax({
        url:'/ajax/reject-policy',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            policy_id: g_policy_id
        },
        success: function (data) {
            $('.ajax-loader').fadeOut();
            if(data.status == 0){
                showError(data.error);
                return;
            }
            $('#reject-policy').modal('hide');
            $('#modal-success-reject').modal('show');
        }
    });
}

function showOGPOresult(){
    $('#modal-process2').modal('hide');

    content = '<button type="button" onclick="showPayContent()" class="button -green -md">Купить</button>';
    $('#btn_content_modal').html(content);

    $('#modal-order').modal('show');


    setTimeout(function(){
        $('body').addClass('modal-open');
    },500);

    setTimeout(function(){
        $('body').addClass('modal-open');
    },1500);

    /*$('#modal-order').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    $('body').addClass('modal-open');*/
}

var checked_is_kasko = 1;
var kasko_price = 50;
var checked_is_is_need_dgpo = 0;
var ogpo_plus_price = 3000;

$('#is_need_kasko').click(function(){
    if(checked_is_kasko == 0){
        checked_is_kasko = 1;
        g_cost += kasko_price;
        $('#cancel_additional').prop('checked', false);
        $('#kasko_cost_label').css('display','block');
    }
    else {
        checked_is_kasko = 0;
        $('#is_need_kasko').prop('checked', false);
        g_cost -= kasko_price;
        $('#kasko_cost_label').css('display','none');
    }
    var content = g_cost + ' тг.';
    $('.total_policy_cost').html(content);
});

$('#is_need_dgpo').click(function(){
    if(checked_is_is_need_dgpo == 0){
        checked_is_is_need_dgpo = 1;
        g_cost += ogpo_plus_price;
        $('#cancel_additional').prop('checked', false);
        $('#dgpo_cost_label').css('display','block');
    }
    else {
        checked_is_is_need_dgpo = 0;
        $('#is_need_dgpo').prop('checked', false);
        g_cost -= ogpo_plus_price;
        $('#dgpo_cost_label').css('display','none');
    }
    var content = g_cost + ' тг.';
    $('.total_policy_cost').html(content);
});

$('#cancel_additional').click(function(){
    if(checked_is_is_need_dgpo == 1){
        checked_is_is_need_dgpo = 0;
        $('#is_need_dgpo').prop('checked', false);
        g_cost -= ogpo_plus_price;
        $('#dgpo_cost_label').css('display','none');
    }

    if(checked_is_kasko == 1){
        checked_is_kasko = 0;
        $('#is_need_kasko').prop('checked', false);
        g_cost -= kasko_price;
        $('#kasko_cost_label').css('display','none');
    }

    var content = g_cost + ' тг.';
    $('.total_policy_cost').html(content);
});

v_check_correct_date = 1;

$('#policy_start_date').change(function(){

    var st = $('#correct_date').val();
    var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
    var x = new Date(st.replace(pattern,'$3-$2-$1'));

    var st = $('#policy_start_date').val();
    var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
    var y = new Date(st.replace(pattern,'$3-$2-$1'));

    if(x > y){
        showError('Укажите корректную дату');
        v_check_correct_date = 0;
        return;
    }
    else {
        $('#gritter-notice-wrapper').remove();
        v_check_correct_date = 1;
    }
});