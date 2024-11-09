@extends('layoutv2.layout.app')
@section('content')
    <div id="wrapper">
        <div class="content">
            <form action="{{ route('general_settings.edit') }}" id="settings-form" class=""
                  enctype="multipart/form-data" method="post" accept-charset="utf-8">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800"> Cài đặt </h4>
                        <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
                            <li class="settings-group-general">
                                <a href="/setting?group=general" data-group="general">
                                    <i class="fa fa-cog menu-icon"></i>
                                    Tổng quan

                                </a>
                            </li>
                            <li class="settings-group-site_url">
                                <a href="/setting?group=site_url" data-group="site_url">
                                    <i class="fa fa-link menu-icon"></i>
                                    Site URL
                                </a>
                            </li>
{{--                            <li class="settings-group-modules">--}}
{{--                                <a href="/setting?group=modules" data-group="modules">--}}
{{--                                    <i class="fa fa-list-alt menu-icon"></i>--}}
{{--                                    Modules--}}
{{--                                </a>--}}
{{--                            </li>--}}
                            <li class="settings-group-cronjob">
                                <a href="/setting?group=cronjob" data-group="cronjob">
                                    <i class="fa-solid fa-microchip menu-icon"></i>
                                    Email chu kì/Cron Job

                                </a>
                            </li>
                            <li class="settings-group-misc">
                                <a href="/setting?group=misc" data-group="misc">
                                    <i class="fa-solid fa-gears menu-icon"></i>
                                    Khác

                                </a>
                            </li>
                        </ul>
                        <a href="/setting?group=info"
                           class="tw-flex tw-items-center tw-ml-3 settings-group-system-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="tw-w-5 tw-h-5 tw-mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                            </svg>
                            System/Server Info
                        </a>

                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-primary">
                                Lưu lại cài đặt
                            </button>
                        </div>
                    </div>
                    @include('settings.includes.index', ['title'=> $title, 'view'=> $view])

                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
@endsection
