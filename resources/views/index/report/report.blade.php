@extends('index.layout.layout')

@section('meta-tags')

    <title>{{$menu['menu_meta_title_'.$lang]}}</title>
    <meta name="description" content="{{$menu['menu_meta_description_'.$lang]}}"/>
    <meta name="keywords" content="{{$menu['menu_meta_keywords_'.$lang]}}"/>

@endsection

@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">
        <div class="page">
            <div class="container">
                <header class="page__header">
                    <ul class="breadcrumbs">
                        <li class="breadcrumbs__item">
                            <a href="/" class="breadcrumbs__link">{{Lang::get('app.homepage')}}</a>
                        </li>
                        @if($menu->parent_name != '')
                            <li class="breadcrumbs__item">
                                <a href="#" class="breadcrumbs__link">{{$menu->parent_name}}</a>
                            </li>
                        @endif
                        <li class="breadcrumbs__item -active">
                            {{$menu['menu_name_'.$lang]}}
                        </li>
                    </ul>
                    <h1 class="page__header-title">{{$menu['menu_name_'.$lang]}}</h1>
                </header>

                <main role="main" class="page__content">

                    @foreach($report_types as $key => $item)

                        <div class="section-heading -green @if($key > 0) pt-5 @endif pb-3">
                            <h2 class="section-heading__title">{{$item['report_type_name_'.$lang]}}</h2>
                        </div>
                        <ul class="docs">

                            <?php $report = \App\Models\Report::where('report_type_id',$item->report_type_id)
                                                    ->where('is_show',1)
                                                    ->orderBy('report_date','asc')
                                                ->select('*',
                                                        DB::raw('DATE_FORMAT(report.report_date,"%d.%m.%Y") as date'))
                                                    ->get(); ?>

                            @foreach($report as $report_item)

                                <li class="docs__item">
                                    <a href="{{$report_item['report_file']}}" target="_blank" class="docs__link">
                                        <div class="docs__title">{{$report_item['report_name_'.$lang]}}</div>
                                        <div class="docs__date">{{$report_item['date']}}</div>
                                    </a>
                                </li>
                                
                            @endforeach
                            
                        </ul>
                        
                    @endforeach

                </main>

            </div>
        </div>
    </div>

@endsection

@section('js')




@endsection