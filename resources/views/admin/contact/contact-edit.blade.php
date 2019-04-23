@extends('admin.layout.layout')

@section('content')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-8 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0" >
                    {{ $title }}
                </h3>
            </div>
            <div class="col-md-4 col-4 align-self-center text-right">
                <a href="/admin/contacts" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"> Назад</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-block">
                        @if (isset($error))
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endif
                        @if($row->contact_id > 0)
                            <form action="/admin/contacts/{{$row->contact_id}}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                @else
                            <form action="/admin/contacts" method="POST">
                                @endif
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="contact_id" value="{{ $row->contact_id }}">

                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Город</label>
                                        <select id="city_id"  name="city_id" data-placeholder="Выберите" class="form-control select21">
                                            @foreach($cities as $item)
                                                <option @if($item->city_id == $row->city_id) selected @endif value="{{$item->city_id}}">{{$item->city_name_ru}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#ru" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Русский</span></a> </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#kz" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Казахский</span></a> </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#en" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Английский</span></a> </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#address" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Адрес</span></a> </li>
                                    </ul>

                                    <div class="tab-content tabcontent-border">
                                        <div class="tab-pane active" id="ru" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Адрес (если есть офис)</label>
                                                    <textarea class="form-control" name="address_ru">{{ $row->address_ru }}</textarea>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="tab-pane " id="kz" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Адрес (если есть офис)</label>
                                                    <textarea  class="form-control" name="address_kz">{{ $row->address_kz }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="en" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Адрес (если есть офис)</label>
                                                    <textarea  class="form-control" name="address_en">{{ $row->address_en }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="address" role="tabpanel">
                                            <div class="card-block">
                                                <div id="address_list">
                                                    @include('admin.contact.address-list')
                                                </div>
                                                <div class="text-center">
                                                    <a href="javascript:void(0)" onclick="addNewAddress()" style="text-decoration: underline; font-weight: bold">Добавить адрес</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Телефон (офис)</label>
                                        <input value="{{ $row->phone }}" type="text" class="form-control" name="phone" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Телефон 2 (офис)</label>
                                        <input value="{{ $row->phone2 }}" type="text" class="form-control" name="phone2" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Электронная почта (офис)</label>
                                        <input value="{{ $row->email }}" type="text" class="form-control" name="email" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Время работы (офис)</label>
                                        <input value="{{ $row->schedule }}" type="text" class="form-control" name="schedule" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Телефон (call center)</label>
                                        <input value="{{ $row->phone3 }}" type="text" class="form-control" name="phone3" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Телефон 2 (call center)</label>
                                        <input value="{{ $row->phone4 }}" type="text" class="form-control" name="phone4" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Электронная почта (call center)</label>
                                        <input value="{{ $row->email2 }}" type="text" class="form-control" name="email2" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Время работы (call center)</label>
                                        <input value="{{ $row->schedule2 }}" type="text" class="form-control" name="schedule2" placeholder="">
                                    </div>
                                </div>
                               
                                <div class="box-footer" style="padding-top: 20px">
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
           
        </div>

    </div>

@endsection

@section('js')

    <style>
        .address-item {
            padding: 15px;
            border: 1px solid #565656;
            margin-bottom: 30px;
        }
    </style>
    <script>

        function deleteAddress(ob){
            $(ob).closest('.address-item').remove();
        }

        function addNewAddress() {
            $('.ajax-loader').fadeIn(100);
            $.ajax({
                url:'/admin/contacts/address',
                type: 'POST',
                data: {
                   count: $('.last_counter:last').val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('.ajax-loader').fadeOut(100);
                    $('#address_list').append(data);
                }
            });
        }

    </script>

@endsection



