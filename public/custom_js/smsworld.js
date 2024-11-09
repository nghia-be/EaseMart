
function getLogs(_this){
    let form = $(_this).closest('form');
    let date = new Date( $(form).find('input[name="date"]').val());
    let countryCode = $(form).find('input[name="country_code"]').val();
    let phone = $(form).find('input[name="phone"]').val();
    param = {
        _token:$('meta[name="csrf-token"]').attr('content')
    };
    if(phone != undefined && countryCode != undefined){
        param.PhoneNumber = countryCode+phone;
    };
    if(date != null && date != undefined && date != "Invalid Date"){
        param.Month = date.getMonth()+1;
        param.Year = date.getFullYear();
    };
    callAPIHelper("/smsworld/getlog",param,'POST',successCallGetListLog);
}

function successCallGetListLog(response){
    if(response.error != undefined){
        showLogs.innerHTML = '';
        showLogs.classList.remove('card');
        showMessage('error',response.error);
    }else{
        // showLogs.classList.add('card');
        var html = '';
        response.forEach(log => {
        html += `<div class="card">`;
        html +=`<div class="card-header collapsed" id="heading`+log.STT+`" data-toggle="collapse" data-target="#collapse`+log.STT+`" aria-expanded="true" aria-controls="collapse`+log.STT+`">
                    <span class="title"><b>STT : `+log.STT+`  - Phone: `+log.PhoneNumber+` -  Time: `+log.Date+`</b></span>
                    <span class="accicon"><i class="fas fa-angle-up rotate-icon"></i></span>
                </div>`;
        html += ` <div id="collapse`+log.STT+`" class="collapse" data-parent="#showLogs">`;
        html += ` <div class="card-body">`;
        html += `<div><b>Source:</b> `+log.Source+`</div>`;
        html += `<div><b>Message:</b> `+log.Message+`</div>`;
        html += `</div>`;
        html += `</div>`;
        html += `</div>`;
        });
        showLogs.innerHTML = html;
    }
}
