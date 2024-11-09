<div class="row">
    <div class="col-md-12">
        <form id="formCategories" novalidate="novalidate" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="categories_name">Tên danh mục</label>
                        <input type="text"  id="slug" name="categories_name" onkeyup="ChangeToSlug();" class="form-control" placeholder="Tên danh mục">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" id="convert_slug" name="slug"  class="form-control" placeholder="Slug">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="categories_parent_id">Danh mục cha</label>
                        <select name="categories_parent_id" id="categories_parent_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                            <option value="">Vui lòng chọn danh mục cha</option>
                            
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="icon">Biểu tượng</label>
                        <select name="icon" id="icon" class="form-control selectpicker" data-live-search="true" data-size="10">
                            <option value="">Vui lòng chọn icon</option>
                            
                        </select>
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
            <button type="button" onclick="pushCategories()" class="btn btn-info">Submit</button>
        </div>
    </div>
</div>
