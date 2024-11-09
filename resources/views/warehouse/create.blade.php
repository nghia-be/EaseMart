<div class="row">
    <div class="col-md-12">
        <form id="formWarehouse" novalidate="novalidate" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Tên kho hàng<small class="req text-danger">* </small></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Tên kho hàng">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="warehouse_code">Mã kho hàng<small class="req text-danger">* </small></label>
                        <input type="text" id="warehouse_code" name="warehouse_code" class="form-control" placeholder="Mã kho hàng">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="phone">Số điện thoại<small class="req text-danger">* </small></label>
                        <input type="text" id="phone" name="phone"  class="form-control" placeholder="Số điện thoại" >
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">Email<small class="req text-danger">* </small></label>
                        <input type="text" id="email" name="email"  class="form-control" placeholder="Email" >
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="address">Địa chỉ<small class="req text-danger">* </small></label>
                        <input type="text" id="address" name="address"  class="form-control" placeholder="Địa chỉ">
                    </div>
                </div>
            </div>
        </form>
        <div class="model-footer" style="float: right">
            <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
            <button type="button" onclick="pushWarehouse()" class="btn btn-info">Submit</button>
        </div>
    </div>
</div>
