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
                <a href="/admin/report" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"> Назад</a>
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
                        @if($row->report_id > 0)
                            <form action="/admin/report/{{$row->report_id}}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                @else
                            <form action="/admin/report" method="POST">
                                @endif
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="report_id" value="{{ $row->report_id }}">
                                <input type="hidden" class="image-name" id="report_image" name="report_image" value="{{ $row->report_image }}"/>

                                <div class="box-body">

                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#ru" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Русский</span></a> </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#kz" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Казахский</span></a> </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#en" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Английский</span></a> </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#pdf" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Файл</span></a> </li>
                                    </ul>

                                    <div class="tab-content tabcontent-border">
                                        <div class="tab-pane active" id="ru" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input value="{{ $row->report_name_ru }}" type="text" class="form-control" name="report_name_ru" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="kz" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input value="{{ $row->report_name_kz }}" type="text" class="form-control" name="report_name_kz" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="en" role="tabpanel">
                                            <div class="card-block">
                                                <div class="form-group">
                                                    <label>Название</label>
                                                    <input value="{{ $row->report_name_en }}" type="text" class="form-control" name="report_name_en" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="pdf" role="tabpanel">
                                            <input type="hidden" class="report_file" value="{{$row['report_file']}}" name="report_file">
                                            <div id="photo_content" style="min-height: 300px; padding: 30px">
                                                @if(isset($row->report_file) && $row->report_file != null)
                                                    <a href="{{$row->report_file}}" target="_blank">Скачать файл</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Тип документа</label>
                                        <select id="report_type_id"  name="report_type_id" data-placeholder="Выберите" class="form-control select2">
                                            @foreach($report_types as $item)
                                                <option @if($item->report_type_id == $row->report_type_id) selected @endif value="{{$item->report_type_id}}">{{$item->report_type_name_ru}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Дата</label>
                                        <input id="date-format" value="{{ $row->report_date }}" type="text" class="form-control datetimepicker-input" name="report_date" placeholder="">
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
                        <div class="box box-primary" style="padding: 30px; text-align: center" id="file_upload_content">
                            <p>Загрузите файл</p>
                            <div style="padding: 20px; border: 1px solid #c2e2f0">
                                <img class="image-src" src="/file.png" style="width: 100%; "/>
                            </div>
                            <div style="background-color: #c2e2f0;height: 40px;margin: 0 auto;width: 2px;"></div>
                            <form id="image_form_document" enctype="multipart/form-data" method="post" class="image-form">
                                <i class="fa fa-plus"></i>
                                <input id="avatar-file" type="file" onchange="uploadDocument()" name="image"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('js')

    <script>
        function uploadDocument(){
            $('.ajax-loader').css('display','block');
            $("#image_form_document").submit();
        }

        $("#image_form_document").submit(function(event) {
            event.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url:'/image/upload/file',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('.ajax-loader').css('display','none');
                    if(data.success == 0){
                        showError(data.error);
                        return;
                    }
                    $('.report_file').val(data.file_url);
                    $('.nav-tabs li').removeClass('active');
                    $('.tab-pane').removeClass('active');
                    $('#pdf').addClass('active');
                    $('.photo-tab').closest('li').addClass('active');

                    $('#photo_content').html('<a href="' + data.file_url +'" target="_blank">Скачать файл</a>');
                }
            });
        });

    </script>

    <script src="/admin/assets/plugins/moment/moment.js"></script>
    <script src="/admin/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="/admin/assets/plugins/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js" type="text/javascript"></script>

    <script>

        $('#date-format').bootstrapMaterialDatePicker({ format: 'DD.MM.YYYY HH:mm' });

    </script>

@endsection



