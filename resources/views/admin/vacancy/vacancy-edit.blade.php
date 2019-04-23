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
                <a href="/admin/vacancy" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"> Назад</a>
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
                        @if($row->vacancy_id > 0)
                            <form action="/admin/vacancy/{{$row->vacancy_id}}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                @else
                            <form action="/admin/vacancy" method="POST">
                                @endif
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="vacancy_id" value="{{ $row->vacancy_id }}">
                                <input type="hidden" class="image-name" id="vacancy_image" name="vacancy_image" value="{{ $row->vacancy_image }}"/>

                                <div class="box-body">

                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#ru" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Русский</span></a> </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#kz" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Казахский</span></a> </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#en" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Английский</span></a> </li>
                                    </ul>

                                    <div class="tab-content tabcontent-border">
                                        <div class="tab-pane active" id="ru" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input value="{{ $row->vacancy_name_ru }}" type="text" class="form-control" name="vacancy_name_ru" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Описание</label>
                                                    <textarea name="vacancy_desc_ru" class=" form-control"><?=$row->vacancy_desc_ru?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="kz" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input value="{{ $row->vacancy_name_kz }}" type="text" class="form-control" name="vacancy_name_kz" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Описание</label>
                                                    <textarea name="vacancy_desc_kz" class=" form-control"><?=$row->vacancy_desc_kz?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="en" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input value="{{ $row->vacancy_name_en }}" type="text" class="form-control" name="vacancy_name_en" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Описание</label>
                                                    <textarea name="vacancy_desc_en" class=" form-control"><?=$row->vacancy_desc_kz?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Город</label>
                                        <select id="city_id"  name="city_id" data-placeholder="Выберите" class="form-control select2">
                                            @foreach($cities as $item)
                                                <option @if($item->city_id == $row->city_id) selected @endif value="{{$item->city_id}}">{{$item->city_name_ru}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Порядковый номер сортировки</label>
                                        <input value="{{ $row->sort_num }}" type="text" class="form-control" name="sort_num" placeholder="">
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
    <script src="/admin/assets/plugins/moment/moment.js"></script>
    <script src="/admin/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="/admin/assets/plugins/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js" type="text/javascript"></script>

    <script>

        $('#date-format').bootstrapMaterialDatePicker({ format: 'DD.MM.YYYY HH:mm' });

    </script>

@endsection



