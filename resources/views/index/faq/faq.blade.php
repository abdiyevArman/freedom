@extends('index.layout.layout')

@section('meta-tags')

    <title>{{$menu['parent_name']}}. {{$menu['menu_meta_title_'.$lang]}}</title>
    <meta name="description" content="{{$menu['parent_name']}}. {{$menu['menu_meta_description_'.$lang]}}"/>
    <meta name="keywords" content="{{$menu['parent_name']}}, {{$menu['menu_meta_keywords_'.$lang]}}"/>

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
                        <li class="breadcrumbs__item -active">
                            {{$menu['menu_name_'.$lang]}}
                        </li>
                    </ul>
                    <h1 class="page__header-title">{{$menu->parent_name}}. {{$menu['menu_name_'.$lang]}}</h1>
                </header>
                <main role="main" class="page__content">
                    <section class="faq -collapse pt-0">
                        <div class="container">
                            <div id="faq" class="accordion">

                                @foreach($questions as $key => $item)

                                    <div class="faq__item">
                                        <div class="faq__heading" data-toggle="collapse" data-parent="#faq" aria-expanded="false" data-target="#faq{{$key}}" href="javascript:void(0);">
                                            <div class="faq__num">{{$key + 1}}</div>
                                            <div class="faq__title">{{$item['faq_name_'.$lang]}}</div>
                                        </div>
                                        <div id="faq{{$key}}" class="collapse" data-parent="#faq">
                                            <div class="faq__body">
                                                {!! $item['faq_desc_'.$lang] !!}
                                            </div>
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>

@endsection

@section('js')




@endsection