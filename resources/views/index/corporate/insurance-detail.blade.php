@extends('index.layout.layout')

@section('meta-tags')

    <title>{{$menu['menu_meta_title_'.$lang]}}</title>
    <meta name="description" content="{{$menu['menu_meta_description_'.$lang]}}"/>
    <meta name="keywords" content="{{$menu['menu_meta_keywords_'.$lang]}}"/>
    <meta property="og:title" content="{{$menu['menu_meta_title_'.$lang]}}" />
    <meta property="og:description" content="{{$menu['menu_meta_description_'.$lang]}}" />

@endsection


@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">
        <div class="page">
            <div class="container">
                <ul class="breadcrumbs">
                    <li class="breadcrumbs__item">
                        <a href="/" class="breadcrumbs__link">{{Lang::get('app.homepage')}}</a>
                    </li>
                    <li class="breadcrumbs__item">
                        <a href="/corporate" class="breadcrumbs__link">{{$menu->parent_name}}</a>
                    </li>
                    <li class="breadcrumbs__item -active">{{$menu['menu_name_'.$lang]}}</li>
                </ul>

                <main role="main" class="page__content">
                    <article class="entity-article">
                        <header class="entity-article__header">
                            <h1 class="entity-article__title">{{$menu['menu_name_'.$lang]}}</h1>
                            <a href="javascript:void(0)" onclick="focusRequest()" class="button -bordered_white -md entity-article__button">{{Lang::get('app.issue')}}</a>
                            <div class="entity-article__img" style="background-image: url('{{$menu['menu_image']}}')"></div>
                        </header>
                        <div class="entity-article__body">
                            {!! $menu['menu_text_'.$lang] !!}
                        </div>
                    </article>

                    @include('index.corporate.request')

                </main>
            </div>
        </div>
    </div>


@endsection

