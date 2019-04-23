@foreach($offer_list as $item)

    <a href="{{$item['offer_url_'.$lang]}}" class="news">
        <img src="{{$item->offer_image}}?width=545&height=400" alt="{{$item['offer_name_'.$lang]}}" class="news__img">
        <div class="news__content">
            {{--<time class="offer__date">{{\App\Http\Helpers::getDateFormat($item->offer_date)}}</time>--}}
            <div class="news__title">{{$item['offer_name_'.$lang]}}</div>
        </div>
    </a>

@endforeach