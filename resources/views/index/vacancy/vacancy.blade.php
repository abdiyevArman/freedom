@extends('index.layout.layout')

@section('meta-tags')

    <title>{{$vacancy['city_name_'.$lang]}}. {{$menu['menu_meta_title_'.$lang]}}</title>
    <meta name="description" content="{{$vacancy['city_name_'.$lang]}}. {{$menu['menu_meta_description_'.$lang]}}"/>
    <meta name="keywords" content="{{$vacancy['city_name_'.$lang]}}, {{$menu['menu_meta_keywords_'.$lang]}}"/>

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

                    <li class="breadcrumbs__item">
                        <a href="#" class="breadcrumbs__link">
                            {{$menu['menu_name_'.$lang]}}
                        </a>
                    </li>
                    <li class="breadcrumbs__item -active">
                         {{$vacancy['city_name_'.$lang]}}
                    </li>
                </ul>

                <main role="main" class="page__content">
                    <div class="row">
                        <div class="col-lg-8">

                            <div class="section-heading">
                                <h1 class="section-heading__title">{{Lang::get('app.vacancies')}}</h1>
                            </div>

                            <div class="vacancy">

                                @foreach($vacancies as $item)

                                    <div class="vacancy__item">
                                        <div class="vacancy__title">{{$item['vacancy_name_'.$lang]}}</div>
                                        <div class="vacancy__text">{{$item['vacancy_desc_'.$lang]}}</div>
                                        <a href="javascript:void(0)" onclick="showVacancyModal('{{$item->vacancy_id}}')" class="button -green -md vacancy__button">{{Lang::get('app.response')}}</a>
                                    </div>

                                @endforeach

                            </div>

                        </div>
                        <div class="col-lg-4">

                            <!-- Aside Begin -->
                            <aside>
                                <ul class="city">
                                    @foreach($city_list as $item)
                                        <li class="city__item"><a href="/vacancy/{{$item['city_url_'.$lang]}}" class="city__link @if($item->city_id == $vacancy->city_id) -active @endif">{{$item['city_name_'.$lang]}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </aside>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    @include('index.vacancy.modal')

@endsection

@section('js')



@endsection