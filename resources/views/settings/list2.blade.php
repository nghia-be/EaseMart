{{--@extends('layouts.default')--}}

{{--@section('content')--}}
{{--    <!-- Content Wrapper. Contains page content -->--}}
{{--    <div class="content-wrapper">--}}
{{--        <!-- Content Header (Page header) -->--}}
{{--        <div class="content-header">--}}
{{--            <div class="container-fluid">--}}
{{--                <div class="row mb-2">--}}
{{--                    <div class="col-sm-6">--}}
{{--                        <h1 style="float: left; margin-right: 20px" class="uppercase"> Quản lí setting</h1>--}}
{{--                        @if(Auth::user()->role_id == ADMIN || $aclCurrentModule->create == 1)--}}
{{--                            <a href="{{ route('settings.create') }}" class="btn btn-primary btn-sm">--}}
{{--                                <i class="fas fa-plus"></i> Thêm mới setting--}}
{{--                            </a>--}}
{{--                        @endif--}}
{{--                    </div><!-- /.col -->--}}
{{--                    <div class="col-sm-6">--}}
{{--                        <ol class="breadcrumb float-sm-right">--}}
{{--                            {!! breadcrumb() !!}--}}
{{--                        </ol>--}}
{{--                    </div><!-- /.col -->--}}
{{--                </div><!-- /.row -->--}}
{{--            </div><!-- /.container-fluid -->--}}
{{--        </div>--}}
{{--        <!-- /.content-header -->--}}

{{--        <!-- Main content -->--}}
{{--        <section class="content">--}}
{{--            <div class="container-fluid">--}}
{{--                <div class="card card-body col-sm-12">--}}

{{--                    {!! $dataTable->table() !!}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}
{{--        <!-- /.content -->--}}
{{--    </div>--}}
{{--    <!-- /.content-wrapper -->--}}

{{--@endsection--}}
{{--@push('scripts')--}}
{{--    {{ $dataTable->scripts() }}--}}
{{--@endpush--}}
