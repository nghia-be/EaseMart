@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="_buttons">
                        <a onclick="addSupplier(event)" class="btn btn-primary mright5 test pull-left display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            Thêm mới</a>
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
                                {{ $dataTable->table(['id' => 'supplier_manage'], $footer = false) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.modal', ['id' => 'showDetail_Modal', 'title'=>'Form Supplier', 'form'=>'supplier.list'])
@endsection
@push('script')
    {{ $dataTable->scripts() }}
    <script>
        function deleteSupplier(data) {
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/supplier/destroy', dataPost).done(function (response) {
                alert_float('success', response.message);
                let table = $('#supplier_manage').DataTable();
                table.ajax.reload(null, false);
            });
        }

        function addSupplier(e) {
            e.preventDefault();
            $('#showDetail_Modal').modal('toggle');
            document.getElementById('formSupplier').reset();
            $('#group_supplier_id').val('').change();
            $('#uri').val('').change();
            $('#icon').val('').change();
            window.urlMethod = '/supplier/store';
            window.type = 'POST';
        }

        function detailSupplier(_this) {
            let dataPost = {};
            dataPost.id = $(_this).data('id');
            $.post('/supplier/show', dataPost).done(function (response) {
                console.log(response.data);
                for (let [key, value] of Object.entries(response.data)) {
                    let k = $('#' + key);
                    if (key === 'status' && value === '1') {
                        $('#status').prop('checked', false);
                    } else {
                        $('#status').prop('checked', true);
                    }
                    k.val(value);
                    k.trigger('change');
                }
                $('#showDetail_Modal').modal('toggle');
                window.urlMethod = '/supplier/update/' + $(_this).data('id');
                window.type = 'PUT';
            });
        }

        function pushSupplier() {
            $(this).attr('disabled', 'disabled');
            let data = $('#formSupplier').serialize();
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
                        $('#submit').prop('disabled', false);
                        let table = $('#supplier_manage').DataTable();
                        table.ajax.reload(null, false);
                    } else {
                        alert_float('danger', data.message);
                        $('#submit').prop('disabled', false);
                    }
                }, error: function (xhr) {
    let errorString = xhr.responseJSON?.message ?? 'Đã xảy ra lỗi'; // Kiểm tra và thiết lập giá trị mặc định nếu không tìm thấy message
    if (xhr.responseJSON?.errors) {
        $.each(xhr.responseJSON.errors, function (key, value) {
            errorString = value; // Lấy thông báo lỗi đầu tiên
            return false; // Dừng vòng lặp
        });
    }
    alert_float('danger', errorString); // Hiển thị thông báo lỗi
    $('#submit').prop('disabled', false); // Khôi phục trạng thái của nút
}
            });
        }
    </script>
@endpush
