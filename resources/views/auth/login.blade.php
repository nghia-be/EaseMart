@extends('layouts.empty')
@section('title','Đăng nhập')
@section('content')

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <form class="login100-form validate-form" action="{{ route('login') }}" method="POST">
                @csrf
                <span class="login100-form-title p-b-43">
                    Login to continue
                </span>
                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100" type="text" name="email">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Email</span>
                </div>


                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="password">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Password</span>
                </div>

                <div class="flex-sb-m w-full p-t-3 p-b-32">
                    <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember">
                        <label class="label-checkbox100" for="ckb1">
                            Remember me
                        </label>
                    </div>
                </div>


                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">
                        Login
                    </button>
                </div>
                @if ($error = $errors->first())
                <div class="alert alert-danger" style="color:red">
                    {{ $error }}
                </div>
                @endif
            </form>
            <div class="login100-more" style="background-image: url({{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/login/ease_mart3.jpg);background-size: 100% 100%">
            </div>
        </div>
    </div>
</div>
@endsection
