@extends('index.layout.layout')

@section('meta-tags')

    <title>{{$contact['city_name_'.$lang]}}. {{$menu['menu_meta_title_'.$lang]}}</title>
    <meta name="description" content="{{$contact['city_name_'.$lang]}}. {{$menu['menu_meta_description_'.$lang]}}"/>
    <meta name="keywords" content="{{$contact['city_name_'.$lang]}}, {{$menu['menu_meta_keywords_'.$lang]}}"/>

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
                         {{$contact['city_name_'.$lang]}}
                    </li>
                </ul>

                <main role="main" class="page__content">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="contacts">

                                @if($contact['address_'.$lang] != '' || $contact['email'] != '' || $contact['phone'] != '' || $contact['phone2'] != '' || $contact['schedule'] != '')

                                    <div class="section-heading">
                                        <h1 class="section-heading__title">{{Lang::get('app.contact')}}</h1>
                                    </div>
                                    <div class="row mb-5" vertical-gutter="20">
                                        @if($contact['address_'.$lang] != '')
                                            <div class="col-md-12">
                                                <div class="contacts__info -location">{{$contact['address_'.$lang]}}</div>
                                            </div>
                                        @endif
                                        @if($contact['email'] != '')
                                            <div class="col-md-12">
                                                <div class="contacts__info -mail">{{$contact['email']}}</div>
                                            </div>
                                        @endif
                                        @if($contact['phone'] != '')
                                            <div class="col-md-12">
                                                <div class="contacts__info -phone">{{$contact['phone']}}</div>
                                            </div>
                                        @endif
                                        @if($contact['phone2'] != '')
                                            <div class="col-md-12">
                                                <div class="contacts__info -phone">{{$contact['phone2']}}</div>
                                            </div>
                                        @endif
                                        @if($contact['schedule'] != '')
                                            <div class="col-md-12">
                                                <div class="contacts__info -schedule">{{$contact['schedule']}}</div>
                                            </div>
                                        @endif
                                    </div>

                                @endif

                                @if($contact['email2'] != '' || $contact['phone3'] != '' || $contact['phone4'] != '' || $contact['schedule2'] != '')

                                    <div class="section-heading">
                                        <div class="section-heading__title">Call-center</div>
                                    </div>
                                    <div class="row mb-5" vertical-gutter="20">
                                        @if($contact['email2'] != '')
                                            <div class="col-md-12">
                                                <div class="contacts__info -mail">{{$contact['email2']}}</div>
                                            </div>
                                        @endif
                                        @if($contact['phone3'] != '')
                                            <div class="col-md-12">
                                                <div class="contacts__info -phone">{{$contact['phone3']}}</div>
                                            </div>
                                        @endif
                                        @if($contact['phone4'] != '')
                                            <div class="col-md-12">
                                                <div class="contacts__info -phone">{{$contact['phone4']}}</div>
                                            </div>
                                        @endif
                                        @if($contact['schedule2'] != '')
                                            <div class="col-md-12">
                                                <div class="contacts__info -schedule">{{$contact['schedule2']}}</div>
                                            </div>
                                        @endif
                                    </div>

                                @endif

                                @if(count($address_list) > 0 && isset($address_list[0]))
                                        <div id="jsYandexMap" class="contacts__map"></div>
                                @endif


                                <div class="section-heading">
                                    <div class="section-heading__title">{{Lang::get('app.exist_question')}}</div>
                                </div>
                                <form class="contacts__form">
                                    <div class="control">
                                        <div class="control__group">
                                            <input type="text" class="control__input" id="user_name_request" placeholder="{{Lang::get('app.full_name')}}" />
                                            <div class="control__help">Это поле нужно заполнить</div>
                                        </div>
                                        <div class="control__group">
                                            <input type="text" class="control__input phone-mask" id="phone_request" placeholder="{{Lang::get('app.phone_number')}}" />
                                            <div class="control__help">Это поле нужно заполнить</div>
                                        </div>
                                        <div class="control__group">
                                            <input type="text" class="control__input" id="email_request" placeholder="Email" />
                                            <div class="control__help">Это поле нужно заполнить</div>
                                        </div>
                                        <div class="control__group">
                                            <textarea class="control__input -textarea" id="comment" placeholder="{{Lang::get('app.comment')}}"></textarea>
                                            <div class="control__help">Это поле нужно заполнить</div>
                                        </div>
                                        <button type="button" onclick="addRequest()" class="button -green -md contacts__button">{{Lang::get('app.send')}}</button>
                                    </div>
                                </form>
                            </div>
                            <!--/. Contacts End -->

                        </div>
                        <div class="col-lg-4">

                            <!-- Aside Begin -->
                            <aside>
                                <ul class="city">
                                    @foreach($city_list as $item)
                                        <li class="city__item"><a href="/contact/{{$item['city_url_'.$lang]}}" class="city__link @if($item->city_id == $contact->city_id) -active @endif">{{$item['city_name_'.$lang]}}</a>
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

@endsection

@section('js')

    @if(count($address_list) > 0 && isset($address_list[0]))

        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

        <script type="text/javascript">

            ymaps.ready(init);
            var myMap;

            function init() {

                var i;
                var place;
                var pointer = [
                    @foreach($address_list as $item)
                        [{{$item->latitude}},{{$item->longitude}}],
                    @endforeach
                ];

                var myMap = new ymaps.Map("jsYandexMap", {

                    center: [{{$address_list[0]['latitude']}},{{$address_list[0]['longitude']}}],
                    zoom: 13

                });

                for(i = 0; i < pointer.length; ++i) {

                    place = new ymaps.Placemark(pointer[i]);
                    myMap.geoObjects.add(place);

                }

            };

        </script>

    @endif



@endsection