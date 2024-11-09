"use strict";

function unclockSupportCode(_this) {
    var table = $("#support-code-table").DataTable();
    var data = table.row($(_this).parents('tr')).data();

    $("#support-code-modal").val(data.support_code);
    $('#noteForLogs_Modal').modal();
    // I7652870
}