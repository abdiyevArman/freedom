@extends('admin.layout.layout')

@section('content')


    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-8 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0 d-inline-block menu-tab">
                    <a href="/admin/order-vacancy?active=1" class="@if($request->active == '1') active-page @endif">Новые</a>
                </h3>
                <h3 class="text-themecolor m-b-0 m-t-0 d-inline-block menu-tab second-tab" >
                    <a href="/admin/order-vacancy" class="@if(!isset($request->active) || $request->active == '0') active-page @endif">Прочитанные</a>
                </h3>
                <div class="clear-float"></div>
            </div>
        </div>

        <div class="row white-bg">
            <div style="text-align: left" class="form-group col-md-6" >

                @if($request->active == '1')

                    <h4 class="box-title box-edit-click">
                        <a href="javascript:void(0)" onclick="isShowDisabledAll('order')">Отметить как прочитанное</a>
                    </h4>

                @else

                    <h4 class="box-title box-edit-click">
                        <a href="javascript:void(0)" onclick="isShowEnabledAll('order')">Отметить как непрочитанное</a>
                    </h4>

                @endif

            </div>
            <div style="text-align: right" class="form-group col-md-6" >
                <h4 class="box-title box-delete-click">
                    <a href="javascript:void(0)" onclick="deleteAll('order')">Удалить отмеченные</a>
                </h4>
            </div>

            <div class="col-md-12">
                <div class="box-body">
                    <table id="news_datatable" class="table table-bordered table-striped">
                        <thead>
                        <tr style="border: 1px">
                            <th style="width: 30px">№</th>
                            <th>ФИО </th>
                            <th style="min-width: 300px">Файлы </th>
                            <th>Телефон </th>
                            <th>Почта </th>
                            <th>Вакансия </th>
                            <th>Город </th>
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
                                    <input value="{{$request->vacancy_name}}" type="hidden" class="form-control" name="vacancy_name" placeholder="">
                                    <input value="{{$request->city_name}}" type="hidden" class="form-control" name="city_name" placeholder="">
                                    <input value="@if(isset($request->active)){{$request->active}}@else{{'0'}}@endif" type="hidden" class="form-control" name="active" placeholder="">
                                </form>
                            </td>
                            <td>

                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <form>
                                    <input value="{{$request->search}}" type="hidden" class="form-control" name="search" placeholder="">
                                    <input value="{{$request->vacancy_name}}" type="text" class="form-control" name="vacancy_name" placeholder="">
                                    <input value="{{$request->city_name}}" type="hidden" class="form-control" name="city_name" placeholder="">
                                    <input value="@if(isset($request->active)){{$request->active}}@else{{'0'}}@endif" type="hidden" class="form-control" name="active" placeholder="">
                                </form>
                            </td>
                            <td>
                                <form>
                                    <input value="{{$request->search}}" type="hidden" class="form-control" name="search" placeholder="">
                                    <input value="{{$request->vacancy_name}}" type="hidden" class="form-control" name="vacancy_name" placeholder="">
                                    <input value="{{$request->city_name}}" type="text" class="form-control" name="city_name" placeholder="">
                                    <input value="@if(isset($request->active)){{$request->active}}@else{{'0'}}@endif" type="hidden" class="form-control" name="active" placeholder="">
                                </form>
                            </td>
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
                                    <?php $file_list = \App\Models\UserDocument::where('order_id',$val->order_id)->get();?>
                                    @foreach($file_list as $item)
                                        <div>
                                            <a href="{{$item->file_url}}" target="_blank">{{$item->file_name}}<span style="color: #565656">{{$item->file_size}}</span></a>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="arial-font">
                                    <div>
                                        {{ $val->phone }}
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        {{ $val->email }}
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        <strong>{{ $val->vacancy_name_ru }}</strong>
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        <strong>{{ $val->city_name_ru }}</strong>
                                    </div>
                                </td>
                                <td>
                                    {{ \App\Http\Helpers::getDateFormat($val->date)}}
                                </td>
                                <td style="text-align: center">


                                    <a href="javascript:void(0)" onclick="delItem(this,'{{ $val->order_id }}','order')">
                                        <i class="mdi mdi-delete" style="font-size: 20px; color: red;"></i>
                                    </a>

                                </td>

                                <td style="text-align: center;">
                                    <input class="select-all" style="font-size: 15px" type="checkbox" value="{{$val->order_id}}"/>
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