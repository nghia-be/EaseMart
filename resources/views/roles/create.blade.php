@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper" style="min-height: 3494px;">
        <div class="content">
            <div class="row tw-flex tw-justify-between tw-items-center">
                <div class="col-md-7">
                    <div class="panel_s">
                        <div class="panel-body">
                            <form action="{{ route('role.store') }}" method="post" accept-charset="utf-8"
                                  novalidate="novalidate">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="control-label">
                                        <small class="req text-danger">* </small>Tên vị trí
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control" autofocus="1" >
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
                                                        <div class="checkbox tw-ml-2">
                                                            <input id="{{$key.'_'.$val}}" type="checkbox" class="{{$key}} capability" name="permissions[]"
                                                                   value="{{(int) $k}}">
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
                                        Lưu
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
