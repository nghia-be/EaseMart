
$(document).ready(function () {
    initSelect();
    settings_on_search();
    $(document).on('pjax:end', function () {
        initSelect();
        const pathArray = window.location.pathname.split("/");
        let segment = pathArray[1]; //first uri param
        switch (segment) {
            case 'user':
                initUser();
                break;
            case 'modules':
                initModule();
                break;
            case 'groups':
                initGroup();
                break;
            case 'roles':
                initRoles();
                break;
            case 'groupmodule':
                initGroupModule();
                break;
            case 'manageotp':
                initManageOtp();
            case 'logactivities':
                initLogActivities();
                break;
            case 'settings':
                initSettings();
                settings_on_search();
                break;
            case 'hidepayment':
                initHidePaymentLogs();
                break;
            case 'bannermanage':
                callApiGetListBanner();
                break;
            case 'checkuserinfo':
                initCheckUserInfo();
                break;
            case 'smsworld':
                initSmsWorld();
                break;
            case 'checklistmanage':
                initCheckListManage();
                break;
            case 'iconmanagement':
                initIconmanagement();
                break;
            case '':
            case 'home':
                drawChart();
                break
        }

    });
    $(document).on('pjax:popstate', function (event) {
        event.preventDefault();
        $.pjax.reload('#pjax');
    });
    // $(document).on('pjax:error', function (event, xhr, textStatus, errorThrown, options) {
    //     $.pjax.reload('#pjax');
    // });

});

function initSelect() {
    $('form select').selectpicker();
}

function read(obj) {
    if ($(obj).hasClass("less")) {
        $(obj).removeClass("less");
        $(obj).html("Showmore");
    } else {
        $(obj).addClass("less");
        $(obj).html("Showless");
    }
    $(obj).parent().prev().toggle();
    $(obj).prev().toggle();
    return false;
}

function initModule() {
    $('#modules').DataTable({
        "processing": true,
        "serverSide": true,
        "select": true,
        "dataSrc": "tableData",
        "bDestroy": true,
        "scrollX": true,
        retrieve: true,
        "ajax": {
            url: "/modules/initDatatable"
        },
        "columns": [
            {
                title: 'No.',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: 'id',
                name: "id",
                title: "Id"
            },
            {
                data: 'module_name',
                name: "module_name",
                title: "Module Name"
            },
            {
                data: "uri",
                name: "uri",
                title: "Uri"
            },
            {
                data: "group_module_id",
                name: "group_module_id",
                title: "Group Module"
            },
            {
                data: "status",
                name: "status",
                title: "Status"
            },
            {
                data: "created_at",
                name: "created_at",
                title: "Created at"
            },
            {
                data: "created_by",
                name: "created_by",
                title: "Created By"
            },
            {
                data: "updated_at",
                name: "updated_at",
                title: "Updated at"
            },
            {
                data: "updated_by",
                name: "updated_by",
                title: "Updated By"
            },
            {
                data: "action",
                name: "action",
                title: "Action",
                sortable: false
            }
        ],
        "language": {
            "emptyTable": "No Record..."
        },
        "initComplete": function (setting, json) {
            $('#modules').show();
        },
        error: function (xhr, error, code) {
            $.pjax.reload('#pjax');
        },
        searchDelay: 500
    });
}

function initGroupModule() {
    $('#group-module').DataTable({
        "processing": true,
        "serverSide": true,
        "select": true,
        "dataSrc": "tableData",
        "bDestroy": true,
        "scrollX": true,
        retrieve: true,
        "ajax": {
            url: "/groupmodule/initDatatable"
        },
        "columns": [
            {
                title: 'No.',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: 'id',
                name: "id",
                title: "Id"
            },
            {
                data: 'group_module_name',
                name: "group_module_name",
                title: "Group Module Name"
            },
            {
                data: "created_at",
                name: "created_at",
                title: "Created at"
            },
            {
                data: "created_by",
                name: "created_by",
                title: "Created by"
            },
            {
                data: "updated_at",
                name: "updated_at",
                title: "Updated at"
            },
            {
                data: "updated_by",
                name: "updated_by",
                title: "Updated by"
            },
            {
                data: "action",
                name: "action",
                title: "Action"
            }
        ],
        "language": {
            "emptyTable": "No Record..."
        },
        "initComplete": function (setting, json) {
            $('#modules').show();
        },
        error: function (xhr, error, code) {
            $.pjax.reload('#pjax');
        },
        searchDelay: 500
    });
}

function initUser() {
    $('#userTable').DataTable({
        "processing": true,
        "serverSide": true,
        "select": true,
        "dataSrc": "tableData",
        "bDestroy": true,
        responsive: true,
        "scrollX": true,
        retrieve: true,
        "ajax": {
            url: "user/initDatatable"
        },
        "columns": [
            {
                title: 'No.',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: "id",
                name: "id",
                title: "Id"
            },
            {
                data: "name",
                name: "name",
                title: "Name"
            },
            {
                data: "email",
                name: "email",
                title: "Email"
            },
            {
                data: "role_id",
                name: "role_id",
                title: "Role"
            },
            {
                data: "created_at",
                name: "created_at",
                title: "Created at"
            },
            {
                data: "action",
                name: "action",
                title: "Action",
                sortable: false
            }
        ],
        "language": {
            "emptyTable": "No Record..."
        },
        "initComplete": function (setting, json) {
            $('#userTable').show();
        },
        error: function (xhr, error, code) {
            $.pjax.reload('#pjax');
        },
        searchDelay: 500,
    });
}

function initGroup() {
    $('#groupTable').DataTable({
        "order": [
            [1, "desc"]
        ],
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: true,
        "bDestroy": true,
        "scrollX": true,
        retrieve: true,
        ajax: {
            url: base_url + '/groups/getList',
        },
        searchDelay: 500,
        columns: [
            {
                title: 'No.',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: 'id'
            },
            {
                data: 'group_name'
            },
            {
                data: 'created_by'
            },
            {
                data: "action",
                name: "action",
                title: "Action",
                sortable: false
            }
        ],
        error: function (xhr, error, code) {
            $.pjax.reload('#pjax');
        },
    });
}

function initRoles() {
    $('#rolesTable').DataTable({
        "order": [
            [1, "desc"]
        ],
        responsive: true,
        processing: true,
        "bDestroy": true,
        "dataSrc": "tableData",
        serverSide: true,
        "scrollX": true,
        retrieve: true,
        ajax: {
            url: base_url + '/roles/getList',
        },
        columns: [
            {
                title: 'No.',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: 'id',
            },
            {
                data: 'role_name',
            },
            {
                data: 'created_by',
            },
            {
                data: "action",
                name: "action",
                title: "Action",
                sortable: false
            }
        ],
        "initComplete": function (setting, json) {
            $('#rolesTable').show();
        },
        error: function (xhr, error, code) {
            $.pjax.reload('#pjax');
        },
        searchDelay: 500,
    });
}

function initLogActivities() {
    $('#logTable').DataTable({
        "order": [
            [0, "desc"]
        ],
        responsive: true,
        searchDelay: 500,
        processing: true,
        "bDestroy": true,
        serverSide: true,
        "scrollX": true,
        retrieve: true,
        ajax: {
            url: base_url + '/logactivities/initDatatable',
        },
        searchDelay: 500,
        columns: [
            {
                title: 'No.',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: 'id',
            },
            {
                data: 'user_id',
                className: "text-center"
            },
            {
                data: 'email',
                className: "text-center"
            },
            {
                data: 'user_role',
                className: "text-center"
            },
            {
                data: 'method',
                className: "text-center"
            },
            {
                data: 'url'
            },
            {
                data: 'created_at',
                class: 'font-weight-bold'
            },
            {
                data: 'param',
                render: function (data, type, full) {
                    var showChar = 200;
                    var ellipsestext = "...";
                    var moretext = "Show More";
                    var lesstext = "Show Less";
                    var contentt = JSON.stringify(data);
                    var content = contentt.replace(/["]+/g, '').substring(0, contentt.length - 1);
                    if (content.length > showChar) {
                        var c = content.substr(0, showChar);
                        var h = content.substr(showChar, content.length - showChar);
                        var html = c + '<span class="moreellipses">' + ellipsestext + '</span><span class="morecontent"><span style="display:none">' + h + '</span>&nbsp;&nbsp;<a onclick="read(this)" class="morelink" style="cursor: pointer;">' + moretext + '</a></span>'; //here call the read() function
                        return html.toString();
                    }
                    return data;
                }
            },
            {
                data: 'ip',
                className: "text-center",
            },
            {
                data: 'agent'
            }
        ],
        error: function (xhr, error, code) {
            $.pjax.reload('#pjax');
        },
    });
}

function initManageOtp() {
    $('#manage-otp').DataTable({
        "processing": true,
        "serverSide": true,
        "select": true,
        "dataSrc": "tableData",
        "bDestroy": true,
        "scrollX": true,
        retrieve: true,
        "ajax": {
            url: "/modules/initDatatable"
        },
        "columns": [
            {
                title: 'No.',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: 'otp',
                name: "otp",
                title: "OTP"
            },
            {
                data: 'otp_time',
                name: "otp_time",
                title: "Time OTP"
            },
            {
                data: "phone",
                name: "phone",
                title: "Phone number"
            },
            {
                data: "status",
                name: "status",
                title: "Status"
            },
            {
                data: "created_at",
                name: "created_at",
                title: "Created at"
            },
            {
                data: "created_by",
                name: "created_by",
                title: "Created By"
            },
            {
                data: "updated_at",
                name: "updated_at",
                title: "Updated at"
            },
            {
                data: "updated_by",
                name: "updated_by",
                title: "Updated By"
            },
            {
                data: "action",
                name: "action",
                title: "Action",
                sortable: false
            }
        ],
        "language": {
            "emptyTable": "No Record..."
        },
        "initComplete": function (setting, json) {
            $('#manage-otp').show();
        },
        error: function (xhr, error, code) {
            $.pjax.reload('#pjax');
        },
        searchDelay: 500
    });
}

function initSettings() {
    $('#settings').DataTable({
        "processing": true,
        "serverSide": true,
        "select": true,
        "dataSrc": "tableData",
        "bDestroy": true,
        "scrollX": true,
        "retrieve": true,
        // "autoWidth": false,
        "fixedColumns": true,
        "ajax": {
            url: "/settings/initDatatable"
        },
        "columnDefs": [
            { "width": 20, "targets": 2 },
        ],
        "columns": [
            {
                title: 'No.',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: 'id',
                name: "id",
                title: "Id"
            },
            {
                data: 'name',
                name: "name",
                title: "Unique name"
            },
            {
                data: "value",
                name: "value",
                title: "Value",
                render: function (data, type, row) {
                    const htmlEntities = {
                        "&": "&amp;",
                        "<": "&lt;",
                        ">": "&gt;",
                        '"': "&quot;",
                        "'": "&apos;"
                    };
                    var value = JSON.parse(String(data).replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"'));
                    var template = badgeArrayView(value);
                    return template;
                },
                width: 20
            },
            {
                data: "created_at",
                name: "created_at",
                title: "Created at"
            },
            {
                data: "created_by",
                name: "created_by",
                title: "Created By"
            },
            {
                data: "updated_at",
                name: "updated_at",
                title: "Updated at"
            },
            {
                data: "updated_by",
                name: "updated_by",
                title: "Updated By"
            },
            {
                data: "action",
                name: "action",
                title: "Action",
                sortable: false
            }
        ],
        "language": {
            "emptyTable": "No Record..."
        },
        "initComplete": function (setting, json) {
            $('#settings').show();
        },
        error: function (xhr, error, code) {
            $.pjax.reload('#pjax');
        },
        searchDelay: 500
    });
}

function initHidePaymentLogs() {
    $('#hide-payment').DataTable({
        "processing": true,
        "serverSide": true,
        "select": true,
        "dataSrc": "tableData",
        "bDestroy": true,
        "scrollX": true,
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 75, 100],
        orderMulti: true,
        "order": [[7, "desc"]],
        retrieve: true,
        "ajax": {
            url: "/hidepayment/initDatatable"
        },
        "columns": [
            {
                title: 'No.',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: 'id',
                name: "id",
                title: "Id"
            },
            {
                data: 'version',
                name: "version",
                title: "Version"
            },
            {
                data: 'isUpStoreAndroid',
                name: "isUpStoreAndroid",
                title: "Platform",
                render: function (data, type, row) {
                    var platform = '';
                    if (row.isUpStoreAndroid) {
                        platform += `<span class="badge badge-primary" style="margin-right: 5px">Android</span>`;
                    }

                    if (row.isUpStoreIos) {
                        platform += `<span class="badge badge-secondary" style="margin-right: 5px">IOS</span>`;
                    }
                    return platform;
                }
            },
            {
                data: "isUpStoreIos",
                name: "isUpStoreIos",
                title: "Action",
                render: function (data, type, row) {
                    var action = '';

                    if (row.isUpStoreAndroid == '1') {
                        action += `<span class="badge badge-danger" style="margin-right: 5px">Hide Android</span>`;
                    }

                    if (row.isUpStoreAndroid == '0') {
                        action += `<span class="badge badge-success" style="margin-right: 5px">Show Android</span>`;
                    }

                    if (row.isUpStoreIos == '1') {
                        action += `<span class="badge badge-danger" style="margin-right: 5px">Hide IOS</span>`;
                    }

                    if (row.isUpStoreIos == '0') {
                        action += `<span class="badge badge-success" style="margin-right: 5px">Show IOS</span>`;
                    }
                    return action;
                }
            },
            {
                data: "user.name",
                name: "user.name",
                title: "Created By",
            },
            {
                data: "error_mesg",
                name: "error_mesg",
                title: "Status",
                render: function (data, type, row) {
                    return (data == 'Thành công') ? `<span class="badge bg-success">${data}</span>` : `<span class="badge bg-danger">${data}</span>`;
                }
            },
            {
                data: "created_at",
                name: "created_at",
                title: "Created at"
            },
        ],
        "language": {
            "emptyTable": "No Record..."
        },
        "initComplete": function (setting, json) {
            $('#hide-payment').show();
        },
        error: function (xhr, error, code) {
            $.pjax.reload('#pjax');
        },
        searchDelay: 500
    });
}
function initBannerManage(response) {
    console.log(response);
    var dataTable = [];
    var flagAcl = false;
    var toDay = new Date();
    var stt = 1;
    response.data.forEach(element => {
        let subData = {
            bannerId: '',
            bannerType: '',
            public_date_start: '',
            public_date_end: '',
            title_vi: '',
            title_en: '',
            direction_id: '',
            direction_url: '',
            image: '',
            thumb_image: '',
            ordering: '-1',
            view_count: 0,
            date_created: '',
            date_created: '',
            created_by: '',
            modified_by: '',
            is_banner_expired: false
        };
        if (element.banner_id != undefined) {
            subData.bannerId = element.banner_id;
            subData.title_vi = element.banner_title != undefined ? element.banner_title : '';
            subData.bannerType = element.custom_data != undefined ? element.custom_data : '';
            subData.image = element.image_url != undefined ? element.image_url : '';
            subData.ordering = element.ordering != undefined ? element.ordering : '-1';
            subData.view_count = element.view_count != undefined ? element.view_count : '0';
            subData.direction_url = element.direction_url != undefined ? element.direction_url : '';
            subData.date_created = element.date_created;
            subData.is_banner_expired = element.is_selected ? false : true;
        } else {
            let public_date_end = new Date(element.public_date_end);
            subData.bannerId = element.event_id;
            subData.title_vi = element.title_vi != undefined ? element.title_vi : '';
            subData.bannerType = element.event_type != undefined ? element.event_type : '';
            subData.image = element.image != undefined ? element.image : '';
            subData.ordering = element.ordering != undefined ? element.ordering_on_home : '-1';
            subData.view_count = element.view_count != undefined ? element.view_count : '0';
            subData.direction_url = element.event_url != undefined ? element.event_url : '';
            subData.date_created = element.date_created;
            subData.is_banner_expired = (public_date_end < toDay) ? true : false;

            subData.created_by = element.created_by != undefined ? element.created_by : '';
            subData.public_date_start = element.public_date_start != undefined ? element.public_date_start : '';
            subData.public_date_end = element.public_date_end != undefined ? element.public_date_end : '';
            subData.modified_by = element.modified_by != undefined ? element.modified_by : '';
        }
        dataTable.push(subData);
    });
    var columnData = [
        {
            title: 'STT',
            className: 'text-center',
            render: function (data, type, row, meta) {
                return `<span class ="infoRow" data-type="` + row.bannerType + `" data-id = "` + row.bannerId + `">` + parseInt(meta.row + 1) + `</span>`;
            },
            className: 'text-center'
        },
        {
            data: 'title_vi',
            title: "Tiêu Đề"
        },
        {
            data: 'image',
            title: "Ảnh Banner",
            "render": function (data, type, row) {
                return `<img src="` + data + `"  style="width:150px" onerror="this.onerror=null;this.src='/images/img_404.svg';"  onclick ="window.open('` + data + `').focus()"/>`;
            },
            "sortable": false,
            className: 'text-center'
        },
        // {
        //     data :'direction_url',
        //     title: "Direction URL",
        //     "render": function(data, type, row) {
        //         return `<a href="`+data+`" target="_blank">`+data+`</a>`;
        //     }
        // },
        {
            data: 'bannerType',
            title: 'Loại Banner',
            className: 'text-center'
        },
        {
            data: 'public_date_start',
            title: 'Ngày Hiển Thị'
        },
        {
            data: 'public_date_end',
            title: 'Ngày kết thúc'
        },
        {
            data: 'is_banner_expired',
            title: 'Trạng Thái',
            render: function (data, type, row) {
                let is_show = (data) ? 'Hide' : 'Show';
                let badge = (data) ? 'badge badge-danger' : 'badge badge-success';
                return `<h4 class="` + badge + `">` + is_show + `</h4>`;
            },
            className: 'text-center'
        },
        {
            data: 'ordering',
            title: 'Độ ưu tiên',
            "render": function (data, type, row) {
                let disable = row.is_banner_expired ? 'disabled' : '';
                return `<input type="number" onchange="updateOrdering(this)" style="width:80%" value="` + data + `" ` + disable + `/>`;
            },
            "sortable": false,
            className: 'text-center'
        },
        {
            data: 'view_count',
            title: 'Số lượt view',
            className: 'text-center'
        },
        {
            data: 'date_created',
            title: 'Ngày tạo'
        },
        {
            data: 'created_by',
            title: 'Người Tạo'
        },
        {
            data: 'modified_by',
            title: 'Người cập nhật'
        }
    ];

    if (response.isAdmin === true) {
        flagAcl = true;
    } else {
        var aclCurrentModule = response.aclCurrentModule;
        if (aclCurrentModule.update == 1) {
            flagAcl = true;
        }
    }
    if (flagAcl) {
        columnData.push(
            {
                title: 'Action',
                render: function (data, type, row) {
                    var bannerType = row.bannerType;
                    if (bannerType == 'highlight') {
                        bannerType = 'bannerHome';
                    };
                    var exists = 0 != $('#show_at option[value=' + bannerType + ']').length;
                    if (exists === false) return "";
                    return `<a style="" type="button" onclick="getDetailBanner(this)" class="btn btn-sm fas fa-edit btn-icon bg-olive"></a>`;
                },
                className: 'text-center',
                "sortable": false
            }
        );
    } else {
        columnData.push(
            {
                title: 'Action',
                render: function (data, type, row) {
                    if (row.bannerType === 'event_normal') return "";
                    return `<div></div>`;
                },
                className: 'text-center',
                "sortable": false
            }
        );
    };
    $('#banner_manage').DataTable({
        // "bAutoWidth": false,
        // "autoWidth":false,
        data: dataTable,
        "processing": true,
        "select": true,
        responsive: true,
        "bDestroy": true,
        "scrollX": true,
        "columns": columnData,
        "language": {
            "emptyTable": "No Record..."
        },
        "order": [[9, "desc"]],
        columnDefs: [
            { width: '5%', targets: 0 }, //stt
            // { width: '10%', targets: 1 }, // 1 bannerId
            { width: '10%', targets: 1 }, // 2 Title
            { width: '15%', targets: 2 }, // 3 Image
            // { width: '10%', targets: 3 }, // 4 direction URL
            { width: '1%', targets: 3 }, // 5 Banner Type,
            { width: '10%', targets: 4 }, // 6 public date start
            { width: '10%', targets: 5 }, // 7 public date end
            { width: '3%', targets: 6 }, // is expired
            { width: '5%', targets: 7 }, // 8 ordering
            { width: '3%', targets: 8 }, // 9 view count
            { width: '10%', targets: 9 }, // 10 create at
            { width: '10%', targets: 10 }, // 11 create by
        ],
        // "fnRowCallback": function(row, data, iDisplayIndex, iDisplayIndexFull) {
        //     if(data.is_banner_expired){
        //         $('td', row).css('background-color', 'rgb(255 108 94 / 51%)');
        //     }
        // }
    });
}
function initCheckUserInfo() {
    var columnData = [
        {
            title: 'No.',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
        },
        {
            data: 'Id',
            title: 'ID'
        },
        {
            data: 'FullName',
            title: 'Full Name'
        },
        {
            data: 'Contract',
            title: 'Contract'
        },
        {
            data: 'Phone',
            title: 'Phone'
        },
        {
            title: 'Action',
            data: 'Id',
            render: function (data, type, row) {
                var tmp = JSON.stringify(row);
                return `<div><button class="btn btn-sm fas fa-eye btn-icon bg-olive" onclick='viewUserInfo(` + tmp + `)'></button?</div>`;
            },
            className: 'text-center',
            "sortable": false
        }
    ];
    $('#checkuserinfo_table').DataTable({
        data: data,
        "processing": true,
        "select": true,
        responsive: true,
        "bDestroy": true,
        "scrollX": true,
        "columns": columnData,
        "language": {
            "emptyTable": "No Record..."
        },
    });
};

function initSmsWorld() {
    var columnData = [
        {
            data: 'STT',
            title: 'No.'
        },
        {
            data: 'Date',
            title: 'Date'
        },
        {
            data: 'Source',
            title: 'Source'
        },
        {
            data: 'Message',
            title: 'Message'
        },
        {
            data: 'PhoneNumber',
            title: 'Phone Number'
        }
    ];
    $('#smsworld_table').DataTable({
        data: data,
        "processing": true,
        "select": true,
        responsive: true,
        "bDestroy": true,
        "scrollX": true,
        "columns": columnData,
        "language": {
            "emptyTable": "No Record..."
        },
    });
};

function initCheckListManage() {
    console.log(listCheckList);
    var columnData = [
        // {
        //     title:'No.',
        //     render: function(data,type, row ,meta){
        //         return meta.row + 1;
        //     },
        //     className: 'text-center'
        // },
        {
            title: 'No.',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
        },
        {
            data: 'ID',
            title: 'ID'
        },
        {
            data: 'HD',
            title: 'Contract'
        },
        {
            data: 'action',
            title: 'Action',
            render: function (data, type, row) {
                var html = `<form  action=` + route_checklistmanage + ` method="POST" onsubmit="handleSubmit(event,this)">
                    <input type="hidden" name="_token" value=`+ crsf + ` />
                    <input type="text" class="form-control" name="checkListId" hidden value="`+ row.ID + `">
                    <button class="btn btn-sm btn-outline-success"><i class="fa fa-check" aria-hidden="true"></i></button>
                </form>`;
                return html;
            }
        }
    ]
    $('#checklistManage_table').dataTable({
        data: listCheckList,
        "processing": true,
        "select": true,
        responsive: true,
        "bDestroy": true,
        "scrollX": true,
        "columns": columnData,
        "language": {
            "emptyTable": "No Record..."
        },
    });
}

function initIconmanagement() {
    // $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
    //     $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    // } );

    $('#icon-management').DataTable({
        "processing": true,
        "select": true,
        "bDestroy": true,
        "scrollX": true,
        "scrollCollapse": true,
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 75, 100],
        "orderMulti": true,
        "retrieve": true,
        "serverSide": true,
        "ajax": {
            url: "/iconmanagement/initDatatable"
        },
        "data": [],
        "columns": [
            {
                data: "no",
                name: "no",
                title: "STT",
                className: 'text-center',
            },
            {
                data: 'icon_url',
                name: "icon_url",
                title: "Hình ảnh",
                render: function (data, type, row) {
                    return `<img src="${data}" style="width:40px">`;
                },
                className: 'text-center',
            },
            {
                data: 'productNameVi',
                name: "productNameVi",
                title: "Tên sản phẩm - VN",
                className: 'text-center',
            },
            {
                data: 'productNameEn',
                name: "productNameEn",
                title: "Tên sản phẩm - EN",
                className: 'text-center',
            },
            {
                data: "status",
                name: "status",
                title: "Trạng thái",
                render: function (data, type, row) {
                    var html = '';
                    if (!row['status']) {
                        html = `<div class="df-switch">
                                    <button type="button" class="btn btn-lg btn-toggle active" data-toggle="button" aria-pressed="true" autocomplete="off" disabled>
                                        <div class="inner-handle"></div>
                                        <div class="handle"></div>
                                    </button>
                                </div>`;
                    }
                    else {
                        html = `<div class="df-switch">
                                    <button type="button" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off" disabled>
                                        <div class="inner-handle"></div>
                                        <div class="handle"></div>
                                    </button>
                                </div>`;
                    }
                    return html;
                },
                className: 'text-center',
            },
            {
                title: 'Action',
                data: 'Id',
                render: function (data, type, row) {
                    var tmp = JSON.stringify(row);
                    return `<div>
                                <button style="float: left; margin-right: 5px" class="btn btn-primary btn-sm" onclick='viewUserInfo(`+ tmp + `)' data-toggle="tooltip" data-placement="top" title="Xem chi tiết"><i class="far fa-eye"></i></button>
                                <a style="float: left; margin-right: 5px" href="/iconmanagement/edit/${id}" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Chỉnh sửa"><i class="far fa-edit"></i></a>
                                <button style="float: left; type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Xóa"><i class="fas fa-trash"></i></button>
                            </div>`;
                },
                className: 'text-center',
                "sortable": false
            }
        ],
        "language": {
            "emptyTable": "No Record..."
        },
        "initComplete": function (setting, json) {
            $('#icon-management-home').show();
        },
        error: function (xhr, error, code) {
            $.pjax.reload('#pjax');
        },
        searchDelay: 500
    });

    $('input:radio[name="status"]').change(() => {
        if($('#status-clock').is(':checked')) {
            $('#status-clock-date-time').show();
        }
        else {
            $('#status-clock-date-time').hide();
        } 
    });
    
    $('input:checkbox[name="is_new_show"]').change(() => {
        if($('#is-new-show').is(':checked')) {
            $('#is-new-icon-show-date-time').show();
        }
        else {
            $('#is-new-icon-show-date-time').hide();
        } 
    });
}

function badgeArrayView(arrayInput) {
    var badge = ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning text-dark', 'bg-info text-dark', 'bg-light text-dark', 'bg-dark'];
    var count_badge = 0;
    var template = ``;
    $.map(arrayInput, function (n, i) {
        if (count_badge == badge.length) {
            count_badge = 0;
        }
        template += `<span class="badge ${badge[count_badge]}">${JSON.stringify(n)}</span> `;
        count_badge++;
    });
    return template;
}

function settings_on_search() {
    $('form .bs-searchbox input').keyup(function (e) {
        if (e.which == 13) {
            var search = $(".bs-searchbox input").val();
            $("#value").append($('<option>', {
                value: search,
                text: search
            }));
            $('#value').selectpicker('refresh');
            $('#value').selectpicker('selectAll');
        }
    });

    $('#value').on('changed.bs.select', function (e) {
        $("#hidden-value").val('[' + $("#value").val().join() + ']');
    });
}
