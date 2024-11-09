@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="_buttons">
                        <!-- Dropdown Button for "Thêm xuất nhập" -->
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" onclick="toggleDropdown()">
                                <i class="fa-regular fa-plus"></i> Thêm xuất nhập
                            </button>
                            <div id="dropdownMenu" class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('stock-transaction.import') }}">Tạo phiếu nhập</a>
                                
                                <a class="dropdown-item" href="{{ route('stock-transaction.export') }}">Tạo phiếu xuất</a>
                               
                            </div>
                        </div>

                        <!-- Separate "Liên hệ" Button -->
                        <a href="#" onclick="alert('Liên hệ nghiatd12@fpt.com nếu xảy ra lỗi không mong muốn!')" class="btn btn-default mright5">
                            <i class="fa-regular fa-user"></i> Liên hệ
                        </a>

                        <div class="visible-xs">
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="panel_s tw-mt-2 sm:tw-mt-4">
                        <div class="panel-body">
                            <div class="panel-table-full">
                                {{ $dataTable->table(['id' => 'stock-transaction_manage'], $footer = false) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.modal', ['id' => 'showDetail_Modal', 'title'=>'Chi tiết giao dịch', 'form'=>'stock-transaction.list'])
@endsection


@push('script')
    {{ $dataTable->scripts() }}
    <script>

        
        function detailTransaction(_this) {
    let dataPost = {};
    dataPost.id = $(_this).data('id');
    
    // Gửi yêu cầu để lấy chi tiết giao dịch
    $.post('/stock-transaction/show', dataPost).done(function (response) {
        // Kiểm tra nếu có dữ liệu
        if (response.data) {
            console.log(response.data);
            
            // Điền dữ liệu vào các phần tử trong modal
            for (let [key, value] of Object.entries(response.data)) {
                let k = $('#' + key);  // Ví dụ: #transaction_code
                k.val(value);           // Điền giá trị vào trường
                k.trigger('change');    // Kích hoạt sự kiện thay đổi (nếu cần)
            }
            $('#showDetail_Modal').modal('toggle');
            // Hiển thị modal
            $('#showDetail_Modal').modal('show');
            window.type = 'POST'; 
        } else {
            alert('Không tìm thấy giao dịch.');
        }
    }).fail(function() {
        alert('Có lỗi xảy ra khi lấy thông tin.');
    });
}

        // Toggle Dropdown Menu
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.closest('.dropdown')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].classList.remove('show');
                }
            }
        }
        // Hàm để hiển thị thông báo SweetAlert2


    </script>
@endpush

<style>
    /* General wrapper and layout settings */
    #wrapper {
        position: relative;
    }

    .content {
        padding: 20px;
    }

    /* Dropdown styling */
    .dropdown {
        position: relative;
        display: inline-block;
        z-index: 1000; /* Ensure the dropdown button is above other elements */
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 200px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        border-radius: 4px;
        margin-top: 5px;
        z-index: 2000; /* Ensure the dropdown menu is above other elements */
    }

    .dropdown-menu a {
        color: black;
        padding: 10px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-menu a:hover {
        background-color: #ddd;
    }

    /* Show the dropdown when .show class is added */
    .dropdown-menu.show {
        display: block;
    }

    .dropdown-toggle {
        cursor: pointer;
    }
</style>
