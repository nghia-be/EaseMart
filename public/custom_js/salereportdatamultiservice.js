$(document).ready(function() {
    var zones = $('#zone').val();
    $('#branch_code option').hide();
    $('#ftel_branch option').hide();

    if(zones.length > 0) {
        zones.forEach(zone => {
            $('#branch_code option[rel=' + zone.replace(/\s/g, '') + ']').show();
            $('#branch_code option[rel=' + zone.replace(/\s/g, '') + ']').each(function() {
                var branch_code_value = $(this).attr('data-locationId');
                $('#ftel_branch option[rel=' + branch_code_value + ']').show();
            });
        });
    }
    else {
        $('#branch_code option').show();
        $('#ftel_branch option').show();
    }

    $('#branch_code').selectpicker('refresh');
    $('#ftel_branch').selectpicker('refresh');
});

$("#zone").change(function () {
    var zones = $('#zone').val();
    $('#branch_code option').hide();
    $('#ftel_branch option').hide();

    if(zones.length > 0) {
        zones.forEach(zone => {
            $('#branch_code option[rel=' + zone.replace(/\s/g, '') + ']').show();
            $('#branch_code option[rel=' + zone.replace(/\s/g, '') + ']').each(function() {
                var branch_code_value = $(this).attr('data-locationId');
                $('#ftel_branch option[rel=' + branch_code_value + ']').show();
            });
        });
    }
    else {
        $('#branch_code option').show();
        $('#ftel_branch option').show();
    }

    $('#branch_code').selectpicker('refresh');
    $('#ftel_branch').selectpicker('refresh');
});

$("#branch_code").change(function () {
    var branch_code = $('#branch_code').val();
    $('#ftel_branch option').hide();
    if(branch_code.length > 0) {
        $("#branch_code option:selected").each(function() {
            $('#ftel_branch option[rel=' + $(this).attr('data-locationId') + ']').show();
        });
    }
    else {
        var branch_code_value = $('#branch_code option:not([style*="display: none"])');
        branch_code_value.each(function() {
            var branch_code_value = $(this).attr('data-locationId');
            $('#ftel_branch option[rel=' + branch_code_value + ']').show();
        });
    }

    $('#ftel_branch').selectpicker('refresh');
});

function exportPhoneOnly(e) {
    e.preventDefault();
    var filter_data = $('#filter-form').serializeArray();
    console.log(filter_data);
}

$('#is-and-service-type').change(function() {
    if(this.checked) {
        $('#all-export').hide();
    }
    else {
        $('#all-export').show();
    }       
});