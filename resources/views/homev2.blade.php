@extends('layoutv2.layout.app')
@section('content')
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <div class="col-lg-6">
                    <div class="clearfix"></div>
                    <div class="panel_s tw-mt-2 sm:tw-mt-4">
                        <div class="panel-body">
                            @include('chart.revenueChart')
                        </div>
                        <div class="panel-body">
                            @include('chart.paymentErrorUserSystemEcomChart')
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-6">
                    <div class="clearfix"></div>
                    <div class="panel_s tw-mt-2 sm:tw-mt-4">
                        <div class="panel-body">
                            <h3 class="card-title">SỐ LƯỢNG LỖI THANH TOÁN TRONG 30 NGÀY GẦN NHẤT</h3>
                            <div class="panel-table-full">
                                <table id="error-payment-table" class="table table-hover table-striped dataTable no-footer" style="width: 100%">
                                </table>
                            </div>
                            @include('chart.paymentErrorUserSystemFtelChart')
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-6">
                    <div class="clearfix"></div>
                    <div class="panel_s tw-mt-2 sm:tw-mt-4">
                        <div class="panel-body">
                            @include('chart.paymentErrorDetailEcomChart')
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-6">
                    <div class="clearfix"></div>
                    <div class="panel_s tw-mt-2 sm:tw-mt-4">
                        <div class="panel-body">
                            @include('chart.paymentErrorDetailFtelChart')
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-6">
                    <div class="clearfix"></div>
                    <div class="panel_s tw-mt-2 sm:tw-mt-4">
                        <div class="panel-body">
                            @include('chart.paymentErrorDetailSystemEcomChart')
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-6">
                    <div class="clearfix"></div>
                    <div class="panel_s tw-mt-2 sm:tw-mt-4">
                        <div class="panel-body">
                            @include('chart.paymentErrorDetailSystemFtelChart')
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    </div>
@endsection

@push('script')
    @hasanyrole('Super Admin|Admin')
        <script type="text/javascript" id="initTable-js" src="{{ asset('custom_js/initTable.js')}}"></script>
        <script type="text/javascript" id="dashboard3-js" src="{{ asset('assets/js/dashboard3.js')}}"></script>
    @endhasanyrole
@endpush

