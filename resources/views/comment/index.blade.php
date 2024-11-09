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
                                {{ $dataTable->table(['id' => 'comment_manage'], $footer = false) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.modal', ['id' => 'showDetail_Modal', 'title'=>'Bình luận', 'form'=>'comment.list'])

@endsection
@push('script')
    {{ $dataTable->scripts() }}
    <script>
        
        function dialogConfirmWithAjax(sureCallbackFunction, data, text = "Xin hay kiểm tra lại") {
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
                    let table = $('#brand_manage').DataTable();
                    table.ajax.reload(null, false);
                }
            });
        }

    function changeStatusComment(data){
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/comment/change-status', dataPost).done(function(response) {
                alert_float('success', response.message);
                $('#comment_manage').DataTable().ajax.reload(null,false);
            });
        }
        function deleteComment(data) {
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/comment/destroy', dataPost).done(function (response) {
                alert_float('success', response.message);
                let table = $('#comment_manage').DataTable();
                table.ajax.reload(null, false);
            });
        }

        function detailComment(_this) {
    let dataPost = {};
    dataPost.id = $(_this).data('id');

    $.post('/comment/show', dataPost).done(function (response) {
        // Đặt giá trị cho các trường trong modal
        $('#customer_id').val(response.customer_name);
        $('#product_id').val(response.product_name);
        $('#content').val(response.content);
        
        // Hiển thị số sao tương ứng với `rating`
        let rating = response.rating;
        let starContainer = $('#star-rating');
        starContainer.empty(); // Xóa các ngôi sao cũ
        
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                starContainer.append('<i class="fa fa-star checked"></i>'); // Ngôi sao đầy
            } else {
                starContainer.append('<i class="fa fa-star"></i>'); // Ngôi sao rỗng
            }
        }

        // Hiển thị modal
        $('#showDetail_Modal').modal('toggle');
    });
}



    </script>
    <style>
        .star-rating .fa-star {
    color: #ddd;
    font-size: 20px;
}

.star-rating .fa-star.checked {
    color: #ffd700;
}

    </style>
@endpush
