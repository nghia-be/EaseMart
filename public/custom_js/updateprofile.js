function changePassWord(_this){
    let form = $(_this).closest('form');
    param = {
        _token:$('meta[name="csrf-token"]').attr('content'),
        current_password:$(form).find('input[name="current_password"]').val(),
        password:$(form).find('input[name="password"]').val(),
        password_confirmation:$(form).find('input[name="password_confirmation"]').val()
    };
    callAPIHelper("/profile/changePassword",param,'POST',successChangePassword);
}
function successChangePassword(response){
    showSuccess();
    $('#changePasswordModal').modal('toggle');
}