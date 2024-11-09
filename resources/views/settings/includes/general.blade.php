<div class="row">
    <div class="col-md-12">
        @if(!empty($data['setting']))
            @foreach($data['setting'] as $key => $value)
                <div class="form-group">
                    <label for="{{ $key }}" class="control-label">{{Str::title(str_replace("_", " ", $key))}}</label>
                    <textarea id="{{ $key }}"
                              name="{{ $key }}" class="form-control">{!! setting($key) !!}</textarea>
                </div>
                <hr>
            @endforeach
        @endif
    </div>
</div>
