var chart_name = [];
var detailChart = [];
var detailSystemChart = [];
var myChart = null;
var show_from_last = $('#show_from_last').val();
var show_to_last = $('#show_to_last').val();
var show_from = $('#show_from').val();
var show_to = $('#show_to').val();

// console.log(show_from);
drawUserSystemEcom();
drawUserSystemFtel();
drawPaymentErrorDetailEcom();
drawPaymentErrorDetailFtel();
drawPaymentErrorDetailSystemEcom();
drawPaymentErrorDetailSystemFtel();
$("#filter_condition").click(function() {
    show_from_last = $('#show_from_last').val();
    show_to_last = $('#show_to_last').val();
    show_from = $('#show_from').val();
    show_to = $('#show_to').val();
    // console.log(show_from);
    if(show_from && show_to && show_from_last && show_to_last) {
        showLoadingIcon();
        drawUserSystemEcom(show_from_last, show_to_last, show_from, show_to);
        drawUserSystemFtel(show_from_last, show_to_last, show_from, show_to);
        drawPaymentErrorDetailEcom(show_from, show_to);
        drawPaymentErrorDetailFtel(show_from, show_to);
        drawPaymentErrorDetailSystemEcom(show_from, show_to);
        drawPaymentErrorDetailSystemFtel(show_from, show_to);
    }
    else {
        swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `Xin vui lòng chọn ngày để lọc.`
        });
    }
});

function drawUserSystemEcom(from_last, to_last, from, to) {
    $.ajax({
        url: "/errorpaymentchart/getPaymentErrorUserSystem",
        type: 'POST',
        data: {
            from_last: from_last,
            to_last: to_last,
            from: from,
            to: to,
            type: 'ecom'
        },
        success: function (data) {
            drawChartUserSystem('ecom', data);
        },
        error: function (err) {}
    });
}

function drawUserSystemFtel(from_last, to_last, from, to) {
    $.ajax({
        url: "/errorpaymentchart/getPaymentErrorUserSystem",
        type: 'POST',
        data: {
            from_last: from_last,
            to_last: to_last,
            from: from,
            to: to,
            type: 'ftel'
        },
        success: function (data) {
            drawChartUserSystem('ftel', data);
        },
        error: function (err) {}
    });
}

function drawChartUserSystem(type, data) {
    // myLineChart.destroy();
    if(chart_name[type]) {
        chart_name[type].destroy();
    }

    chart_name[type] = new Chart(document.getElementById('payment-error-user-system-' + type).getContext('2d'), {
        type: 'bar',
        data: data,
        options: {
            title: {
                display: true,
                text: 'Báo cáo lỗi thanh toán dịch vụ ' + type.toUpperCase() + ' từ ' + show_from_last + ' đến ' + show_to,
                align: 'center',
                position: 'bottom'
            },
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true,
                        min: 0
                    },
                    stacked: true
                }],
                xAxes: [{
                    stacked: true,
                    barPercentage: 0.4
                }]
            },
            responsive: true,
        },
    });
}

function drawPaymentErrorDetailEcom(from = null, to = null) {
    $.ajax({
        url: '/errorpaymentchart/getPaymentErrorDetail',
        type:'POST',
        data: {
            from: from,
            to: to,
            type: 'ecom'
        },
        success: function (response){
            drawPaymentErrorDetailChart('ecom', response);
        },
        error: function (xhr) {
            var errorString = xhr.responseJSON.message;
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString = value;
                return false;
            });
            showMessage('error',errorString);
            console.log(data);
        }
    });
}

function drawPaymentErrorDetailFtel(from = null, to = null) {
    $.ajax({
        url: '/errorpaymentchart/getPaymentErrorDetail',
        type:'POST',
        data: {
            from: from,
            to: to,
            type: 'ftel'
        },
        success: function (response){
            drawPaymentErrorDetailChart('ftel', response);
        },
        error: function (xhr) {
            var errorString = xhr.responseJSON.message;
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString = value;
                return false;
            });
            showMessage('error',errorString);
            console.log(data);
        }
    });
}

function drawPaymentErrorDetailChart(type, data) {
    if(detailChart[type]) {
        detailChart[type].destroy();
    }
    detailChart[type] = new Chart(document.getElementById("payment-error-detail-" + type), {
        type: 'doughnut',
        options: {
            title: {
                display: true,
                text: 'Báo cáo lỗi thanh toán chi tiết cho dịch vụ ' + type.toUpperCase() + ' từ ' + show_from + ' đến ' + show_to,
                align: 'center',
                position: 'bottom'
            },
            scales: {
                yAxes: {
                    beginAtZero: true
                }
            },
            responsive: true,
            legend: {
                display: false
            },
            tooltips: {
                // display: true,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return parseInt(previousValue) + parseInt(currentValue);
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var precentage = (((currentValue/total) * 100) + 0.5).toFixed(2);
                        // console.log(total);
                        return precentage + "%";
                    }
                }
            },
            legendCallback: function (chart) {
                // Return the HTML string here.
                var text = [];
                text.push('<ul style="display: flex; flex-direction: row; margin: 0px; padding: 0px; flex-wrap: wrap;" class="' + chart.id + '-legend">');
                for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
                    text.push('<li style="align-items: center; cursor: pointer; display: flex; flex-direction: row; margin-left: 10px; margin-bottom: 10px"><span id="legend-' + i + '-item" style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '; border-width: 3px; display: inline-block; height: 20px; margin-right: 10px; width: 20px;" onclick="updateDataset(event, ' + '\'' + i + '\'' + ')"></span><p style="color: rgb(102, 102, 102); margin: 0px; padding: 0px;">');
                    if (chart.data.labels[i]) {
                        text.push(chart.data.labels[i]);
                        text.push(' (' + chart.data.datasets[0]['data'][i] + ')');
                    }
                    text.push('</p></li>');
                }
                text.push('</ul>');
                $('#legend-container-' + type).html(text.join(""));
            },
        },
        data: data,
    });
    detailChart[type].generateLegend();
}

function drawPaymentErrorDetailSystemEcom(from = null, to = null) {
    $.ajax({
        url: '/errorpaymentchart/getPaymentErrorDetail',
        type:'POST',
        data: {
            from: from,
            to: to,
            type: 'ecom',
            is_system: 1
        },
        success: function (response){
            drawPaymentErrorDetailSystemChart('ecom', response);
        },
        error: function (xhr) {
            var errorString = xhr.responseJSON.message;
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString = value;
                return false;
            });
            showMessage('error',errorString);
            console.log(data);
        }
    });
}

function drawPaymentErrorDetailSystemFtel(from = null, to = null) {
    $.ajax({
        url: '/errorpaymentchart/getPaymentErrorDetail',
        type:'POST',
        data: {
            from: from,
            to: to,
            type: 'ftel',
            is_system: 1
        },
        success: function (response){
            drawPaymentErrorDetailSystemChart('ftel', response);
            $("#spinner").removeClass("show");
        },
        error: function (xhr) {
            var errorString = xhr.responseJSON.message;
            $.each(xhr.responseJSON.errors, function (key, value) {
                errorString = value;
                return false;
            });
            showMessage('error',errorString);
            console.log(data);
        }
    });
}

function drawPaymentErrorDetailSystemChart(type, data) {
    if(detailSystemChart[type]) {
        detailSystemChart[type].destroy();
    }
    detailSystemChart[type] = new Chart(document.getElementById("payment-error-detail-system-" + type), {
        type: 'doughnut',
        options: {
            title: {
                display: true,
                text: 'Báo cáo lỗi thanh toán chi tiết lỗi hệ thống cho ' + type.toUpperCase() + ' từ ' + show_from + ' đến ' + show_to,
                align: 'center',
                position: 'bottom'
            },
            scales: {
                yAxes: {
                    beginAtZero: true
                }
            },
            responsive: true,
            legend: {
                display: false
            },
            tooltips: {
                // display: true,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return parseInt(previousValue) + parseInt(currentValue);
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var precentage = (((currentValue/total) * 100) + 0.5).toFixed(2);
                        // console.log(total);
                        return precentage + "%";
                    }
                }
            },
            legendCallback: function (chart) {
                // Return the HTML string here.
                var text = [];
                text.push('<ul style="display: flex; flex-direction: row; margin: 0px; padding: 0px; flex-wrap: wrap;" class="' + chart.id + '-legend">');
                for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
                    text.push('<li style="align-items: center; cursor: pointer; display: flex; flex-direction: row; margin-left: 10px; margin-bottom: 10px"><span id="legend-' + i + '-item" style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '; border-width: 3px; display: inline-block; height: 20px; margin-right: 10px; width: 20px;" onclick="updateDataset(event, ' + '\'' + i + '\'' + ')"></span><p style="color: rgb(102, 102, 102); margin: 0px; padding: 0px;">');
                    if (chart.data.labels[i]) {
                        text.push(chart.data.labels[i]);
                        text.push(' (' + chart.data.datasets[0]['data'][i] + ')');
                    }
                    text.push('</p></li>');
                }
                text.push('</ul>');
                console.log(text);
                $('#legend-container-system-' + type).html(text.join(""));
            },
        },
        data: data,
    });
    detailSystemChart[type].generateLegend();
}

function showLoadingIcon() {
    $("#spinner").addClass("show");
    // setTimeout(function () {
    //     $("#spinner").removeClass("show");
    // }, 50000);
}
