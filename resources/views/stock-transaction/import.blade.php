@extends('layoutv2.layout.app')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
@section('content')
<div id="wrapper" style="min-height: 1405px;">
    <div class="content">
       <div class="row">
       <div class="container bg-white border rounded shadow-sm p-4 mt-3">
            <!-- Form POST -->
            <form action="{{ route('stock-transaction.storeImport') }}" method="POST">
                @csrf <!-- Include CSRF token for form security -->
                <!-- Header -->
                <div class="bg-light p-3 border-bottom mb-3">
                    <h4 class="mb-0">Tạo Phiếu Nhập Mới</h4>
                </div>

                <div class="row">
                    <!-- Danh sách sản phẩm -->
                    <div class="col-md-8 border-end">
                        <h5 class="text-secondary mb-3">Danh sách sản phẩm</h5>
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <select class="form-select w-auto" name="warehouse_code" id="warehouse_code" data-live-search="true" data-size="10">
                                @foreach ($data['list_warehouse'] as $warehouse)
                                <option value="{{$warehouse->warehouse_code}}">{{$warehouse->name}}</option>
                                @endforeach
                            </select>
                            <div class="flex-grow-1 d-flex align-items-center">
                            <select name="product_id" id="product_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                                <option value="">Vui lòng chọn sản phẩm</option>
                                @foreach ($data['list_product'] as $product)
                                <option value="{{ $product->id }}" 
                                        data-image="{{ $product->primaryImage ? asset($product->primaryImage->image_path) : '' }}" 
                                        data-content='
                                        <span>
                                            @if ($product->primaryImage)
                                                <img src="{{ asset($product->primaryImage->image_path) }}" style="width: 30px; height: 20px; margin-right: 10px;" />
                                            @endif
                                            {{ $product->name }}
                                        </span>'>
                                    {{ $product->name }}
                                </option>
                                @endforeach
                            </select>
                            </div>
                            <a class="btn btn-outline-primary" data-toggle="modal" data-target="#productModal">Chọn nhiều</a>  
                            <a class="btn btn-outline-secondary" onclick="addExcel(event)"><i class="fa fa-download"></i></a>
                        </div>

                        <!-- Bảng danh sách sản phẩm -->
                        <div class="bg-light p-2 rounded">
                            <ul class="list-unstyled d-flex justify-content-between mb-0">
                                <li class="fw-bold text-secondary">Sản phẩm</li>
                                <li class="fw-bold text-secondary text-center">Số lượng</li>
                            </ul>
                        </div>
                        <div id="selectedProductsList"></div>
                    </div>

                    <!-- Thông tin phiếu -->
                    <div class="col-md-4">
                        <h5 class="text-secondary mb-3">Thông tin phiếu</h5>
                        <div class="mb-3">
                            <label class="form-label">Nhà cung cấp:</label>
                            <div class="d-flex align-items-center gap-2">
                                <select class="form-select w-auto border" name="supplier_code" id="supplier_code" data-live-search="true" data-size="10">
                                    <option value="">Nhập nhà cung cấp..</option>
                                    @foreach ($data['list_supplier'] as $supplier)
                                        <option value="{{$supplier->supplier_code}}">{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
@include('template.modal', ['id' => 'showImportView_Modal', 'title'=>'Thêm Excel', 'form'=>'stock-transaction.import_view'])

<script>
    function addExcel(e) {
            e.preventDefault();
            $('#showImportView_Modal').modal('toggle');
            document.getElementById('formExcel').reset();
            window.urlMethod = '/stock-transaction/import-view';
            window.type = 'POST';
        }
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

document.addEventListener('DOMContentLoaded', function() {
    $('#productModal').on('show.bs.modal', function() {
        fetchProducts();
    });
});

function fetchProducts() {
    // Gọi API để lấy danh sách sản phẩm
    fetch('http://hi-admin-web.local/api/products')  // Thay đường dẫn này bằng API lấy sản phẩm của bạn
        .then(response => response.json())
        .then(data => {
            renderProductList(data);
        })
        .catch(error => console.error('Error fetching products:', error));
}

function renderProductList(products) {
    const productList = document.getElementById('productList');
    productList.innerHTML = ''; // Xóa các sản phẩm cũ (nếu có)

    products.forEach(product => {
        const productItem = document.createElement('div');
        productItem.className = 'col-md-12 mb-3';
        productItem.innerHTML = `
            <div class="card product-item">
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="product-checkbox me-3" data-product-id="${product.id}" data-product-name="${product.name}" />
                    <img src="" class="product-image me-3" alt="${product.name}" />
                    <h5 class="card-title mb-0">${product.name}</h5>
                </div>
            </div>
        `;
        
        // Lấy phần tử <img> của sản phẩm
        const imgElement = productItem.querySelector('img');

        // Kiểm tra xem sản phẩm có hình ảnh chính (primary_image) hay không
        if (product.primary_image && product.primary_image.image_path) {
            imgElement.src = product.primary_image.image_path; // Gán đường dẫn hình ảnh vào thẻ <img>
        } else {
             // Nếu không có hình ảnh, thay thế với "Image not available"
            imgElement.src = "{{asset('images/image_holder.png')}}"; // Gán hình ảnh mặc định nếu không có hình ảnh
        }

        productList.appendChild(productItem);
    });

    // Cập nhật lại selectpicker (nếu có)
    $('.selectpicker').selectpicker('refresh');
}





// Function to remove a selected product from the list
function removeProduct(button) {
    button.parentElement.remove();
    updateProductCount(-1);
}
function addSelectedProducts() {
    const selectedProducts = [];
    document.querySelectorAll('#productList .product-checkbox:checked').forEach(checkbox => {
        selectedProducts.push({
            id: checkbox.getAttribute('data-product-id'),
            name: checkbox.getAttribute('data-product-name'),
            image: checkbox.getAttribute('data-image') // Lấy đường dẫn hình ảnh
        });
    });

    if (selectedProducts.length > 0) {
        const productList = document.getElementById('selectedProductsList');
        selectedProducts.forEach(product => {
            if (!productList.querySelector(`[data-product-id="${product.id}"]`)) {
                const productItem = document.createElement('div');
                const unitOptions = @json($data['list_unit']).map(unit => `<option value="${unit.unit_code}">${unit.unit_name}</option>`).join('');
                productItem.className = 'd-flex justify-content-between align-items-center py-2 border-bottom';
                productItem.setAttribute('data-product-id', product.id);
                productItem.innerHTML = `
                    <div class="d-flex align-items-center">
                        <img src="${product.image}" alt="${product.name}" style="width: 40px; height: 40px; object-fit: cover;" class="me-3">
                        <span>${product.name.length > 20 ? product.name.substring(0, 30) + '...' : product.name}</span>
                    </div>
                    <select name="products[${product.id}][unit_code]" class="form-control w-auto">
                        <option value="">Chọn đơn vị</option>
                        ${unitOptions}
                    </select>
                    <div class="d-flex align-items-center">
                       <input type="number" name="products[${product.id}][quantity]" class="form-control w-15" value="1" min="1" />
                        <input type="hidden" name="products[${product.id}][id]" value="${product.id}" />
                        <button class="btn btn-outline-danger btn-sm" onclick="removeProduct(${product.id})">Xóa</button>
                    </div>
                `;
                productList.appendChild(productItem);
                updateProductCount(1);

            }
        });
        $('#productModal').modal('hide');
    }
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
