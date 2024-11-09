
function insertModuleToTable(_this) {
    let row = _this.closest('tr');
    let table = document.getElementById('aclRoletableBody');
    // let tmp = row.querySelector('.module_id');
    let moduleObj = {
        id: row.querySelector('.module_id').innerHTML,
        name: row.querySelector('.module_name').innerHTML
    };
    let check = table.querySelector('tr[name="' + moduleObj.id + '"');
    if (check == null) {
        var new_row = table.insertRow(-1);
        new_row.setAttribute('name', moduleObj.id);
        var cell1 = new_row.insertCell(0);
        var cell2 = new_row.insertCell(1);
        var cell3 = new_row.insertCell(2);
        var cell4 = new_row.insertCell(3);
        var cell5 = new_row.insertCell(4);
        var cell6 = new_row.insertCell(5);
        cell1.innerHTML = `<input name="module_id[]" value="${moduleObj.id}" hidden/>${moduleObj.name}`;
        cell2.innerHTML = `<select name="view[]" id="${moduleObj.id}-view" record="8" class="options_Module form-control">
                                <option value="0" selected>None</option>
                                <option value="1">All</option>
                            </select>`;
        cell3.innerHTML = `<select name="create[]" id="${moduleObj.id}-create" record="8" class="options_Module form-control">
                                <option value="0" selected>None</option>
                                <option value="1">All</option>
                            </select>`;
        cell4.innerHTML = `<select name="update[]" id="${moduleObj.id}-update" record="8" class="options_Module form-control">
                                <option value="0" selected="">None</option>
                                <option value="1">All</option>
                            </select>`;
        cell5.innerHTML = `<select name="delete[]" id="${moduleObj.id}-delete" record="8" class="options_Module form-control">
                                <option value="0" selected="">None</option>
                                <option value="1">All</option>
                            </select>`;
        cell6.innerHTML = `<a type="button" onclick="deleteRow(this)" class="btn btn-danger">
                            <i class="fa fa-trash-alt"></i></a>`;
        // console.log(`#${moduleObj.id}-view`);
        $(`#${moduleObj.id}-view`).selectpicker();
        $(`#${moduleObj.id}-create`).selectpicker();
        $(`#${moduleObj.id}-update`).selectpicker();
        $(`#${moduleObj.id}-delete`).selectpicker();
    }
}

function deleteRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}