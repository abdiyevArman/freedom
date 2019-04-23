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
        <div class="page">
            <div class="container">
                <header class="page__header">
                    <ul class="breadcrumbs">
                        <li class="breadcrumbs__item">
                            <a href="/" class="breadcrumbs__link">{{Lang::get('app.homepage')}}</a>
                        </li>
                        @if($menu->parent_name != '')
                            <li class="breadcrumbs__item">
                                <a href="#" class="breadcrumbs__link">{{$menu->parent_name}}</a>
                            </li>
                        @endif

                        @if($request->tag != '')

                            <li class="breadcrumbs__item">
                                <a href="{{$menu->menu_redirect}}" class="breadcrumbs__link">{{$menu['menu_name_'.$lang]}}</a>
                            </li>

                            <li class="breadcrumbs__item -active">{{Lang::get('app.article_by_tag')}} "{{$request->tag}}"</li>

                        @else
                            <li class="breadcrumbs__item -active">{{$menu['menu_name_'.$lang]}}</li>
                        @endif

                    </ul>

                    @if($request->tag != '')

                        <h1 class="page__header-title">{{Lang::get('app.article_by_tag')}} "{{$request->tag}}"</h1>

                    @else

                        <h1 class="page__header-title">{{$menu['menu_name_'.$lang]}}</h1>

                    @endif


                </header>

                <main role="main" class="page__content">
                    <div class="row">
                        @include('index.news.news-list-loop')
                    </div>
                    <div>
                        {!! $news_list->links() !!}
                    </div>
                </main>
            </div>
        </div>
    </div>

@endsection