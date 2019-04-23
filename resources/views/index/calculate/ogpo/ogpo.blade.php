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
                    <div class="casco__title -promo">
                        <span>{{strip_tags(\App\Http\Helpers::getInfoText(18))}}</span>
                        @if(\App\Http\Helpers::getInfoText(1) != '')
                            <a href="{{strip_tags(\App\Http\Helpers::getInfoText(17))}}">{{Lang::get('app.read_more_offer')}}</a>
                        @endif
                    </div>
                    <div class="calc" id="main_content">
                        <div class="calc__title">{{$menu['menu_name_'.$lang]}}</div>
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="control" id="iin_info">
                                    <input type="number" id="iin" class="control__input calc__input" placeholder="{{Lang::get('app.iin_driver')}}" />
                                    <div class="control__help" id="iin_error"></div>
                                    <div class="calc__driver" id="iin_message"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a href="javascript:void(0)" onclick="showNewDriverModal()" class="calc__add-link"  id="add_driver_label">{{Lang::get('app.add_driver')}}</a>
                            </div>
                        </div>
                        <div class="control mb-4">
                            <div class="calc__agree mb-3">
                                <input type="checkbox" class="filled-in" id="is_has_discount">
                                <label for="is_has_discount">{{Lang::get('app.privileges')}}</label>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <div class="control input-car" id="auto_info">
                                    <input type="text" id="auto_number" class="control__input calc__input input-number text-transform" placeholder="{{Lang::get('app.write_auto_number')}}" />
                                    <input style="display: none" type="text" id="passport_number" class="control__input calc__input input-passport text-transform" placeholder="{{Lang::get('app.write_tech_number')}}" />
                                    <div class="change-link">
                                        <a class="input-by-passport" href="javascript:void(0)" >{{Lang::get('app.forget_auto_number')}}</a>
                                        <a class="input-by-number" href="javascript:void(0)" style="display: none">{{Lang::get('app.forget_tech_number')}}</a>
                                    </div>
                                    <div class="control__help" id="auto_input_error"></div>
                                    <div class="calc__driver" id="auto_input_message"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a href="javascript:void(0)" onclick="showNewCarModal()" class="calc__add-link" id="add_car_label">{{Lang::get('app.add_auto')}}</a>
                            </div>
                        </div>
                        <div class="calc__agree">
                            <input type="checkbox" class="filled-in" id="filled-in-box" checked="checked">
                            <label for="filled-in-box">{!!Lang::get('app.agree_label')!!}</label>
                        </div>

                        <div id="message_content"></div>
                        <div id="btn_content" class="calc__buttons">
                            <a href="javascript:void(0)" onclick="calculateOGPO()" class="button -green -md calc__button">{{Lang::get('app.calculate')}}</a>
                        </div>

                    </div>
                    <div class="calc" id="pay_content">
                        <div class="calc__title">ОГПО</div>
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="control " id="email_info">
                                    <input type="email" id="email" class="control__input calc__input" placeholder="Email" />
                                    <div class="calc__driver font-12" id="email_message">{{Lang::get('app.after_buy_email')}}</div>
                                    <div class="control__help" id="email_error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="control" id="phone_info">
                                    <input type="text" id="phone" class="control__input calc__input phone-mask" placeholder="{{Lang::get('app.phone_number')}}" />
                                    <div class="control__help" id="phone_error"></div>
                                    <div class="calc__driver font-12" id="phone_message">{{Lang::get('app.after_buy_number')}}</div>
                                </div>
                            </div>
                        </div>
                        <div id="second_message_content"></div>
                        <div id="btn_content_second" class="calc__buttons">
                            <a href="javascript:void(0)" onclick="payPolice()" class="button -green -md calc__button">{{Lang::get('app.buy')}}</a>
                        </div>
                    </div>
                    <div class="casco__img" style="background-image: url('{!! $menu['menu_image'] !!}')"></div>
                </div>

            </div>
        </section>

        <input type="hidden" id="btn_type" value="calculateOGPO"/>

        @include('index.calculate.ogpo.content')

        @include('index.index.request')

    </div>
    
@endsection

@section('js')

    <script src="/custom/js/esbd.js?v=38"></script>

    <script>
        g_ogpo = 1;
    </script>

@endsection

@section('modals')

    @include('index.calculate.content.new-driver-modal')

    @include('index.calculate.content.new-car-modal')

    @include('index.calculate.ogpo.modal')

@endsection