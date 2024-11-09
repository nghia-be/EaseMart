@extends('layouts.default')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 style="float: left; margin-right: 20px" class="uppercase">{{$title}}</h1>
                        @if(Auth::user()->role_id == ADMIN || $aclCurrentModule->create == 1)
                        <a href="{{$controller}}/{{$action_edit}}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add new {{$controller}}
                        </a>
                        @endif
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">{{ucfirst($controller)}}</li>
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
                    <table id="groupTable" class="table table-hover table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID</th>
                            <th>Group name</th>
                            <th>Created by</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection