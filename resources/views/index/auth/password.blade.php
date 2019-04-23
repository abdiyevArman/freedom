@extends('index.layout.layout')

@section('meta-tags')

    <title>{{Lang::get('app.set_password')}}</title>

@endsection

@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">
        <div class="page">
            <div class="container">
                <header class="page__header text-center">
                    <h1 class="page__header-title">{{Lang::get('app.set_password')}}</h1>
                </header>
                <div class="login">
                    <form class="login__form" method="post" action="/auth/password">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="control">
                            <div class="control__group">
                                <input type="password" min="5" name="password"  class="control__input" placeholder="{{Lang::get('app.password')}}" />
                                <div class="control__help">Это поле нужно заполнить</div>
                            </div>
                            <div class="control__group">
                                <input type="password" min="5" name="confirm_password"  class="control__input" placeholder="{{Lang::get('app.confirm_password')}}" />
                                <div class="control__help">Это поле нужно заполнить</div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="button -green -md">{{Lang::get('app.save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection