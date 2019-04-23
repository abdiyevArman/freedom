<aside class="left-sidebar">
  <div class="scroll-sidebar">
    <div class="user-profile">
      <div class="profile-img"> <img src="{{Auth::user()->avatar}}" alt="user" /> </div>
      <div class="profile-text"> <a href="#" class="dropdown-toggle link u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{Auth::user()->name}} <span class="caret"></span></a>
        <div class="dropdown-menu animated flipInY">
          <div class="dropdown-divider"></div> <a href="/admin/password" class="dropdown-item"><i class="ti-settings"></i> Сменить пароль</a>
          <div class="dropdown-divider"></div> <a href="/admin/logout" class="dropdown-item"><i class="fa fa-power-off"></i> Выйти</a>
        </div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <ul id="sidebarnav" style="padding-bottom: 140px">
        {{--<li class="nav-small-cap">МЕНЮ</li>--}}
        <li>
          <a class="@if(isset($menu) && $menu == 'request-policy-kasko') active @endif" href="/admin/request-policy/kasko?active=1">
            <?php $count = \App\Models\RequestPolicy::where('is_show','=','1')->where('type','=','kasko')->count();?>
            <i class="mdi mdi-view-sequential"></i><span class="hide-menu">КАСКО <span @if($count == 0) style="display: none" @endif class="label label-rounded label-danger">{{$count}}</span></span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'request-policy-express') active @endif" href="/admin/request-policy/kasko-express?active=1">
            <?php $count = \App\Models\RequestPolicy::where('is_show','=','1')->where('type','=','kasko-express')->count();?>
            <i class="mdi mdi-view-sequential"></i><span class="hide-menu">КАСКО Express <span @if( $count == 0) style="display: none" @endif class="label label-rounded label-danger">{{$count}}</span></span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'policy') active @endif" href="/admin/policy" >
            <i class="mdi mdi-view-sequential"></i><span class="hide-menu">ОГПО</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'order') active @endif" href="/admin/order?active=1">
            <?php $count = \App\Models\Order::where('is_show','=','1')->where('type',1)->count();?>
            <i class="mdi mdi-view-sequential"></i><span class="hide-menu">Обратная связь <span @if($count == 0) style="display: none" @endif class="label label-rounded label-danger">{{$count}}</span></span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'insurance') active @endif" href="/admin/insurance?active=1">
            <?php $count = \App\Models\InsuranceRequest::where('is_show','=','1')->count();?>
            <i class="mdi mdi-view-sequential"></i><span class="hide-menu">Страховой случай <span @if($count == 0) style="display: none" @endif class="label label-rounded label-danger">{{$count}}</span></span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'order-insurance') active @endif" href="/admin/order-insurance?active=1">
            <?php $count = \App\Models\Order::where('is_show','=','1')->where('type',2)->count();?>
            <i class="mdi mdi-view-sequential"></i><span class="hide-menu">Заявки от юр. лиц<span @if($count == 0) style="display: none" @endif class="label label-rounded label-danger">{{$count}}</span></span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'order-vacancy') active @endif" href="/admin/order-vacancy?active=1">
            <?php $count = \App\Models\Order::where('is_show','=','1')->where('type',3)->count();?>
            <i class="mdi mdi-view-sequential"></i><span class="hide-menu">Резюме <span @if($count == 0) style="display: none" @endif class="label label-rounded label-danger">{{$count}}</span></span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'news') active @endif" href="/admin/news" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Новости / Блог</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'menu') active @endif" href="/admin/menu" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Меню</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'faq') active @endif" href="/admin/faq" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Вопросы и ответы</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'offer') active @endif" href="/admin/offer" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Акция</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'vacancy') active @endif" href="/admin/vacancy" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Вакансии</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'info') active @endif" href="/admin/info" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Тексты на сайте</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'city') active @endif" href="/admin/city" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Города</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'contact') active @endif" href="/admin/contacts" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Контакты</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'person') active @endif" href="/admin/person" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Команда</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'team') active @endif" href="/admin/team" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Структура команды</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'report') active @endif" href="/admin/report" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Отчеты</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'document-type') active @endif" href="/admin/document-type" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Виды документов</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'report-type') active @endif" href="/admin/report-type" >
            <i class="mdi mdi-view-grid"></i><span class="hide-menu">Виды отчета</span>
          </a>
        </li>
       {{-- <li>
          <a class="has-arrow @if(isset($menu) && $menu == 'client') active @endif" href="#" aria-expanded="false">
            <i class="mdi mdi-account"></i><span class="hide-menu">Пользователи</span>
          </a>
          <ul aria-expanded="false" class="collapse">
            <li><a href="/admin/client?active=0" class="active">Активные</a></li>
            <li><a href="/admin/client?active=1">Заблокированные</a></li>
          </ul>
        </li>--}}
        <li>
          <a class="@if(isset($menu) && $menu == 'action') active @endif" href="/admin/action" >
            <i class="mdi mdi-receipt"></i><span class="hide-menu">Действие пользователей</span>
          </a>
        </li>
        <li>
          <a class="@if(isset($menu) && $menu == 'password') active @endif" href="/admin/password" >
            <i class="mdi mdi-settings"></i><span class="hide-menu">Сменить пароль</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>

  <div class="sidebar-footer">
    <!-- item-->
    <a href="/admin/password" class="link" data-toggle="tooltip" title="Сменить пароль"><i class="ti-settings"></i></a>
    <!-- item-->
    <a href="" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
    <!-- item-->
    <a href="/admin/logout" class="link" data-toggle="tooltip" title="Выйти"><i class="mdi mdi-power"></i></a>
  </div>

</aside>