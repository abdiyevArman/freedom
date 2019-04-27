@extends('index.layout.layout')

@section('meta-tags')

    <title>{{Lang::get('app.my_policies')}}</title>

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
                            <a href="/profile" class="cabinet__nav-link">{{Lang::get('app.my_profile')}}</a>
                        </li>
                        <li class="cabinet__nav-item">
                            <a href="#" class="cabinet__nav-link -active">{{Lang::get('app.my_policies')}}</a>
                        </li>
                        <li class="cabinet__nav-item">
                            <a href="/auth/logout" class="cabinet__nav-link">{{Lang::get('app.logout')}}</a>
                        </li>
                    </ul>
                </div>
            </header>

            @if(count($policy) == 0)

                <div class="cabinet__content">
                    <div class="empty-policies">
                        <div class="container">
                            <div class="empty-policies__content">
                                <div class="empty-policies__title">{{Lang::get('app.empty_policies')}}</div>
                                <a href="/ogpo" class="button -green">{{Lang::get('app.add_policy')}}</a>
                            </div>
                        </div>
                    </div>
                </div>

            @else

                <div class="cabinet__content">
                    <div class="container">
                        <div class="row" data-gutter="15">

                            @foreach($policy as $item)

                                <div class="user-card">
                                    <div class="user-card__inner">
                                        <div class="user-card__title">{{Lang::get('app.ogpo')}}</div>
                                        <div class="user-card__text">
                                            <img src="/static/img/general/ic-car.svg" class="mr-3" alt="">{{$item['transport_name']}}
                                        </div>
                                        <div class="user-card__text">
                                            <img src="/static/img/general/ic-price.svg" class="mr-3" alt="">{{$item['cost']}} тг
                                        </div>
                                        <div class="user-card__text">
                                            <img src="/static/img/general/ic-date.svg" class="mr-3" alt="">до {{\App\Http\Helpers::getDateFormat3($item['policy_finish_date'])}}
                                        </div>
                                        <div class="my-4"></div>
                                        <a target="_blank" href="{{$item->pdf_hash_url}}" class="button -green user-card__button">{{Lang::get('app.view')}}</a>
                                       {{-- <a href="#" class="button -bordered_gray user-card__button">Добавить водителя</a>--}}
                                        <a target="_blank" href="/insurance" class="button -bordered_gray user-card__button">{{Lang::get('app.insurance_event')}}</a>
                                        <a href="javascript:void(0)" onclick="rejectPolicyModal('{{$item->policy_id}}')" class="button -bordered_gray user-card__button">{{Lang::get('app.reject')}}</a>
                                    </div>
                                </div>

                            @endforeach

                        </div>
                    </div>
                </div>

            @endif

        </div>

    </div>

    @include('index.profile.modal')

@endsection

@section('js')

    <script src="/custom/js/esbd.js?v=15"></script>

    <script>


        window.onbeforeunload = function() {
            alert("Back/Forward clicked!");
        }

    </script>

    @if(isset($success) && $success == 1)
        <script>
            $('#modal-success').modal('show');
        </script>
    @endif

@endsection