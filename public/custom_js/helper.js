function getDetail(_this) {
    let row = _this.closest('tr');
    let infoRow = row.querySelector('.infoRow');
    window.location.href = `/helper/edit/` + infoRow.getAttribute('data-id');
}

function deleteDetail(_this) {
    let row = _this.closest('tr');
    let infoRow = row.querySelector('.infoRow');
    window.location.href = `/helper/destroy/` + infoRow.getAttribute('data-id');
}

function validateDataLogReport(event, form) {
    event.preventDefault();

    var passed = true;

    var formData = getDataInForm(form);
    var passed = checkSubmitLogReport(formData);
    if (passed.status) {
        handleSubmit(event, form);
    } else {
        showMessage('error','Missing Field !!')
    }
}

function checkEnableSaveLogReport(form) {
    var formData = getDataInForm(form);
    if (checkSubmitLogReport(formData).status) {
        $('form').find(':submit').prop('disabled', false);
    } else {
        $('form').find(':submit').prop('disabled', true);
    }
}

function checkSubmitLogReport(formData) {
    const pathArray = window.location.pathname.split("/");
    let action = pathArray[2]; // action ['create','edit']
    if(action === 'edit'){
        return {
            status: true,
            data: null
        };
    }
    var data_required = getDataRequiredLogReport();
    let intersection = Object.keys(data_required).filter(x => !Object.keys(formData).includes(x));
    // console.log(intersection);
    var result = {};
    if (intersection.length === 0) {
        result.status = true;
        result.data = null;
    } else {
        result.status = false;
        result.data = intersection;
    }
    return result;
}

function getDataRequiredLogReport() {
    var data = {
        'name': true,
        'description': true,
        // 'solve_way': true
    };
    return data;
}
