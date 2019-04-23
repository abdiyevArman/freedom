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
                <a href="/admin/faq" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"> Назад</a>
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
                        @if($row->faq_id > 0)
                            <form action="/admin/faq/{{$row->faq_id}}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                @else
                            <form action="/admin/faq" method="POST">
                                @endif
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="faq_id" value="{{ $row->faq_id }}">
                                <input type="hidden" class="image-name" id="faq_image" name="faq_image" value="{{ $row->faq_image }}"/>

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
                                                    <label>Вопрос</label>
                                                    <input value="{{ $row->faq_name_ru }}" type="text" class="form-control" name="faq_name_ru" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ответ</label>
                                                    <textarea name="faq_desc_ru" class=" form-control text_editor"><?=$row->faq_desc_ru?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="kz" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Вопрос</label>
                                                    <input value="{{ $row->faq_name_kz }}" type="text" class="form-control" name="faq_name_kz" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ответ</label>
                                                    <textarea name="faq_desc_kz" class=" form-control text_editor"><?=$row->faq_desc_kz?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="en" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Вопрос</label>
                                                    <input value="{{ $row->faq_name_en }}" type="text" class="form-control" name="faq_name_en" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ответ</label>
                                                    <textarea name="faq_desc_en" class=" form-control text_editor"><?=$row->faq_desc_kz?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Рубрика</label>
                                        <select id="menu_id"  name="menu_id" data-placeholder="Выберите" class="form-control select2">
                                            @foreach($menu as $item)
                                                <option @if($item->menu_id == $row->menu_id) selected @endif value="{{$item->menu_id}}">{{$item->menu_name_ru}}</option>
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



