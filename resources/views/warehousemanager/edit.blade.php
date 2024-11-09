@extends('layoutv2.layout.app')

@section('content')
<div id="wrapper" style="min-height: 1405px;">
    <div class="content">
        <div class="row">
            <form action="{{ route('products.update', [$products->id]) }}" class="staff-form dirty" autocomplete="off"
                  enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
                @csrf
                @method('PUT')
                <input type="text" name="role_id" class="hide" value="{{ $products->role_id }}">
                <div class="col-md-8 col-md-offset-2" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body">
                            <!-- Các trường thông tin sản phẩm -->
                            <div class="form-group" app-field-wrapper="name">
                                <label for="name" class="control-label">
                                    <small class="req text-danger">* </small>Tên sản phẩm</label>
                                <input type="text" id="name" name="name" class="form-control"
                                       autofocus="1" value="{{ $products->name }}">
                            </div>
                            <div class="form-group" app-field-wrapper="quantity">
                                    <label for="quantity" class="control-label">
                                        <small class="req text-danger">* </small>Số lượng
                                    </label>
                                    <input type="number" value="{{ $products->quantity }}" id="quantity" name="quantity" class="form-control" min="1" required>
                            </div>
                            <div class="form-group" app-field-wrapper="price_old">
                                <label for="price_old" class="control-label">
                                    <small class="req text-danger">* </small>Giá</label>
                                <div class="input-group">
                                    <input type="text" id="price" name="price" class="form-control"
                                           autocomplete="off" value="{{ $products->price }}">
                                    <span class="input-group-addon">
                                        <i class="fa-solid fa-dong-sign"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group" app-field-wrapper="price_sale">
                                <label for="price_sale" class="control-label">
                                    <small class="req text-danger">* </small>Giá giảm</label>
                                <div class="input-group">
                                    <input type="text" id="price_sale" name="price_sale" class="form-control"
                                           autocomplete="off" value="{{ $products->price_sale }}">
                                    <span class="input-group-addon">
                                        <i class="fa-solid fa-dong-sign"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Các trường danh mục và thương hiệu -->
                            <div class="form-group" app-field-wrapper="categories">
                                <label for="categories" class="control-label">Danh mục</label>
                                <div class="dropdown bootstrap-select bs3" style="width: 100%;">
                                    <select id="categories_id" name="categories_id" class="selectpicker" data-width="100%"
                                            data-none-selected-text="Không có mục nào được chọn"
                                            data-live-search="true" tabindex="-98">
                                        <option value=""></option>
                                        @foreach($data['list_categories'] as $categories)
                                            <option value="{{ $categories->id }}"
                                                {{ $categories->id == $products->categories_id ? 'selected' : '' }}
                                                data-content='<span><img src="{{ asset($categories->image ? $categories->image : "images/image_holder.png" ) }}" style="width: 30px; height: 20px; margin-right: 10px;" /> {{ $categories->categories_name }}</span>'>
                                                {{ $categories->categories_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" app-field-wrapper="brand">
                                <label for="brand" class="control-label">Thương hiệu</label>
                                <div class="dropdown bootstrap-select bs3" style="width: 100%;">
                                    <select id="brand_id" name="brand_id" class="selectpicker" data-width="100%"
                                            data-none-selected-text="Không có mục nào được chọn"
                                            data-live-search="true" tabindex="-98">
                                        <option value=""></option>
                                        @foreach($data['list_brand'] as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ $brand->id == $products->brand_id ? 'selected' : '' }}
                                                data-content='<span><img src="{{ asset($brand->image) }}" style="width: 30px; height: 20px; margin-right: 10px;" /> {{ $brand->name }}</span>'>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Trường mô tả sản phẩm -->
                            <div class="form-group" app-field-wrapper="description">
                                <label for="description" class="control-label">
                                    <small class="req text-danger">* </small>Mô tả</label>
                                <textarea id="description" name="description" class="form-control" rows="5">{{ $products->description }}</textarea>
                            </div>

                            <!-- Trường upload hình ảnh -->
                            <div class="form-group" id="path_1">
                                <label class="control-label"><small class="req text-danger">* </small>Ảnh</label>
                                <input type="file" accept="image/*" name="path_1[]" class="form-control" multiple id="uploadImage"/>
                                <div id="img_container_1" style="padding:10px;margin-top:10px; display: flex; flex-wrap: wrap;">
                                    @foreach($products->images as $index => $image)
                                        <div class="existing-image" data-image-id="{{ $image->id }}" style="display: inline-block; margin: 5px; position: relative;">
                                            <img src="{{ asset($image->image_path) }}" width="100" height="100" 
                                                style="border: 2px solid #007bff; cursor: pointer;"
                                                onclick="setPrimaryImage('{{ $image->id }}', this)"/>
                                            @if($image->is_primakey)
                                                <span class="primary-star" style="position: absolute; top: 5px; left: 5px; color: gold; font-size: 20px;">★</span>
                                            @endif
                                            <button type="button" class="btn btn-danger btn-sm" style="position: absolute; bottom: 0; right: 0;" onclick="removeImage(this)">Xóa</button>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" id="img_path_1_names" name="img_path_1_names" value="{{ $products->images->pluck('id')->join(',') }}">
                                <input type="hidden" id="deleted_images" name="deleted_images" value="">
                            </div>

                            <!-- Trường trạng thái sản phẩm -->
                            <div class="form-group" app-field-wrapper="status">
                                <label class="control-label">Trạng thái</label>
                                <div>
                                    <label>
                                        <input type="checkbox" name="status" value="1" {{ $products->status ? 'checked' : '' }}> Kích hoạt
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-bottom-toolbar text-right">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                    <a href="{{ route('products.index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Thêm mã JavaScript vào đây -->
<script>
    let images = [];
    let selectedImages = [];
    let primaryImage = null;

    // Lưu trữ các hình ảnh mới
    let newImageIds = [];
function setPrimaryImage(imageId, imgElement) {
    // Bỏ ngôi sao khỏi tất cả ảnh khác
    let selectedImages = [];
    document.querySelectorAll('.primary-star').forEach(star => star.remove());

    // Cập nhật is_primary cho các ảnh khác thành 0
    selectedImages = selectedImages.map(id => {
        if (id === imageId) {
            return { id: imageId, is_primakey: 1 };
        }
        return { id, is_primakey: 0 };
    });

    // Thêm ngôi sao vào ảnh đã chọn
    const starElement = document.createElement('span');
    starElement.className = 'primary-star';
    starElement.style.position = 'absolute';
    starElement.style.top = '5px';
    starElement.style.left = '5px';
    starElement.style.color = 'gold';
    starElement.style.fontSize = '20px';
    starElement.innerText = '★';
    imgElement.parentElement.appendChild(starElement);

    // Lưu id ảnh chính vào input ẩn
    document.getElementById('img_path_1_names').value = selectedImages.map(img => img.id).join(',');
}

// Biến lưu trữ hình ảnh và trạng thái


// Tạo hàm xử lý tải lên hình ảnh
document.getElementById('uploadImage').addEventListener('change', function(event) {
    const files = event.target.files;
    const imgContainer = document.getElementById('img_container_1');
    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = '100px';
            img.style.height = '100px';
            img.style.border = '2px solid #007bff';
            img.style.cursor = 'pointer';

            const imageDiv = document.createElement('div');
            imageDiv.className = 'existing-image';
            imageDiv.style.display = 'inline-block';
            imageDiv.style.margin = '5px';
            imageDiv.style.position = 'relative';
            imageDiv.appendChild(img);

            const imageId = 'new_' + (index + 1); // Sử dụng ID giả để hình ảnh mới
            newImageIds.push(imageId); // Lưu ID hình ảnh mới

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-danger btn-sm';
            removeBtn.style.position = 'absolute';
            removeBtn.style.bottom = '0';
            removeBtn.style.right = '0';
            removeBtn.innerText = 'Xóa';
            removeBtn.onclick = function() {
                imgContainer.removeChild(imageDiv);
                // Xóa ID hình ảnh mới nếu nó được xóa
                newImageIds = newImageIds.filter(id => id !== imageId);
                updateHiddenInput(); // Cập nhật input ẩn mỗi khi xóa
            };
            imageDiv.appendChild(removeBtn);
            imgContainer.appendChild(imageDiv);
        }
        reader.readAsDataURL(file);
    });

    // Cập nhật input ẩn với các ID hình ảnh mới
    updateHiddenInput();
});

// Cập nhật giá trị của input ẩn với các ID hình ảnh
function updateHiddenInput() {
    const allIds = [...selectedImages.map(img => img.id), ...newImageIds];
    document.getElementById('img_path_1_names').value = allIds.join(',');
}

// Hàm xóa hình ảnh đã có
function removeImage(button) {
    const imageDiv = button.parentElement;
    const imageId = imageDiv.getAttribute('data-image-id');
    // Thêm ID hình ảnh đã xóa vào input ẩn
    const deletedImages = document.getElementById('deleted_images');
    deletedImages.value = deletedImages.value ? deletedImages.value + ',' + imageId : imageId;

    // Xóa hình ảnh khỏi DOM
    imageDiv.parentNode.removeChild(imageDiv);

    // Cập nhật input ẩn với các ID hình ảnh còn lại
    updateHiddenInput();
}
</script>
@endsection
