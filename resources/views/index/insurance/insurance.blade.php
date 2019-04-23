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

                    <!-- Insurance Begin -->
                    <div class="insurance">
                        <div class="row mb-4" data-gutter="15">
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <input type="text" id="last_name" value="{{$row->last_name}}" class="control__input" placeholder="{{Lang::get('app.name')}}*" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <input type="text" id="first_name" value="{{$row->first_name}}" class="control__input" placeholder="{{Lang::get('app.last_name')}}*" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <input type="text" id="policy_number" value="{{$row->policy_number}}" class="control__input" placeholder="{{Lang::get('app.your_policy_number')}}*" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <input type="text" id="phone" value="{{$row->phone}}" class="control__input phone-mask" placeholder="{{Lang::get('app.phone_number')}}*" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4" data-gutter="15">
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <label class="insurance__label">{{Lang::get('app.begin_date_event')}}: <span>*</span>
                                        </label>
                                        <input type="text" id="policy_date" class="control__input jsBootstrapDatePicker" placeholder="{{Lang::get('app.date')}}" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <label class="insurance__label">{{Lang::get('app.time')}}: <span>*</span>
                                        </label>
                                        <input type="text" id="policy_time" class="control__input jsTimepicker1" placeholder="{{Lang::get('app.time')}}*" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <label class="insurance__label">{{Lang::get('app.city_event')}}: <span>*</span>
                                        </label>
                                        <select class="jsSelectPicker" id="city_id" title="Город" data-live-search="true">
                                            @foreach($city_list as $item)
                                                <option value="{{$item->city_id}}">{{$item['city_name_'.$lang]}}</option>
                                            @endforeach
                                        </select>
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <label class="insurance__label">{{Lang::get('app.culprit')}}: <span>*</span>
                                        </label>
                                        <input type="text" id="causer_name" class="control__input" placeholder="{{Lang::get('app.full_name')}}*" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="insurance__label">{{Lang::get('app.begin_event_car')}}: <span>*</span>
                        </div>
                        <div class="row mb-4" data-gutter="15">
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <input type="text" class="control__input car_policy_list" placeholder="{{Lang::get('app.car')}} 1" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <input type="text" class="control__input car_policy_list" placeholder="{{Lang::get('app.car')}} 2" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <input type="text" class="control__input car_policy_list" placeholder="{{Lang::get('app.car')}} 3" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <input type="text" class="control__input car_policy_list" placeholder="{{Lang::get('app.car')}} 4" />
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <div class="control">
                                    <div class="control__group">
                                        <div class="insurance__label">{{Lang::get('app.short_desc_event')}}: <span>*</span>
                                        </div>
                                        <textarea class="control__input -textarea" id="situation_desc" placeholder="{{Lang::get('app.describe_event')}}"></textarea>
                                        <div class="control__help">Неккоректный номер телефона</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <div class="insurance__title">{{Lang::get('app.take_photo_event')}}</div>

                            @foreach($document_types as $key => $item)

                                @if($item->is_doc == 0)

                                    <div class="insurance__label">{{$item['document_type_name_'.$lang]}}:</div>
                                    <div class="doc-upload">
                                        <form id="upload_document_{{$item->document_type_id}}" enctype="multipart/form-data" method="post">
                                            <div class="doc-upload__area">
                                                <label for="doc-modal{{$item->document_type_id}}" class="doc-upload__label">{{Lang::get('app.upload_image')}}</label>
                                                <input id="doc-modal{{$item->document_type_id}}" name="image" onchange="uploadInsuranceDocument('{{$item->document_type_id}}')" type="file" class="doc-upload__input">
                                            </div>
                                        </form>
                                        <div class="doc-upload__files" id="file_list_{{$item->document_type_id}}">

                                        </div>
                                    </div>

                                @endif

                            @endforeach


                        </div>
                        <div class="mb-4">
                            <div class="insurance__title">{{Lang::get('app.take_photo_doc')}}</div>

                            @foreach($document_types as $key => $item)

                                @if($item->is_doc == 1)
                                    <div class="mb-4">
                                        <div class="insurance__label">{{$item['document_type_name_'.$lang]}}:</div>
                                        <div class="doc-upload">
                                            <form id="upload_document_{{$item->document_type_id}}" enctype="multipart/form-data" method="post">
                                                <div class="doc-upload__area">
                                                    <label for="doc-modal{{$item->document_type_id}}" class="doc-upload__label">{{Lang::get('app.upload_image')}}</label>
                                                    <input id="doc-modal{{$item->document_type_id}}" name="image" onchange="uploadInsuranceDocument('{{$item->document_type_id}}')" type="file" class="doc-upload__input">
                                                </div>
                                            </form>
                                            <div class="doc-upload__files" id="file_list_{{$item->document_type_id}}">

                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @endforeach

                            {{--<div class="mb-4">
                                <div class="insurance__label">Нужен ли эвакуатор?</div>
                                <div class="input-group">
                                    <div class="mr-4">
                                        <input type="radio" value="1" name="is_need_evacuator" class="with-gap is_need_evacuator" id="filled-in-box">
                                        <label for="filled-in-box">Да</label>
                                    </div>
                                    <div>
                                        <input type="radio" value="0" name="is_need_evacuator" checked class="with-gap is_need_evacuator" id="filled-in-box2">
                                        <label for="filled-in-box2">Нет</label>
                                    </div>
                                </div>
                            </div>--}}
                        </div>
                        <button type="button" onclick="addInsuranceRequest()" class="button -green -md mt-4">{{Lang::get('app.send')}}</button>
                    </div>
                    <!--/. Insurance End -->

                </main>
            </div>
        </div>
    </div>

    @include('index.insurance.modal')

@endsection


@section('js')

    <script>

        @foreach($document_types as $item)

            $("#upload_document_{{$item->document_type_id}}").submit(function(event) {
                console.log(this);
                $('.ajax-loader').css('display','block');
                $('.ajax-loader').fadeIn();

                event.preventDefault();
                var formData = new FormData($(this)[0]);
                $('.ajax-loader').fadeIn();
                $.ajax({
                    url:'/image/upload/file',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if(data.success == 0){
                            showError(data.error);
                            $('.ajax-loader').css('display','none');
                            return;
                        }
                        g_file_name = data.file_name;
                        g_file_size = data.file_size;
                        g_file_url = data.file_url;
                        g_document_type = '{{$item->document_type_id}}';
                        $('#document_type').val('{{$item->document_type_id}}');
                        getInsuranceDocumentList();
                    }
                });
            });

        @endforeach

    </script>

@endsection