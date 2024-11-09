<div class="row">
    <div class="col-md-12">
        <form id="formVoucher" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
            @csrf
            <div class="row">     
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="code">Mã Voucher</label>
                        <input type="text" id="code" name="code" class="form-control" placeholder="Mã Voucher" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="discount">Giảm Giá</label>
                        <input type="number" id="discount" name="discount" class="form-control" placeholder="Giảm Giá" required min="0" step="0.01" oninput="formatPrice(this)">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="start_date">Ngày Bắt Đầu</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="end_date">Ngày Kết Thúc</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="usage_limit">Giới Hạn Sử Dụng</label>
                        <input type="number" id="usage_limit" name="usage_limit" class="form-control" placeholder="Giới Hạn Sử Dụng" min="0">
                    </div>
                </div>
            </div>
        </form>
        <div class="modal-footer" style="float: right">
            <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Đóng</button>
            <button type="button" onclick="pushVoucher()" class="btn btn-info">Gửi</button>
        </div>
    </div>
</div>
