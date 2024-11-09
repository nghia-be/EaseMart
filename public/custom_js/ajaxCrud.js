
function ChangeToSlug()
        {
            var slug;
         
            //Lấy text từ thẻ input title 
            slug = document.getElementById("slug").value;
            slug = slug.toLowerCase();
            //Đổi ký tự có dấu thành không dấu
                slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
                slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
                slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
                slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
                slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
                slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
                slug = slug.replace(/đ/gi, 'd');
                //Xóa các ký tự đặt biệt
                slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
                //Đổi khoảng trắng thành ký tự gạch ngang
                slug = slug.replace(/ /gi, "-");
                //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
                //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
                slug = slug.replace(/\-\-\-\-\-/gi, '-');
                slug = slug.replace(/\-\-\-\-/gi, '-');
                slug = slug.replace(/\-\-\-/gi, '-');
                slug = slug.replace(/\-\-/gi, '-');
                //Xóa các ký tự gạch ngang ở đầu và cuối
                slug = '@' + slug + '@';
                slug = slug.replace(/\@\-|\-\@|\@/gi, '');
                //In slug ra textbox có id “slug”
            document.getElementById('convert_slug').value = slug;
        }
        function responseImageStatic(res, input) {
            console.log(res);
            if (res.statusCode === 0 && res.data !== null) {
                const [file] = input.files;
                const input_name = 'img_' + input.name;
                console.log(input_name);
                document.getElementById(input_name).src = URL.createObjectURL(file);
                console.table(input_name + '_name', res.data.uploadedImageFileName)
                document.getElementById(input_name + '_name').value = res.data.uploadedImageFileName;
                console.log(res.data.uploadedImageFileName);
            } else {
                alert_float('danger',res.message);
            }
        }

        function handleUploadImage(input) {
            const [file] = input.files;
            if (file.size > 700000) { // handle file
                resetData(input, null);
                alert_float('danger','File is too big! Allowed memory size of 0.7MB');
                return false;
            };
            uploadFileStatic(file, input, responseImageStatic);
        }

        function dialogConfirmWithAjaxchangeStatusBrand(sureCallbackFunction, data, text = "Xin hay kiểm tra lại") {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Chắc chắn!',
                reverseButtons: true

            }).then((result) => {
                if (result.isConfirmed) {
                    sureCallbackFunction(data);
                }else {
                    let table = $('#brand_manage').DataTable();
                    table.ajax.reload(null, false);
                }
            });
        }
     
        function changeStatusBrand(data){
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/brand/change-status', dataPost).done(function(response) {
                alert_float('success', response.message);
                $('#brand_manage').DataTable().ajax.reload(null,false);
            });
        }
    
        function deleteBrand(data) {
            let dataPost = {};
            dataPost.id = $(data).data('id');
            $.post('/brand/destroy', dataPost).done(function (response) {
                alert_float('success', response.message);
                let table = $('#brand_manage').DataTable();
                table.ajax.reload(null, false);
            });
        }

        function addBrand(e) {
            e.preventDefault();
            $('#showDetail_Modal').modal('toggle');
            document.getElementById('formBrand').reset();
            window.urlMethod = '/brand/store';
            window.type = 'POST';
        }

        function detailBrand(_this) {
            let dataPost = {};
            dataPost.id = $(_this).data('id');
            $.post('/brand/show', dataPost).done(function (response) {
                console.log(response.data);
                for (let [key, value] of Object.entries(response.data)) {
                    let k = $('#' + key);
                    k.val(value);
                    k.trigger('change');
                }
                $('#slug').val(response.data.name);  // Gán name vào trường slug
                $('#convert_slug').val(response.data.slug); 
                if (response.data.image) {
                    let imagePath = "http://hi-admin-web.local" + response.data.image;  // Đường dẫn đầy đủ đến ảnh, thay thế "path_to_images" bằng đường dẫn thật của bạn
                    $('#img_path_1').attr('src', imagePath);  // Gán ảnh vào thẻ img với id "img_path_1"
                    $('#img_path_1_name').val(response.data.image);  // Đặt tên ảnh vào input hidden
                } else {
                    // Nếu không có ảnh, hiển thị ảnh placeholder
                    $('#img_path_1').attr('src', "{{ asset('/images/image_holder.png') }}");
                    $('#img_path_1_name').val('');
                }
                $('#showDetail_ModalEdit').modal('toggle');
                window.urlMethod = '/brand/update/' + $(_this).data('id');
                window.type = 'PUT';
                console.log('Modal edit opened');
                console.log('Data:', response.data);

            });
        }

        function pushBrand() {
            $(this).attr('disabled', 'disabled');
            let data = $('#formBrand').serialize();
            $.ajax({
                url: urlMethod,
                type: window.type,
                dataType: 'json',
                data: data,
                cache: false,
                success: (data) => {
                    if (data.success) {
                        $('#showDetail_Modal').modal('toggle');
                        document.getElementById('formBrand').reset();
                        $('#categories_id').val('').selectpicker('refresh'); 
                        $('#img_path_1').attr('src', "{{ asset('/images/image_holder.png') }}"); // Đặt lại ảnh mặc định
                        $('#img_path_1_name').val(''); // Xóa giá trị tên file
                        alert_float('success', data.message);
                        $('#submit').prop('disabled', false);
                        let table = $('#brand_manage').DataTable();
                        table.ajax.reload(null, false);
                    } else {
                        alert_float('danger', data.message);
                        $('#submit').prop('disabled', false);
                    }
                }, error: function (xhr) {
                    let errorString = xhr.responseJSON.message ?? '';
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorString = value;
                        return false;
                    });
                    alert_float('danger', errorString);
                    $('#submit').prop('disabled', false);
                }
            });
        }
