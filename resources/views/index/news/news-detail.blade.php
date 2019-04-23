@extends('index.layout.layout')

@section('meta-tags')

    <title>{{$news['news_name_'.$lang]}}</title>
    <meta name="description" content="{{$news['news_meta_description_'.$lang]}}"/>
    <meta name="keywords" content="{{$news['news_meta_keywords_'.$lang]}}"/>
    <meta property="og:title" content="{{$news['news_name_'.$lang]}}" />
    <meta property="og:description" content="{{$news['news_meta_description_'.$lang]}}" />
    <meta property="og:url" content="{{URL('/')}}/{{$news['news_url_'.$lang]}}" />
    <meta property="og:image" content="{{$news->news_image}}" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="500" />
    <link rel="image_src" href="{{$news->news_image}}" />
    
@endsection

@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">
        <div class="page">
            <div class="container">
                <ul class="breadcrumbs">
                    <li class="breadcrumbs__item -back">
                        <a href="{{$menu->menu_redirect}}" class="breadcrumbs__link">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 11.0001H7.83L13.42 5.41006L12 4.00006L4 12.0001L12 20.0001L13.41 18.5901L7.83 13.0001H20V11.0001Z" fill="#4F9D3A" />
                            </svg>
                            Назад
                        </a>
                    </li>
                    <li class="breadcrumbs__item">
                        <a href="/" class="breadcrumbs__link">{{Lang::get('app.homepage')}}</a>
                    </li>
                    @if($menu->parent_name != '')
                        <li class="breadcrumbs__item">
                            <a href="#" class="breadcrumbs__link">{{$menu->parent_name}}</a>
                        </li>
                    @endif
                    <li class="breadcrumbs__item">
                        <a href="{{$menu->menu_redirect}}" class="breadcrumbs__link">{{$menu['menu_name_'.$lang]}}</a>
                    </li>
                    <li class="breadcrumbs__item -active">{{$news['news_name_'.$lang]}}</li>

                </ul>

                <main role="main" class="page__content">
                    <div class="row">
                        <div class="col-lg-8">
                            <article class="article">
                                <header class="article__header">
                                    <h1 class="article__header-title">{{$news['news_name_'.$lang]}}</h1>
                                    <div class="article__header-subtitle">{{$news['news_desc_'.$lang]}}</div>

                                    <?php $news['tag_'.$lang] = str_replace(', ',',',$news['tag_'.$lang]); $tags = explode(",", $news['tag_'.$lang]);?>

                                    @if(isset($tags[0]) && $tags[0] != '')
                                        <div class="tags">
                                            @foreach($tags as $key => $item)
                                                @if($item != '')
                                                  <a class="tags__link" href="{{$menu->menu_redirect}}?tag={{$item}}">#{{$item}}</a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </header>
                                <div class="article__body">
                                    @if($news->news_image != '/static/img/content/default.jpg')
                                        <div>
                                            <img src="{{$news->news_image}}" alt="">
                                        </div>
                                    @endif
                                    <div>
                                        {!! $news['news_text_'.$lang] !!}
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-lg-4">
                            <aside>
                                @include('index.news.other-news-list-loop')
                            </aside>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>


@endsection

