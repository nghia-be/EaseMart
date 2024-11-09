@extends('layouts.default')

@section('content')
<!-- Content Wrapper. Contains page content -->
<?php
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 style="float: left; margin-right: 20px" class="uppercase">Quản Lý customer locations</h1>
                    <a href="{{ route('customer_locations.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Thêm Mới
                    </a>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Quản Lý customer locations</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-body col-sm-12">

                {{ $dataTable->table([], true) }}

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
<!--end::Table-->
@push('scripts')
    <script src="{{ asset('/custom_js/customer_locations.js')}}" type="text/javascript" charset="utf-8"></script>
    {{ $dataTable->scripts() }}
    {{-- <script>
        const table = $('#helper');
        table.on('preXhr.dt', function(e, settings, data){
            data.bannerType = $('#show_at').val();
            data.public_date_start = $('#show_from').val();
            data.public_date_end = $('#show_to').val();
        });
    </script> --}}
@endpush
