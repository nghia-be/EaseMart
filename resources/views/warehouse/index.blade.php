@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="_buttons">
                        <a onclick="addCategories(event)" class="btn btn-primary mright5 test pull-left display-block">
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
                                {{ $dataTable->table(['id' => 'warehouse_manage'], $footer = false) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.modal', ['id' => 'showCreated_Modal', 'title'=>'Thêm kho hàng', 'form'=>'warehouse.create'])
@endsection
@push('script')
    {{ $dataTable->scripts() }}
    <script>
//         let map, marker, autocomplete;

// function initMap() {
//     const defaultLocation = { lat: 10.8231, lng: 106.6297 }; // Vị trí mặc định là Hà Nội

//     // Khởi tạo bản đồ
//     map = new google.maps.Map(document.getElementById("map"), {
//         center: defaultLocation,
//         zoom: 15,
//     });

//     // Khởi tạo marker
//     marker = new google.maps.Marker({
//         position: defaultLocation,
//         map: map,
//         draggable: true,
//     });

//     // Tự động gợi ý địa chỉ khi nhập
//     const addressInput = document.getElementById("address");
//     autocomplete = new google.maps.places.Autocomplete(addressInput);
    
//     // Khi người dùng chọn địa chỉ từ gợi ý
//     autocomplete.addListener("place_changed", () => {
//         const place = autocomplete.getPlace();
//         if (place.geometry) {
//             const location = place.geometry.location;
//             map.setCenter(location);
//             map.setZoom(15);
//             marker.setPosition(location);
//         } else {
//             alert("Không tìm thấy địa chỉ.");
//         }
//     });

//     // Cập nhật vị trí bản đồ khi kéo marker
//     marker.addListener("dragend", () => {
//         const position = marker.getPosition();
//         map.panTo(position);
//     });
// }

// // Khởi tạo bản đồ khi tải trang
// window.onload = initMap;
        function deleteWarehouse(data) {
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/warehouse/destroy', dataPost).done(function (response) {
                alert_float('success', response.message);
                let table = $('#warehouse').DataTable();
                table.ajax.reload(null, false);
            });
        }

        function addCategories(e) {
            e.preventDefault();
            $('#showCreated_Modal').modal('toggle');
            document.getElementById('formWarehouse').reset();
            window.urlMethod = '/warehouse/store';
            window.type = 'POST';
        }

        function detailWarehouse(_this) {
            let dataPost = {};
            dataPost.id = $(_this).data('id'); // Lấy ID từ data attribute
            $.post('/warehouse/show', dataPost) // Gửi yêu cầu AJAX
                .done(function (response) {
            console.log(response.data);
            for (let [key, value] of Object.entries(response.data)) {
                let k = $('#' + key); // Lấy phần tử input theo ID
                k.val(value); // Gán giá trị cho input
                k.trigger('change'); // Kích hoạt sự kiện change
            }
            $('#showCreated_Modal').modal('toggle'); // Hiển thị modal
            window.urlMethod = '/warehouse/update/' + dataPost.id; // Đường dẫn cho cập nhật
            window.type = 'PUT'; // Phương thức PUT
        });
}

function pushWarehouse() {
    $(this).attr('disabled', 'disabled'); // Vô hiệu hóa nút để tránh nhiều lần nhấn
    let data = $('#formWarehouse').serialize(); // Lấy dữ liệu từ form
    $.ajax({
        url: urlMethod, // Đường dẫn API
        type: window.type, // Phương thức (POST hoặc PUT)
        dataType: 'json',
        data: data,
        cache: false,
        success: (response) => {
            if (response.success) {
                $('#showCreated_Modal').modal('toggle'); // Đóng modal
                alert_float('success', response.message); // Hiển thị thông báo thành công
                
                // Cập nhật dữ liệu trong DataTable mà không cần reload lại trang
                let table = $('#warehouse_manage').DataTable();
                table.ajax.reload(null, false); // Tải lại bảng với dữ liệu mới
            } else {
                alert_float('danger', response.message); // Hiển thị thông báo lỗi
            }
        },
        error: function (xhr) {
            let errorString = xhr.responseJSON.message ?? '';
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString = value; // Lấy lỗi đầu tiên
                return false; // Dừng vòng lặp sau khi có lỗi
            });
            alert_float('danger', errorString); // Hiển thị thông báo lỗi
        },
        complete: function () {
            $('#submit').prop('disabled', false); // Kích hoạt lại nút submit
        }
    });
}

    </script>
    <style>
    .suggestions {
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
        display: none; /* Ẩn danh sách gợi ý mặc định */
        position: absolute;
        background-color: white;
        z-index: 1000;
    }
    .suggestion-item {
        padding: 10px;
        cursor: pointer;
    }
    .suggestion-item:hover {
        background-color: #f0f0f0;
    }
</style>
@endpush
