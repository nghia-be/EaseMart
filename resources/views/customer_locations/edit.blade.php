@extends('layouts.default')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ (!empty($user)) ? 'CHỈNH SỬA' : 'TẠO MỚI' }} CUSTOMER LOCATIONS</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('customer_locations.index')}}">Customer locations</a></li>
                        <li class="breadcrumb-item active">{{ (!empty($user)) ? 'chỉnh sửa' : 'Tạo mới' }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-sm-6">
                    @php
                        $action = (empty($data)) ? route('customer_locations.store') : route('customer_locations.update', [$data->customer_location_id]);
                    @endphp
                    {{-- <form action="{{$action}}" method="POST" onSubmit="validateDataLogReport(event,this)" onchange="checkEnableSaveLogReport(this)" onkeydown="checkEnableSaveLogReport(this)"> --}}
                        <form action="{{$action}}" method="POST" onSubmit="validateDataLogReport(event,this)">
                        @csrf
                        @if(!empty($data))
                            @method('PUT')
                        @endif
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title uppercase">Thông tin Customer locations</h3>
                            </div>
                            <div class="card-body" style="overflow-y: scroll">
                                <div class="form-group">
                                    <label for="customer_location_id" class="required_red_dot">customer_location_id</label>
                                    <input type="text" name="customer_location_id" class="form-control" value="{{ !empty($data)?$data->customer_location_id : ''}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="location_code" class="required_red_dot">location_code</label>
                                    <input type="text" name="location_code" class="form-control" value="{{ !empty($data)?$data->location_code : ''}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="location_name">location_name</label>
                                    <input type="text" name="location_name" class="form-control" value="{{ !empty($data)?$data->location_name : ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="location_name_vi">location_name_vi</label>
                                    <input type="text" name="location_name_vi" class="form-control" value="{{ !empty($data)?$data->location_name_vi : ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="location_zone">location_zone</label>
                                    <input type="text" name="location_zone" class="form-control" value="{{ !empty($data)?$data->location_zone : ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="location_zone">location_zone</label>
                                    <input type="text" name="location_zone" class="form-control" value="{{ !empty($data)?$data->location_zone : ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="location_zone_vi">location_zone_vi</label>
                                    <input type="text" name="location_zone_vi" class="form-control" value="{{ !empty($data)?$data->location_zone_vi : ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="is_deleted">is_deleted</label>
                                    <input type="text" name="is_deleted" class="form-control" value="{{ !empty($data)?$data->is_deleted : ''}}">
                                </div>
                            </div>
                            <div class="card-footer" style="text-align: center">
                                <a href="{{ route('customer_locations.index') }}" type="button" class="btn btn-default">Thoát</a>
                                <button type="submit" class="btn btn-info">Lưu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@push('scripts')
    {{-- <script type="text/javascript" src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/ckeditor/js/ckeditor.js" charset="utf-8"></script> --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script src="{{ asset('/custom_js/customer_locations.js')}}" type="text/javascript" charset="utf-8"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#solve_way' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
@endpush

<style>
    @import url("https://fonts.googleapis.com/css?family=Montserrat|Roboto+Mono");
    body {
        justify-content: center;
        align-items: center;
        position: absolute;
        height: 100%;
        width: 100%;
        margin: 0;
    }
    @media screen and (max-height: 375px) {
        body {
            position: relative;
            overflow: auto;
        }
    }

    .card {
        width: 100%;
    }
    @media screen and (max-width: 1200px) {
        .card {
            width: 100%;
        }
    }

    #editorWrapper {
        position: relative;
        height: 100%;
        min-height: 45vh;
    }
    @media (min-height: 600px) {
        #editorWrapper {
            height: 55vh;
        }
    }
    @media (min-height: 900px) {
        #editorWrapper {
            height: 65vh;
        }
    }
    #editorWrapper #editor {
        font-family: "Courier Mono", monospace;
        font-size: 14px;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
</style>
