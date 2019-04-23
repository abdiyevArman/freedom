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
                <a href="/admin/client" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"> Назад</a>
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
                        @if($row->user_id > 0)
                            <form action="/admin/client/{{$row->user_id}}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                @else
                                    <form action="/admin/client" method="POST">
                                        @endif
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="user_id" value="{{ $row->user_id }}">
                                        <input type="hidden" class="image-name" id="news_image" name="avatar" value="{{ $row->avatar }}"/>

                                        <div class="box-body">

                                            <ul class="nav nav-tabs" role="tablist">
                                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#info" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Инфо</span></a> </li>
                                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#document" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Документы</span></a> </li>
                                            </ul>

                                            <div class="tab-content tabcontent-border">
                                                <div class="tab-pane active" id="info" role="tabpanel" style="padding: 15px">
                                                    <div class="form-group">
                                                        <label>ФИО</label>
                                                        <input value="{{ $row->name }}" type="text" class="form-control" name="name" placeholder="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Почта</label>
                                                        <input value="{{ $row->email }}" type="text" class="form-control" name="email" placeholder="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Роль</label>
                                                        <select class="form-control select2" name="role_id">
                                                            @foreach($roles as $key => $item)

                                                                <option value="{{$item['role_id']}}" @if($item['role_id'] == $row->role_id) selected="selected" @endif >{{$item['role_name_ru']}}</option>

                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="document" role="tabpanel" style="padding: 15px">

                                                    <?php $document_list = \App\Models\Document::where('is_show',1)->get(); ?>
                                                        @foreach($document_list as $item)

                                                            <div class="request-leave-cover">
                                                                <h5 class="subtitle">- {{$item['document_name_'.$lang]}};</h5>
                                                                <div class="document_upload_list" id="document_{{$item->document_id}}">

                                                                    <?php $user_document_list = \App\Models\UserDocument::where('document_id',$item->document_id)
                                                                            ->where('user_id',$row->user_id)
                                                                            ->orderBy('user_document_id','asc')
                                                                            ->get() ?>

                                                                        @foreach($user_document_list as $item)

                                                                            <div class="request-leave-item" style="padding-left: 40px">
                                                                                <p class="request-document">
                                                                                    <i class="icon i-pdf"></i><a href="{{$item->document_url}}" target="_blank">{{$item->document_name}}</a>  <span class="document-size">~{{$item->document_size}} mb</span>
                                                                                </p>
                                                                            </div>

                                                                        @endforeach

                                                                </div>
                                                            </div>

                                                        @endforeach

                                                </div>
                                            </div>

                                        </div>
                                        <div class="box-footer">
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
                                <img class="image-src" src="{{ $row->avatar }}" style="width: 100%; "/>
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



