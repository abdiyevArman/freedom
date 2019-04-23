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
                <a href="/admin/news" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"> Назад</a>
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
                        @if($row->news_id > 0)
                            <form action="/admin/news/{{$row->news_id}}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                @else
                            <form action="/admin/news" method="POST">
                                @endif
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="news_id" value="{{ $row->news_id }}">
                                <input type="hidden" class="image-name" id="news_image" name="news_image" value="{{ $row->news_image }}"/>

                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Рубрика</label>
                                        <select id="menu_id"  name="menu_id" data-placeholder="Выберите" class="form-control select21">
                                            @foreach($menu as $item)
                                                <option @if($item->menu_id == $row->menu_id) selected @endif value="{{$item->menu_id}}">{{$item->menu_name_ru}}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                                    <input value="{{ $row->news_name_ru }}" type="text" class="form-control" name="news_name_ru" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Краткое описание</label>
                                                    <textarea name="news_desc_ru" class=" form-control"><?=$row->news_desc_ru?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Текст</label>
                                                    <textarea id="news_text_ru" name="news_text_ru" class="ckeditor form-control text_editor"><?=$row->news_text_ru?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO description </label>
                                                    <input value="{{ $row->news_meta_description_ru }}" type="text" class="form-control" name="news_meta_description_ru" placeholder="Введите">
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO keywords</label>
                                                    <input value="{{ $row->news_meta_keywords_ru }}" type="text" class="form-control" name="news_meta_keywords_ru" placeholder="Введите">
                                                </div>
                                                <div class="form-group" style="margin-top: 20px">
                                                    <label>Тэги (через запятую)</label>
                                                    <textarea name="tag_ru" class="form-control"><?=$row->tag_ru?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="kz" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input value="{{ $row->news_name_kz }}" type="text" class="form-control" name="news_name_kz" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Краткое описание</label>
                                                    <textarea name="news_desc_kz" class=" form-control"><?=$row->news_desc_kz?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Текст</label>
                                                    <textarea id="news_text_kz" name="news_text_kz" class="ckeditor form-control text_editor"><?=$row->news_text_kz?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO description </label>
                                                    <input value="{{ $row->news_meta_description_kz }}" type="text" class="form-control" name="news_meta_description_kz" placeholder="Введите">
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO keywords</label>
                                                    <input value="{{ $row->news_meta_keywords_kz }}" type="text" class="form-control" name="news_meta_keywords_kz" placeholder="Введите">
                                                </div>
                                                <div class="form-group" style="margin-top: 20px">
                                                    <label>Тэги (через запятую)</label>
                                                    <textarea name="tag_kz" class="form-control"><?=$row->tag_kz?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="en" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input value="{{ $row->news_name_en }}" type="text" class="form-control" name="news_name_en" placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Краткое описание</label>
                                                    <textarea name="news_desc_en" class=" form-control"><?=$row->news_desc_kz?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Текст</label>
                                                    <textarea id="news_text_en" name="news_text_en" class="ckeditor form-control text_editor"><?=$row->news_text_en?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO description </label>
                                                    <input value="{{ $row->news_meta_description_en }}" type="text" class="form-control" name="news_meta_description_en" placeholder="Введите">
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta SEO keywords</label>
                                                    <input value="{{ $row->news_meta_keywords_en }}" type="text" class="form-control" name="news_meta_keywords_en" placeholder="Введите">
                                                </div>
                                                <div class="form-group" style="margin-top: 20px">
                                                    <label>Тэги (через запятую)</label>
                                                    <textarea name="tag_en" class="form-control"><?=$row->tag_en?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Дата</label>
                                        <input id="date-format" value="{{ $row->news_date }}" type="text" class="form-control datetimepicker-input" name="news_date" placeholder="">
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
                                <img class="image-src" src="{{ $row->news_image }}" style="width: 100%; "/>
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



