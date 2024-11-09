<div class="row">
    <div class="col-md-12">
        <form id="formProductUnits" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
        @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="product_id">Sản phẩm</label>
                        <select name="product_id" id="product_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                            <option value="">Vui lòng chọn sản phẩm</option>
                            @foreach ($data['list_product'] as $product)
                                <option value="{{ $product->id }}" data-content='
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

                    <div class="form-group">
                        <label for="unit_code">Đơn vị</label>
                        <select name="unit_code" id="unit_code" class="form-control selectpicker" data-live-search="true" data-size="10">
                            <option value="">Vui lòng chọn đơn vị</option>
                            @foreach ($data['list_unit'] as $unit)
                                <option value="{{ $unit->unit_code }}" >{{ $unit->unit_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Giá</label>
                        <input type="number" id="price" name="price" class="form-control" placeholder="Giá" oninput="formatPrice(this)">
                    </div>
                    <div class="form-group">
                        <label for="price_sale">Giá giảm</label>
                        <input type="number" id="price_sale" name="price_sale" class="form-control" placeholder="Giá giảm" oninput="formatPrice(this)">
                    </div>

                    <div class="form-group">
                        <label for="level">Cấp độ</label>
                        <input type="text" id="level" name="level" class="form-control" placeholder="Cấp độ">
                    </div>
                    <div class="form-group">
                        <label for="exchangrate">Tỷ lệ quy đổi</label>
                        <input type="text" id="exchangrate" name="exchangrate" class="form-control" placeholder="Tỷ lệ quy đổi">
                    </div>
                </div>
            </div>
        </form>
        <div class="model-footer" style="float: right">
            <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
            <button type="button" onclick="pushProductUnits()" class="btn btn-info">Submit</button>
        </div>
    </div>
</div>
