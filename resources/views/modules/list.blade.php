<div class="row">
    <div class="col-md-12">
        <form id="formModules" novalidate="novalidate" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="module_name">Module name</label>
                        <input type="text" id="module_name" name="module_name" class="form-control" placeholder="Module name">
                    </div>
                    <div class="form-group">
                        <label for="uri">Uri</label>
                        <select name="uri" id="uri" class="form-control selectpicker" data-live-search="true" data-size="10">
                            <option value="">Please choose uri</option>
                            @foreach (json_decode(setting('uri_config', [])) as $uri)
                                <option value="{{ $uri->uri }}">{{ $uri->uri }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="uri">Group module</label>
                        <select name="group_module_id" id="group_module_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                            <option value="">Please choose group module</option>
                            @foreach ($data['list_group_module'] as $group_module)
                                <option value="{{ $group_module->id }}">{{ $group_module->group_module_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="uri">Icon</label>
                        <select name="icon" id="icon" class="form-control selectpicker" data-live-search="true" data-size="10">
                            <option value="">Please choose icon</option>
                            @foreach ($data['list_icon'] as $icon)
                                <option value="fas fa-{{ $icon }}" data-icon="fas fa-{{ $icon }}">fas fa-{{ $icon }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input id="status" name="status" class="form-check-input" type="checkbox" value="true" checked>
                            <label>Status</label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="model-footer" style="float: right">
            <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
            <button type="button" onclick="pushModules()" class="btn btn-info">Submit</button>
        </div>
    </div>
</div>
