<!DOCTYPE html>
<html>
<head>
    @include('layouts.header')
    @stack('header')
    {{ vite_assets('css') }}
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
        {{-- snining --}}
        <div id="spinner">
            <div class="loader">
                <img style="width: 100px" src="/images/Ellipsis-1.3s-200px.svg" alt="loading">
                <div class="text-center text-white">Đang tải dữ liệu...</div>
            </div>
        </div>
        {{-- snining --}}
        <!-- Preloader -->
        {{--<div class="preloader flex-column justify-content-center align-items-center">--}}
        {{--<img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">--}}
        {{--</div>--}}

        <!-- Navbar -->
        @include('layouts.navbar')
        <!-- /.navbar -->
        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- /.sidebar -->

        @can('role-permission')
        <div id="pjax">
            @yield('content')
        </div>
        @else
        @yield('content')
        @endcan
        @include('layouts.footer')
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    {{ vite_assets('scripts') }}
    @include('layouts.scripts')
    @stack('scripts')
    <!-- jQuery -->
</body>

</html>
