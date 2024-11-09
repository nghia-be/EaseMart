<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{empty($title) ? 'EaseMart' : $title}}</title>
    <link rel="shortcut icon" href="{{asset('themes/images/easemart-logo1.png')}}"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link href="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/login/util.css?v=7.0.6"
          rel="stylesheet" type="text/css"/>
    <link href="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/login/main.css?v=7.0.6"
          rel="stylesheet" type="text/css"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="gray-bg">
<div id="eagle-alert"></div>
@if(!empty(Session::get('message')))
    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible">
        <button type="button" class="close" aria-hidden="true">×</button>
        @if(is_array(Session::get('message')))
            @foreach (Session::get('message') as $key=>$error)
                @if(is_array($error))
                    @foreach ($error as $k=>$v)
                        {!! $v !!}<br>
                    @endforeach
                @else
                    {!! $error !!}
                @endif
            @endforeach
        @else
            {{ Session::get('message') }}
        @endif
    </div>
@endif
@yield('content')
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/login/jquery-3.2.1.min.js?v=7.0.6"></script>
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/login/main.js?v=7.0.6"></script>

</body>
</html>
