<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>{{empty($title) ? 'EaseMart' : $title}}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0ulwyD-c8W2n8J4d9as2nbOxz-VuFZsc&libraries=places"></script>
    <link
      rel="stylesheet"
      href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css"
    />
    <link rel="stylesheet" type="text/css" id="reset-css" href="{{ asset('assets/css/reset.min.css')}}">
    <link rel="stylesheet" type="text/css" id="inter-font" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" type="text/css" id="vendor-css" href="{{ asset('assets/builds/vendor-admin.css')}}">
    <link rel="stylesheet" type="text/css" id="fontawesome-css"
          href="{{ asset('assets/plugins/font-awesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" type="text/css" id="fontawesome-brands"
          href="{{ asset('assets/plugins/font-awesome/css/brands.min.css')}}">
    <link rel="stylesheet" type="text/css" id="fontawesome-solid"
          href="{{ asset('assets/plugins/font-awesome/css/solid.min.css')}}">
    <link rel="stylesheet" type="text/css" id="fontawesome-regular"
          href="{{ asset('assets/plugins/font-awesome/css/regular.min.css')}}">
    <link rel="stylesheet" type="text/css" id="tailwind-css" href="{{ asset('assets/builds/tailwind.css?v=3.0.4')}}">
    <link rel="stylesheet" type="text/css" id="app-css" href="{{ asset('assets/css/style.min.css')}}">
    <link rel="stylesheet" type="text/css" id="app-css" href="{{ asset('assets/css/phieu_xuat.css')}}">
    <link rel="stylesheet" type="text/css" id="app-css" href="{{ asset('assets/css/styletransaction.css')}}">
    <link rel="stylesheet" type="text/css" id="app-css" href="{{ asset('assets/css/input.css')}}">
    <link rel="stylesheet" type="text/css" id="app-css" href="{{ asset('assets/css/output.css')}}">
    <link rel="stylesheet" type="text/css" id="app-css" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
    
    <link rel="stylesheet" type="text/css" id="fullcalendar-css"
          href="{{ asset('assets/plugins/fullcalendar/lib/main.min.css')}}">
    <link rel="stylesheet" type="text/css" id="custom-css" href="{{ asset('assets/css/custom.css')}}">
    <link rel="icon" sizes="192x192"  href="{{asset('themes/images/easemart-logo1.png')}}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">
    <script>
        const URL_STATIC = "{{env('URL_STATIC')}}";
        var site_url = "{{ url('') }}";
        var admin_url = "{{ url('') }}";
        var app = {};
        app.available_tags = [];
        app.available_tags_ids = [];
        app.user_recent_searches = [""];
        app.months_json = ["Th\u00e1ng 1", "Th\u00e1ng 2", "Th\u00e1ng 3", "Th\u00e1ng 4", "Th\u00e1ng 5", "Th\u00e1ng 6", "Th\u00e1ng 7", "Th\u00e1ng 8", "Th\u00e1ng 9", "Th\u00e1ng 10", "Th\u00e1ng 11", "Th\u00e1ng 12"];
        app.tinymce_lang = "vi";
        app.locale = "vi";
        app.browser = "chrome";
        app.user_language = "vietnamese";
        app.is_mobile = "";
        app.user_is_staff_member = "1";
        app.user_is_admin = "1";
        app.max_php_ini_upload_size_bytes = "2147483648";
        app.calendarIDs = "";
        app.options = {};
        app.lang = {};
        app.options.date_format = "Y-m-d H:i:s";
        app.options.decimal_places = "2";
        app.options.company_is_required = "1";
        app.options.default_view_calendar = "dayGridMonth";
        app.options.calendar_events_limit = "4";
        app.options.tables_pagination_limit = "25";
        app.options.time_format = "24";
        app.options.decimal_separator = ".";
        app.options.thousand_separator = ",";
        app.options.timezone = "Asia/Ho_Chi_Minh";
        app.options.calendar_first_day = "0";
        app.options.allowed_files = ".png,.jpg,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt";
        app.options.desktop_notifications = "0";
        app.options.show_table_export_button = "to_all";
        app.options.has_permission_tasks_checklist_items_delete = "1";
        app.options.show_setup_menu_item_only_on_hover = "0";
        app.options.newsfeed_maximum_files_upload = "10";
        app.options.dismiss_desktop_not_after = "0";
        app.options.enable_google_picker = "1";
        app.options.google_client_id = "";
        app.options.google_api = "";
        app.options.has_permission_create_task = "1";
        var app_language = "vietnamese", app_is_mobile = "", app_user_browser = "chrome", app_date_format = "Y-m-d",
            app_decimal_places = "2", app_company_is_required = "1", app_default_view_calendar = "dayGridMonth",
            app_calendar_events_limit = "4", app_tables_pagination_limit = "25", app_time_format = "24",
            app_decimal_separator = ".", app_thousand_separator = ",", app_timezone = "Asia/Ho_Chi_Minh",
            app_calendar_first_day = "0", app_allowed_files = ".png,.jpg,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt",
            app_desktop_notifications = "0", max_php_ini_upload_size_bytes = "2147483648",
            app_show_table_export_button = "to_all", calendarIDs = "", is_admin = "1", is_staff_member = "1",
            has_permission_tasks_checklist_items_delete = "1", app_show_setup_menu_item_only_on_hover = "0",
            app_newsfeed_maximum_files_upload = "10", app_dismiss_desktop_not_after = "0",
            app_enable_google_picker = "1", app_google_client_id = "", google_api = "";
        var appLang = {};
    </script>
    <script>
        var totalUnreadNotifications = 0,
            proposalsTemplates = [],
            contractsTemplates = [],
            isRTL = 'false',
            taskid, taskTrackingStatsData, taskAttachmentDropzone, taskCommentAttachmentDropzone, newsFeedDropzone,
            expensePreviewDropzone, taskTrackingChart, cfh_popover_templates = {},
            _table_api;
    </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            if (typeof (jQuery) === 'undefined' && !window.deferAfterjQueryLoaded) {
            window.deferAfterjQueryLoaded = [];
            Object.defineProperty(window, "$", {
                set: function (value) {
                    window.setTimeout(function () {
                        $.each(window.deferAfterjQueryLoaded, function (index, fn) {
                            fn();
                        });
                    }, 0);
                    Object.defineProperty(window, "$", {
                        value: value
                    });
                },
                configurable: true
            });
        }

        if (typeof (jQuery) == 'undefined') {
            window.deferAfterjQueryLoaded.push(function () {
                csrf_jquery_ajax_setup();
            });
            window.addEventListener('load', function () {
                csrf_jquery_ajax_setup();
            }, true);
        } else {
            csrf_jquery_ajax_setup();
        }

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
        });

        
    </script>
    <script>

        var leadUniqueValidationFields = ["email"];
        var leadAttachmentsDropzone;
    </script>
</head>


