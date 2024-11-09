@extends('layouts.default')

@section('content')
@php
    $uri_config = $Settings->firstWhere('name','uri_config');
    $list_uri = [];
    if(!empty($uri_config)){
        $list_uri = json_decode($uri_config->value);
    }
@endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ ($module->id) ? 'EDIT' : 'ADD NEW' }} MODULE</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Module</li>
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
                    <div class="col-sm-12">
                        <form action="/modules{{(!$module->id) ? '/store' : '/update'}}{{ (!$module->id) ? '' : '/' . $module->id }}" method="POST" novalidate="novalidate" autocomplete="off" onSubmit="handleSubmit(event,this)">
                            @csrf
            
                            @if (isset($module->id) && $module->id)
                                @method('PUT')
                            @endif
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title uppercase">Module Info</h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="module_name">Module name</label>
                                            <input type="text" id="module_name" name="module_name" class="form-control @error('module_name') is-invalid @enderror" placeholder="Module name" value="{{ $module->module_name }}" >
                                            @error('module_name')
                                                <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="uri">Uri</label>
                                            
                                            <select name="uri" id="uri" class="form-control selectpicker @error('module_name') is-invalid @enderror" data-live-search="true" data-size="10">
                                                <option value="">Please choose uri</option>
                                                @foreach ($list_uri as $uri)
                                                    <option value="{{ $uri->uri }}" {{ ($module->uri == $uri->uri) ? 'selected' : '' }}>{{ $uri->uri }}</option>
                                                @endforeach
                                            </select>
                                            @error('uri')
                                                <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="uri">Group module</label>
                                            <select name="group_module_id" id="group_module" class="form-control selectpicker" data-live-search="true" data-size="10">
                                                <option value="">Please choose group module</option>
                                                @foreach ($module->list_group_module as $group_module)
                                                    <option value="{{ $group_module->id }}" {{ ($group_module->id == $module->group_module_id) ? 'selected' : '' }}>{{ $group_module->group_module_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('uri')
                                                <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="uri">Icon</label>
                                            <select name="icon" id="icon" class="form-control selectpicker" data-live-search="true" data-size="10">
                                                <option value="">Please choose icon</option>
                                                @foreach ($module->list_icon as $icon)
                                                    <option value="fas fa-{{ $icon }}" {{ ("fas fa-" . $icon == $module->icon) ? 'selected' : '' }} data-icon="fas fa-{{ $icon }}">fas fa-{{ $icon }}</option>
                                                @endforeach
                                            </select>
                                            @error('uri')
                                                <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input id="status" name="status" class="form-check-input" type="checkbox" value="true" {{ ($module->status) ? 'checked' : '' }}>
                                                <label>Status</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" style="text-align: center">
                                    <a href="/modules" type="button" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="btn btn-info">Save</button>
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
    <style>
        select {
            font-family: 'Lato', 'Font Awesome 5 Free', 'Font Awesome 5 Brands';
        }
    </style>
@endsection