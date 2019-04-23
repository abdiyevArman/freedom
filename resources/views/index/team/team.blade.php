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
                        <li class="breadcrumbs__item -active">
                            {{$menu['menu_name_'.$lang]}}
                        </li>
                    </ul>
                    <h1 class="page__header-title">{{$menu['menu_name_'.$lang]}}</h1>
                </header>

                <main role="main" class="page__content">

                    @foreach($team as $key => $item)

                        <div class="section-heading -green @if($key > 0) pt-5 @endif pb-4">
                            <h2 class="section-heading__title">{{$item['team_name_'.$lang]}}</h2>
                            <div class="section-heading__text">
                                <p>{{$item['team_desc_'.$lang]}}</p>
                            </div>
                        </div>
                        <div class="row" data-gutter="15" vertical-gutter="30">
                            <?php $person = \App\Models\Person::where('team_id',$item->team_id)->where('is_show',1)->orderBy('sort_num','asc')->get(); ?>
                            @foreach($person as $person_item)
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="team">
                                        <img src="{{$person_item['person_image']}}?width=265&height=315" alt="" class="team__img">
                                        <div class="team__name">{{$person_item['person_name_'.$lang]}}</div>
                                        <div class="team__job">{{$person_item['person_desc_'.$lang]}}</div>
                                    </div>
                                </div>
                             @endforeach
                        </div>

                    @endforeach

                </main>

            </div>
        </div>
    </div>

@endsection

@section('js')




@endsection