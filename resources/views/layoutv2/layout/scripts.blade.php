<script type="text/javascript" id="vendor-js" src="{{ asset('assets/builds/vendor-admin.js')}}"></script>
<script type="text/javascript" id="jquery-migrate-js" src="{{ asset('assets/plugins/jquery/jquery-migrate.min.js')}}"></script>
<script type="text/javascript" id="datatables-js" src="{{ asset('assets/plugins/datatables/datatables.min.js')}}"></script>
<script type="text/javascript" id="moment-js" src="{{ asset('assets/builds/moment.min.js')}}"></script>
<script type="text/javascript" id="bootstrap-select-js" src="{{ asset('assets/builds/bootstrap-select.min.js')}}"></script>
<script type="text/javascript" id="tinymce-js" src="{{ asset('assets/plugins/tinymce/tinymce.min.js')}}"></script>
<script type="text/javascript" id="tinymce-dropzone-js" src="{{ asset('assets/plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript" id="jquery-validation-js" src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script type="text/javascript" id="jquery-validation-lang-js" src="{{ asset('assets/plugins/jquery-validation/localization/messages_vi.min.js')}}"></script>
<script type="text/javascript" id="google-js" src="https://apis.google.com/js/api.js?onload=onGoogleApiLoad" defer></script>
<script type="text/javascript" id="common-js" src="{{ asset('assets/builds/common.js')}}"></script>
<script type="text/javascript" id="app-js" src="{{ asset('assets/js/main.js')}}"></script>
<script type="text/javascript" id="fullcalendar-js" src="{{ asset('assets/plugins/fullcalendar/lib/main.min.js')}}"></script>
<script type="text/javascript" id="fullcalendar-lang-js" src="{{ asset('assets/plugins/fullcalendar/lib/locales/vi.js')}}"></script>
<script type="text/javascript" id="daterangepicker-js" src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" id="javascript-js" src="{{ asset('custom_js/javascript.js')}}"></script>
<!--<script>
    $(function(){
        if(typeof("system_popup") != undefined) {
            var popupData = {};
            popupData.message = 'Thông báo';
            system_popup(popupData);
        }
    });
    $(function(){
        alert_float("success","' . $alert_message . '");
    });
</script>-->
<script>
    @if($errors->any())
        alert_float('danger','{{$errors->first()}}');
    @elseif (session()->has('success'))
        alert_float('success', `{{ (session()->has('html')) ? session()->get('html') : '' }}`)
    @elseif (session()->has('error') || session()->has('danger'))
        alert_float('danger', `{{ (session()->has('html')) ? session()->get('html') : '' }}`)
    @elseif (session()->has('status'))
        alert_float(`{{session()->get('status')}}`, `{{ (session()->has('html')) ? session()->get('html') : '' }}`)
    @endif

    function csrf_jquery_ajax_setup() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ajaxError(function (event, request, settings) {
            if (request.status === 419) {
                alert_float('warning', 'Page expired, refresh the page make an action.')
            }
        });
    }

    {{ session()->forget('error') }}
    {{ session()->forget('success') }}
    {{ session()->forget('danger') }}
    {{ session()->forget('html') }}
    {{ session()->forget('status') }}

</script>

