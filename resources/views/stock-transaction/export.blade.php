@extends('layoutv2.layout.app')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
@section('content')
<div id="wrapper" style="min-height: 1405px;">
    <div class="content">
       <div class="row">
       <div class="container bg-white border rounded shadow-sm p-4 mt-3">
            <!-- Form POST -->
            <form action="{{ route('stock-transaction.storeExport') }}" method="POST">
                @csrf <!-- Include CSRF token for form security -->
                <!-- Header -->
                <div class="bg-light p-3 border-bottom mb-3">
                    <h4 class="mb-0">Tạo Phiếu Xuất Mới</h4>
                </div>

                <div class="row">
                    <!-- Danh sách sản phẩm -->
                    <div class="col-md-8 border-end">
                        <h5 class="text-secondary mb-3">Danh sách sản phẩm</h5>
                        <div class="d-flex align-items-center gap-2 mb-4">
                        <select class="form-select w-auto" name="warehouse_code" id="warehouse_code" data-live-search="true" data-size="10">
                            @foreach ($data['list_warehouse'] as $index => $warehouse)
                            <option value="{{ $warehouse->warehouse_code }}" {{ $index === 0 ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                            @endforeach
                        </select>

                            <div class="flex-grow-1 d-flex align-items-center">
                            <select name="product_id" id="product_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                                <option value="">Vui lòng chọn sản phẩm</option>
                            </select>
                            </div>
                            <a class="btn btn-outline-primary" data-toggle="modal" data-target="#productModal">Chọn nhiều</a>  
                            <button class="btn btn-outline-secondary"><i class="fa fa-download"></i></button>
                        </div>

                        <!-- Bảng danh sách sản phẩm -->
                        <div class="bg-light p-2 rounded">
                            <ul class="list-unstyled d-flex justify-content-between mb-0">
                                <li class="fw-bold text-secondary">Sản phẩm</li>
                                <li class="fw-bold text-secondary text-center">Tồn kho sẵn có</li>
                                <li class="fw-bold text-secondary">Số lượng</li>
                            </ul>
                        </div>
                        <div id="selectedProductsList"></div>
                    </div>

                    <!-- Thông tin phiếu -->
                    <div class="col-md-4">
                        <h5 class="text-secondary mb-3">Thông tin phiếu</h5>
                        <!--  -->
                        <div>
                            <label class="form-label">Ghi chú:</label>
                            <textarea class="form-control" name="note" rows="4" placeholder="Nhập ghi chú..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <span class="fw-semibold text-secondary">Tổng sản phẩm: <span id="totalProductCount" class="text-muted">0 sp</span></span>
                    <div>
                    <button type="button" class="btn btn-outline-danger me-2" onclick="window.location.href='/stock-transaction'">Quay lại</button>
                        <button type="button" class="btn btn-outline-danger me-2">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo phiếu</button>
                    </div>
                </div>
            </form>
            <!-- End Form -->
        </div>
       </div>
    </div>
</div>
<!-- Modal Chọn Nhiều Sản Phẩm -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Chọn Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="productSearch" class="form-control mb-3" placeholder="Tìm kiếm sản phẩm" oninput="filterProducts()" />
                <div id="productList" class="row"></div>
            </div>
            <div class="modal-footer d-flex">
                
            <div class="form-check me-auto">
                <input type="checkbox" class="form-check-input" id="selectAllCheckbox" onclick="toggleSelectAllProducts(this)">
                <label class="form-check-label" for="selectAllCheckbox">Chọn tất cả</label>
            </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearSelectedCheckboxes()">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="addSelectedProducts()">Thêm vào đơn</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleSelectAllProducts(selectAllCheckbox) {
    const checkboxes = document.querySelectorAll('#productList input[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function clearSelectedCheckboxes() {
    const checkboxes = document.querySelectorAll('#productList input[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
    });
}

function openModal() {
    $('#productModal').modal('show');
}

function filterProducts() {
    const searchValue = document.getElementById('productSearch').value.toLowerCase();
    document.querySelectorAll('#productList .d-flex').forEach(item => {
        const productName = item.querySelector('span').textContent.toLowerCase();
        item.style.display = productName.includes(searchValue) ? 'flex' : 'none';
    });
}

function dialogConfirmWithAjax(sureCallbackFunction, data, text = "Bạn có chắc chắn?") {
    Swal.fire({
        title: 'Bạn có chắc chắn?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#d33',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Tiếp tục', // Văn bản nút xác nhận
        cancelButtonText: 'Hủy'          // Văn bản nút hủy
    }).then((result) => {
        if (result.isConfirmed) {
            sureCallbackFunction(data);
        }
    });
}

        document.addEventListener('DOMContentLoaded', function () {
    const warehouseDropdown = document.getElementById('warehouse_code');
    
    // Kích hoạt sự kiện 'change' trên dropdown để tự động lấy sản phẩm của kho đầu tiên
    if (warehouseDropdown.value) {
        warehouseDropdown.dispatchEvent(new Event('change'));
    }
});

document.getElementById('warehouse_code').addEventListener('change', function () {
    const warehouseCode = this.value;
    const productDropdown = document.getElementById('product_id');
    const productList = document.getElementById('selectedProductsList');

    // Kiểm tra xem có sản phẩm nào đã được chọn hay không
    if (productList.children.length > 0) {
        // Hiển thị cảnh báo xác nhận
        dialogConfirmWithAjax(
            // Hàm callback khi người dùng xác nhận tiếp tục
            function () {
                // Xóa hết sản phẩm đã chọn
                productList.innerHTML = '';

                // Cập nhật lại tổng số lượng sản phẩm đã chọn về 0
                updateProductCount(-productList.children.length);

                // Tải sản phẩm mới theo kho đã chọn
                loadProductsByWarehouse(warehouseCode, productDropdown);
            },
            {},
            "Thao tác này của bạn sẽ xóa hết sản phẩm bạn đã chọn. Bạn có muốn tiếp tục?"
        );
    } else {
        // Nếu không có sản phẩm đã chọn, tải sản phẩm mới
        loadProductsByWarehouse(warehouseCode, productDropdown);
    }
});

// Hàm tải sản phẩm theo kho
// Hàm tải sản phẩm theo kho và hiển thị trong modal
function loadProductsByWarehouse(warehouseCode, productDropdown) {
    const productList = document.getElementById('productList');
    productList.innerHTML = ''; // Xóa các sản phẩm cũ trong modal
    productDropdown.innerHTML = '<option value="">Vui lòng chọn sản phẩm</option>';
    if (warehouseCode) {
        fetch(`http://hi-admin-web.local/api/products-by-warehouse/${warehouseCode}`)
            .then(response => response.json())
            .then(products => {
                if (Array.isArray(products)) {
                    products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.setAttribute('data-image', product.primary_image ? product.primary_image.image_path : '');
                        option.textContent = product.name;
                        const productItem = document.createElement('div');
                        productItem.className = 'col-md-12 mb-3';
                        productItem.innerHTML = `
                            <div class="card product-item">
                                <div class=" d-flex align-items-center">
                                    <input type="checkbox" class="product-checkbox me-3" data-product-id="${product.id}" data-product-name="${product.name}" />
                                    <img src="${product.primary_image ? product.primary_image.image_path : ''}" class="product-image me-3" alt="${product.name}" />
                                    <h5 class="card-title mb-0">${product.name}</h5>
                                </div>
                            </div>

                        `;
                        productDropdown.appendChild(option);
                        productList.appendChild(productItem);
                    });
                }
                $('.selectpicker').selectpicker('refresh');
            })
            .catch(error => console.error('Error fetching products:', error));
    }
}

// Hàm tìm kiếm sản phẩm trong modal
function filterProducts() {
    const searchValue = document.getElementById('productSearch').value.toLowerCase();
    document.querySelectorAll('#productList .card').forEach(item => {
        const productName = item.querySelector('.card-title').textContent.toLowerCase();
        item.style.display = productName.includes(searchValue) ? 'block' : 'none';
    });
}

// Hàm thêm sản phẩm đã chọn vào đơn
function addSelectedProducts() {
    const selectedProducts = [];
    document.querySelectorAll('#productList .product-checkbox:checked').forEach(checkbox => {
        selectedProducts.push({
            id: checkbox.getAttribute('data-product-id'),
            name: checkbox.getAttribute('data-product-name'),
            image: checkbox.getAttribute('data-image') 
        });
    });

    if (selectedProducts.length > 0) {
        const productList = document.getElementById('selectedProductsList');
        
        selectedProducts.forEach(product => {
            // Kiểm tra xem sản phẩm đã có trong danh sách chưa
            if (!productList.querySelector(`[data-product-id="${product.id}"]`)) {
                
                const productItem = document.createElement('div');
                
                productItem.className = 'd-flex justify-content-between align-items-center py-2 border-bottom';
                productItem.setAttribute('data-product-id', product.id);
                const unitOptions = @json($data['list_unit']).map(unit => `<option value="${unit.unit_code}">${unit.unit_name}</option>`).join('');
                productItem.innerHTML = `
                    <div class="d-flex align-items-center">
                     <img src="${product.image}" alt="${product.name}" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;" />
                        <span>${product.name}</span>
                    </div>
                    <select name="products[${product.id}][unit_code]" class="form-control w-auto">
                        <option value="">Chọn đơn vị</option>
                        ${unitOptions}
                    </select>
                    <input type="number" name="products[${product.id}][quantity]" class="form-control w-25" value="1" min="1" />
                    <input type="hidden" name="products[${product.id}][id]" value="${product.id}" />
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProduct(this)">Xóa</button>
                `;
                productList.appendChild(productItem);

                // Cập nhật tổng số sản phẩm
                updateProductCount(1); // Tăng số lượng sản phẩm thêm 1
            }
        });
    }

    // Đóng modal
    $('#productModal').modal('hide');
}
document.querySelectorAll('#productList .product-checkbox').forEach(checkbox => {
        if (selectedProducts.some(product => product.id === checkbox.getAttribute('data-product-id'))) {
            checkbox.checked = false;
            checkbox.disabled = true; // Vô hiệu hóa checkbox để tránh chọn lại sản phẩm đã thêm
        }
    });

// Hàm xóa sản phẩm khỏi danh sách đã chọn
function removeProduct(button) {
    button.parentElement.remove();
}


// Function to update the total product count






// JavaScript for handling product selection
document.getElementById('product_id').addEventListener('change', function () {
    const productDropdown = this;
    const selectedProductId = productDropdown.value;
    const selectedProductText = productDropdown.options[productDropdown.selectedIndex].text;
    const selectedProductImage = productDropdown.options[productDropdown.selectedIndex].getAttribute('data-image');

    if (selectedProductId) {
        const productList = document.getElementById('selectedProductsList');

        // Check if the product is already added
        if (!productList.querySelector(`[data-product-id="${selectedProductId}"]`)) {
            // Create a new element for the selected product
            const unitOptions = @json($data['list_unit']).map(unit => `<option value="${unit.unit_code}">${unit.unit_name}</option>`).join('');
            const productItem = document.createElement('div');
            productItem.className = 'd-flex justify-content-between align-items-center py-2 border-bottom';
            productItem.setAttribute('data-product-id', selectedProductId);
            productItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${selectedProductImage}" alt="${selectedProductText}" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;" />
                    <span>${selectedProductText}</span>
                </div>
                <select name="products[${selectedProductId}][unit_code]" class="form-control w-auto">
                    <option value="">Chọn đơn vị</option>
                    ${unitOptions}
                </select>
                    </select>
                <input type="hidden" name="products[${selectedProductId}][id]" value="${selectedProductId}" />
                <input type="number" name="products[${selectedProductId}][quantity]" class="form-control w-25" value="1" min="1" />
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProduct(this)">Xóa</button>
            `;

            productList.appendChild(productItem);

            // Update the total product count
            updateProductCount(1);
        }
    }
});

// Function to remove a selected product from the list
function removeProduct(button) {
    button.parentElement.remove();
    updateProductCount(-1);
}

// Function to update the total product count
function updateProductCount(change) {
    const totalProductCountElement = document.getElementById('totalProductCount');
    let currentCount = parseInt(totalProductCountElement.innerText);
    currentCount += change;
    totalProductCountElement.innerText = `${Math.max(currentCount, 0)} sp`;
}
</script>
<style>
    #productList {
    display: flex;
    flex-direction: column;
}


.product-item input[type="checkbox"] {
    width: 15px; /* Kích thước checkbox */
    height: 20px;
}

.product-item img {
    width: 40px; /* Kích thước hình ảnh */
    height: 20x;
    object-fit: cover;
}

.product-item .product-name {
    font-size: 16px;
    font-weight: 600;
}

.product-item .product-checkbox {
    margin-right: 10px;
}
.product-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 5px;
    display: flex;
    
}

.product-checkbox {
    width: 20px;
    height: 20px;
    margin-right: 5px; /* Khoảng cách giữa checkbox và hình ảnh */
}

.product-image {
    width: 40px;
    height: 40px;
    object-fit: cover;
    margin-right: 5px; /* Khoảng cách giữa hình ảnh và tên sản phẩm */
}

.card-title {
    font-size: 16px;
    margin: 0; /* Xóa khoảng trắng bên dưới tên sản phẩm */
}



</style>
@endsection
