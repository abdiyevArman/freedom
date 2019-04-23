@foreach($news_list as $item)

    <div class="col-sm-6 col-lg-4">
        <a href="{{$item['news_url_'.$lang]}}" class="news">
            <img src="{{$item->news_image}}?width=545&height=400" alt="{{$item['news_name_'.$lang]}}" class="news__img">
            <div class="news__content">
                <time class="news__date">{{\App\Http\Helpers::getDateFormat($item->news_date)}}</time>
                <div class="news__title">{{$item['news_name_'.$lang]}}</div>
            </div>
        </a>
    </div>

@endforeach