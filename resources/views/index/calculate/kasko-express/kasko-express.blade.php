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
                    <div class="casco__title">{{Lang::get('app.kasko_express_label')}}</div>
                    <div class="calc">
                        <div class="calc__title">{{$menu['menu_name_'.$lang]}}</div>
                        <div class="calc" id="main_content">
                            <div class="row mb-4">
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
                            <div class="row mb-3">
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
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="control" id="phone_info">
                                        <input type="text" id="phone" class="control__input calc__input phone-mask" placeholder="{{Lang::get('app.phone_number')}}" />
                                        <div class="control__help" id="phone_error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="mb-5">
                            <div class="calc__label">{{Lang::get('app.insurance_sum_auto')}}</div>
                            <div class="control">
                                <ul class="control__input-group">
                                    <li class="control__input-group-item">
                                        <input onchange="calculateLimitKASKO()" class="with-gap insurance-cost" name="money" value="300000" type="radio" id="test1" />
                                        <label for="test1">300 000 тг.</label>
                                    </li>
                                    <li class="control__input-group-item">
                                        <input onchange="calculateLimitKASKO()" class="with-gap insurance-cost" name="money" value="400000" type="radio" id="test2"  />
                                        <label for="test2">400 000 тг.</label>
                                    </li>
                                    <li class="control__input-group-item">
                                        <input onchange="calculateLimitKASKO()" class="with-gap insurance-cost" name="money" value="500000" type="radio" id="test3" />
                                        <label for="test3">500 000 тг.</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="calc__agree">
                            <input type="checkbox" class="filled-in" id="filled-in-box" checked="checked">
                            <label for="filled-in-box">{!! Lang::get('app.agree_label') !!}</label>
                        </div>
                        <div id="message_content"></div>
                        <div id="btn_content" class="calc__buttons">
                            <a href="javascript:void(0)" onclick="addRequestLimitKASKO()" class="button -green -md calc__button">{!! Lang::get('app.add_request') !!}</a>
                        </div>
                    </div>
                    <div class="casco__img" style="background-image: url('{!! $menu['menu_image'] !!}')"></div>
                </div>
            </div>
        </section>


        <input type="hidden" id="btn_type" value="calculateLimitKASKO"/>

        @include('index.calculate.kasko-express.content')

        @include('index.index.request')


    </div>

    @include('index.calculate.kasko-express.modal')
@endsection

@section('js')

    <script src="/custom/js/esbd.js?v=42"></script>

    <script>
        g_is_page_limit_kasko = 1;
    </script>

@endsection

@section('modals')

    @include('index.calculate.content.new-driver-modal')


@endsection