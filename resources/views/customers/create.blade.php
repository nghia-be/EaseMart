<div class="row">
    <div class="col-md-12">
        <form id="formCustomers" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
        @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Tên</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Tên danh mục">
                    </div>
                    <div class="form-group" id="path_1">
                                <label class="control-label"><small class="req text-danger">* </small>Ảnh</label>
                                <input type="file" accept="image/*" name="path_1" class="form-control" onchange="handleUploadImage(this)"/>
                                <img id="img_path_1" src="{{ asset('/images/image_holder.png') }}" alt="your image"
                                     class="img-thumbnail img_viewable" style="max-width: 150px;padding:10px;margin-top:10px"/>
                                <input name="image" id="img_path_1_name" value="" hidden/>
                            </div>
                </div>
            </div>
        </form>
        <div class="model-footer" style="float: right">
            <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
            <button type="button" onclick="pushCustomers()" class="btn btn-info">Submit</button>
        </div>
    </div>
</div>
