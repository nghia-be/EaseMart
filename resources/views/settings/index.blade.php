{{--@extends('layouts.default')--}}
{{--@push('header')--}}
{{--    <link media="all" type="text/css" rel="stylesheet" href="{{url('/')}}/base/css/core.css">--}}
{{--@endpush--}}
{{--@section('content')--}}

{{--    <div class="content-wrapper">--}}
{{--        <!-- Content Header (Page header) -->--}}
{{--        <div class="content-header">--}}
{{--            <div class="container-fluid">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-sm-12">--}}
{{--                        <ol class="breadcrumb float-sm-right">--}}
{{--                            {!! breadcrumb() !!}--}}
{{--                        </ol>--}}
{{--                    </div><!-- /.col -->--}}
{{--                </div><!-- /.row -->--}}
{{--            </div><!-- /.container-fluid -->--}}
{{--        </div>--}}
{{--        <!-- /.content-header -->--}}

{{--        <div class="clearfix"></div>--}}
{{--        {!! Form::open(['route' => ['general_settings.edit']])!!}--}}
{{--        <div class="max-width-1200">--}}
{{--            <div class="flexbox-annotated-section">--}}
{{--                <div class="flexbox-annotated-section-annotation">--}}
{{--                    <div class="annotated-section-title pd-all-20">--}}
{{--                        <h2 style="font-weight: 500; font-size: 20px !important;">Description cron jobs time config</h2>--}}
{{--                    </div>--}}
{{--                    <div class="annotated-section-description pd-all-20 p-none-t">--}}
{{--                        <p class="color-note">The left panel shows the input cron job time attribute ...</p>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="flexbox-annotated-section-content">--}}
{{--                    <div class="wrapper-content pd-all-20">--}}

{{--                        <pre tabindex="0"><code class="border-none"># ┌───────────── minute (0 - 59)--}}
{{--# │ ┌───────────── hour (0 - 23)--}}
{{--# │ │ ┌───────────── day of the month (1 - 31)--}}
{{--# │ │ │ ┌───────────── month (1 - 12)--}}
{{--# │ │ │ │ ┌───────────── day of the week (0 - 6) (Sunday to Saturday;--}}
{{--# │ │ │ │ │                                   7 is also Sunday on some systems)--}}
{{--# │ │ │ │ │                                   OR sun, mon, tue, wed, thu, fri, sat--}}
{{--# │ │ │ │ │--}}
{{--# * * * * *     >>>     input = * * * * * (run every a minutes)--}}
{{--</code></pre>--}}

{{--                        <table class="table">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Miêu tả</font></font></th>--}}
{{--                                <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Tương--}}
{{--                                            đương với</font></font></th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            <tr>--}}
{{--                                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Chạy--}}
{{--                                            mỗi năm một lần vào nửa đêm ngày 1 tháng 1</font></font></td>--}}
{{--                                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0 0 1--}}
{{--                                            1 *</font></font></td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Chạy--}}
{{--                                            mỗi tháng một lần vào nửa đêm của ngày đầu tiên của tháng</font></font></td>--}}
{{--                                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0 0 1--}}
{{--                                            * *</font></font></td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Chạy--}}
{{--                                            mỗi tuần một lần vào lúc nửa đêm vào sáng Chủ Nhật</font></font></td>--}}
{{--                                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0 0 *--}}
{{--                                            * 0</font></font></td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Chạy--}}
{{--                                            một lần một ngày vào lúc nửa đêm</font></font></td>--}}
{{--                                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0 0 *--}}
{{--                                            * *</font></font></td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Chạy--}}
{{--                                            mỗi giờ một lần vào đầu giờ</font></font></td>--}}
{{--                                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0 * *--}}
{{--                                            * *</font></font></td>--}}
{{--                            </tr>--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                        <div class="help-ts">--}}
{{--                            <i class="fa fa-info-circle"></i>--}}
{{--                            <span>Kiểm tra biểu thức cronjob <a class="text-primary" target="_blank" href="https://crontab.guru/#*_*_*_*_*">ở đây</a></span>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            --}}{{--Payment Unpaid--}}
{{--            <div class="flexbox-annotated-section">--}}
{{--                <div class="flexbox-annotated-section-annotation">--}}
{{--                    <div class="annotated-section-title pd-all-20">--}}
{{--                        <h2 style="font-weight: 500; font-size: 20px !important;">Config Service Payment Unpaid</h2>--}}
{{--                    </div>--}}
{{--                    <div class="annotated-section-description pd-all-20 p-none-t">--}}
{{--                        <p class="color-note">Settings email, enabled, time ...</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="flexbox-annotated-section-content">--}}
{{--                    <div class="wrapper-content pd-all-20">--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field form-check-label"--}}
{{--                                   for="admin_locale_direction">Bật service--}}
{{--                            </label>--}}
{{--                            <label class="me-2 form-check-label mt-2">--}}
{{--                                <input type="radio" name="hi_admin_cron_service_check_payment_unpaid_enable" value="1"--}}
{{--                                       @if (setting('hi_admin_cron_service_check_payment_unpaid_enable', '0') === '1') checked @endif>{{ __('Bật') }}--}}
{{--                            </label>--}}
{{--                            <label class=" form-check-label mt-2">--}}
{{--                                <input type="radio" name="hi_admin_cron_service_check_payment_unpaid_enable" value="0"--}}
{{--                                       @if (setting('hi_admin_cron_service_check_payment_unpaid_enable', '0') === '0') checked @endif>{{ __('Tắt') }}--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field form-check-label"--}}
{{--                                   for="hi_admin_cron_service_check_payment_unpaid_list_email">Email người nhận</label>--}}
{{--                            <textarea data-counter="120" type="text" class="next-input mt-2"--}}
{{--                                      name="hi_admin_cron_service_check_payment_unpaid_list_email"--}}
{{--                                      id="hi_admin_cron_service_check_payment_unpaid_list_email">{{ setting('hi_admin_cron_service_check_payment_unpaid_list_email') }}</textarea>--}}
{{--                        </div>--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field form-check-label"--}}
{{--                                   for="hi_admin_cron_service_check_payment_unpaid_time">{{ __('Cron Job Time') }}</label>--}}
{{--                            <input type="text" class="next-input mt-2 col-md-6"--}}
{{--                                   name="hi_admin_cron_service_check_payment_unpaid_time"--}}
{{--                                   id="hi_admin_cron_service_check_payment_unpaid_time"--}}
{{--                                    placeholder="Set time cron jobs, example above!"--}}
{{--                            value="{{ setting('hi_admin_cron_service_check_payment_unpaid_time') }}"/>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            --}}{{--End Payment Unpaid--}}


{{--            --}}{{--VSML--}}
{{--            <div class="flexbox-annotated-section">--}}
{{--                <div class="flexbox-annotated-section-annotation">--}}
{{--                    <div class="annotated-section-title pd-all-20">--}}
{{--                        <h2 style="font-weight: 500; font-size: 20px !important;">Config Service VSML Report Week</h2>--}}
{{--                    </div>--}}
{{--                    <div class="annotated-section-description pd-all-20 p-none-t">--}}
{{--                        <p class="color-note">Settings email, enabled, time ...</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="flexbox-annotated-section-content">--}}
{{--                    <div class="wrapper-content pd-all-20">--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field form-check-label"--}}
{{--                                   for="admin_locale_direction">Bật service--}}
{{--                            </label>--}}
{{--                            <label class="me-2 form-check-label mt-2">--}}
{{--                                <input type="radio" name="hi_admin_cron_service_air_condition_weekly_enable" value="1"--}}
{{--                                       @if (setting('hi_admin_cron_service_air_condition_weekly_enable', '0') === '1') checked @endif>{{ __('Bật') }}--}}
{{--                            </label>--}}
{{--                            <label class=" form-check-label mt-2">--}}
{{--                                <input type="radio" name="hi_admin_cron_service_air_condition_weekly_enable" value="0"--}}
{{--                                       @if (setting('hi_admin_cron_service_air_condition_weekly_enable', '0') === '0') checked @endif>{{ __('Tắt') }}--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field form-check-label"--}}
{{--                                   for="hi_admin_cron_service_air_condition_weekly_list_email">Email người nhận</label>--}}
{{--                            <textarea data-counter="120" type="text" class="next-input mt-2"--}}
{{--                                      name="hi_admin_cron_service_air_condition_weekly_list_email"--}}
{{--                                      id="hi_admin_cron_service_air_condition_weekly_list_email">{{ setting('hi_admin_cron_service_air_condition_weekly_list_email') }}</textarea>--}}
{{--                        </div>--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field form-check-label"--}}
{{--                                   for="hi_admin_cron_service_air_condition_weekly_time">{{ __('Cron Job Time') }}</label>--}}
{{--                            <input type="text" class="next-input mt-2 col-md-6"--}}
{{--                                   name="hi_admin_cron_service_air_condition_weekly_time"--}}
{{--                                   id="hi_admin_cron_service_air_condition_weekly_time"--}}
{{--                                   placeholder="Set time cron jobs, example above!"--}}
{{--                                   value="{{ setting('hi_admin_cron_service_air_condition_weekly_time') }}"/>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            --}}{{--End VSML--}}


{{--            --}}{{--Health check--}}
{{--            <div class="flexbox-annotated-section">--}}
{{--                <div class="flexbox-annotated-section-annotation">--}}
{{--                    <div class="annotated-section-title pd-all-20">--}}
{{--                        <h2 style="font-weight: 500; font-size: 20px !important;">Config Service Health Check</h2>--}}
{{--                    </div>--}}
{{--                    <div class="annotated-section-description pd-all-20 p-none-t">--}}
{{--                        <p class="color-note">Settings email, enabled, time ...</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="flexbox-annotated-section-content">--}}
{{--                    <div class="wrapper-content pd-all-20">--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field form-check-label"--}}
{{--                                   for="hi_admin_cron_service_health_check_api_enable">Bật service--}}
{{--                            </label>--}}
{{--                            <label class="me-2 form-check-label mt-2">--}}
{{--                                <input type="radio" name="hi_admin_cron_service_health_check_api_enable" value="1"--}}
{{--                                       @if (setting('hi_admin_cron_service_health_check_api_enable', '0') === '1') checked @endif>{{ __('Bật') }}--}}
{{--                            </label>--}}
{{--                            <label class=" form-check-label mt-2">--}}
{{--                                <input type="radio" name="hi_admin_cron_service_health_check_api_enable" value="0"--}}
{{--                                       @if (setting('hi_admin_cron_service_health_check_api_enable', '0') === '0') checked @endif>{{ __('Tắt') }}--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field form-check-label"--}}
{{--                                   for="hi_admin_cron_service_health_check_api_time">{{ __('Cron Job Time') }}</label>--}}
{{--                            <input type="text" class="next-input mt-2 col-md-6"--}}
{{--                                   name="hi_admin_cron_service_health_check_api_time"--}}
{{--                                   id="hi_admin_cron_service_health_check_api_time"--}}
{{--                                   placeholder="Set time cron jobs, example above!"--}}
{{--                                   value="{{ setting('hi_admin_cron_service_health_check_api_time') }}"/>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            --}}{{--End Health check--}}

{{--            --}}{{--Health check--}}
{{--            <div class="flexbox-annotated-section">--}}
{{--                <div class="flexbox-annotated-section-annotation">--}}
{{--                    <div class="annotated-section-title pd-all-20">--}}
{{--                        <h2 style="font-weight: 500; font-size: 20px !important;">Config Description Payment Error</h2>--}}
{{--                    </div>--}}
{{--                    <div class="annotated-section-description pd-all-20 p-none-t">--}}
{{--                        <p class="color-note">Settings description, status ...</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="flexbox-annotated-section-content">--}}
{{--                    <div class="wrapper-content pd-all-20">--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field form-check-label"--}}
{{--                                   for="hi_admin_cron_service_health_check_api_time">{{ __('Description error (separated by commas)') }}</label>--}}
{{--                            <textarea data-counter="120" type="text" class="next-input mt-2"--}}
{{--                                      name="hi_admin_web_service_payment_support_description"--}}
{{--                                      id="hi_admin_web_service_payment_support_description">{!! setting('hi_admin_web_service_payment_support_description') !!}</textarea>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            --}}{{--End Health check--}}

{{--            <div class="flexbox-annotated-section">--}}
{{--                <div class="flexbox-annotated-section-annotation">--}}
{{--                    <div class="annotated-section-title pd-all-20">--}}
{{--                        <h2 style="font-weight: 500; font-size: 20px !important;">Add key settings hi-admin-cron</h2>--}}
{{--                    </div>--}}
{{--                    <div class="annotated-section-description pd-all-20 p-none-t">--}}
{{--                        <p class="color-note">Example: <code>service_payment</code> will render 3 key--}}
{{--                        </p>--}}
{{--                        <code>hi_admin_cron_service_payment_enable--}}
{{--                        hi_admin_cron_service_payment_list_email--}}
{{--                        hi_admin_cron_service_payment_time</code>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="flexbox-annotated-section-content">--}}
{{--                    <div class="wrapper-content pd-all-20">--}}
{{--                        <div class="form-group mb-3">--}}
{{--                            <label class="text-title-field form-check-label"--}}
{{--                                   for="hi_admin_cron_service_check_payment_unpaid_list_email">{{ __('Key') }}</label>--}}
{{--                            <input data-counter="120" type="text" class="next-input mt-2"--}}
{{--                                   name="hi_admin_cron_add_key"--}}
{{--                                   id="hi_admin_cron_add_key"--}}
{{--                            placeholder="Add key cron jobs ..."/>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="flexbox-annotated-section" style="border: none">--}}
{{--                <div class="flexbox-annotated-section-annotation">--}}
{{--                </div>--}}
{{--                <div class="flexbox-annotated-section-content">--}}
{{--                    <button class="btn btn-info" type="submit">Lưu cài đặt</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        {!! Form::close() !!}--}}
{{--    </div>--}}
{{--@endsection--}}
