@extends('layoutv2.layout.app')

@section('content')
    <div id="wrapper" style="min-height: 1405px;">
        <div class="content">
            <div class="row">
                <form action="{{ route('user.update', [$user->id]) }}" class="staff-form dirty" autocomplete="off"
                      enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
                    @csrf
                    @method('PUT')
                    <input type="text" name="role_id" class="hide" value="{{ $user->role_id }}">
                    <div class="col-md-8 col-md-offset-2" id="small-table">
                        <div class="panel_s">
                            <div class="panel-body">
                                <div class="form-group" app-field-wrapper="username">
                                    <label for="username" class="control-label">
                                        <small class="req text-danger">* </small>Username</label>
                                    <input type="text" id="username" name="name" class="form-control"
                                           autofocus="1" value="{{ $user->name }}">
                                </div>
                                <div class="form-group" app-field-wrapper="email"><label for="email"
                                                                                         class="control-label">
                                        <small class="req text-danger">* </small>Email</label><input
                                        type="email" id="email" name="email" class="form-control"
                                        autocomplete="off" value="{{ $user->email }}">
                                </div>
                                <div class="form-group" app-field-wrapper="role"><label for="role"
                                                                                        class="control-label">Vị
                                        trí</label>
                                    <div class="dropdown bootstrap-select bs3" style="width: 100%;">
                                        <select id="role" name="role" class="selectpicker" data-width="100%"
                                                data-none-selected-text="Không có mục nào được chọn"
                                                data-live-search="true" tabindex="-98">
                                            <option value=""></option>

                                            @if(!empty($role))
                                                @foreach($role as $value)
                                                    @if(!empty($user->getRoleNames()->toArray()) && $user->getRoleNames()[0] == $value->name)
                                                        <option value="{{ $value->name }}" selected>{{ $value->name }}</option>
                                                    @else
                                                        <option value="{{ $value->name }}">{{ $value->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="control-label"> <small
                                            class="req text-danger">* </small>Mật khẩu</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control password" name="password"
                                               autocomplete="off" value="{{ old('password') }}">
                                        <span class="input-group-addon tw-border-l-0">
                                                <a href="#password" class="show_password"
                                                   onclick="showPassword('password'); return false;"><i
                                                        class="fa fa-eye"></i></a>
                                            </span>
                                        <span class="input-group-addon">
                                                <a href="#" class="generate_password"
                                                   onclick="generatePassword(this);return false;"><i class="fa fa-refresh"></i></a>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="control-label"> <small
                                            class="req text-danger">* </small>Xác nhận mật khẩu</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control password" name="password_confirmation"
                                               autocomplete="off" value="{{ old('password_confirmation') }}">
                                        <span class="input-group-addon tw-border-l-0">
                                                <a href="#password_confirmation" class="show_password"
                                                   onclick="showPassword('password_confirmation'); return false;"><i
                                                        class="fa fa-eye"></i></a>
                                            </span>
                                        <span class="input-group-addon">
                                                <a href="#" class="generate_password"
                                                   onclick="generatePassword(this);return false;"><i class="fa fa-refresh"></i></a>
                                            </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <hr class="hr-10">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" name="administrator" id="administrator" @if($user->hasRole('Admin')) checked @endif>
                                            <label for="administrator">Người quản lý</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-primary">Lưu lại</button>
                        </div>
                </form>
            </div>
        </div>
        <script>
            $(function () {
                $('#role').trigger('change');
                appValidateForm($('.staff-form'), {
                    firstname: 'required',
                    lastname: 'required',
                    username: 'required',
                    password: {
                        required: {
                            depends: function (element) {
                                return ($('input[name="isedit"]').length == 0) ? true : false
                            }
                        }
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: admin_url + "misc/staff_email_exists",
                            type: 'post',
                            data: {
                                email: function () {
                                    return $('input[name="email"]').val();
                                },
                                memberid: function () {
                                    return $('input[name="memberid"]').val();
                                }
                            }
                        }
                    }
                });
            });
        </script>
    </div>
@endsection
