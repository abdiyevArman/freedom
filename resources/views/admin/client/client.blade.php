@extends('admin.layout.layout')

@section('content')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-8 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0 d-inline-block menu-tab">
                    <a href="/admin/client" class="@if(!isset($request->active) || $request->active == '0') active-page @endif">Активные пользователи</a>
                </h3>
                <h3 class="text-themecolor m-b-0 m-t-0 d-inline-block menu-tab second-tab" >
                    <a href="/admin/client?active=1" class="@if($request->active == '1') active-page @endif">Заблокированные пользователи</a>
                </h3>
                <div class="clear-float"></div>
            </div>
            <div class="col-md-4 col-4 align-self-center text-right">
                <a href="/admin/client/create" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"> Добавить</a>
            </div>
        </div>

        <div class="row white-bg">
            <div style="text-align: left" class="form-group col-md-6" >

                @if($request->active == '1')

                    <h4 class="box-title box-edit-click">
                        <a href="javascript:void(0)" onclick="isShowDisabledAll('client')">Разблокировать отмеченные</a>
                    </h4>

                @else

                    <h4 class="box-title box-edit-click">
                        <a href="javascript:void(0)" onclick="isShowEnabledAll('client')">Заблокировать отмеченные</a>
                    </h4>

                @endif

            </div>
            <div style="text-align: right" class="form-group col-md-6" >
                <h4 class="box-title box-delete-click">
                    <a href="javascript:void(0)" onclick="deleteAll('client')">Удалить отмеченные</a>
                </h4>
            </div>

            <div class="col-md-12">
                <div class="box-body">
                    <table id="client_datatable" class="table table-bordered table-striped">
                        <thead>
                        <tr style="border: 1px">
                            <th style="width: 30px">№</th>
                            <th>Фото</th>
                            <th>ФИО</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Телефон</th>
                            <th>Сбросить пароль</th>
                            <th style="width: 15px"></th>
                            <th style="width: 15px"></th>
                            <th class="no-sort" style="width: 0px; text-align: center; padding-right: 16px; padding-left: 14px;" >
                                <input onclick="selectAllCheckbox(this)" style="font-size: 15px" type="checkbox" value="1"/>
                            </th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <form>
                                     <input value="{{$request->email}}" type="hidden" class="form-control" name="email" placeholder="">
                                     <input value="{{$request->redaction}}" type="hidden" class="form-control" name="redaction" placeholder="">
                                     <input value="{{$request->role}}" type="hidden" class="form-control" name="role" placeholder="">
                                     <input value="{{$request->client_name}}" type="text" class="form-control" name="client_name" placeholder="">
                                     <input type="hidden" value="@if(!isset($request->active)){{'0'}}@else{{$request->active}}@endif" name="active"/>
                                </form>
                            </td>
                            <td>
                                <form>
                                    <input value="{{$request->email}}" type="text" class="form-control" name="email" placeholder="">
                                    <input value="{{$request->redaction}}" type="hidden" class="form-control" name="redaction" placeholder="">
                                    <input value="{{$request->role}}" type="hidden" class="form-control" name="role" placeholder="">
                                    <input value="{{$request->client_name}}" type="hidden" class="form-control" name="client_name" placeholder="">
                                    <input type="hidden" value="@if(!isset($request->active)){{'0'}}@else{{$request->active}}@endif" name="active"/>
                                </form>
                            </td>
                            <td>
                                <form>
                                    <input value="{{$request->email}}" type="hidden" class="form-control" name="email" placeholder="">
                                    <input value="{{$request->redaction}}" type="hidden" class="form-control" name="redaction" placeholder="">
                                    <input value="{{$request->role}}" type="text" class="form-control" name="role" placeholder="">
                                    <input value="{{$request->client_name}}" type="hidden" class="form-control" name="client_name" placeholder="">
                                    <input type="hidden" value="@if(!isset($request->active)){{'0'}}@else{{$request->active}}@endif" name="active"/>
                                </form>
                            </td>
                            <td>
                                <form>
                                    <input value="{{$request->email}}" type="hidden" class="form-control" name="email" placeholder="">
                                    <input value="{{$request->redaction}}" type="text" class="form-control" name="redaction" placeholder="">
                                    <input value="{{$request->role}}" type="hidden" class="form-control" name="role" placeholder="">
                                    <input value="{{$request->client_name}}" type="hidden" class="form-control" name="client_name" placeholder="">
                                    <input type="hidden" value="@if(!isset($request->active)){{'0'}}@else{{$request->active}}@endif" name="active"/>
                                </form>
                            </td>

                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        @foreach($row as $key => $val)

                            <tr>
                                <td> {{ $key + 1 }}</td>
                                <td>
                                    <div class="object-image">
                                        <a class="fancybox" href="{{$val->avatar}}">
                                            <img src="{{$val->avatar}}">
                                        </a>
                                    </div>
                                    <div class="clear-float"></div>
                                </td>
                                <td>
                                    {{ $val['name']}}
                                </td>
                                <td>
                                    {{ $val['email']}}
                                </td>
                                <td>
                                    {{ $val['role_name_ru']}}
                                </td>
                                <td>
                                    {{ $val['phone']}}
                                </td>
                                <td>
                                    <a href="/admin/client/reset/{{$val->user_id}}?page={{$request->page}}" class="btn waves-effect waves-light btn-info pull-right hidden-sm-down" ><i class="mdi mdi-settings"></i>Сбросить пароль</a>
                                </td>
                                <td style="text-align: center">
                                    <a href="javascript:void(0)" onclick="delItem(this,'{{ $val->user_id }}','client')">
                                        <i class="mdi mdi-delete" style="font-size: 20px; color: red;"></i>
                                    </a>
                                </td>
                                <td style="text-align: center">
                                    <a href="/admin/client/{{ $val->user_id }}/edit">
                                        <i class="mdi mdi-grease-pencil" style="font-size: 20px;"></i>
                                    </a>
                                </td>
                                <td style="text-align: center;">
                                    <input class="select-all" style="font-size: 15px" type="checkbox" value="{{$val->user_id}}"/>
                                </td>
                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                    <div style="text-align: center">
                        {{ $row->appends(\Illuminate\Support\Facades\Input::except('page'))->links() }}
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

@section('js')

    <link href="/custom/fancybox/jquery.fancybox.css" type="text/css" rel="stylesheet">
    <script src="/custom/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>

    <script>
        $('a.fancybox').fancybox({
            padding: 10
        });
    </script>
@endsection