<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="dropdown user user-menu">
            <p href="#" class="dropdown-toggle nav-link m-0 pt-2" data-toggle="dropdown" aria-expanded="false">
                <img src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/dist/img/avatar5.png" class="user-image" alt="User Image">
                <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </p>
            <ul class="dropdown-menu" style="transform: translate(-25%, 0px);">
                <!-- User image -->
                <li class="user-header">
                    <img src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/dist/img/avatar5.png" class="img-circle" alt="User Image">
                    <p>
                        {{ Auth::user()->name }}
                    </p>
                </li>
                <!-- Menu Body -->
                <!-- Menu Footer-->
                <li class="user-footer">
                    <div class="pull-left">
                        <a href="#" class="btn btn-default btn-flat" data-toggle="modal" data-target="#profileModal"><i class="fas fa-user"></i>&nbsp;Profile</a>
                        <a class="btn btn-default btn-flat" href="#" data-toggle="modal" data-target="#changePasswordModal"><i class="fas fa-key"></i>&nbsp;Change Password</a>
                    </div>
                </li>
            </ul>

        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                {{ __('Logout') }}&nbsp;&nbsp;<i class="fas fa-sign-out-alt"></i>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</nav>
<div id="changePasswordModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>
            <form action="/profile/changePassword" method="POST" onsubmit="handleSubmit(event,this)">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input placeholder="Current Password" type="password" name="current_password" class="form-control" data-toggle="password" />
                    </div>
                    <div class="form-group">
                        <input placeholder="New Password" type="password" name="password" class="form-control" data-toggle="password" />
                    </div>
                    <div class="form-group">
                        <input placeholder="Confirm New Password" type="password" name="password_confirmation" class="form-control" data-toggle="password" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="dialogConfirmWithAjax(changePassWord, this)">Submit</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- --------------------------------------------------profileModal ---------------------------------------------------------}}
<div id="profileModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>
            <div class="modal-body">
                <form action="/profile/updateprofile" method="POST" onsubmit="handleSubmit(event,this)">
                    @csrf
                    <div class="container">
                        <div class="row gutters">
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="account-settings">
                                            <div class="user-profile">
                                                <div class="user-avatar"> <img src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/dist/img/avatar5.png" alt="Maxwell Admin"></div>
                                                <h5 class="user-name">{{ Auth::user()->name}}</h5>
                                                <h6 class="user-email">{{ Auth::user()->email}}</h6>
                                            </div>
                                            <div class="about">
                                                <h5>About</h5>
                                                <p>Something....</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="row gutters">
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <h6 class="mb-2 text-primary">Personal Details</h6>
                                            </div>
                                            <div class="ol-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <div class="form-group"> <label for="name">Role: </label><b>&nbsp;{{ !empty(Auth::user()->role) ? Auth::user()->role->role_name: ''}}</b></div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group"> <label for="name">Full Name</label> <input type="text" class="form-control" id="name" name="name" value="{{Auth::user()->name}}"></div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group"> <label for="eMail">Email</label> <input class="form-control" value="{{Auth::user()->email}}" disabled></div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group"> <label for="phone">Phone</label> <input type="text" class="form-control" id="phone" placeholder="Enter phone number"></div>
                                            </div>
                                            {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group"> <label for="website">Website URL</label> <input type="text" class="form-control" id="website" placeholder="Website url"></div>
                                            </div> --}}
                                        </div>
                                        <div class="row gutters">
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <div class="text-right">
                                                    <button data-dismiss="modal" aria-label="Close" class="btn btn-secondary">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .account-settings .user-profile {
        margin: 0 0 1rem 0;
        padding-bottom: 1rem;
        text-align: center;
    }

    .account-settings .user-profile .user-avatar {
        margin: 0 0 1rem 0;
    }

    .account-settings .user-profile .user-avatar img {
        width: 90px;
        height: 90px;
        -webkit-border-radius: 100px;
        -moz-border-radius: 100px;
        border-radius: 100px;
    }

    .account-settings .user-profile h5.user-name {
        margin: 0 0 0.5rem 0;
    }

    .account-settings .user-profile h6.user-email {
        margin: 0;
        font-size: 0.8rem;
        font-weight: 400;
        color: #9fa8b9;
    }

    .account-settings .about {
        margin: 2rem 0 0 0;
        text-align: center;
    }

    .account-settings .about h5 {
        margin: 0 0 15px 0;
        color: #007ae1;
    }

    .account-settings .about p {
        font-size: 0.825rem;
    }

    .form-control {
        border: 1px solid #cfd1d8;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        font-size: .825rem;
        background: #ffffff;
        color: #2e323c;
    }

    .card {
        background: #ffffff;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border: 0;
        margin-bottom: 1rem;
    }

</style>
