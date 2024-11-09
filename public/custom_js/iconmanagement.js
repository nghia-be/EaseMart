"use strict";

var today = new Date();
// today.setMinutes(today.getMinutes() - 1);
// today.setSeconds(0);
today.setHours(0, 0, 0, 0);

var tomorrow = new Date();
tomorrow.setDate(today.getDate() + 1);
tomorrow.setHours(0, 0, 0, 0);

$(document).ready(function () {
    var $form = $('form');
    var initialState = $form.serialize();
    
    $form.change(function (e) {
        if (initialState === $form.serialize()) {
            $( "#submit-button" ).prop( "disabled", true );
        } else {
            $( "#submit-button" ).prop( "disabled", false );
        }
        e.preventDefault();
    });

    if ($('#status-clock').is(':checked')) {
        $('#status-clock-date-time').show();
    }
    else {
        $('#status-clock-date-time').hide();
    }

    if ($('#is-new-show').is(':checked')) {
        $('#is-new-icon-show-date-time').show();
    }
    else {
        $('#is-new-icon-show-date-time').hide();
    }

    $('#show_from').datetimepicker({
        format: "YYYY-MM-DD HH:mm:ss",
        useCurrent: false,
        sideBySide: true,
        icons: {
            time: 'fas fa-clock',
            date: 'fas fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-arrow-left',
            next: 'fas fa-arrow-right',
            today: 'fas fa-calendar-day',
            clear: 'fas fa-trash',
            close: 'fas fa-window-close'
        },
        minDate: ($('#show_from').val()) ? new Date($('#show_from').val()) : today,
        maxDate: ($('#show_to').val()) ? new Date($('#show_to').val()) : tomorrow,
    });

    $('#show_to').datetimepicker({
        format: "YYYY-MM-DD HH:mm:ss",
        useCurrent: false,
        sideBySide: true,
        icons: {
            time: 'fas fa-clock',
            date: 'fas fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-arrow-left',
            next: 'fas fa-arrow-right',
            today: 'fas fa-calendar-day',
            clear: 'fas fa-trash',
            close: 'fas fa-window-close'
        },
        minDate: ($('#show_from').val()) ? new Date($('#show_from').val()) : tomorrow,
    });

    $('#new_from').datetimepicker({
        format: "YYYY-MM-DD HH:mm:ss",
        useCurrent: false,
        sideBySide: true,
        icons: {
            time: 'fas fa-clock',
            date: 'fas fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-arrow-left',
            next: 'fas fa-arrow-right',
            today: 'fas fa-calendar-day',
            clear: 'fas fa-trash',
            close: 'fas fa-window-close'
        },
        minDate: ($('#new_from').val()) ? new Date($('#new_from').val()) : today,
        maxDate: ($('#new_to').val()) ? new Date($('#new_to').val()) : tomorrow,
    });

    $('#new_to').datetimepicker({
        format: "YYYY-MM-DD HH:mm:ss",
        useCurrent: false,
        sideBySide: true,
        icons: {
            time: 'fas fa-clock',
            date: 'fas fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-arrow-left',
            next: 'fas fa-arrow-right',
            today: 'fas fa-calendar-day',
            clear: 'fas fa-trash',
            close: 'fas fa-window-close'
        },
        minDate: ($('#new_from').val()) ? new Date($('#new_from').val()) : today,
    });
});

$("#show_from").on("dp.change", function (e) {
    if ($('#show_to').data("DateTimePicker") != undefined) {
        $('#show_to').data("DateTimePicker").minDate(e.date);
    }
});

$("#show_to").on("dp.change", function (e) {
    if ($('#show_from').data("DateTimePicker") != undefined) {
        $('#show_from').data("DateTimePicker").maxDate(e.date);
    }
});

$("#new_from").on("dp.change", function (e) {
    if ($('#new_to').data("DateTimePicker") != undefined) {
        $('#new_to').data("DateTimePicker").minDate(e.date);
    }
});

$("#new_to").on("dp.change", function (e) {
    if ($('#new_from').data("DateTimePicker") != undefined) {
        $('#new_from').data("DateTimePicker").maxDate(e.date);
    }
});

$('input:radio[name="isDisplay"]').change(() => {
    if ($('#status-clock').is(':checked')) {
        $('#status-clock-date-time').show();
    }
    else {
        $('#status-clock-date-time').hide();
    }
});

$('input:checkbox[name="isNew"]').change(() => {
    if ($('#is-new-show').is(':checked')) {
        $('#is-new-icon-show-date-time').show();
    }
    else {
        $('#is-new-icon-show-date-time').hide();
    }
});

$("#status-all").change(function () {
    if (this.checked) {
        $("input[name='status']").prop('checked', true);
    }
    else {
        $("input[name='status']").prop('checked', false);
    }
});

$("#pheduyet-all").change(function () {
    if (this.checked) {
        $("input[name='pheduyet']").prop('checked', true);
    }
    else {
        $("input[name='pheduyet']").prop('checked', false);
    }
});

$('#icon-management tbody').on('click', '.delete-button', function () {
    var data = icon_management_table.row($(this).parents('tr')).data();
    deleteButton(JSON.stringify(data), data['productNameVi'], '/iconmanagement/destroy');
});

$('#action').on('change', function() {
    if($.inArray(this.value, ['open_url_in_app', 'open_url_out_app', 'open_url_in_app_with_access_token', 'open_url_in_browser'])) {
        $("#dataActionProduction").prop("disabled", false);
        $("#dataActionStaging").prop("disabled", false);
    }
    else {
        $("#dataActionProduction").prop("disabled", true);
        $("#dataActionStaging").prop("disabled", true);
    }
});