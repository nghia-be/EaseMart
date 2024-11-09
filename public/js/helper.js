async function dataTable(model, permision, module, url) {
    var columns = [];
    await model.map(col => {
        columns.push({
            data: col
        });
    });
    if (permision.update && permision.delete) {
        columns.push({
            data: null,
            render: function (data, type, row, meta) {
                var buttonHtml = ``;
                if (permision['update']) {
                    buttonHtml += `<a style="float: left; margin-right: 5px" href="${module}/edit" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></a>`;
                }
                if (permision['delete']) {
                    buttonHtml += `<form action="${module}/${data.id}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>`;
                }
            }
        });
    }

    $('#grid').DataTable({
        "pageLength": 3,
        "lengthMenu": [3, 10, 25, 50, 100],
        "processing": true,
        "serverSide": true,
        "ajax": module + url,
        "columns": columns
    });
};