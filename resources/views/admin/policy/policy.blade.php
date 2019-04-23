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
        </div>

        <div class="row white-bg">


            <div class="col-md-12">
                <div class="box-body">
                    <table id="news_datatable" class="table table-bordered table-striped">
                        <thead>
                        <tr style="border: 1px">
                            <th style="width: 30px">№</th>
                            <th>ФИО </th>
                            <th>Номер авто </th>
                            <th>Транспорт</th>
                            <th>Телефон </th>
                            <th>Почта </th>
                            <th>Дата</th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr>
                            <td></td>
                            <td>
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

                                    </div>
                                </td>
                                <td class="arial-font">
                                    <div>
                                        {{ $val->transport_name }}
                                    </div>
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
                                <td>
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



@endsection