@extends('index.layout.layout')

@section('meta-tags')

    <title>{{Lang::get('app.registration')}}</title>

@endsection

@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">
        <div class="page">
            <div class="container">
                <header class="page__header text-center">
                    <h1 class="page__header-title">{{Lang::get('app.registration')}}</h1>
                </header>
                <div class="login">
                    <form class="login__form" method="post" action="/auth/register">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="control">
                            <div class="control__group">
                                <input type="text"  name="phone" class="control__input phone-mask" placeholder="{{Lang::get('app.phone_number')}}" />
                                <div class="control__help">Это поле нужно заполнить</div>
                            </div>
                            <div class="control__group">
                                <input type="password" min="5" name="password"  class="control__input" placeholder="{{Lang::get('app.password')}}" />
                                <div class="control__help">Это поле нужно заполнить</div>
                            </div>
                            <div class="control__group">
                                <input type="password" min="5" name="confirm_password"  class="control__input" placeholder="{{Lang::get('app.confirm_password')}}" />
                                <div class="control__help">Это поле нужно заполнить</div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="button -green -md">{{Lang::get('app.register_label')}}</button>
                            </div>
                        </div>
                    </form>
                    <div class="login__footer">
                        {{Lang::get('app.exist_account')}}
                        <br><a href="/auth/login">{{Lang::get('app.authorize_label')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection