@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="_buttons">
                        <a href="#" onclick="alert('Liên hệ nghiatd12@fpt.com nếu xảy ra lỗi không mong muốn!')"
                           class="btn btn-default pull-left display-block mright5">
                            <i class="fa-regular fa-user tw-mr-1"></i>Liên hệ
                        </a>
                        <div class="visible-xs">
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel_s tw-mt-2 sm:tw-mt-4">
                        <div class="panel-body">
                            <div class="panel-table-full">
                                {{ $dataTable->table(['id' => 'orders_manage'], $footer = false) }}
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

function updateOrderStatus(orderId, statusId, currentStatusId) {
    $.ajax({
        url: '/orders/update-status',  // sử dụng tên route để lấy URL
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            order_id: orderId,
            order_status_id: statusId
        },
        success: function(response) {
            // Hiển thị thông báo và cập nhật dropdown nếu thành công
            alert_float('success', response.message);

            // Cập nhật lại select nếu thành công
            let selectElement = $(`select[data-order-id='${orderId}']`);
            selectElement.val(statusId);  // Thay đổi giá trị select
        },
        error: function() {
            // Hiển thị thông báo lỗi và khôi phục lại giá trị cũ
            alert_float('danger', 'Không thể cập nhật trạng thái.');

            // Khôi phục lại trạng thái cũ trong dropdown nếu có lỗi
            let selectElement = $(`select[data-order-id='${orderId}']`);
            selectElement.val(currentStatusId);
        }
    });
}



        function deleteCategories(data) {
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/categories/destroy', dataPost).done(function (response) {
                alert_float('success', response.message);
                let table = $('#categories_manage').DataTable();
                table.ajax.reload(null, false);
            });
        }

        
        function pushCategories() {
            $(this).attr('disabled', 'disabled');
            let data = $('#formCategories').serialize();
            $.ajax({
                url: urlMethod,
                type: window.type,
                dataType: 'json',
                data: data,
                cache: false,
                success: (data) => {
                    if (data.success) {
                        $('#showDetail_Modal').modal('toggle');
                        alert_float('success', data.message);
                        let table = $('#categories_manage').DataTable();
                        table.ajax.reload(null, false);
                        $('#submit').prop('disabled', false);
                    } else {
                        alert_float('danger', data.message);
                        $('#submit').prop('disabled', false);
                    }
                }
        , error: function (xhr) {
                    let errorString = xhr.responseJSON.message ?? '';
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorString = value;
                        return false;
                    });
                    alert_float('danger', errorString);
                    $('#submit').prop('disabled', false);
                }
            });
        }
    </script>
@endpush
