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
                    @if($menu->parent_name != '')
                        <li class="breadcrumbs__item">
                            <a href="#" class="breadcrumbs__link">{{$menu->parent_name}}</a>
                        </li>
                    @endif

                    <li class="breadcrumbs__item -active">{{$menu['menu_name_'.$lang]}}</li>
                </ul>

                <main role="main" class="page__content">
                    <div class="row">
                        <div class="col-lg-12">
                            <article class="article">
                                <header class="article__header">
                                    <h1 class="article__header-title">{{$menu['menu_name_'.$lang]}}</h1>
                                </header>
                                <div class="article__body">
                                    <div>
                                        {!! $menu['menu_text_'.$lang] !!}
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>


@endsection

