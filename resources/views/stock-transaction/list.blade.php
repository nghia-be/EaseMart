<div class="row">
    <div class="col-md-12">
        <form id="showDetail_Modal" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
        @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-body">
                        <!-- Các sản phẩm sẽ được hiển thị ở đây -->
                    </div>
                    <input type="hidden" name="product_id" id="product_id" >
                    <div class="form-group">
                        <label for="unit_code">Đơn vị</label>
                        <select name="unit_code" id="unit_code" class="form-control selectpicker" data-live-search="true" data-size="10">
                            <option value="">Vui lòng chọn đơn vị</option>
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
