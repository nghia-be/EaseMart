@extends('layoutv2.layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div id="wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-danger">403</h2>
                <div class="error-content">
                    <h3>
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                        Oops! Permission denied
                    </h3>
                    <p>
                        You don't have permission to access this page. You may return to dashboard or try another page.
                    </p>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <style>
        select {
            font-family: 'Lato', 'Font Awesome 5 Free', 'Font Awesome 5 Brands';
        }
    </style>
@endsection
