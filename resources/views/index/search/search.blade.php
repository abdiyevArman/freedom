@extends('index.layout.layout')

@section('meta-tags')

    <title>{{Lang::get('app.search_label')}}</title>


@endsection

@section('footer')
    @include('index.layout.footer')
@endsection

@section('content')

    <div class="layout__main">

        <div class="page -boxed">
            <div class="container">

                <main role="main" class="page__content">
                    <form class="search mb-4">
                        <input type="search" class="search__input" name="q" value="{{$request->q}}" placeholder="{{Lang::get('app.search_in_site')}}" />
                        <button type="submit" class="button search__button">{{Lang::get('app.search')}}</button>
                    </form>

                    @include('index.search.search-list-loop')

                </main>
            </div>
        </div>

    </div>

@endsection

