<div class="row">
    <div class="col-md-12">
        <form id="formSupplier" novalidate="novalidate" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Tên nhà cung cấp<small class="req text-danger">* </small></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Tên nhà cung cấp">
                    </div>
                    <div class="form-group">
                        <label for="supplier_code">Mã nhà cung cấp<small class="req text-danger">* </small></label>
                        <input type="text" id="supplier_code" name="supplier_code" class="form-control" placeholder="Mã nhà cung cấp">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Số điện thoại<small class="req text-danger">* </small></label>
                        <input type="text" id="phone" name="phone"  class="form-control" placeholder="Số điện thoại" >
                    </div>
               
             
                    <div class="form-group">
                        <label for="email">Email<small class="req text-danger">* </small></label>
                        <input type="text" id="email" name="email"  class="form-control" placeholder="Email" >
                    
                </div>
                
                    <div class="form-group">
                        <label for="address">Địa chỉ<small class="req text-danger">* </small></label>
                        <input type="text" id="address" name="address"  class="form-control" placeholder="Địa chỉ">
                    </div>
                
                <div class="form-group" app-field-wrapper="name">
                    <label for="desciption" class="control-label">Mô tả</label>
                    <textarea id="desciption" name="desciption" class="form-control" cols="100" id=""></textarea>  
                </div>
            </div>
        </div>
        </form>
        <div class="model-footer" style="float: right">
            <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
            <button type="button" onclick="pushSupplier()" class="btn btn-info">Submit</button>
        </div>
    </div>
</div>
