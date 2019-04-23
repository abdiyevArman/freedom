@extends('index.layout.layout')

@section('meta-tags')

    <title>{{Lang::get('app.forget_password')}}</title>

@endsection

@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">
        <div class="page">
            <div class="container">
                <header class="page__header text-center">
                    <h1 class="page__header-title">{{Lang::get('app.forget_password')}}</h1>
                </header>
                <div class="login">
                    <form class="login__form" method="post" action="/auth/reset">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="login__text">
                            <p>
                                {{Lang::get('app.set_phone_number')}}
                            </p>
                        </div>
                        <div class="control">
                            <div class="control__group">
                                <input type="text" name="phone" class="control__input phone-mask" value="{{$request->phone}}" placeholder="{{Lang::get('app.phone_number')}}" />
                                <div class="control__help">Это поле нужно заполнить</div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="button -green -md">{{Lang::get('app.send_code')}}</button>
                            </div>
                        </div>
                    </form>
                    <div class="login__footer">
                        {{Lang::get('app.know_password')}}
                        <br><a href="/auth/login">{{Lang::get('app.authorize_label')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script>

        function timerSms() {
            var obj = parseInt($('#timer_inp').html());
            obj--;
            var num = '';
            if(obj < 10){
                num = '0';
            }
            $('#timer_inp').html(num + obj);
            if (obj == 0) {
                $('#sms_label').css('display','none');
                $('#send_sms_repeat').html('<a id="send_sms_label" href="javascript:void(0)" onclick="sendSMS()">{{Lang::get('app.repeat_send_code')}}</a>');
                setTimeout(function() {}, 1000);
            } else {
                setTimeout(timerSms, 1000);
            }
        }

        $(document).ready(function(){
            setTimeout(timerSms, 1000);
        });

    </script>

@endsection