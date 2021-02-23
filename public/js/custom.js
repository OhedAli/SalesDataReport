
! function (s) {
    "use strict";
    s(document).on("scroll", function () {
        100 < s(this).scrollTop() ? s(".scroll-to-top").fadeIn() : s(".scroll-to-top").fadeOut()
    }), s(document).on("click", "a.scroll-to-top", function (e) {
        s("html, body").stop().animate({
            scrollTop: s('.slim-header').offset().top
        }), e.preventDefault()
    })
}(jQuery);

function markActiveNav(page) {
    //console.log(page);
    $(".nav-item").removeClass('active');
    if (page == 'sales-view' || page == 'wholesaleslogs')
        $("a[name='saleslogs']").parent().addClass('active');
    else
        $("a[name='"+page+"']").parent().addClass('active');
}

function insert_table_data(res_details) {
    console.log(1);
    if ($.fn.DataTable.isDataTable("#datatable1"))
        $('#datatable1').DataTable().clear().destroy();

    let result = JSON.parse($("<div/>").html(res_details).text());
    html_data = '';
    if (result.length != 0) {
        $.each(result, function (key, value) {
            html_data += '<tr>' +
                '<td>' + value.app_number + '</td>' +
                '<td>' + value.salesman + '</td>' +
                '<td>' + value.team + '</td>' +
                '<td>' + value.model + '</td>' +
                '<td>' + value.create_at + '</td>' +
                '</tr>';
        });
        //console.log(html_data);
        $("#sales_info_data").html(html_data);
    }
    else {
        $("#sales_info_data").html('');
    }

    datatable_reset();
}

function datatable_reset() {

    $('#datatable1').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        }
    });

    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });


}
