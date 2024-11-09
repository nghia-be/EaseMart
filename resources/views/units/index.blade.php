@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="_buttons">
                        <a onclick="addUnits(event)" class="btn btn-primary mright5 test pull-left display-block">
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
                                {{ $dataTable->table(['id' => 'units_manage'], $footer = false) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.modal', ['id' => 'showDetail_Modal', 'title'=>'Form Units', 'form'=>'units.create'])
@endsection
@push('script')
    {{ $dataTable->scripts() }}
    <script>

        function dialogConfirmWithAjaxUnits(sureCallbackFunction, data, text = "Xin hay kiểm tra lại") {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Chắc chắn!',
                reverseButtons: true

            }).then((result) => {
                if (result.isConfirmed) {
                    sureCallbackFunction(data);
                }else {
                    let table = $('#units_manage').DataTable();
                    table.ajax.reload(null, false);
                }
            });
        }
        
        function changeStatusUnits(data){
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/units/change-status', dataPost).done(function(response) {
                alert_float('success', response.message);
                $('#units_manage').DataTable().ajax.reload(null,false);
            });
        }
    
        function deleteUnits(data) {
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/units/destroy', dataPost).done(function (response) {
                alert_float('success', response.message);
                let table = $('#units_manage').DataTable();
                table.ajax.reload(null, false);
            });
        }

        function addUnits(e) {
                e.preventDefault();
                $('#showDetail_Modal').modal('toggle');
                document.getElementById('formUnits').reset(); 
                window.urlMethod = '/units/store'; 
                window.type = 'POST'; //
            }

        function detailUnits(_this) {
            let dataPost = {};
            dataPost.id = $(_this).data('id');
            $.post('/units/show', dataPost).done(function (response) {
                console.log(response.data);
                for (let [key, value] of Object.entries(response.data)) {
                    let k = $('#' + key);
                    k.val(value);
                    k.trigger('change');
                }
            $('#showDetail_Modal').modal('toggle');
            window.urlMethod = '/units/update/' + $(_this).data('id');
            window.type = 'PUT';
        });
}


        function pushUnits() {
            $(this).attr('disabled', 'disabled');
            let data = $('#formUnits').serialize();
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
                        let table = $('#units_manage').DataTable();
                        table.ajax.reload(null, false);
                        $('#submit').prop('disabled', false);
                    } else {
                        alert_float('danger', data.message);
                        $('#submit').prop('disabled', false);
                    }
                }, error: function (xhr) {
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
