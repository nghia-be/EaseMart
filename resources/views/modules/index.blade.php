@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="_buttons">
                        <a onclick="addModules(event)" class="btn btn-primary mright5 test pull-left display-block">
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
                                {{ $dataTable->table(['id' => 'modules_manage'], $footer = false) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.modal', ['id' => 'showDetail_Modal', 'title'=>'Form Modules', 'form'=>'modules.list'])
@endsection
@push('script')
    {{ $dataTable->scripts() }}
    <script>
        function deleteModules(data) {
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/modules/destroy', dataPost).done(function (response) {
                alert_float('success', response.message);
                let table = $('#modules_manage').DataTable();
                table.ajax.reload(null, false);
            });
        }

        function addModules(e) {
            e.preventDefault();
            $('#showDetail_Modal').modal('toggle');
            document.getElementById('formModules').reset();
            $('#group_module_id').val('').change();
            $('#uri').val('').change();
            $('#icon').val('').change();
            window.urlMethod = '/modules/store';
        }

        function detailModules(_this) {
            let dataPost = {};
            dataPost.id = $(_this).data('id');
            $.post('/modules/show', dataPost).done(function (response) {
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
                window.urlMethod = '/modules/update/' + $(_this).data('id');
            });
        }

        function pushModules() {
            $(this).attr('disabled', 'disabled');
            let data = $('#formModules').serialize();
            $.ajax({
                url: urlMethod,
                type: 'POST',
                dataType: 'json',
                data: data,
                cache: false,
                success: (data) => {
                    if (data.success) {
                        $('#showDetail_Modal').modal('toggle');
                        alert_float('success', data.message);
                        $('#submit').prop('disabled', false);
                        let table = $('#modules_manage').DataTable();
                        table.ajax.reload(null, false);
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
