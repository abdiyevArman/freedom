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
                <a href="/admin/offer" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"> Назад</a>
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
                        @if($row->offer_id > 0)
                            <form action="/admin/offer/{{$row->offer_id}}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                @else
                            <form action="/admin/offer" method="POST">
                                @endif
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="offer_id" value="{{ $row->offer_id }}">
                                <input type="hidden" class="image-name" id="offer_image" name="offer_image" value="{{ $row->offer_image }}"/>

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
                                                    <input value="{{ $row->offer_name_ru }}" type="text" class="form-control" name="offer_name_ru" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Краткое описание</label>
                                                    <textarea name="offer_desc_ru" class=" form-control"><?=$row->offer_desc_ru?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Текст</label>
                                                    <textarea id="offer_text_ru" name="offer_text_ru" class="ckeditor form-control text_editor"><?=$row->offer_text_ru?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO description </label>
                                                    <input value="{{ $row->offer_meta_description_ru }}" type="text" class="form-control" name="offer_meta_description_ru" placeholder="Введите">
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO keywords</label>
                                                    <input value="{{ $row->offer_meta_keywords_ru }}" type="text" class="form-control" name="offer_meta_keywords_ru" placeholder="Введите">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="tab-pane " id="kz" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input value="{{ $row->offer_name_kz }}" type="text" class="form-control" name="offer_name_kz" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Краткое описание</label>
                                                    <textarea name="offer_desc_kz" class=" form-control"><?=$row->offer_desc_kz?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Текст</label>
                                                    <textarea id="offer_text_kz" name="offer_text_kz" class="ckeditor form-control text_editor"><?=$row->offer_text_kz?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO description </label>
                                                    <input value="{{ $row->offer_meta_description_kz }}" type="text" class="form-control" name="offer_meta_description_kz" placeholder="Введите">
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO keywords</label>
                                                    <input value="{{ $row->offer_meta_keywords_kz }}" type="text" class="form-control" name="offer_meta_keywords_kz" placeholder="Введите">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="tab-pane " id="en" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input value="{{ $row->offer_name_en }}" type="text" class="form-control" name="offer_name_en" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Краткое описание</label>
                                                    <textarea name="offer_desc_en" class=" form-control"><?=$row->offer_desc_kz?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Текст</label>
                                                    <textarea id="offer_text_en" name="offer_text_en" class="ckeditor form-control text_editor"><?=$row->offer_text_en?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO description </label>
                                                    <input value="{{ $row->offer_meta_description_en }}" type="text" class="form-control" name="offer_meta_description_en" placeholder="Введите">
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO keywords</label>
                                                    <input value="{{ $row->offer_meta_keywords_en }}" type="text" class="form-control" name="offer_meta_keywords_en" placeholder="Введите">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Дата</label>
                                        <input id="date-format" value="{{ $row->offer_date }}" type="text" class="form-control datetimepicker-input" name="offer_date" placeholder="">
                                    </div>
                                </div>
                                <div class="box-footer" style="padding-top: 20px">
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="box box-primary" style="padding: 30px; text-align: center">
                            <div style="padding: 20px; border: 1px solid #c2e2f0">
                                <img class="image-src" src="{{ $row->offer_image }}" style="width: 100%; "/>
                            </div>
                            <div style="background-color: #c2e2f0;height: 40px;margin: 0 auto;width: 2px;"></div>
                            <form id="image_form" enctype="multipart/form-data" method="post" class="image-form">
                                <i class="fa fa-plus"></i>
                                <input id="avatar-file" type="file" onchange="uploadImage()" name="image"/>
                            </form>
                        </div>
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



