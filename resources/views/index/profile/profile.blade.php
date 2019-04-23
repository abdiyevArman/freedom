@extends('index.layout.layout')

@section('meta-tags')

    <title>{{Lang::get('app.profile')}}</title>

@endsection

@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">
        <div class="cabinet">

            <header class="cabinet__header">
                <div class="container">
                    <h1 class="cabinet__header-title">{{Lang::get('app.cabinet')}}</h1>
                    <ul class="cabinet__nav">
                        <li class="cabinet__nav-item">
                            <a href="#" class="cabinet__nav-link -active">{{Lang::get('app.my_profile')}}</a>
                        </li>
                        <li class="cabinet__nav-item">
                            <a href="/profile/policy" class="cabinet__nav-link">{{Lang::get('app.my_policies')}}</a>
                        </li>
                        <li class="cabinet__nav-item">
                            <a href="/auth/logout" class="cabinet__nav-link">{{Lang::get('app.logout')}}</a>
                        </li>
                    </ul>
                </div>
            </header>

            <div class="cabinet__content">
                <div class="container">
                    <div class="cabinet__card">
                        <form action="/profile" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="cabinet__card-body">
                                <div class="cabinet__title">Личные данные</div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="control mb-4">
                                            <div class="cabinet__label">{{Lang::get('app.last_name')}}</div>
                                            <input type="text" class="control__input" required name="first_name" placeholder="" value="@if($row->first_name != ''){{$row->first_name}}@else{{$row->name}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="control mb-4">
                                            <div class="cabinet__label">{{Lang::get('app.name')}}</div>
                                            <input type="text" class="control__input" required name="last_name"  placeholder="" value="{{$row->last_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="control mb-4">
                                            <div class="cabinet__label">{{Lang::get('app.middle_name')}}</div>
                                            <input type="text" class="control__input" name="mid_name" placeholder="" value="{{$row->mid_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="control mb-4">
                                            <div class="cabinet__label">{{Lang::get('app.iin')}}</div>
                                            <input type="text" class="control__input" required name="iin" placeholder="" value="{{$row->iin}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4 mb-5">
                                    <div class="col-md-4">
                                        <div class="control mb-4">
                                            <div class="cabinet__label">{{Lang::get('app.address')}}</div>
                                            <input type="text" class="control__input" name="address" placeholder="" value="{{$row->address}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="control mb-4">
                                            <div class="cabinet__label">{{Lang::get('app.phone_number')}}</div>
                                            <input type="text" class="control__input phone-mask" required name="phone" placeholder="" value="{{$row->phone}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="control mb-4">
                                            <div class="cabinet__label">E-mail</div>
                                            <input type="email" class="control__input" name="email" placeholder="" value="{{$row->email}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="cabinet__title">{{Lang::get('app.password')}}</div>
                                <div class="row mt-4 mb-5">
                                    <div class="col-md-4">
                                        <div class="control mb-4">
                                            <div class="cabinet__label">{{Lang::get('app.old_password')}}</div>
                                            <input type="password" class="control__input" name="old_password" placeholder="{{Lang::get('app.old_password')}}" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="control mb-4">
                                            <div class="cabinet__label">{{Lang::get('app.new_password')}}</div>
                                            <input type="password" class="control__input" name="new_password" placeholder="{{Lang::get('app.new_password')}}" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="control mb-4">
                                            <div class="cabinet__label">{{Lang::get('app.confirm_new_password')}}</div>
                                            <input type="password" class="control__input" name="confirm_password" placeholder="{{Lang::get('app.confirm_new_password')}}" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cabinet__footer">
                               {{-- <a href="#" class="button -default cabinet__button">Отмена</a>--}}
                                <button type="submit" class="button -green cabinet__button">{{Lang::get('app.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/. Cabinet Content End -->

        </div>
        <!--./ Cabinet End -->

    </div>

@endsection

@section('js')


@endsection