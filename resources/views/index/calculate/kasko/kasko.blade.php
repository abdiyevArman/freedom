@extends('index.layout.layout')

@section('meta-tags')

    <title>{{$menu['menu_meta_title_'.$lang]}}</title>
    <meta name="description" content="{{$menu['menu_meta_description_'.$lang]}}"/>
    <meta name="keywords" content="{{$menu['menu_meta_keywords_'.$lang]}}"/>

@endsection

@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">

        <section class="casco">
            <div class="container">
                <div class="casco__inner">
                    <div class="casco__title">{!! Lang::get('app.kasko_label') !!}</div>
                    <div class="kasko_step1">
                        <div class="calc">
                            <div class="calc__title">{!! Lang::get('app.calc_cost_policy') !!}</div>
                            <div class="cars">
                                <div class="cars__item">
                                    <input class="cars__radio" onchange="changeTransportTab(1)" name="cars" type="radio" checked id="car1" />
                                    <label for="car1">
                                        <img src="/static/img/general/ic-crossover.svg" alt="" class="cars__img">
                                        <span>{!! Lang::get('app.transport_1') !!}</span>
                                    </label>
                                </div>
                                <div class="cars__item">
                                    <input class="cars__radio" onchange="changeTransportTab(2)" name="cars" type="radio" id="car2"  />
                                    <label for="car2">
                                        <img src="/static/img/general/ic-school-bus.svg" alt="" class="cars__img">
                                        <span>{!! Lang::get('app.transport_2') !!}</span>
                                    </label>
                                </div>
                                <div class="cars__item">
                                    <input class="cars__radio" onchange="changeTransportTab(3)" name="cars" type="radio" id="car3" />
                                    <label for="car3">
                                        <img src="/static/img/general/ic-ambulance.svg" alt="" class="cars__img">
                                        <span>{!! Lang::get('app.transport_3') !!}</span>
                                    </label>
                                </div>
                                <div class="cars__item">
                                    <input class="cars__radio" onchange="changeTransportTab(4)" name="cars" type="radio" id="car4" />
                                    <label for="car4">
                                        <img src="/static/img/general/ic-motorcycle.svg" alt="" class="cars__img">
                                        <span>{!! Lang::get('app.transport_4') !!}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="control mb-4">
                                <div class="control__group" id="transport_model_info">
                                    <input type="text" id="transport_model" class="control__input calc__input auto-complete" placeholder="{!! Lang::get('app.model_transport') !!}" />
                                    <div class="control__help" id="transport_model_error">Неккоректная информация</div>
                                </div>
                                <div class="control__group" id="transport_cost_info">
                                    <input type="number" id="transport_cost" class="control__input calc__input" placeholder="{!! Lang::get('app.cost_my_transport') !!}" />
                                    <div class="control__help" id="transport_cost_error">Неккоректный номер телефона</div>
                                </div>
                            </div>
                            <div class="calc__agree">
                                <input type="checkbox" class="filled-in" id="filled-in-box">
                                <label for="filled-in-box">{!! Lang::get('app.agree_label') !!}</label>
                            </div>
                            <div class="calc__buttons">
                                <a href="javascript:void(0)" onclick="showKaskoStep2()" class="button -green -md calc__button">{!! Lang::get('app.next') !!}</a>
                            </div>
                        </div>
                    </div>
                    <div class="kasko_step2" style="display: none">
                        <div class="calc">
                            <div class="calc__title">{!! Lang::get('app.info_user_insurance') !!}</div>
                            <div class="control mb-4">
                                <div class="control__group" id="user_name_info">
                                    <input type="text" id="user_name" class="control__input calc__input" placeholder="{!! Lang::get('app.full_name_middle') !!}" />
                                    <div class="control__help" id="user_name_error">Неккоректный номер телефона</div>
                                </div>
                                <div class="row" data-gutter="15">
                                    <div class="col-md-6">
                                        <div class="control__group" id="phone_info">
                                            <input type="text" id="phone" class="control__input calc__input phone-mask" placeholder="{!! Lang::get('app.phone_number') !!}" />
                                            <div class="control__help" id="phone_error">Неккоректный номер телефона</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="control__group" id="email_info">
                                            <input type="email" id="email" class="control__input calc__input" placeholder="E-mail" />
                                            <div class="control__help" id="email_error">Неккоректный номер телефона</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4" data-gutter="15" vertical-gutter="30">
                                <div class="col-md-6">
                                    <div class="age">
                                        <div class="age__label">{!! Lang::get('app.age_driver') !!}:</div>
                                        <div class="age__input">
                                            <div class="age__item">
                                                <input value="Младше 22 лет" class="calc__cars-radio driver-age" name="age" type="radio" id="ageLess" checked />
                                                <label for="ageLess">{!! Lang::get('app.age_22') !!}</label>
                                            </div>
                                            <div class="age__item">
                                                <input value="Старше 22 лет" class="calc__cars-radio driver-age" name="age" type="radio" id="ageBigger" />
                                                <label for="ageBigger">{!! Lang::get('app.age_22_after') !!}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="age">
                                        <div class="age__label">{!! Lang::get('app.experience_driver') !!}:</div>
                                        <div class="age__input">
                                            <div class="age__item">
                                                <input value="Свыше 2 лет" class="calc__cars-radio driver-experience" name="exp" type="radio" id="expBigger" checked />
                                                <label for="expBigger">{!! Lang::get('app.experience_2_year') !!}</label>
                                            </div>
                                            <div class="age__item">
                                                <input value="Меньше 2 лет" class="calc__cars-radio driver-experience" name="exp" type="radio" id="expLess" />
                                                <label for="expLess">{!! Lang::get('app.experience_1_year') !!}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="calc__agree">
                                <input type="checkbox" class="filled-in is_exist_accident" id="filled-in-box2">
                                <label for="filled-in-box2">{!! Lang::get('app.accident') !!}</label>
                            </div>
                            <div class="calc__buttons">
                                <a href="javascript:void(0)" onclick="showKaskoStep1()" class="button -bordered_gray -md calc__button">{!! Lang::get('app.back') !!}</a>
                                <a href="javascript:void(0)" onclick="addRequestKASKO()" style="display: none" id="btn_request" class="button -green -md calc__button">{!! Lang::get('app.add_request') !!}</a>
                                <a href="javascript:void(0)" onclick="showKaskoStep3()" id="btn_calculator" class="button -green -md calc__button">{!! Lang::get('app.calculate') !!}</a>
                            </div>
                        </div>
                    </div>
                    <div class="kasko_step3" style="display: none">
                        <div class="calc">
                            <div class="calc__title">{!! Lang::get('app.your_kasko') !!}</div>
                            <div class="calc__promo">
                                <div class="calc__promo-title"><span id="label_from">от</span> <span class="calc__promo-number" id="policy_cost" data-discount="10"></span> тг/год</div>
                                {!! Lang::get('app.your_discount') !!}
                            </div>
                            <p>{!! Lang::get('app.choice_franchise') !!}:</p>
                            <div class="franchise-slider">
                                <div class="franchise-slider__bar">
                                    <div class="franchise-slider__handler"></div>
                                </div>
                                <div class="franchise-slider__bottom">
                                    <div class="franchise-slider__text" data-value="2">2%</div>
                                    <div class="franchise-slider__text" data-value="1">1%</div>
                                    <div class="franchise-slider__text" data-value="0">0%</div>
                                </div>
                            </div>
                            <div class="calc__total">{!! Lang::get('app.your_franchise') !!}: <strong><span class="calc__total-number" id="fransh_cost"></span>тг.</strong>
                            </div>
                            <div class="row mt-3 mb-4" vertical-gutter="30">
                                <div class="col-md-6">
                                    <div class="mb-2">{!! Lang::get('app.calc_sum') !!}:</div>
                                    <p class="m-0">
                                        <input type="checkbox" class="filled-in" id="amort" onchange="checkedGai()" />
                                        <label for="amort">{!! Lang::get('app.without_amort') !!}</label>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">{!! Lang::get('app.call_GAI') !!}</div>
                                    <p class="m-0">
                                        <input type="checkbox" class="filled-in" id="gai" onchange="checkedGai()"/>
                                        <label for="gai">{!! Lang::get('app.not_necessary') !!}</label>
                                    </p>
                                </div>
                            </div>
                            <p class="m-0">{!! Lang::get('app.kasko_label2') !!}</p>
                            <div class="calc__buttons">
                                <a href="javascript:void(0)" onclick="showKaskoStep2()" class="button -bordered_gray -md calc__button">{!! Lang::get('app.back') !!}</a>
                                <a href="javascript:void(0)" onclick="addRequestKASKO()" class="button -green -md calc__button">{!! Lang::get('app.send_request') !!}</a>
                            </div>
                        </div>
                    </div>
                    <div class="casco__img" style="background-image: url('{!! $menu['menu_image'] !!}')"></div>
                </div>
            </div>
        </section>
        
        @include('index.calculate.kasko.content')

        @include('index.index.request')

        @include('index.calculate.kasko-express.modal')

    </div>
    
@endsection

@section('js')


    <script src="/custom/js/kasko.js?v=1"></script>

    <script src="/custom/js/jquery-ui.js?v=12"></script>
    <link href="/custom/js/jquery-ui.css?v=12" rel="stylesheet" type="text/css">

    <script>
        $( function() {
            var availableTags = [
                "BMW X5 2010",
                "BMW X5 2011",
                "BMW X5 2012",
                "BMW X5 2013",
                "Toyota Camry 2010",
                "Toyota Camry 2011",
                "Toyota Camry 2012",
                "Toyota Camry 2013",
                "Toyota Corolla 2010",
                "Toyota Corolla 2011",
                "Toyota Corolla 2012",
                "Toyota Corolla 2013",
                "Mercedes CLK 2010",
                "Mercedes CLK 2011",
                "Mercedes CLK 2012",
                "Mercedes CLK 2013",
            ];
            $( ".auto-complete" ).autocomplete({
                source: availableTags
            });

            $('#transport_model').change(function(){
               $('#transport_cost').val('6000000');
            });
        } );
    </script>

@endsection

@section('modals')



@endsection