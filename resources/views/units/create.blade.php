<div class="row">
    <div class="col-md-12">
        <form id="formUnits" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
        @csrf
            <div class="row">
                <div class="col-md-12">
                <div class="form-group">
                        <label for="unit_code">Mã đơn vị</label>
                        <input type="text" id="unit_code" name="unit_code" class="form-control" placeholder="Mã đơn vị">
                    </div>
                    <div class="form-group">
                        <label for="unit_name">Tên đơn vị</label>
                        <input type="text" id="unit_name" name="unit_name" class="form-control" placeholder="Tên đơn vị">
                    </div>
                </div>
            </div>
        </form>
        <div class="model-footer" style="float: right">
            <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
            <button type="button" onclick="pushUnits()" class="btn btn-info">Submit</button>
        </div>
    </div>
</div>
