<div class="row">
    <div class="col-md-12">
        <form id="showDetail_Modal" novalidate="novalidate" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="customer_id">Khách hàng</label>
                        <input type="text" id="customer_id" name="customer_id" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="product_id">Sản phẩm</label>
                        <input type="text" id="product_id" name="product_id" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Đánh giá</label>
                        <div id="star-rating" name="rating" class="star-rating">
                        <input type="text" id="rating" name="rating" class="form-control" readonly>
                            <!-- Nơi hiển thị các ngôi sao -->
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group" app-field-wrapper="name">
                        <label for="content" class="control-label">Mô tả</label>
                        <textarea id="content" name="content" class="form-control" rows="7" cols="200" readonly></textarea>  
                    </div>
                </div>
            </div>
        </form>
        <div class="model-footer" style="float: right">
            <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
