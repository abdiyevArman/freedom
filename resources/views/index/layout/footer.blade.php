<?php

$footer_menu_list = \App\Models\Menu::where('is_show',1)
        ->where('parent_id',null)
        ->where('is_show_footer',1)
        ->orderBy('sort_num','asc')
        ->select('*',
                DB::raw('(SELECT count(*) FROM menu as child
                          WHERE child.is_show_footer = 1
                          and child.parent_id = menu.menu_id
                          and child.is_show = 1
                          and child.deleted_at is null) as child_count')
        )
        ->get();
?>

<footer class="footer">
    <div class="footer__links">
        <div class="container">
            <div class="row" vertical-gutter="40">

                    @foreach($footer_menu_list as $key => $item)

                        <div class="col">
                            <div class="footer__title">{{$item['menu_name_'.$lang]}}</div>

                            <ul class="footer__menu">

                                <?php
                                $child_menu_list = \App\Models\Menu::where('is_show',1)
                                        ->where('parent_id',$item->menu_id)
                                        ->where('is_show',1)
                                        ->where('is_show_footer',1)
                                        ->orderBy('sort_num','asc')
                                        ->get();
                                ?>

                                @foreach($child_menu_list as $child_item)

                                    <li class="footer__menu-item">
                                        <a href="@if($child_item->menu_redirect == '')/{{$child_item['menu_url_'.$lang]}}@else{{$child_item['menu_redirect']}}@endif" class="footer__menu-link">{{$child_item['menu_name_'.$lang]}}</a>
                                    </li>

                                @endforeach

                            </ul>

                            @if($key == 4)
                                <form class="search mt-4" method="get" action="/search">
                                    <input name="q" type="search" class="search__input" placeholder="{{Lang::get('app.search_in_site')}}" />
                                    <button type="submit" class="button search__button">{{Lang::get('app.search')}}</button>
                                </form>
                            @endif
                        </div>

                    @endforeach

            </div>
        </div>
    </div>
    <div class="container">
        <div class="footer__contacts">
            <div class="footer__title">{{Lang::get('app.contact')}}</div>
            <div class="footer__contacts-row">
                <div class="footer__contacts-info -location">{!! \App\Http\Helpers::getInfoText(1) !!}</div>
                <div class="footer__contacts-info -phone">{!! \App\Http\Helpers::getInfoText(2) !!}</div>
                <div class="footer__contacts-info -mail">{!! \App\Http\Helpers::getInfoText(3) !!}</div>
                <div class="footer__social">
                    <div class="footer__social-title">{{Lang::get('app.social_website')}}</div>
                    <a target="_blank" href="{{\App\Http\Helpers::getInfoText(13)}}" class="footer__social-link">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a target="_blank" href="{{\App\Http\Helpers::getInfoText(15)}}" class="footer__social-link">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <a target="_blank" href="{{\App\Http\Helpers::getInfoText(14)}}" class="footer__social-link">
                        <i class="fa fa-vk"></i>
                    </a>
                </div>
            </div>
            <ul class="footer__copyright">
                <li class="footer__copyright-item">{!! \App\Http\Helpers::getInfoText(4) !!}</li>
                <li class="footer__copyright-item footer__copyright-link">
                    {!! \App\Http\Helpers::getInfoText(10) !!}
                </li>
            </ul>
        </div>
    </div>
</footer>