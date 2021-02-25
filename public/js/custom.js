
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
    //console.log(1);
    if ($.fn.DataTable.isDataTable("#datatable1"))
        $('#datatable1').DataTable().clear().destroy();

    let result = JSON.parse($("<div/>").html(res_details).text());
    html_data = '';
    var sl_cnt = 0;
    var team_name_flag = false;
    if (result.length != 0) {
        for (i = 0; i < result.length-1; i++) {
            if (result[i].salesman == result[i + 1].salesman) {
                sl_cnt += result[i].sales_count;
                if (result[i].sales_count > result[i + 1].sales_count) {
                    if (result[i].team != '')
                        team_name = result[i].team;
                }
                else {
                    if (result[i + 1].team != '')
                        team_name = result[i + 1].team;
                }

                team_name_flag = true;
            }
            else {
                sl_cnt += result[i].sales_count;
                if (team_name_flag == false) {
                    if (result[i].team == '') {
                        sl_cnt = 0;
                        team_name_flag = false;
                        continue;
                    }
                    else
                        team_name = result[i].team;
                }
                    
                html_data += table_data_insertion(result[i].salesman, sl_cnt, team_name);

                sl_cnt = 0;
                team_name_flag = false;
            }
            
        }

        sl_cnt += result[result.length-1].sales_count;
        if (team_name_flag == false) {
            if (result[result.length - 1].team != '') {
                team_name = result[result.length - 1].team;
                html_data += table_data_insertion(result[result.length - 1].salesman, sl_cnt, team_name);
            }    
        }

        else
            html_data += table_data_insertion(result[result.length - 1].salesman, sl_cnt, team_name);

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

function table_data_insertion(salesman,sales_count,sales_team) {

    data = '';

    data += '<tr>' +
        '<td>' + salesman + '</td>' +
        '<td>' + sales_count + '</td>' +
        '<td>' + sales_team + '</td>' +
        '<td>' + '' + '</td>' +
        '<td>' + '' + '</td>' +
        '</tr>';

    return data;
}
