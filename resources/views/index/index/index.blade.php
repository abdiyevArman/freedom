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
        <section class="entry">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-xl-6">
                        <img src="{!! \App\Http\Helpers::getInfoImage(16) !!}?width=528&height=336" alt="" class="entry__img">
                        <div class="entry__promo text-center mb-2 promo-label-main">{!! \App\Http\Helpers::getInfoText(16) !!}</div>
                        <div class="text-center">
                            <a href="{!! \App\Http\Helpers::getInfoText(17) !!}" class="button -green -md">{{Lang::get('app.read_more')}}</a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-xl-6">

                        {!! \App\Http\Helpers::getInfoText(12) !!}

                        <div class="entry__buttons">
                            <a href="javascript:void(0)" onclick="showCheckValidateOGPOModal()" class="button -bordered entry__button">{{Lang::get('app.check_policy_label')}}</a>
                            <a href="javascript:void(0)" onclick="showBuyModal()" class="button -bordered entry__button">{{Lang::get('app.buy_policy')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('index.index.content')

        <section class="auto">
            <div class="container">
                <div class="auto__inner">
                    <div class="auto__content">
                        <div>
                            {!! \App\Http\Helpers::getInfoText(19) !!}
                        </div>

                        <a href="javascript:void(0)" onclick="setFocusRequest()" class="button -bordered_white auto__button">{{Lang::get('app.send_request')}}</a>
                    </div>
                    <div class="auto__img">
                        <img src="{!! \App\Http\Helpers::getInfoImage(19) !!}" alt="">
                    </div>
                </div>
            </div>
        </section>

        @include('index.index.request')

    </div>

    @include('index.index.modal')

    
@endsection