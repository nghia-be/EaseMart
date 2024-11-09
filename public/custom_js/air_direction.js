'use strict';

$('body').on('click', '#submit', function (event){
    $(this).attr('disabled','disabled');
    event.preventDefault();
    let data = $('#formActionAirDiretion').serialize();
    $.ajax({
        url: urlMethod,
        type: 'POST',
        dataType: 'json',
        data: data,
        cache: false,
        success: (data) => {
            if(data.data.statusCode === 0){
                $('#push_air_direction').modal('toggle');
                alert_float('success', data.data.message);
                $('#submit').prop('disabled', false);
                var table = $('#air_direction_table').DataTable();
                table.ajax.reload();
            }else{
                alert_float('danger',data.data.message);
                $('#submit').prop('disabled', false);
            }
        },
        error: function (xhr) {
            var errorString = xhr.responseJSON.message;
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString = value;
                return false;
            });
            alert_float('danger', errorString);
            $('#submit').prop('disabled', false);
        }
    });
});

$('body').on('click', '#push_air_direction_form', function (e) {
    e.preventDefault();
    document.getElementById('formActionAirDiretion').reset();
    $('#key_air_direction').val(0).change();
    $('#push_air_direction').modal('toggle');
    window.urlMethod = '/air-direction/add';
});
function detailAirDirection(_this){
    let dataPost = {};
    dataPost.id = $(_this).data('id');
    $.post('/air-direction/getById', dataPost).done(function(response) {
        for (let [key, value] of Object.entries(response)) {
            let air_direction = $('#'+key+'_air_direction');
            if (key === 'key') {
                if (value==='open_url_in_app_with_access_token') {
                    value = 1;
                } else {
                    value = 0;
                }
            }
            air_direction.val(value);
            air_direction.trigger('change');
        }
        $('#push_air_direction').modal('toggle');
        window.urlMethod = '/air-direction/update';
    });
}

function deleteAirDirection(data){
    let dataPost = {};
    dataPost.id = $(data).data('id');
    dataPost.key = $(data).data('key');
    $.post('/air-direction/delete', dataPost).done(function(response) {
        const status = (response.data.statusCode === 0) ? 'success' : 'danger';
        alert_float(status, response.data.message);
        var table = $('#air_direction_table').DataTable();
        table.ajax.reload(null,false);
    }).fail(function(xhr) {
        var errorString = xhr.responseJSON.message;
        $.each(xhr.responseJSON.errors, function (key, value) {
            errorString = value;
            return false;
        });
        alert_float('danger', errorString);
        console.log(xhr);
    });
}
