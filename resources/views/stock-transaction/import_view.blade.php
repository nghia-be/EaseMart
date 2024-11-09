<div class="row">
    <div class="col-md-12">
        <form action="{{ route('stock-transaction.import.excel') }}" id="formExcel"  method="POST" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
        @csrf
            <div class="row">     
                <div class="col-md-12">
                    <label for="slug">Import Excel</label>
                    <input type="file" name="file" id="file" class="form-control"/>  
                </div>
            </div>
    </div>
    <div class="model-footer" style="float: right">
            <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
            <button type="submit"  class="btn btn-info">Submit</button>
        </div>
        </form>
        
    </div>
</div>
