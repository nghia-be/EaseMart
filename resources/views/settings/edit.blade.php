@extends('layouts.default')

@section('content')
@php
    $uri_config = $Settings->firstWhere('name','uri_config');
    $list_uri = [];
    if(!empty($uri_config)){
        $list_uri = json_decode($uri_config->value);
    }
@endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ ($setting->id) ? 'EDIT' : 'ADD NEW' }} SETTING</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Setting v1</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-md-center">
                    <div class="col-sm-12">
                    @php
                        $action = (isset($setting->id) && $setting->id) ? route('settings.update',$setting->id) : route('settings.store');
                    @endphp
                        <form id="setting-id" action="{{$action}}" method="POST" novalidate="novalidate" autocomplete="off" onsubmit="handleBeforeSubmitSetting(event,this)">
                            @csrf
                            @if (isset($setting->id) && $setting->id)
                                @method('PUT')
                            @endif
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title uppercase">Setting Info</h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="module_name">Unique name</label>
                                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Unique name" value="{{ $setting->name }}" {{ (!empty($setting->id)) ? 'readonly' : '' }} >
                                            @error('name')
                                                <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            {{-- <input type="hidden" name="value" id="hidden-value" value="{{ $setting->value }}" />
                                            <select id="value" class="form-control selectpicker" data-live-search="true" data-size="10" multiple>
                                                @foreach (json_decode($setting->value, true) as $one_value)
                                                    <option value="{{ json_encode($one_value) }}" selected>{{ json_encode($one_value, JSON_UNESCAPED_UNICODE) }}</option>
                                                @endforeach
                                            </select> --}}
                                            <div class="form-group">
                                                <label for="value">Value</label>
                                                <div id="editorWrapper">
                                                    <div id="editor"></div>
                                                </div>
                                                <input type="hidden" name="value" id="value-setting" value="{{ $setting->value }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" style="text-align: center">
                                    <a href="{{ route('settings.index') }}" type="button" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="btn btn-info">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @push('scripts')
    <script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/ace-editor/js/ace.js" type="text/javascript" charset="utf-8"></script>
    <script>
        const editor = ace.edit('editor', {
            mode: 'ace/mode/json',
            selectionStyle: 'text',
            showPrintMargin: false,
            theme: 'ace/theme/chrome'
        })

        editor.getSession().setValue(JSON.stringify(JSON.parse("{{ $setting->value }}".replace( /&quot;/g, '"' )), null, 4));
        
        const formatText = (spacing = 0) => {
            try {
                const current = JSON.parse(editor.getValue())
                editor.setValue(JSON.stringify(current, null, spacing))
                return editor.getValue();
                // editor.focus()
                // editor.selectAll()
                // document.execCommand('copy')
            } catch (err) {
                alert('ERROR: Unable to parse text as JSON')
            }
        }
        
        editor.on('paste', (event) => {
            try {
                event.text = JSON.stringify(JSON.parse(event.text), null, 4)
            } catch (err) {
                console.log(err)
            }
        })

        function handleBeforeSubmitSetting(event, form) {
            event.preventDefault();
            var value = formatText().replace(/'/g, '"');
            var passed = true;

            var formData = getDataInForm(form);
            if(value) {
                formData.value = JSON.stringify(value);
                $("#value-setting").val(value);
            }
            handleSubmit(event, form);
        }

        function checkSubmitLogReport(formData) {
            const pathArray = window.location.pathname.split("/");
            let action = pathArray[2]; // action ['create','edit']
            if(action === 'edit'){
                return {
                    status: true,
                    data: null
                };
            }
            var data_required = getDataRequiredLogReport();
            let intersection = Object.keys(data_required).filter(x => !Object.keys(formData).includes(x));
            // console.log(intersection);
            var result = {};
            if (intersection.length === 0) {
                result.status = true;
                result.data = null;
            } else {
                result.status = false;
                result.data = intersection;
            }
            return result;
        }

        function getDataRequiredSetting() {
            var data = {
                'name': true
            };
        }
        
    </script>
@endpush
    <style>
        @import url("https://fonts.googleapis.com/css?family=Montserrat|Roboto+Mono");
    body {
        justify-content: center;
        align-items: center;
        position: absolute;
        height: 100%;
        width: 100%;
        margin: 0;
    }
    @media screen and (max-height: 375px) {
        body {
            position: relative;
            overflow: auto;
        }
    }

    .card {
        width: 100%;
    }
    @media screen and (max-width: 1200px) {
        .card {
            width: 100%;
        }
    }

    #editorWrapper {
        position: relative;
        height: 100%;
        min-height: 45vh;
    }
    @media (min-height: 600px) {
        #editorWrapper {
            height: 55vh;
        }
    }
    @media (min-height: 900px) {
        #editorWrapper {
            height: 65vh;
        }
    }
    #editorWrapper #editor {
        font-family: "Courier Mono", monospace;
        font-size: 14px;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
        select {
            font-family: 'Lato', 'Font Awesome 5 Free', 'Font Awesome 5 Brands';
        }
        
    </style>
@endsection