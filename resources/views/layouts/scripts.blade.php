<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)

</script>
<!-- Bootstrap 4 -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/chart.js/Chart.min.js"></script>
<!-- jQuery Knob Chart -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/moment/moment.min.js"></script>
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/dist/js/pages/dashboard3.js"></script>
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/select2/js/select2.full.min.js"></script>
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/bootstrap-select-1.13.14/dist/js/bootstrap-select.js"></script>
<script src="{{asset('custom_js/jquery.pjax.js')}}"></script>
<!-- DataTables -->

<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/datatables/jquery.dataTables.js"></script>
{{-- <script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/datatables-searchpanes/js/dataTables.searchPanes.min.js"></script>
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/datatables-searchpanes/js/searchPanes.bootstrap4.min.js"></script> --}}
{{-- <script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script> --}}
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/datatables-select/js/dataTables.select.min.js"></script>
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- jquery-tmpl -->
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/jquery-tmpl/jquery-tmpl.min.js"></script>
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/toastr/toastr.min.js"></script>
<!-- jQuery UI 1.11.4 -->

<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/dragula-master/dist/dragula.min.js"></script>
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/lightslider-master/dist/js/lightslider.min.js"></script>
<script src="{{url(Theme::find(config('platform_config.current_theme'))->themesPath)}}/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>


<script src="{{ asset('js/dataTablebutton.min.js') }}"></script>
<script src="{{ asset('js/buttonHtml5.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('custom_js/javascript.js')}}"></script>
<script src="{{ asset('custom_js/initTable.js')}}"></script>
<script src="{{ asset('/custom_js/rolemanage.js')}}"></script>
<script src="{{ asset('/custom_js/closerequest.js')}}"></script>
<script src="{{ asset('/custom_js/updateprofile.js')}}"></script>
<script src="{{ asset('/custom_js/smsworld.js')}}"></script>
<script src="{{ asset('/custom_js/checkuserinfo.js')}}"></script>
<script src="{{ asset('/custom_js/popupmanage.js')}}"></script>
<script src="{{ asset('/custom_js/otp.js')}}"></script>
<script src="{{ asset('/custom_js/ftel_phone.js')}}"></script>
<script src="{{ asset('/custom_js/air_direction.js')}}"></script>
<script src="{{ asset('/base/libraries/pace/pace.min.js')}}"></script>
<script>
@if($errors->any())
    showMessage('error','{{$errors->first()}}');
@endif
@if (session()->has('success'))
    showMessage('success', `{{ (session()->has('html')) ? session()->get('html') : '' }}`)
@endif

@if (session()->has('error'))
    showMessage('error', `{{ (session()->has('html')) ? session()->get('html') : '' }}`)
@endif

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

{{ session()->forget('error') }}
{{ session()->forget('success') }}
{{ session()->forget('html') }}

</script>
