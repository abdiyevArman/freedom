@extends('index.layout.layout')

@section('meta-tags')

    <title>{{Lang::get('app.authorize')}}</title>

@endsection

@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">
        <div class="page">
            <div class="container">
                <header class="page__header text-center">
                    <h1 class="page__header-title">{{Lang::get('app.login_cabinet')}}</h1>
                </header>

                <div class="login">
                    <form class="login__form"  method="post" action="/auth/login">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="control">
                            <div class="control__group">
                                <input type="text" name="phone" class="control__input phone-mask" placeholder="{{Lang::get('app.phone_number')}}" />
                                <div class="control__help">Это поле нужно заполнить</div>
                            </div>
                            <div class="control__group">
                                <input type="password" name="password" class="control__input" placeholder="{{Lang::get('app.password')}}" />
                                <div class="control__help">Это поле нужно заполнить</div>
                            </div>
                            <div class="login__help text-center mt-4">
                                <a href="/auth/reset" class="login__restore-link">{{Lang::get('app.forget_password')}}</a>
                                <button type="submit" class="button -green -md">{{Lang::get('app.login')}}</button>
                            </div>
                        </div>
                    </form>
                    <div class="login__footer">
                        {{Lang::get('app.no_account')}}
                        <br><a href="/auth/register">{{Lang::get('app.register')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection