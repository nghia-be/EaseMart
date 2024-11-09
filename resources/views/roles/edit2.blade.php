@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper" style="min-height: 3494px;">
        <div class="content">
            <div class="row tw-flex tw-justify-between tw-items-center">
                <div class="col-md-7">
                    <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
                        <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                            Thêm vị trí mới </h4>
                        <a href="/role/create" class="btn btn-success btn-sm">Vị trí mới                    </a>
                    </div>
                    <div class="panel_s">
                        <div class="panel-body">
                            <form action="{{ route('role.update', [$role->id]) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="control-label">
                                        <small class="req text-danger">* </small>Tên vị trí
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control" autofocus="1" value="{{ $role->name }}">
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered roles no-margin">
                                        <thead>
                                            <tr>
                                                <th>Tính năng</th>
                                                <th>Quyền hạn</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($permission as $key => $value)
                                            <tr data-name="{{ $key }}">
                                                <td>
                                                    <div class="checkbox tw-ml-2">
                                                        <input onclick="check_value(this)" id="{{ $key }}" type="checkbox" class="capability">
                                                        <label for="{{ $key }}"><b>{{ $value['name'] }}</b></label>
                                                    </div>
                                                </td>
                                                <td class="tw-flex tw-justify-between tw-items-center" >
                                                    @foreach($value['permission'] as $k => $val)
                                                        <div class="checkbox tw-ml-2" >
                                                            <input id="{{$key.'_'.$val}}" type="checkbox" class="{{$key}} capability" name="permissions[]"
                                                                   value="{{(int) $k}}" @if(in_array($k, $rolePermissions)) checked @endif>
                                                            <label for="{{$key.'_'.$val}}"> {!! $val !!} </label>
                                                        </div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="btn-bottom-toolbar text-right">
                                    <button type="submit" class="btn btn-primary">
                                        Lưu lại cài đặt
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@push('script')
    <script>
        function check_value(element){
            let _id = element.id;
            $('input:checkbox.' + _id).each(function (key, value) {
                $(value).prop( "checked", $('#' + _id).is(":checked") );
            });
        }
    </script>
@endpush
