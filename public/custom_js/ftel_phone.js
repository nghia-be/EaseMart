/*
    Create by: trinhdev
    Update at: 2022/08/04
    Contact: trinhhuynhdp@gmail.com
*/
'use strict';

// function changeFileFtelPhone() {
//     $('#number_phone_import').change(function(e) {
//         let data = new FormData($('#importExcel')[0]);
//         $.ajax( {
//             url: '/ftel-phone/import',
//             type: 'POST',
//             data: data,
//             processData: false,
//             contentType: false,
//             success: function(response) {
//                 console.log('success', response);
//                 $('#number_phone').val(response.data);
//                 showSuccess(response.message);
//             },
//             error: function (xhr) {
//                 var errorString = xhr.responseJSON.message;
//                 $.each(xhr.responseJSON.errors, function (key, value) {
//                     errorString = value;
//                     return false;
//                 });
//                 $('#importExcel').find('input:text, input:password, input:file, select, textarea').val('');
//                 $('#number_phone').val('');
//                 showMessage('error',errorString);
//             }
//         } );
//         e.preventDefault();
//     });
// }

function datatableFtelPhoneExport(){
    $('#phoneExport').DataTable({
        processing: true,
        lengthChange: false,
        responsive: true,
        autoWidth: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'collection',
                text: 'Show options',
                autoClose: true,
                buttons: [
                    {
                        extend: 'colvisGroup',
                        text: 'Show all',
                        show: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22]
                    },
                    {
                        extend: 'colvisGroup',
                        text: 'Hide all',
                        hide: [6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22]
                    },
                    {
                        extend: 'colvisGroup',
                        text: 'Show default (After hide all)',
                        show: [1,2,3,4,5,6,7]
                    }
                ],
                dropup: true
            },
            'colvis'
        ],
        "columnDefs": [
            {
                "targets": [9,10,11,12,13,14,15,16,17,18,19,20,21,22],
                "visible": false
            }
        ]
    });
}
