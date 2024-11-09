<div class="horizontal-scrollable-tabs panel-full-width-tabs">
    <div class="scroller arrow-left" style="display: none;"><i class="fa fa-angle-left"></i></div>
    <div class="scroller arrow-right" style="display: none;"><i class="fa fa-angle-right"></i></div>
    <div class="horizontal-tabs">
        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
            <li role="presentation" class="active">
                <a href="#cron_command" aria-controls="cron_command" role="tab" data-toggle="tab"
                   aria-expanded="true">Command</a>
            </li>
            @if(!empty($data['key']))
                @foreach($data['key'] as $value)
                    <li role="presentation" class="">
                        <a href="#{{$value}}" aria-controls="{{$value}}" role="tab" data-toggle="tab"
                           aria-expanded="false">{{Str::title(str_replace("_", " ", str_replace("service_", "", $value)))}}</a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
<div class="tab-content mtop15">
    <div role="tabpanel" class="tab-pane active" id="cron_command">
        <div class="form-group content editor">
            <div class="schedule"><input type="text"
                                         class="form-control schedule" data-toggle="tooltip"
                                         data-title="Biểu thức thực hiện thao tác tự động mỗi ngày."
                                         value="*/10 * * * *">
            </div>
            <div class="description"><div class="text"></div></div>
            <div class="schedule">
                <div class="breakdown"><h2 class="heading minute">Minutes</h2>
                    <div class="text minute">:01</div>
                    <h2 class="heading hour">Hours</h2>
                    <div class="text hour">1am</div>
                    <h2 class="heading day-of-month">Day of Month</h2>
                    <div class="text day-of-month">1st</div>
                    <h2 class="heading month">Month</h2>
                    <div class="text month">January</div>
                    <h2 class="heading day-of-week">Day of Week</h2>
                    <div class="text day-of-week">Monday</div>
                </div>
            </div>
        </div>
        <div class="alert alert-info tw-mb-0">
            <div class="wrapper-content pd-all-20">
                        <pre tabindex="0"><code class="border-none"># ┌───────────── minute (0 - 59)
# │ ┌───────────── hour (0 - 23)
# │ │ ┌───────────── day of the month (1 - 31)
# │ │ │ ┌───────────── month (1 - 12)
# │ │ │ │ ┌───────────── day of the week (0 - 6) (Sunday to Saturday;
# │ │ │ │ │                                   7 is also Sunday on some systems)
# │ │ │ │ │                                   OR sun, mon, tue, wed, thu, fri, sat
# │ │ │ │ │
# * * * * *     >>>     input = * * * * * (run every a minutes)
</code></pre>

                <table class="table">
                    <thead>
                    <tr>
                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Miêu
                                    tả</font></font></th>
                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Tương
                                    đương với</font></font></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Chạy
                                    mỗi năm một lần vào nửa đêm ngày 1 tháng 1</font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0
                                    0 1
                                    1 *</font></font></td>
                    </tr>
                    <tr>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Chạy
                                    mỗi tháng một lần vào nửa đêm của ngày đầu tiên của tháng</font></font>
                        </td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0
                                    0 1
                                    * *</font></font></td>
                    </tr>
                    <tr>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Chạy
                                    mỗi tuần một lần vào lúc nửa đêm vào sáng Chủ Nhật</font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0
                                    0 *
                                    * 0</font></font></td>
                    </tr>
                    <tr>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Chạy
                                    một lần một ngày vào lúc nửa đêm</font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0
                                    0 *
                                    * *</font></font></td>
                    </tr>
                    <tr>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Chạy
                                    mỗi giờ một lần vào đầu giờ</font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">0
                                    * *
                                    * *</font></font></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label for="hi_admin_cron_add_key" class="control-label">
                <i class="fa-regular fa-circle-question" data-toggle="tooltip"
                   data-title="Example: service will render 3 key hi_admin_cron_service_enable,
                        hi_admin_cron_service_list_email, hi_admin_cron_service_time"></i>
                {{ __('Add key cronjob (Only Dev Use)') }}
            </label>
            <input type="text" id="hi_admin_cron_add_key"
                   name="hi_admin_cron_add_key"
                   class="form-control" data-toggle="tooltip"
                   data-title="Định dạng tên dịch vụ, ví dụ: service."
                   placeholder="Add key">
        </div>
    </div>
    @if(!empty($data['key']))
        @foreach($data['key'] as $value)
                <?php
                $key = 'hi_admin_cron_' . $value;
                $to = json_decode(setting($key . '_list_email'), true)[0]['to'] ?? null;
                $cc = json_decode(setting($key . '_list_email'), true)[0]['cc'] ?? null;
                $bcc = json_decode(setting($key . '_list_email'), true)[0]['bcc'] ?? null;
                ?>
            <div role="tabpanel" class="tab-pane" id="{{$value}}">
                <div class="form-group">
                    <label
                        for="{{ $key }}_time" class="control-label">
                        {{ __('Biểu thức thực hiện thao tác tự động mỗi ngày') }}
                    </label>
                    <input type="text" id="{{ $key }}_time"
                           name="{{ $key }}_time"
                           class="form-control schedule" data-toggle="tooltip"
                           data-title="Biểu thức thực hiện thao tác tự động mỗi ngày."
                           value="{{ setting($key.'_time') }}">
                </div>
                <hr>
                @if($value != 'service_health_check_api')
                    <div class="form-group">
                        <div class="form-group no-mbot">
                            <label for="email_to" class="control-label"><i class="fa fa-envelope" aria-hidden="true"></i>
                                Email to</label>
                            <input type="text" class="tagsinput" id="{{$key}}_email_to" name="{{$key}}_email_to"
                                   value="{{ $to }}"
                                   data-role="tagsinput">
                        </div>
                        <div class="form-group no-mbot">
                            <label for="email_cc" class="control-label"><i class="fa fa-envelope" aria-hidden="true"></i>
                                Email cc</label>
                            <input type="text" class="tagsinput" id="{{$key}}_email_cc" name="{{$key}}_email_cc"
                                   value="{{ $cc }}"
                                   data-role="tagsinput">
                        </div>
                        <div class="form-group no-mbot">
                            <label for="email_bcc" class="control-label"><i class="fa fa-envelope" aria-hidden="true"></i>
                                Email bcc</label>
                            <input type="text" class="tagsinput" id="{{$key}}_email_bcc" name="{{$key}}_email_bcc"
                                   value="{{ $bcc }}"
                                   data-role="tagsinput">
                        </div>
                    </div>
                    <hr>
                @endif
                <div class="form-group">
                    <label class="control-label clearfix">
                        <i class="fa-regular fa-circle-question" data-toggle="tooltip"
                           data-title="Bật tắt cron của dịch vụ?"></i>
                        Bật tắt cron của dịch vụ? </label>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio"
                               id="{{ $key }}_enable_1"
                               name="{{ $key }}_enable" value="1"
                               @if (setting($key.'_enable', '0') === '1') checked @endif>
                        <label for="{{ $key }}_enable_1">
                            Có </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio"
                               id="{{ $key }}_enable_0"
                               name="{{ $key }}_enable" value="0"
                               @if (setting($key.'_enable', '0') === '0') checked @endif>
                        <label for="{{ $key }}_enable_0">
                            Không </label>
                    </div>
                </div>
                @if($value == 'service_air_condition_weekly')
                    <hr>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="{{ $key }}_button" class="control-label">
                                        Lấy dữ liệu thủ công
                                    </label>
                                    <div class="input-group date">
                                        <input type="text" id="manually_email_daterange" class="form-control daterange" autocomplete="off">
                                        <div class="input-group-addon">
                                            <i class="fa-regular fa-calendar calendar-icon"></i>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"> </label>
                                <div class="input-group">
                                    <button onclick="sendMailManually(this)" data-key="{{$value}}" type="button"  class="tw-mt-1 btn btn-info">Send mail</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>

@push('script')
    <script type="text/javascript" id="cron-js" src="{{ asset('assets/js/cron.js')}}"></script>
    <script>
        function sendMailManually(_this){
            let data = {};
            data.daterange = $('#manually_email_daterange').val();
            data.key = $(_this).data('key');
            $.post('/setting/sendMailManually', data).done(function(response) {
                alert_float('success', response.html);
            }).fail(function(xhr) {
                var errorString = xhr.responseJSON.message;
                $.each(xhr.responseJSON.errors, function (key, value) {
                    errorString = value;
                    return false;
                });
                alert_float('danger', errorString);
            });
        }
    </script>
@endpush
