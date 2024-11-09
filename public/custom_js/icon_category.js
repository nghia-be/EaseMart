"use strict";

var slider;

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
        format: "YYYY-MM-DD HH:mm",
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
        }
    });

    $('#show_to').datetimepicker({
        format: "YYYY-MM-DD HH:mm",
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
        }
    });

    $('#new_from').datetimepicker({
        format: "YYYY-MM-DD HH:mm:SS",
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
        }
    });

    $('#new_to').datetimepicker({
        format: "YYYY-MM-DD HH:mm:SS",
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
        }
    });

    // lightSlider
    slider = $('#all-product').lightSlider({
        item: 5,
        loop: true,
        slideMove: 1,
        easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
        speed: 600,
        slideMargin: 15,
        enableDrag: false,
        enableTouch: false,
        pager: false
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

$('input:radio[name="status"]').change(() => {
    if ($('#status-clock').is(':checked')) {
        $('#status-clock-date-time').show();
    }
    else {
        $('#status-clock-date-time').hide();
    }
});

$('input:checkbox[name="is_new_show"]').change(() => {
    if ($('#is-new-show').is(':checked')) {
        $('#is-new-icon-show-date-time').show();
    }
    else {
        $('#is-new-icon-show-date-time').hide();
    }
});

// Dragula CSS Release 3.2.0 from: https://github.com/bevacqua/dragula
dragula([document.getElementById('all-product'), document.getElementById('selected-product')], {
    direction: 'horizontal',
    revertOnSpill: true,
    copy: function (el, source) {
        return source === document.getElementById('all-product')
    },
    accepts: function (el, target, source, sibling) {
        var li_all = $(el).attr('id');
        if ($('#' + li_all + '-selected-product').length != 0) {
            swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `Sản phẩm này đã tồn tại trong danh mục`
            });
            return false;
        }

        return target !== document.getElementById('all-product')
    }
}).on('drop', (el, target, source, sibling) => {
    var li_all = $(el).attr('id');
    $(el).attr('id', li_all + '-selected-product');
    $(el).removeClass("lslide");
    $(el).removeClass("active");
    $(el).removeClass("gu-transit");
    $(el).addClass("col-sm-2");

    $(el).css('margin-right', 0);

    var spanElement = $(el).find("span:first");
    $(spanElement).removeClass("badge-light");
    $(spanElement).addClass("badge-dark");

    if ($(el).find('span.position').length < 1) {
        $(el).append(`<h6><span class="badge badge-warning position">${$(el).index() + 1}</span></h6>`);
    }

    $(target).find("li").each((key, value) => {
        $(value).find("span.position").text($(value).index() + 1);
    });

    // $("#" + li_all).remove();
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

$('#icon-category tbody').on('click', '.delete-button', function () {
    var data = icon_category.row($(this).parents('tr')).data();
    deleteButton(JSON.stringify(data), data['productTitleNameVi'], '/iconcategory/destroy');
});

