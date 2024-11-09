@extends('layouts.default')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 style="float: left; margin-right: 20px">{{$title}}</h1>
{{--                        <a href="{{$info['controller']}}/{{$info['action_edit']}}" class="btn btn-primary btn-sm">--}}
{{--                            <i class="fas fa-plus"></i> Add new {{$info['controller']}}--}}
{{--                        </a>--}}
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
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
                <div class="col-sm-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title uppercase">Infomation group</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        {!! Form::open(array('url' => Config::get('app.backendUrl').'/'.$controller.'/save','method'=>'post' ,'id' => 'form-view','action' =>'index','class'=>'form-horizontal','enctype' =>'multipart/form-data')) !!}
                        <input type="hidden" name="model_name" value="{{$model_name}}">
                        <input type="hidden" name="action_detail" value="{{$action_detail}}">
                        <input type="hidden" name="id" value="@if(!empty($data)){{$data->id}}@endif"/>

                        <div class="card-body">
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Group Name</label>
                                    <div class="col-sm-10">
                                        <input type="name" class="form-control" id="name" placeholder="Group Name" name="group_name" value="@if(!empty($data)){{$data->group_name}}@endif">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info float-right" style="margin-left: 5px">Save</button>
                                <a href="/{{$controller}}" class="btn btn-default float-right">Cancel</a>
                            </div>
                            <!-- /.card-footer -->
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
