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

                        <li class="breadcrumbs__item -active">{{$menu['menu_name_'.$lang]}}</li>

                    </ul>

                    <h1 class="page__header-title">{{$menu['menu_name_'.$lang]}}</h1>

                </header>

                <main role="main" class="page__content">
                    <div class="row">
                        @include('index.offer.offer-list-loop')
                    </div>
                    <div>
                        {!! $offer_list->links() !!}
                    </div>
                </main>
            </div>
        </div>
    </div>

@endsection