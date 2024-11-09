<div class="row">
    <div class="col-md-12">
        <form id="formBrand" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
        @csrf
            <div class="row">     
            <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Tên thương hiệu</label>
                        <input type="text" id="slug" name="name" onkeyup="ChangeToSlug();" class="form-control" placeholder="Tên thương hiệu">
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
                        <label for="categories_id">Danh mục</label>
                        <select name="categories_id" id="categories_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                            <option value="">Vui lòng chọn danh mục</option>
                            @foreach ($data['list_categories'] as $categories)
                                <option value="{{ $categories->id }}">{{ $categories->categories_name }}</option>
                            @endforeach
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
            <button type="button" onclick="pushBrand()" class="btn btn-info">Submit</button>
        </div>
    </div>
</div>
