@extends('admin.layout.layout')

@section('content')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-8 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0 d-inline-block menu-tab">
                    <a href="/admin/offer" class="@if(!isset($request->active) || $request->active == '1') active-page @endif">Опубликованные акции</a>
                </h3>
                <h3 class="text-themecolor m-b-0 m-t-0 d-inline-block menu-tab second-tab" >
                    <a href="/admin/offer?active=0" class="@if($request->active == '0') active-page @endif">Неопубликованные акции</a>
                </h3>
                <div class="clear-float"></div>
            </div>
            <div class="col-md-4 col-4 align-self-center text-right">
                <a href="/admin/offer/create" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"> Добавить</a>
            </div>
        </div>

        <div class="row white-bg">
            <div style="text-align: left" class="form-group col-md-6" >

                @if($request->active == '0')

                    <h4 class="box-title box-edit-click">
                        <a href="javascript:void(0)" onclick="isShowEnabledAll('offer')">Опубликовать отмеченные</a>
                    </h4>

                @else

                    <h4 class="box-title box-edit-click">
                        <a href="javascript:void(0)" onclick="isShowDisabledAll('offer')">Неопубликовать отмеченные</a>
                    </h4>

                @endif

            </div>
            <div style="text-align: right" class="form-group col-md-6" >
                <h4 class="box-title box-delete-click">
                    <a href="javascript:void(0)" onclick="deleteAll('offer')">Удалить отмеченные</a>
                </h4>
            </div>

            <div class="col-md-12">
                <div class="box-body">
                    <table id="offer_datatable" class="table table-bordered table-striped">
                        <thead>
                        <tr style="border: 1px">
                            <th style="width: 30px">№</th>
                            <th>Фото </th>
                            <th>Название (ru)</th>
                            <th>Название (kz)</th>
                            <th>Название (en)</th>
                            <th>Дата</th>
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
                                     <input value="{{$request->offer_name}}" type="text" class="form-control" name="offer_name" placeholder="">
                                     <input value="{{$request->rubric_name}}" type="hidden" class="form-control" name="rubric_name" placeholder="">
                                     <input value="{{$request->user_name}}" type="hidden" class="form-control" name="user_name" placeholder="">
                                     <input type="hidden" value="@if(!isset($request->active)){{'1'}}@else{{$request->active}}@endif" name="active"/>
                                </form>
                            </td>
                            <td>
                                <form>
                                    <input value="{{$request->offer_name}}" type="text" class="form-control" name="offer_name" placeholder="">
                                    <input value="{{$request->rubric_name}}" type="hidden" class="form-control" name="rubric_name" placeholder="">
                                    <input value="{{$request->user_name}}" type="hidden" class="form-control" name="user_name" placeholder="">
                                    <input type="hidden" value="@if(!isset($request->active)){{'1'}}@else{{$request->active}}@endif" name="active"/>
                                </form>
                            </td>
                            <td>
                                <form>
                                    <input value="{{$request->offer_name}}" type="text" class="form-control" name="offer_name" placeholder="">
                                    <input value="{{$request->rubric_name}}" type="hidden" class="form-control" name="rubric_name" placeholder="">
                                    <input value="{{$request->user_name}}" type="hidden" class="form-control" name="user_name" placeholder="">
                                    <input type="hidden" value="@if(!isset($request->active)){{'1'}}@else{{$request->active}}@endif" name="active"/>
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
                                        <a class="fancybox" href="{{$val->offer_image}}">
                                            <img src="{{$val->offer_image}}">
                                        </a>
                                    </div>
                                    <div class="clear-float"></div>
                                </td>
                                <td>
                                    <a target="_blank" href="{{ $val['offer_url_ru']}}">
                                        {{ $val['offer_name_ru']}}
                                    </a>
                                </td>
                                <td>
                                    <a target="_blank" href="{{ $val['offer_url_kz']}}">
                                        {{ $val['offer_name_kz']}}
                                    </a>
                                </td>
                                <td>
                                    <a target="_blank" href="{{ $val['offer_url_en']}}">
                                        {{ $val['offer_name_en']}}
                                    </a>
                                </td>
                                <td>
                                    {{ $val['date']}}
                                </td>
                                <td style="text-align: center">
                                    <a href="javascript:void(0)" onclick="delItem(this,'{{ $val->offer_id }}','offer')">
                                        <i class="mdi mdi-delete" style="font-size: 20px; color: red;"></i>
                                    </a>
                                </td>
                                <td style="text-align: center">
                                    <a href="/admin/offer/{{ $val->offer_id }}/edit">
                                        <i class="mdi mdi-grease-pencil" style="font-size: 20px;"></i>
                                    </a>
                                </td>
                                <td style="text-align: center;">
                                    <input class="select-all" style="font-size: 15px" type="checkbox" value="{{$val->offer_id}}"/>
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