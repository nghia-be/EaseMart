@extends('layouts.default')

@section('content')
@if(env('APP_ENV') == 'staging' || env('APP_ENV') == 'local' )
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 uppercase">Check List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Check list</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-sm-6">
                    <!--Accordion wrapper-->
                    <div class="accordion md-accordion accordion-blocks" id="accordionEx78" role="tablist" aria-multiselectable="true">

                        <!-- Accordion card -->
                        <div class="card card-info" id="sendStaff">

                            <!-- Card header -->
                            <div class="card-header" role="tab">
                                <!-- Heading -->
                                <a data-toggle="collapse" data-parent="#accordionEx78" href="#collapseUnfiled" aria-expanded="true" aria-controls="collapseUnfiled" class="btn btn-header-link collapsed">
                                    Send Staff
                                </a>
                            </div>
                            <!-- Card body -->
                            <div id="collapseUnfiled" class="collapse" role="tabpanel" data-parent="#accordionEx78">
                                <form action="{{ route('checklistmanage.sendStaff')}}" method="POST" onsubmit="handleSubmit(event,this)">
                                    @csrf
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="contractNo" placeholder="Enter Contract Number...">
                                        </div>
                                    </div>
                                    <div class="card-footer" style="text-align: center">
                                        <button class="btn btn-info">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Accordion card -->

                        <!-- Accordion card -->
                        <div class="card card-info" id="completeCheckList">

                            <!-- Card header -->
                            <div class="card-header" role="tab">
                                <!-- Heading -->
                                <a data-toggle="collapse" data-parent="#accordionEx78" href="#completeChecklist" aria-expanded="true" aria-controls="collapseUnfiled" class="btn btn-header-link collapsed">
                                   Complete
                                </a>
                            </div>

                            <!-- Card body -->
                            <div id="completeChecklist" class="collapse" role="tabpanel" data-parent="#accordionEx78">
                                <form action="{{route('checklistmanage.completeChecklist')}}" method="POST" onsubmit="handleSubmit(event,this)">
                                    @csrf
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="checkListId" placeholder="Enter Id...">
                                        </div>
                                    </div>
                                    <div class="card-footer" style="text-align: center">
                                        <button class="btn btn-info">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Accordion card -->
                    </div>
                    <!--/.Accordion wrapper-->
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="card card-body col-sm-12">
                    <table id="checklistManage_table" class="table table-hover table-striped" style="width:100%">
                    </table>
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
<script>
    var listCheckList = {!! !empty($list_checklist_id) ? json_encode($list_checklist_id) : 'null'; !!};
    var route_checklistmanage = '{{ route('checklistmanage.completeChecklist')}}';
    var crsf = '{{ csrf_token()}}';
</script>
@else
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">ONLY AVAILABLE ON STAGING</h1>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
