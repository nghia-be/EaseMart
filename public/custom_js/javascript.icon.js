$(document).ready( function () {
    var table = $('.table').DataTable();
    table.on('draw.dt', function() {
      $('.dataTables_empty').html('Không tìm thấy kết quả cho từ khoá <b>' + table.search() + '</b>');
    })
} );

function readURL(value, url) {
    $("#spinner").addClass("show");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var files = $(value)[0].files[0];
    var formData = new FormData();
    formData.append("file", files, files.name);
    $.ajax({
        type: 'POST',
        // datatype: 'JSON',
        url: url,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: (data) => {
            if (data.url) {
                $("#img_icon").attr('src', data.url);
                $("#iconUrl").val(data.url);
                $("#spinner").removeClass("show");
            }
        },
        error: function (xhr) {
            $("#spinner").removeClass("show");
            var errorString = xhr.responseJSON.message;
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString = value;
                return false;
            });
            showMessage('error',errorString);
        }
    });
}

// function deleteButton(form_data, name, url, ul_id) {
//     var arrayId = [];
//     $("#" + ul_id).find("li").each((key, value) => {
//         arrayId.push($(value).attr('data-prodid'));
//     });
//     // $("#selected-prod-id").val(arrayId.join(','));
//     if (arrayId.length > 0) {
//         Swal.fire({
//             title: 'Cảnh báo',
//             html: `Xin vui lòng xoá trống danh sách sản phẩm được chọn trước khi xoá danh mục`,
//             icon: 'warning',
//             showCancelButton: true,
//             cancelButtonText: 'Huỷ',
//             cancelButtonColor: '#d33',
//             confirmButtonColor: '#3085d6',
//             confirmButtonText: 'Đồng ý',
//             reverseButtons: true

//         })
//     }

//     Swal.fire({
//         title: 'Xóa sản phẩm',
//         html: `Bạn có chắc muốn xóa sản phẩm <span class="badge bg-warning text-dark">${name}</span>?`,
//         icon: 'warning',
//         showCancelButton: true,
//         cancelButtonText: 'Huỷ',
//         cancelButtonColor: '#d33',
//         confirmButtonColor: '#3085d6',
//         confirmButtonText: 'Đồng ý',
//         reverseButtons: true

//     }).then((result) => {
//         if (result.isConfirmed) {
//             var formData = new FormData();
//             // var data = serializeObject(form_data);
//             formData.append('formData', form_data);

//             $.ajaxSetup({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 }
//             });
//             $.ajax({
//                 type: 'POST',
//                 url: url,
//                 data: formData,
//                 processData: false,
//                 contentType: false,
//                 cache: false,
//                 success: (data) => {
//                     var result = JSON.parse(data);
//                     if (result.result) {
//                         Swal.fire({
//                             title: 'LƯU THÀNH CÔNG',
//                             text: result.message,
//                             icon: 'success',
//                         })
//                     }
//                 },
//                 error: function (xhr) {
//                     var errorString = xhr.responseJSON.message;
//                     $.each(xhr.responseJSON.errors, function (key, value) {
//                         errorString = value;
//                         return false;
//                     });
//                     showMessage('error',errorString);
//                 }
//             });
//         }
//     });
// }

function deleteButtonTable(from, tableId, productName, url, ul_id) {
    var table = $('#' + tableId).DataTable();
    $('#' + tableId + ' tbody').on('click', 'button', function () {
        var data = table.row($(this).parents('tr')).data();
        if (ul_id) {
            var arrayId = [];
            $("#" + ul_id).find("li").each((key, value) => {
                arrayId.push($(value).attr('data-prodid'));
            });
            data['arrayId'] = arrayId.join(",");
        }
        Swal.fire({
            title: 'Xóa sản phẩm',
            html: `Bạn có chắc muốn xóa sản phẩm <span class="badge bg-warning text-dark">${productName}</span>?`,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Huỷ',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Đồng ý',
            reverseButtons: true

        }).then((result) => {
            if (result.isConfirmed) {
                $("#spinner").addClass("show");
                data['product_type'] = from;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    dataType: "json",
                    success: (result) => {
                        // var result = JSON.parse(data);
                        if (result.result) {
                            $("#spinner").removeClass("show");
                            Swal.fire({
                                title: 'Xoá thành công',
                                text: result.message,
                                icon: 'success',
                            }).then((alertOption) => {
                                if (alertOption.isConfirmed) {
                                    window.location.href = result.url;
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        $("#spinner").removeClass("show");
                        var errorString = xhr.responseJSON.message;
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            errorString = value;
                            return false;
                        });
                        showMessage('error',errorString);
                    }
                });
            }
        });
    });
}

function deleteButton(from, form_data, name, url, ul_id) {
    Swal.fire({
        title: 'Xóa sản phẩm',
        html: `Bạn có chắc muốn xóa sản phẩm <span class="badge bg-warning text-dark">${name}</span>?`,
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Huỷ',
        cancelButtonColor: '#d33',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Đồng ý',
        reverseButtons: true

    }).then((result) => {
        if (result.isConfirmed) {
            $("#spinner").addClass("show");
            var formData = $(form_data).serializeArray();
            var data = serializeObject(formData);
            // data['_token'] = data['_token'][0];
            delete data['_token'];
            delete data['arrayId'];
            if (ul_id) {
                var arrayId = [];
                $("#" + ul_id).find("li").each((key, value) => {
                    arrayId.push($(value).attr('data-prodid'));
                });
                data['arrayId'] = arrayId.join(",");
            }
            data['product_type'] = from;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: "json",
                success: (result) => {
                    // var result = JSON.parse(data);
                    if (result.result) {
                        $("#spinner").removeClass("show");
                        Swal.fire({
                            title: 'Xoá thành công',
                            text: result.message,
                            icon: 'success',
                        }).then((alertOption) => {
                            if (alertOption.isConfirmed) {
                                window.location.href = result.url;
                            }
                        });
                    }
                },
                error: function (xhr) {
                    $("#spinner").removeClass("show");
                    var errorString = xhr.responseJSON.message;
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorString = value;
                        return false;
                    });
                    showMessage('error',errorString);
                }
            });
        }
    });
}

function deleteButtonApprovedRole(form_data, name, url, ul_id) {
    Swal.fire({
        title: 'Xóa sản phẩm',
        html: `Bạn có chắc muốn xóa sản phẩm <span class="badge bg-warning text-dark">${name}</span>?`,
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Huỷ',
        cancelButtonColor: '#d33',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Đồng ý',
        reverseButtons: true

    }).then((result) => {
        if (result.isConfirmed) {
            try {
                var data = JSON.parse(form_data);
            } catch (e) {
                var formData = $(form_data).serializeArray();
                var data = serializeObject(formData);
                // data['_token'] = data['_token'][0];
                delete data['_token'];
                delete data['arrayId'];
                if (ul_id) {
                    var arrayId = [];
                    $("#" + ul_id).find("li").each((key, value) => {
                        arrayId.push($(value).attr('data-prodid'));
                    });
                    data['arrayId'] = arrayId.join(",");
                }
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: "json",
                success: (result) => {
                    // var result = JSON.parse(data);
                    if (result.result) {
                        Swal.fire({
                            title: 'Xoá thành công',
                            text: result.message,
                            icon: 'success',
                        }).then((alertOption) => {
                            if (alertOption.isConfirmed) {
                                window.location.href = result.url;
                            }
                        });
                    }
                },
                error: function (xhr) {
                    var errorString = xhr.responseJSON.message;
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorString = value;
                        return false;
                    });
                    showMessage('error',errorString);
                }
            });
        }
    });
}

function cancelButton(url, withPopup = true) {
    if(withPopup) {
        Swal.fire({
            title: 'Đóng biểu mẫu',
            html: `Các thông tin đã nhập sẽ không được lưu?`,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Huỷ',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Đồng ý',
            reverseButtons: true

        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
    else {
        window.location.href = url;
    }
}

function openDetail(url) {
    $.ajax({
        url: url,
        success: function (result) {
            if (result) {
                console.log(result);
                $("#icon-modal-body").html('');
                $("#icon-modal-body").html(result);
                $('#iconModal').modal();
            }
        },
        error: function (jqXHR, testStatus, error) {
            console.log(error);
        },
    })
}

function filterStatusPheDuyet(tableId) {
    var table = $(tableId).DataTable();
    var typeFilterArr = [];
    var pheduyetFilterArr = [];
    $("#filter-status input[name='type']").filter(function () {
        var type = $(this).val();
        if (this.checked) {
            typeFilterArr.push(type);
        }
        else {
            // typeFilterArr.remove(status);
            typeFilterArr = $.grep(typeFilterArr, function (value) {
                return value != type;
            });
        }
    });

    $("#filter-status input[name='pheduyet']").filter(function () {
        var pheduyet = $(this).val();
        if (this.checked) {
            pheduyetFilterArr.push(pheduyet);
        }
        else {
            // typeFilterArr.remove(status);
            pheduyetFilterArr = $.grep(pheduyetFilterArr, function (value) {
                return value != pheduyet;
            });
        }
    });

    if (typeFilterArr) {
        table.column(1).search(typeFilterArr.join('|'), true);
    }
    if (pheduyetFilterArr) {
        table.column(5).search(pheduyetFilterArr.join('|'), true);
    }
    table.draw();
    $('#filter-status').modal('toggle');
}

function filterStatus(tableId, colNum) {
    var table = $(tableId).DataTable();
    var statusFilterArr = [];
    $("#filter-status input[name='status']").filter(function () {
        var status = $(this).val();
        if (this.checked) {
            statusFilterArr.push(status);
        }
        else {
            // typeFilterArr.remove(status);
            statusFilterArr = $.grep(statusFilterArr, function (value) {
                return value != status;
            });
        }
    });
    if (statusFilterArr) {
        table.column(colNum).search(statusFilterArr.join('|'), true);
    }
    table.draw();
    $('#filter-status').modal('toggle');
}

async function removeFromSelectedProduct(el) {
    var remove_prod_id = $("#" + el).attr('data-prodid');
    var img = $('#' + remove_prod_id + '-selected-product').find("img");
    var span = $('#' + remove_prod_id + '-selected-product').find("span:first");
    var button = $('#' + remove_prod_id + '-selected-product').find("button");
    var h6 = $('#' + remove_prod_id + '-selected-product').find("h6:first");
    $(h6).find('span').removeClass("badge-light");
    $(h6).find('span').addClass("badge-dark");
    var parent_ul = $($("#" + el).parent());
    $("#" + el).remove();
    parent_ul.find("li").each((key, value) => {
        $(value).find("span.position").text($(value).index() + 1);
    });

    var li = $("<li>", {id: remove_prod_id});
    li.addClass("lslide");

    // li.css("text-align", "center");
    // li.css("width", "211.55px");
    // li.css("margin-right", "15px");
    li.attr('data-prodid', remove_prod_id);

    li.append(img);
    li.append('<br>');
    li.append(button);
    li.append(h6);

    // li.insertBefore('#all-product #' + (remove_prod_id + 1));
    $("#all-product").prepend(li);
    var checkExist = setInterval(function() {
        if ($('#all-product #' + remove_prod_id).length) {
           console.log("Exists!");
           slider.refresh();
           clearInterval(checkExist);
        }
    }, 100);
}

function onsubmitIconForm(e, form, ul_id, withPopup = true) {
    e.preventDefault();
    var arrayId = [];
    $("#" + ul_id).find("li").each((key, value) => {
        arrayId.push($(value).attr('data-prodid'));
    });

    $("#selected-prod-id").val(arrayId.join(','));

    if (withPopup) {
        Swal.fire({
            text: "Các thông tin bạn nhập sẽ được chuyển vào yêu cầu kiểm duyệt",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Confirmed!',
            reverseButtons: true

        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
                showLoadingIcon();
                let submitBtn = $(form).closest('form').find('button').append('&ensp;<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
                $('form').find(':button').prop('disabled', true);
                // $("#spinner").toggle("show");
            }
        });
    } else {
        if(arrayId.length <= 0) {
            console.log(arrayId.length);
            Swal.fire({
                text: "Danh mục hiện tại không có sản phẩm nào, bạn có chắc muốn thêm?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy',
                reverseButtons: true

            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    showLoadingIcon();
                    let submitBtn = $(form).closest('form').find('button').append('&ensp;<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
                    $('form').find(':button').prop('disabled', true);
                    // $("#spinner").toggle("show");
                }
            });
        }
        else {
            form.submit();
            showLoadingIcon();
            let submitBtn = $(form).closest('form').find('button').append('&ensp;<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
            $('form').find(':button').prop('disabled', true);
            // $("#spinner").toggle("show");
        }
    }

    if (e.result == true) {
        // $("#spinner").addClass("hide");
        console.log('end submit');
    }
}

function approve(approved_data) {
    Swal.fire({
        title: 'Phê duyệt yêu cầu',
        html: `Thông tin phê duyệt sẽ được lưu?`,
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Huỷ',
        cancelButtonColor: '#d33',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Đồng ý',
        reverseButtons: true

    }).then((result) => {
        if (result.isConfirmed) {
            $("#spinner").addClass("show");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var formData = new FormData();
            formData.append('data', JSON.stringify(approved_data));
            $.ajax({
                type: 'POST',
                url: '/iconapproved/save',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    // $("#spinner").removeClass("show");
                    var response = JSON.parse(data);
                    if (response.status) {
                        window.location.href = "/iconapproved";
                    }
                },
                error: function (xhr) {
                    var errorString = xhr.responseJSON.message;
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorString = value;
                        return false;
                    });
                    showMessage('error',errorString);
                }
            });
        }
    });
}

function serializeObject(obj) {
    var jsn = {};
    $.each(obj, function () {
        if (jsn[this.name]) {
            if (!jsn[this.name].push) {
                jsn[this.name] = [jsn[this.name]];
            }
            jsn[this.name].push(this.value || '');
        } else {
            jsn[this.name] = this.value || '';
        }
    });
    return jsn;
}

function showLoadingIcon() {
    $("#spinner").addClass("show");
    setTimeout(function () {
        $("#spinner").removeClass("show");
    }, 50000);
}
