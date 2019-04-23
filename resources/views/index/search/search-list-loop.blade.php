@foreach($menu_list as $item)

    <div class="search-results">
        <a target="_blank" href="@if($item->menu_redirect == '')/{{$item['menu_url_'.$lang]}}@else{{$item['menu_redirect']}}@endif" class="search-results__title">{{$item['menu_name_'.$lang]}}</a>
    </div>

@endforeach

@foreach($news_list as $item)

    <div class="search-results">
        <a target="_blank" href="{{$item['news_url_'.$lang]}}" class="search-results__title">{{$item['news_name_'.$lang]}}</a>
        <div class="search-results__category">{{Lang::get('app.news')}}</div>
        <div class="search-results__text">{{$item['news_desc_'.$lang]}}</div>
    </div>

@endforeach

@foreach($vacancy_list as $item)

    <div class="search-results">
        <a target="_blank" href="/vacancy" class="search-results__title">{{$item['vacancy_name_'.$lang]}}</a>
        <div class="search-results__category">{{Lang::get('app.vacancy')}}. {{$item['city_name_'.$lang]}}</div>
        <div class="search-results__text">{{$item['vacancy_desc_'.$lang]}}</div>
    </div>

@endforeach

@foreach($offer_list as $item)

    <div class="search-results">
        <a target="_blank" href="{{$item['offer_url_'.$lang]}}" class="search-results__title">{{$item['offer_name_'.$lang]}}</a>
        <div class="search-results__category">{{Lang::get('app.offer')}}</div>
        <div class="search-results__text">{{$item['offer_desc_'.$lang]}}</div>
    </div>

@endforeach
