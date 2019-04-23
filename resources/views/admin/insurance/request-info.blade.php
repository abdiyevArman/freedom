@extends('admin.layout.layout')

@section('content')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-8 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0" >
                    Информация о заявке
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Фамилия</label>
                                <input  value="{{ $row->first_name }}" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Имя</label>
                                <input  value="{{ $row->last_name }}" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Ваш полис</label>
                                <input  value="{{ $row->policy_number }}" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Телефон</label>
                                <input  value="{{ $row->phone }}" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Дата наступления страхового случая</label>
                                <input  value="{{ $row->policy_date }}" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Город, где произошел страховой случай</label>
                                <input  value="{{ $row->city_name_ru }}" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>На кого был составлен протокол</label>
                                <input  value="{{ $row->causer_name }}" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Краткое описание происшествия</label>
                                <textarea  class="form-control">{{ $row->situation_desc }}</textarea>
                            </div>
                           {{-- <div class="form-group">
                                <label>Нужен ли эвакуатор</label>
                                <input  value="@if($row->is_need_evacuator == 1){{'Да'}}@else{{'Нет'}}@endif" type="text" class="form-control">
                            </div>--}}

                            @if(count($row->request_car) > 0)

                            <div class="form-group">
                                <label>Наступление страхового случая между машинами</label>

                                @foreach($row->request_car as $item)
                                    <input style="margin-bottom: 10px"  value="{{ $item->transport_number }}" type="text" class="form-control">
                                @endforeach

                            </div>

                            @endif

                            <?php $document_types = \App\Models\DocumentType::where('is_show',1)->orderBy('sort_num','asc')->get(); ?>

                            @foreach($document_types as $item)

                                <?php $user_documents = \App\Models\UserDocument::where('document_type_id',$item->document_type_id)
                                                                                ->where('request_id',$row->insurance_request_id)
                                                                                ->orderBy('user_document_id','asc')
                                                                                ->get();?>

                            @if(count($user_documents) > 0)

                                <div class="form-group">
                                    <label>{{$item['document_type_name_ru']}}</label>

                                    @foreach($user_documents as $item2)
                                        <div>
                                            <a target="_blank" href="{{$item2['file_url']}}">{{$item2['file_name']}} <span style="color: #565656">{{$item2['file_size']}}</span></a>
                                        </div>

                                    @endforeach

                                </div>

                            @endif

                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

 <style>
     .form-control:, .form-control[readonly] {
         opacity: 1;
     }
     .form-control:, .form-control[readonly] {
         background-color: transparent;
         opacity: 1;
     }
     label {
         font-weight: bold;
     }
 </style>
@endsection



