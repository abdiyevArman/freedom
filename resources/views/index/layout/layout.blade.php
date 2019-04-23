<!doctype html>
<html class="no-js" lang="ru">

@include('index.layout.app')

<body>
<i class="ajax-loader"></i>

<div class="layout">
    <div class="layout__wrapper">
    
    @include('index.layout.header')
    
    @yield('content')

    @yield('footer')

    </div>
</div>

<noindex>

@yield('modals')

</noindex>

<script src="/custom/js/jquery.js"></script>
<script src="/static/js/main.min.js?v=1"></script>
<script src="/static/js/separate-js/scripts.js?v=2"></script>
<script src="/custom/js/custom.js?v=41"></script>

<script>
    @if(isset($error))
        showError('{{$error}}');
    @endif

    @if(isset($message))
        showMessage('{{$message}}');
    @endif
</script>

@yield('js')

<!-- BEGIN JIVOSITE CODE -->
<script type='text/javascript'>
    (function(){ var widget_id = 'wPHqpxFJiR';var d=document;var w=window;function l(){ var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id ; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);} if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);} else{w.addEventListener('load',l,false);}}})();
</script>
<!-- END JIVOSITE CODE -->

@include('index.layout.modals')

</body>
</html>