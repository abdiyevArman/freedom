
<?php

$menu_list = \App\Models\Menu::where('is_show',1)
        ->where('parent_id',null)
        ->where('is_show_main',1)
        ->orderBy('sort_num','asc')
        ->select('*',
                DB::raw('(SELECT count(*) FROM menu as child
                          WHERE child.is_show_main = 1
                          and child.parent_id = menu.menu_id
                          and child.is_show = 1
                          and child.deleted_at is null) as child_count')
        )
        ->get();
?>

<div id="m-menu" class="m-menu">
    <div class="m-menu__content">
        <div class="top-links">
            <a href="/" class="top-links__item @if(!isset($is_company)) -active @endif">{{Lang::get('app.individual')}}</a>
            <a href="/corporate" class="top-links__item @if(isset($is_company)) -active @endif">{{Lang::get('app.entity')}}</a>
        </div>
        <ul class="m-menu__list">


            @foreach($menu_list as $key => $item)

                @if($item->child_count == 0)

                    <li class="m-menu__item">
                        <a href="@if($item->menu_redirect == '')/{{$item['menu_url_'.$lang]}}@else{{$item['menu_redirect']}}@endif" class="m-menu__link">{{$item['menu_name_'.$lang]}}</a>
                    </li>

                @else

                    <?php
                    $child_menu_list = \App\Models\Menu::where('is_show',1)
                            ->where('parent_id',$item->menu_id)
                            ->where('is_show_main',1)
                            ->where('is_show',1)
                            ->orderBy('sort_num','asc')
                            ->get();
                    ?>

                    <li class="m-menu__item">
                        <a href="#mobile-menu-{{$key}}" class="m-menu__link" data-toggle="collapse" aria-expanded="false" aria-controls="mobile-menu-{{$key}}">{{$item['menu_name_'.$lang]}}</a>
                        <div class="collapse" id="mobile-menu-{{$key}}">
                            <ul class="m-menu__submenu">

                                @foreach($child_menu_list as $child_item)

                                    <li>
                                        <a class="m-menu__submenu-link" @if(strpos($child_item->menu_redirect,'onclick') !== false) href="javascript:void(0)" {!! $child_item->menu_redirect !!}  @elseif($child_item->menu_redirect == '') href="/{{$child_item['menu_url_'.$lang]}}" @else href="{{$child_item['menu_redirect']}}" @endif>{{$child_item['menu_name_'.$lang]}}</a>
                                    </li>

                                @endforeach

                            </ul>
                        </div>
                    </li>

                @endif

            @endforeach


        </ul>

        <div class="m-menu__lang">
            <a href="{{\App\Http\Helpers::setSessionLang('ru',$request)}}" class="m-menu__lang-link @if($lang == 'ru') -active @endif">Рус</a>
            <a href="{{\App\Http\Helpers::setSessionLang('kk',$request)}}" class="m-menu__lang-link @if($lang == 'kz') -active @endif">Қаз</a>
            <a href="{{\App\Http\Helpers::setSessionLang('en',$request)}}" class="m-menu__lang-link @if($lang == 'en') -active @endif">Eng</a>
        </div>
    </div>
</div>

<header class="header">
    <div class="header__top">
        <div class="container d-flex">
            <div class="top-links">
                <a href="/" class="top-links__item @if(!isset($is_company)) -active @endif">{{Lang::get('app.individual')}}</a>
                <a href="/corporate" class="top-links__item @if(isset($is_company)) -active @endif">{{Lang::get('app.entity')}}</a>
            </div>
            <div class="top-links ml-auto">
                <a href="{{\App\Http\Helpers::setSessionLang('kk',$request)}}" class="top-links__item @if($lang == 'kz') -active @endif">ҚАЗ</a>
                <a href="{{\App\Http\Helpers::setSessionLang('ru',$request)}}" class="top-links__item @if($lang == 'ru') -active @endif">РУС</a>
                <a href="{{\App\Http\Helpers::setSessionLang('en',$request)}}" class="top-links__item @if($lang == 'en') -active @endif">ENG</a>
            </div>
        </div>
    </div>
    <div class="header__main">
        <div class="container d-flex align-items-center">
            <a href="/" class="logo header__logo" title="FreedomFinance">
                <div class="logo__img"></div>
                <div class="logo__slogan"></div>
            </a>
            <nav class="navigation">
                <ul class="navigation__menu">

                    @foreach($menu_list as $key => $item)

                        @if($item->child_count == 0)

                            <li class="navigation__item">
                                <a href="@if($item->menu_redirect == '')/{{$item['menu_url_'.$lang]}}@else{{$item['menu_redirect']}}@endif" class="navigation__link">{{$item['menu_name_'.$lang]}}</a>
                            </li>

                        @else

                            <?php
                            $child_menu_list = \App\Models\Menu::where('is_show',1)
                                    ->where('parent_id',$item->menu_id)
                                    ->where('is_show',1)
                                    ->where('is_show_main',1)
                                    ->orderBy('sort_num','asc')
                                    ->get();
                            ?>

                            <li class="navigation__item dropdown">
                                <a href="#" class="navigation__link" id="menu{{$key}}" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    {{$item['menu_name_'.$lang]}}
                                </a>
                                <div class="dropdown-menu navigation__dropdown-menu" aria-labelledby="menu{{$key}}">

                                    <div class="container">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <ul>
                                                    @foreach($child_menu_list as $child_item)

                                                        <li>
                                                            <a class="navigation__sublink" @if(strpos($child_item->menu_redirect,'onclick') !== false) href="javascript:void(0)" {!! $child_item->menu_redirect !!} @elseif($child_item->menu_redirect == '') href="/{{$child_item['menu_url_'.$lang]}}" @else href="{{$child_item['menu_redirect']}}" @endif>{{$child_item['menu_name_'.$lang]}}</a>
                                                        </li>

                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="col-md-4">
                                                <img src="{{$item['menu_image']}}?width=656&height=400" alt="">
                                            </div>
                                            <div class="col-md-4">
                                               {!! $item['menu_desc_'.$lang] !!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </li>

                        @endif


                    @endforeach

                </ul>
            </nav>
            <div role="button" class="burger">
                <div class="burger__inner">
                    <span class="burger__item"></span>
                    <span class="burger__item"></span>
                    <span class="burger__item"></span>
                </div>
            </div>
            <div class="header__right">
                <a href="tel:{{\App\Http\Helpers::getInfoText(11)}}" class="header__phone">
                    <div class="header__phone-icon"></div>
                    <div class="header__phone-num">{!! \App\Http\Helpers::getInfoText(11) !!}</div>
                </a>
                <a href="/profile" class="button -green header__button">{{Lang::get('app.cabinet')}}</a>
            </div>
        </div>
    </div>
</header>



