@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="_filters _hidden_inputs hidden">
                        <input type="hidden" name="my_customers" value=""/>
                        <input type="hidden" name="requires_registration_confirmation" value=""/>
                    </div>
                    <div class="_buttons">
                        <a href="testv2/create" class="btn btn-primary mright5 test pull-left display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            Thêm mới</a>
                        <a href="#" class="btn btn-primary pull-left display-block mright5 hidden-xs">
                            <i class="fa-solid fa-upload tw-mr-1"></i>Nhập
                        </a>
                        <a href="#" class="btn btn-default pull-left display-block mright5">
                            <i class="fa-regular fa-user tw-mr-1"></i>Liên hệ
                        </a>
                        <div class="visible-xs">
                            <div class="clearfix"></div>
                        </div>
                        <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data"
                             data-toggle="tooltip"
                             data-title="Được lọc theo">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-filter" aria-hidden="true"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
                                <li class="active"><a href="#" data-cview="all"
                                                      onclick="dt_custom_view('','.table-clients',''); return false;">customers_sort_all</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="#" data-cview="requires_registration_confirmation"
                                       onclick="dt_custom_view('requires_registration_confirmation','.table-clients','requires_registration_confirmation'); return false;">
                                        customer_requires_registration_confirmation
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="#" data-cview="my_customers"
                                       onclick="dt_custom_view('my_customers','.table-clients','my_customers'); return false;">
                                        customers_assigned_to_me
                                    </a>
                                </li>
                                <div class="clearfix"></div>
                                <li class="divider"></li>
                                <li class="dropdown-submenu pull-left responsible_admin">
                                    <a href="#" tabindex="-1">responsible_admin</a>
                                    <ul class="dropdown-menu dropdown-menu-left">
                                        <li>
                                            <a href="#"
                                               data-cview="responsible_admin_staff_id"
                                               onclick="dt_custom_view('responsible_admin_staff_id','.table-clients','responsible_admin_staff_id'); return false;">
                                                staff_id
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel_s tw-mt-2 sm:tw-mt-4">
                        <div class="panel-body">
                            @include('layoutv2.layout.test-data.overview')
                            <hr class="hr-panel-separator"/>
                            <a href="#" data-toggle="modal" data-target="#customers_bulk_action"
                               class="bulk-actions-btn table-btn hide"
                               data-table=".table-clients">Bulk Actions</a>

                            <!-- /.modal -->
                            <div class="clearfix mtop20"></div>
                            <div class="panel-table-full">
                                {!! $dataTable->table() !!}
                            </div>
                            <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Bulk Actions</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="checkbox checkbox-danger">
                                                <input type="checkbox" name="mass_delete" id="mass_delete">
                                                <label for="mass_delete">mass_delete</label>
                                            </div>
                                            <hr class="mass_delete_separator"/>
                                            <div id="bulk_change">
                                                render_select
                                                <p class="text-danger">
                                                    bulk_action_customers_groups_warning</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">close</button>
                                            <a href="#" class="btn btn-primary"
                                               onclick="customers_bulk_action(this); return false;">confirm</a>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    {{ $dataTable->scripts() }}
    <script>
        function customers_bulk_action(event) {
            var r = confirm(app.lang.confirm_action_prompt);
            if (r == false) {
                return false;
            } else {
                var mass_delete = $('#mass_delete').prop('checked');
                var ids = [];
                var data = {};
                if (mass_delete == false || typeof (mass_delete) == 'undefined') {
                    data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
                    if (data.groups.length == 0) {
                        data.groups = 'remove_all';
                    }
                } else {
                    data.mass_delete = true;
                }
                var rows = $('.table-clients').find('tbody tr');
                $.each(rows, function () {
                    var checkbox = $($(this).find('td').eq(0)).find('input');
                    if (checkbox.prop('checked') == true) {
                        ids.push(checkbox.val());
                    }
                });
                data.ids = ids;
                $(event).addClass('disabled');
                setTimeout(function () {
                    $.post(admin_url + 'clients/bulk_action', data).done(function () {
                        window.location.reload();
                    });
                }, 50);
            }
        }
    </script>
@endpush
