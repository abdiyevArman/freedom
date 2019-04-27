@extends('admin.layout.layout')

@section('content')


    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-8 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0 d-inline-block menu-tab">
                    <a href="/admin/policy?active=1" class="@if($request->active == '1') active-page @endif">Купленные полисы (пока только купленные через сайт)</a>
                </h3>
                <div class="clear-float"></div>
            </div>
            <div class="col-md-4 col-4 align-self-center text-right">

                <form method="post" action="/admin/policy/excel">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input style="margin-left: 10px" type="submit"  class="btn waves-effect waves-light  pull-right hidden-sm-down" value="Экспорт"/>
                </form>

            </div>
        </div>

        <div class="row white-bg">


            <div class="col-md-12">
                <div class="box-body">
                    <table id="news_datatable" class="table table-bordered table-striped">
                        <thead>
                        <tr style="border: 1px">
                            <th style="width: 30px">№</th>
                            <th>ФИО/ИИН</th>
                            <th>Номер полиса </th>
                            <th>Доп.продукты</th>
                            <th>Стоимость </th>
                            <th style="width: 150px">Данные авто</th>
                            <th>Контакты </th>
                            <th>Покупка</th>
                            <th>Дата</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr>
                            <td></td>
                            <td style="width: 270px">
                                <form>
                                    <input value="{{$request->search}}" type="text" class="form-control" name="search" placeholder="">
                                    <input value="@if(isset($request->active)){{$request->active}}@else{{'0'}}@endif" type="hidden" class="form-control" name="active" placeholder="">
                                </form>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="2">
                                <form id="date_form">
                                    <div style="width: 180px">
                                        <div style="float:left; padding-bottom: 3px; width: 45%; ">
                                            <input style="font-size: 11px" value="{{$request->date_from}}" type="text" class="form-control date-format datetimepicker-input" name="date_from" placeholder="с">
                                        </div>
                                        <div style="float:right; width: 45%;">
                                            <input style="font-size: 11px" value="{{$request->date_to}}" type="text" class="form-control date-format datetimepicker-input" name="date_to" placeholder="до">
                                        </div>
                                        <div class="clear-float"></div>
                                    </div>
                                    <input value="{{$request->search}}" type="hidden" class="form-control" name="search" placeholder="">
                                    <input value="{{$request->email}}" type="hidden" class="form-control" name="email" placeholder="">
                                    <input value="{{$request->transport_name}}" type="hidden" class="form-control" name="transport_name" placeholder="">
                                    <input value="@if(isset($request->active)){{$request->active}}@else{{'0'}}@endif" type="hidden" class="form-control" name="active" placeholder="">
                                </form>
                            </td>
                            <td>
                                <div style="float:right; width: 3%;">
                                    <a style="font-size: 12px; padding: 9px 10px" href="javascript:void(0)" onclick="$('#date_form').submit()" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"><i class="fa fa-search"></i> </a>
                                </div>
                            </td>
                        </tr>

                        @foreach($row as $key => $val)

                            <tr>
                                <td> {{ $key + 1 }}</td>
                                <td class="arial-font">
                                    <div>
                                        {{ $val->user_name }}
                                    </div>
                                    <div style="margin-top: 10px">
                                        <b style="color: #404040;">{{ $val->iin }}</b>
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        <a href="{{ $val->pdf_hash_url }}" target="_blank">
                                            {{ $val->policy_number }}
                                        </a>
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <?php $policy_products = \App\Models\PolicyProduct::leftJoin('product','product.product_id','=','policy_product.product_id')
                                                                                    ->where('policy_product.policy_id',$val->policy_id)
                                                                                    ->orderBy('policy_product.product_id','asc')
                                                                                    ->get(); ?>
                                    @foreach($policy_products as $item)
                                        <div style="margin-bottom: 10px">
                                            {{$item->product_name_ru}}
                                        </div>
                                    @endforeach
                                </td>
                                <td class="arial-font">
                                    <b style="color: #404040;">{{$val->cost}}тг</b>
                                </td>
                                <td class="arial-font">
                                    <div style="margin-bottom: 10px">
                                        <b style="color: #404040;">{{ $val->transport_number }}</b>
                                    </div>
                                    <div>
                                        {{ $val->transport_name }}
                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        {{ $val->phone }}
                                    </div>
                                    <div>
                                        {{ $val->email }}
                                    </div>
                                </td>
                                <td class="arial-font">
                                    @if($val->is_pay == 1)
                                        <span class="label label-success f-15">оплатил</span>
                                    @else
                                        <span class="label label-danger f-15">не оплатил</span>
                                    @endif

                                    @if($val->is_pay == 1 && $val->is_success == 0)
                                         <span class="label label-danger f-15">PDF не получен</span>
                                    @endif
                                </td>
                                <td colspan="2">
                                    {{ \App\Http\Helpers::getDateFormat($val->date)}}
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
            font-size: 14px !important;
            font-weight: 400 !important;
            color: #4B4B4B !important;
            font-family: Calibri;
        }
        .table-bordered > thead > tr > th {
            font-size: 14px !important;
            font-family: Calibri;
        }
        .f-15 {
            font-size: 13px;
        }
    </style>

@endsection

@section('js')
    <script src="/admin/assets/plugins/moment/moment.js"></script>
    <script src="/admin/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="/admin/assets/plugins/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js" type="text/javascript"></script>

    <script>

        $('.date-format').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD HH:mm' });

    </script>

@endsection