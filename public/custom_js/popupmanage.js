/*
    Create by: trinhdev
    Update at: 2022/08/04
    Contact: trinhhuynhdp@gmail.com
*/
'use strict';
function showHide() {
    $('#templateType_popup').on('change', function () {
        if ($('#templateType_popup').val() === 'popup_image_transparent' || $('#templateType_popup').val() === 'popup_image_full_screen') {
            $('#directionId_popup').attr('style', 'display: none !important');
            $('#buttonImage').attr('style', 'display: none !important');
            $('#form_directionUrl').attr('style', 'display: none !important');
        } else {
            $('#directionId_popup').attr('style', 'display: inline');
            $('#buttonImage').attr('style', 'display: inline');
            $('#form_directionUrl').attr('style', 'display: ');
        }
    });

    $('#type_popup').on('change', function () {
        if ($('#type_popup').val() === 'popup_image_transparent' || $('#type_popup').val() === 'popup_image_full_screen') {
            $('#iconButtonUrl').attr('style', 'display: none !important');
        } else {
            $('#iconButtonUrl').attr('style', 'display: inline');
        }
    });

    $('#repeatTime').on('change', function () {
        let repeatTime = $('#repeatTime').val();
        if (repeatTime === 'other') {
            $('#other_min').show();
        } else {
            $('#other_min').hide();
        }
    });

    $('#actionType_popup').on('change', function () {
        let type_direction = $('#actionType_popup').val();
        if (type_direction === '1') {
            $('#dataAction_popup').prop('readonly', false);
            $('#dataAction_popup').val('https://example.com');
        } else {
            $('#dataAction_popup').prop('readonly', true);
            $('#dataAction_popup').val($('#actionType_popup').find(':selected').text());
        }
    });

    $('#directionId_popup').on('change', function () {
        let type_direction = $('#directionId_popup').val();
        if (type_direction === '1') {
            $('#form_directionUrl').show();
        } else {
            $('#form_directionUrl').hide();
        }
    });
}
function resetData(input_tag, img_tag) {
    input_tag.value = null;
    img_tag.src = "/images/image_holder.png";
}

function getDetailPersonalMaps(idPersonalMaps) {
    $.ajax({
        type: 'POST',
        url: '/popupmanage/getDetailPersonalMaps',
        data: {'personalID': idPersonalMaps.getAttribute('personalID')},
        cache: false,
        success: (data) => {
            $('#object').val(data['pushedObject']).change();
            $('#repeatTime').val(data['showOnceTime']).change();
        },
        error: function (xhr) {
            let errorString = '';
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString = value;
                return false;
            });
            alert_float('danger',errorString);
        }
    });
    $('#popupModal').modal();
}


async function handleUploadImagePopup(_this, event) {
    event.preventDefault();
    let img_tag_name = _this.name + '_popup';
    let img_tag = document.getElementById(img_tag_name);
    if (_this.value === '') {
        resetData(_this, img_tag);
    }
    const [file] = _this.files;
    if (file) {
        if (file.size > 2050000) { // handle file
            resetData(_this, img_tag);
            alert_float('danger',"File is too big! Allowed memory size of 2MB");
            return false;
        }
        uploadFileExternal(file, successCallUploadImagePopup, {
            'img_tag': img_tag,
            'input_tag': _this,
            'file': file
        });
    }
}

function successCallUploadImagePopup(response, passingdata) {
    if (response.statusCode === 0 && response.data !== null) {
        passingdata.img_tag.src = URL.createObjectURL(passingdata.file);
        document.getElementById(passingdata.img_tag.id + '_name').value = response.data.uploadedImageFileName;
    } else {
        resetData(passingdata.input_tag, passingdata.img_tag);
        document.getElementById(passingdata.img_tag.id + '_name').value = "";
        alert_float('danger',response.message);
    }
}


function detailPopup(_this){
    let dataPost = {};
    dataPost.id = $(_this).data('id');
    let url = 'popupmanage/detail/' + dataPost.id;
    $.get(url, dataPost).done(function(response) {
        for (const [key, value] of Object.entries(response)) {
            if(key==='image' || key==='buttonImage') {
                $('#'+key+'_popup').attr("src",URL_STATIC + "/upload/images/event/" + value);
                $('#'+key+'_popup_name').val(value);
            }else if(key==='templateType' || key==='directionId') {
                $('#'+key+'_popup').val(value);
                $('#'+key+'_popup').trigger('change');
            } else {
                $('#'+key+'_popup').val(value);
            }
        }
        $('#show_detail_popup').modal('toggle');
    });
}

function pushPopup() {
    document.getElementById('formAction').reset();
    $('#id_popup').val('');
    document.getElementById('image_popup').attributes[1].value = '/images/image_holder.png';
    document.getElementById('buttonImage_popup').attributes[1].value = '/images/image_holder.png';
    $('#show_detail_popup').modal('toggle');
}

function storePopup() {
    let data = $('#formAction').serialize();
    console.log(data);
    $.ajax({
        url: 'popupmanage/save',
        type:'POST',
        data: data,
        processData: false,
        success: function(data) {
            if(data.data.statusCode === 0){
                $('#show_detail_popup').modal('toggle');
                alert_float('success','Thành công');
                var table = $('#popup_manage_table').DataTable();
                table.ajax.reload(null, false);
            }else{
                alert_float('danger',data.data.message);
            }
        },
        error: function (xhr) {
            var errorString = xhr.responseJSON.message;
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString = value;
                return false;
            });
            alert_float('danger',errorString);
            console.log(data);
        }
    });
}

function handlePushPopUpPrivate() {
    $('body').on('click', '#submit', function (event){
        $(this).attr('disabled','disabled');
        event.preventDefault();
        let data = $('#formActionPrivate').serialize();
        $.ajax({
            url: urlMethod,
            type: 'POST',
            dataType: 'json',
            data: data,
            cache: false,
            success: (data) => {
                if(data.data.statusCode === 0){
                    $('#push_popup_private').modal('toggle');
                    alert_float('success', data.data.message);
                    $('#submit').prop('disabled', false);
                    var table = $('#popup_private_table').DataTable();
                    table.ajax.reload(null, false);
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
                alert_float('danger',errorString);
                $('#submit').prop('disabled', false);
            }
        });
    });
}

function methodAjaxPopupPrivate() {
    $('body').on('click', '#push_popup_private_form', function (e) {
        e.preventDefault();
        $('#push_popup_private').modal('toggle');
        document.getElementById('formActionPrivate').reset();
        document.getElementById('timeline').value = getDate() + " 00:00:00" + " - " + getDate() + " 23:59:59";
        document.getElementById('iconUrl_popup').attributes[1].value = '/images/image_holder.png';
        document.getElementById('iconButtonUrl_popup').attributes[1].value = '/images/image_holder.png';
        window.urlMethod = '/popup-private/addPrivate';
    });

    $('body').on('click', '#detailPopup', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            url: '/popup-private/getByIdPrivate/',
            type:'GET',
            data: {
                id: id
            }, success: function (response){
                $('#number_phone').val('');
                $('#importExcel').find('input:text, input:password, input:file, select, textarea').val('');
                for (const [key, value] of Object.entries(response[0])) {
                    if(key==='dateBegin') {
                        $('#timeline').val(value);
                    }
                    if(key==='dateEnd') {
                        let timeline_current = $('#timeline').val();
                        let timeline = timeline_current + ' - ' + value;
                        $('#timeline').val(timeline);
                    }
                    if(key==='iconUrl' || key==='iconButtonUrl') {
                        $('#' + key + '_popup').attr('src', URL_STATIC + '/upload/images/event/' + value);
                        $('#' + key + '_popup_name').val(value);
                    }
                    if(key==='type' || key==='actionType') {
                        $('#'+key+'_popup').val(value);
                        $('#'+key+'_popup').trigger('change');
                    }
                    else {
                        $('#'+key+'_popup').val(value);
                    }
                }
                $('#push_popup_private').modal('toggle');
                window.urlMethod = '/popup-private/updatePrivate';
            }
        });
    });

    $('body').on('click', '#updatePhoneNumber', function (event) {
        event.preventDefault();
        $('#importExcel').find('input:text, input:password, input:file, select, textarea').val('');
        $('#number_phone').val('');
        var id = $(this).data('id');
        console.log(id);
        $('#id_popup_phone').val(id);
        $('#push_phone_number_private').modal('toggle');
    });
}

function deletePopUpPrivate(data){
    let check_delete = $(data).data('check-delete');
    let check_dateEnd = $(data).data('dateend');
    let id = $(data).data('id');
    if(check_dateEnd < getDate()) {
        alert_float('danger','Popup hết hiệu lực, vui lòng cập nhật ngày hết hạn!');
        return false;
    }
    $.ajax({
        url: '/popup-private/deletePrivate',
        type:'POST',
        data: {
            id: id,
            check: check_delete,
        }, success: function (response){
            alert_float('success', response.data.message);
            var table = $('#popup_private_table').DataTable();
            table.ajax.reload(null, false);
        },
        error: function (xhr) {
            var errorString = xhr.responseJSON.message;
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString = value;
                return false;
            });
            alert_float('danger',errorString);
            console.log(data);
        }
    });
}




