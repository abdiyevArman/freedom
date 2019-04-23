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
                <header class="page__header">
                    <ul class="breadcrumbs">
                        <li class="breadcrumbs__item">
                            <a href="/" class="breadcrumbs__link">{{Lang::get('app.homepage')}}</a>
                        </li>
                        <li class="breadcrumbs__item -active">{{$menu['menu_name_'.$lang]}}</li>
                    </ul>
                    <h1 class="page__header-title">{{$menu['menu_name_'.$lang]}}</h1>
                </header>

                <main role="main" class="page__content">
                    <div class="row">
                        @foreach($service_list as $item)

                            <div class="col-sm-6 col-lg-4">
                                <a href="{{$item['menu_url_'.$lang]}}" class="news">
                                    <img src="{{$item->menu_image}}?width=545&height=400" alt="{{$item['menu_name_'.$lang]}}" class="news__img">
                                    <div class="news__content">
                                        {{--<time class="offer__date">{{\App\Http\Helpers::getDateFormat($item->offer_date)}}</time>--}}
                                        <div class="news__title">{{$item['menu_name_'.$lang]}}</div>
                                    </div>
                                </a>
                            </div>

                        @endforeach
                    </div>
                </main>

            </div>
        </div>
    </div>

@endsection