<div class="row">

    <div class="tw-ml-3 tw-mb-2 sm:tw-mb-4">
        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#showDetail_Modal">
            <i class="fa-regular fa-plus tw-mr-1"></i>
            Thêm mới URI                    </a>
    </div>

    <div class="col-md-12">
        {{ $data['setting']->table(['id' => 'uri_manage'], $footer = false) }}
    </div>
</div>
<div id="showDetail_Modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body" id="showDetailBanner_Modal_body">
                <div class="row justify-content-md-center">
                    <div class="col-sm-12">
                        <form id="formDataUri">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="required_red_dot">Tên URI</label>
                                    <input type="text" name="name_uri" id="name_uri" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label class="required_red_dot">URI</label>
                                    <input type="text" name="uri" id="uri" class="form-control" autocomplete="off">
                                </div>
                            </div>
                            <div class="card-footer" style="text-align: right">
                                <button type="submit" id="submitAjax" class="btn btn-info">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    {!! $data['setting']->scripts() !!}

    <script>
        $(document).ready(function() {

            $('body').on('click', '#detail', function () {
                $('#uri').val($(this).data('uri'));
                $('#name_uri').val($(this).data('name'));
                $('#status').val($(this).data('status')).trigger('change');
            });

            $('body').on('click', '#submitAjax', function (e){
                e.preventDefault();
                let data = {};
                data.uri = $('#uri').val();
                data.name_uri = $('#name_uri').val();
                $.ajax({
                    url: '/setting/saveUriSetting',
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    cache: false,
                    success: (data) => {
                        console.log(data);
                        $("#spinner").removeClass("show");
                        alert_float(data.status,data.html);
                        $('#uri_manage').DataTable().ajax.reload();
                        $('#showDetail_Modal').modal('toggle');
                    },
                    error: function (xhr) {
                        $("#spinner").removeClass("show");
                        $('#submitAjax').prop('disabled', false);
                        var errorString = xhr.responseJSON.message;
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            errorString = value;
                            return false;
                        });
                        alert_float('danger', errorString);
                    }
                });
            });
        });
    </script>
@endpush
