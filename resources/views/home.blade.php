@extends('layouts.default')

@section('content')
<div class="content-wrapper" style="min-height: 1200.88px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
            @include('chart.revenueChart')
            <!-- /.card -->

            @include('chart.paymentErrorUserSystemEcomChart')
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">SỐ LƯỢNG LỖI THANH TOÁN TRONG 30 NGÀY GẦN NHẤT</h3>
                  {{-- <a href="javascript:void(0);">View Report</a> --}}
                </div>
              </div>
              <div class="card-body">
                <table id="error-payment-table" class="table table-hover table-striped dataTable no-footer" style="width: 100%">
                </table>
              </div>
            </div>
            <!-- /.card -->

            @include('chart.paymentErrorUserSystemFtelChart')
          </div>
          <div class="col-lg-6">
            @include('chart.paymentErrorDetailEcomChart')
          </div>
          <div class="col-lg-6">
            @include('chart.paymentErrorDetailFtelChart')
          </div>
          <div class="col-lg-6">
            @include('chart.paymentErrorDetailSystemEcomChart')
          </div>
          <div class="col-lg-6">
            @include('chart.paymentErrorDetailSystemFtelChart')
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
@endsection
