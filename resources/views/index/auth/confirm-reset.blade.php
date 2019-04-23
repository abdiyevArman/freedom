@extends('index.layout.layout')

@section('meta-tags')

    <title>{{Lang::get('app.confirm_phone')}}</title>

@endsection

@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">
        <div class="page">
            <div class="container">
                <header class="page__header text-center">
                    <h1 class="page__header-title">{{Lang::get('app.confirm_phone_number')}}</h1>
                </header>
                <div class="login">
                    <form class="login__form" method="post" action="/auth/confirm-reset">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" required id="phone" name="phone" value="{{$phone}}">
                        <div class="login__text">
                            <p>
                                {{Lang::get('app.we_send_sms')}} <span id="phone">{{$phone}}</span>
                            </p>
                            <p id="sms_label">
                                {{Lang::get('app.repeat_sms')}} <span>00:<span id="timer_inp">30</span></span>
                            </p>
                            <div id="send_sms_repeat">

                            </div>
                        </div>
                        <div class="control">
                            <div class="control__group">
                                <input type="text" required class="control__input" name="code" value="" placeholder="{{Lang::get('app.write_code')}}" />
                                <div class="control__help">Это поле нужно заполнить</div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="button -green -md">{{Lang::get('app.confirm')}}</button>
                            </div>
                        </div>
                    </form>
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
                $('#send_sms_repeat').html('<a id="send_sms_label" href="javascript:void(0)" onclick="sendSMSReset()">{{Lang::get('app.repeat_send_code')}}</a>');
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