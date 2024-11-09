<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    
    @include('layoutv2.layout.head')
    @stack('style')
</head>
<body class="app admin dashboard invoices-total-manual user-id-1 chrome">
@include('layoutv2.layout.header')
@include('layoutv2.layout.aside')
@yield('content')
@include('layoutv2.layout.scripts')
@stack('script')
</body>

</html>
