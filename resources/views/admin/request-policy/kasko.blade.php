@extends('admin.layout.layout')

@section('content')


    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-8 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0 d-inline-block menu-tab">
                    <a href="/admin/request-policy/kasko?active=1" class="@if($request->active == '1') active-page @endif">Новые</a>
                </h3>
                <h3 class="text-themecolor m-b-0 m-t-0 d-inline-block menu-tab second-tab" >
                    <a href="/admin/request-policy/kasko" class="@if(!isset($request->active) || $request->active == '0') active-page @endif">Прочитанные</a>
                </h3>
                <div class="clear-float"></div>
            </div>
        </div>

        <div class="row white-bg">
            <div style="text-align: left" class="form-group col-md-6" >

                @if($request->active == '1')

                    <h4 class="box-title box-edit-click">
                        <a href="javascript:void(0)" onclick="isShowDisabledAll('request-policy')">Отметить как прочитанное</a>
                    </h4>

                @else

                    <h4 class="box-title box-edit-click">
                        <a href="javascript:void(0)" onclick="isShowEnabledAll('request-policy')">Отметить как непрочитанное</a>
                    </h4>

                @endif

            </div>
            <div style="text-align: right" class="form-group col-md-6" >
                <h4 class="box-title box-delete-click">
                    <a href="javascript:void(0)" onclick="deleteAll('request-policy')">Удалить отмеченные</a>
                </h4>
            </div>

            <div class="col-md-12">
                <div class="box-body">
                    <table id="news_datatable" class="table table-bordered table-striped">
                        <thead>
                        <tr style="border: 1px">
                            <th style="width: 30px">№</th>
                            <th>ФИО </th>
                            <th>Контакты </th>
                            <th>Модель/cтоимость авто </th>
                            <th>Возраст</th>
                            <th>Стаж</th>
                            <th style="width: 220px">Инфо</th>
                            <th>Дата</th>
                            <th style="width: 15px"></th>
                            <th class="no-sort" style="width: 0px; text-align: center; padding-right: 16px; padding-left: 14px;" >
                                <input onclick="selectAllCheckbox(this)" style="font-size: 15px" type="checkbox" value="1"/>
                            </th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr>
                            <td></td>
                            <td>
                                <form>
                                    <input value="{{$request->search}}" type="text" class="form-control" name="search" placeholder="">
                                    <input value="{{$request->email}}" type="hidden" class="form-control" name="email" placeholder="">
                                    <input value="{{$request->transport_name}}" type="hidden" class="form-control" name="transport_name" placeholder="">
                                    <input value="@if(isset($request->active)){{$request->active}}@else{{'0'}}@endif" type="hidden" class="form-control" name="active" placeholder="">
                                </form>
                            </td>
                            <td>
                                <form>
                                    <input value="{{$request->search}}" type="hidden" class="form-control" name="search" placeholder="">
                                    <input value="{{$request->email}}" type="text" class="form-control" name="email" placeholder="">
                                    <input value="{{$request->transport_name}}" type="hidden" class="form-control" name="transport_name" placeholder="">
                                    <input value="@if(isset($request->active)){{$request->active}}@else{{'0'}}@endif" type="hidden" class="form-control" name="active" placeholder="">
                                </form>
                            </td>
                            <td>
                                <form>
                                    <input value="{{$request->search}}" type="hidden" class="form-control" name="search" placeholder="">
                                    <input value="{{$request->email}}" type="hidden" class="form-control" name="email" placeholder="">
                                    <input value="{{$request->transport_name}}" type="text" class="form-control" name="transport_name" placeholder="">
                                    <input value="@if(isset($request->active)){{$request->active}}@else{{'0'}}@endif" type="hidden" class="form-control" name="active" placeholder="">
                                </form>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        @foreach($row as $key => $val)

                            <tr>
                                <td> {{ $key + 1 }}</td>
                                <td class="arial-font">
                                    <div>
                                        {{ $val->user_name }}
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        {{ $val->phone }}
                                    </div>
                                    <div>
                                        <strong>{{ $val->email }}</strong>
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        <strong>{{ $val->transport_name }}</strong>
                                    </div>
                                    <div>
                                        {{ $val->transport_cost }}тг
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        {{ $val->driver_age }}
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        {{ $val->driver_experience }}
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        <span>Размер франшизы: </span> <span style="color: #000000">{{ $val->franchise_size }}</span>
                                    </div>
                                    <div>
                                        <span>Без учета амортизации: </span> <span style="color: #000000">@if($val->without_depreciation == 1) Без @else С учетом @endif </span>
                                    </div>
                                    <div>
                                        <span>Вызов гаи: </span> <span style="color: #000000">@if($val->is_call_gai == 1) Да @else Нет @endif </span>
                                    </div>
                                    <div>
                                        <span>ДТП за посл. 2 года: </span> <span style="color: #000000">@if($val->is_exist_accident == 1) Да @else Нет @endif </span>
                                    </div>
                                </td>
                                <td style="color: #178C95; font-weight: 400">
                                    {{ \App\Http\Helpers::getDateFormat($val->date)}}
                                </td>
                                <td style="text-align: center">


                                    <a href="javascript:void(0)" onclick="delItem(this,'{{ $val->request_policy_id }}','request-policy')">
                                        <i class="mdi mdi-delete" style="font-size: 20px; color: red;"></i>
                                    </a>

                                </td>

                                <td style="text-align: center;">
                                    <input class="select-all" style="font-size: 15px" type="checkbox" value="{{$val->request_policy_id}}"/>
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


    <style>
        .table-bordered > tbody > tr > td,.table-bordered > tbody > tr > th {
            font-size: 16px !important;
            font-weight: 400 !important;
            color: #4B4B4B !important;
            font-family: Calibri;
        }
        .table-bordered > thead > tr > th {
            font-size: 15px !important;
            font-family: Calibri;
        }
    </style>
@endsection