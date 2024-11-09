$(document).ready(function () {
    console.log($('#all-title-config li').width());

    var $form = $('form');
    var initialState = $form.serialize();
    
    $form.change(function (e) {
        if (initialState === $form.serialize()) {
            $( "#submit-button" ).prop( "disabled", true );
        } else {
            $( "#submit-button" ).prop( "disabled", false );
        }
        e.preventDefault();
    });

    if ($('#icon-per-row').val()) {
        $("#selected-product-config").css({
            "maxWidth": ($('#icon-per-row').val()) ? ((parseInt($('#icon-per-row').val()) + 1) * 180 / $(".card-info").width()) * 100 + "%" : "100%",
        });
    }

    // lightSlider
    $('#all-product-config').lightSlider({
        item: 5,
        loop: true,
        slideMove: 1,
        easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
        speed: 600,
        slideMargin: 15,
        enableDrag: false,
        enableTouch: false,
        pager: false
    });
});
dragula([document.getElementById('all-product-config'), document.getElementById('selected-product-config')], {
    direction: 'horizontal',
    revertOnSpill: true,
    copy: function (el, source) {
        console.log('copy');
        return source === document.getElementById('all-product-config')
    },
    accepts: function (el, target, source, sibling) {
        var li_all = $(el).attr('id');
        if ($('#' + li_all + '-selected-product-config').length != 0) {
            swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `Sản phẩm này đã tồn tại trong danh mục`
            });
            return false;
        }

        var iconsPerRow = ($('#icon-per-row').val()) ? $('#icon-per-row').val() : 1;
        var rowOnPage = ($('#row-on-page').val()) ? $('#row-on-page').val() : 1;

        if ($('#selected-product-config li').length > iconsPerRow * rowOnPage) {
            swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `Chỉ có thể thêm tối đa ${iconsPerRow * rowOnPage} icon vào vị trí này. Đã quá tổng số sản phẩm có thể thêm. Vui lòng xóa bớt sản phẩm trước khi thêm vào vị trí.`
            });
            return false;
        }

        return target !== document.getElementById('all-product-config')
    }
}).on('drop', (el, target, source, sibling) => {
    var li_all = $(el).attr('id');
    $(el).attr('id', li_all + '-selected-product-config');
    $(el).removeClass("lslide");
    $(el).removeClass("active");
    $(el).removeClass("gu-transit");

    $(el).css('margin-right', 0);

    var spanElement = $(el).find("span:first");
    $(spanElement).removeClass("badge-light");
    $(spanElement).addClass("badge-dark");

    if ($(el).find('span.position').length < 1) {
        $(el).append(`<h6><span class="badge badge-warning position">${$(el).index() + 1}</span></h6>`);
    }

    $(target).find("li").each((key, value) => {
        $(value).find("span.position").text($(value).index() + 1);
    });
});

$('#icon-per-row').change(function () {
    $("#selected-product-config").css({
        // "maxWidth": ($('#icon-per-row').val()) ? ($('#icon-per-row').val() * 100 / ($("#selected-product-config").width() / 180)) + "%" : "100%",
        "maxWidth": ($('#icon-per-row').val()) ? ((parseInt($('#icon-per-row').val()) + 1) * 180 / $(".card-info").width()) * 100 + "%" : "100%",
    });
});

$("input[name='prod_add']").change(function () {
    if (this.value == 'category_add') {
        $('.category-add').css('display', 'block');
        $('.product-add').css('display', 'none');
        if ($('#all-title-config')) {
            $('#all-title-config').lightSlider({
                item: 5,
                loop: true,
                slideMove: 1,
                easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
                speed: 600,
                slideMargin: 15,
                enableDrag: false,
                enableTouch: false,
                pager: false
            });
        }
    }
    else {
        $('.category-add').css('display', 'none');
        $('.product-add').css('display', 'block');
        if ($('#all-product-config')) {
            $('#all-product-config').lightSlider({
                item: 5,
                loop: true,
                slideMove: 1,
                easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
                speed: 600,
                slideMargin: 15,
                enableDrag: false,
                enableTouch: false,
                pager: false
            });
        }
    }
    // console.log(this.value);
});

$("#status-all").change(function () {
    if (this.checked) {
        $("input[name='status']").prop('checked', true);
    }
    else {
        $("input[name='status']").prop('checked', false);
    }
});

$("#pheduyet-all").change(function () {
    if (this.checked) {
        $("input[name='pheduyet']").prop('checked', true);
    }
    else {
        $("input[name='pheduyet']").prop('checked', false);
    }
});

$('#icon-config tbody').on('click', '.delete-button', function () {
    var data = icon_config_table.row($(this).parents('tr')).data();
    deleteButton(JSON.stringify(data), data['name'], '/iconconfig/destroy');
});

function changeSelectedProduct(productList) {
    var new_selected_prod_list = ``;
    $.each(productList, function (key, value) {
        new_selected_prod_list += `
                                    <li class="selected-li" id="${value['productId']}-selected-product-config" data-prodid="${value['productId']}">
                                        <img src="${value['iconUrl']}" alt="${value['productNameVi']}">
                                        <br>
                                        <button type="button" class="close-thik" onClick="removeFromSelectedProduct('${value['productId']}-selected-product-config')"><i class="fas fa-times-circle"></i></button>
                                        <h6><span class="badge badge-dark">${value['productNameVi']}</span></h6>
                                        <h6><span class="badge badge-warning position">${key + 1}</span></h6>
                                    </li>`;
    });
    $('#selected-product-config').html(new_selected_prod_list);
}