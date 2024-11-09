<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning">
            <p>*** LƯU Ý ***<br>
                - Tải lên tối đa 5 file, mỗi file chỉ được chứa tối đa <b>100.000</b> số
                điện thoại <br>
                - Nếu như tải lên file exel, lưu SDT theo 1 cột duy nhất, Tải file mẫu
                <a href="https://docs.google.com/spreadsheets/d/1ifAR0UwfdV03Sidcshjvwl1pn1YmYBD9/edit?usp=sharing&ouid=113322866597815571901&rtpof=true&sd=true"
                   target="_blank"> <b> tại đây</b> </a>
            </p>
        </div>
        <form id="importExcel" enctype="multipart/form-data" action="{{ $action }}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" id="id_popup_phone" name="id">
            <div class="form-group">
                <label class="control-label"><small class="req text-danger">* </small>Nhập vào danh sách số điện thoại</label> <br>
                <textarea rows="6" type="text" id="number_phone" name="number_phone"
                          class="form-control"
                          placeholder="Có thể thêm nhiều số điện thoại cách nhau bằng dấu phẩy ','"></textarea>
            </div>

            <div class="form-group">
                <label class="control-label">Hoặc tải tệp lên</label> <br>
                <input type="file" id="number_phone_import" name="excel[]"
                       accept=".xlsx, .csv" multiple class="form-control">
            </div>

            <div class="model-footer" style="float: right">
                {!! $button !!}
            </div>
        </form>
    </div>
</div>

