var today = new Date();
var dd = today.getDate();

var ac_mm = today.getMonth() + 1;

var yyyy = today.getFullYear();
if (dd < 10) {
    dd = '0' + dd;
}

if (ac_mm < 10) {
    mm = '0' + ac_mm;
}
today = yyyy + '-' + mm + '-' + dd;

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
    else if (page == 'salesman-details')
        $("a[name='dashboard']").parent().addClass('active');
    else
        $("a[name='"+page+"']").parent().addClass('active');
}

function insert_table_data(res_details) {
    //console.log(1);
    if ($.fn.DataTable.isDataTable("#datatable1"))
        $('#datatable1').DataTable().clear().destroy();

    let result = JSON.parse($("<div/>").html(res_details).text());
    html_data = '';
    if (result.length != 0) {
        $.each(result, function (datakey, datavalue) {
            if (datavalue.salesman != '')
                html_data += table_data_insertion(datavalue.salesman, datavalue.sales_count,datavalue.downpay_add,datavalue.cuscost_add);

        });

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
        "order": [[1, "desc"]],
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        }
    });

    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });


}

function table_data_insertion(salesman,sales_count,downpay_add,cuscost_add) {
    var downpayment = downpay_add/cuscost_add;
    data = '';

    data += '<tr>' +
        '<td><a class="sm_name" href="javascript:void(0);">' + salesman + '</a></td>' +
        '<td>' + sales_count + '</td>' +
        '<td>' + downpayment.toFixed(2) + '%'+ '</td>' +
        '<td>' + '' + '</td>' +
        '<td>' + '' + '</td>' +
        '<td>' + '' + '</td>' +
        '</tr>';

    return data;
}


function deal_calendar(res) {
    var data_arr = [];
    let result = JSON.parse($("<div/>").html(res).text());
    $.each(result, function (dkey, dvalue) {
        let temp_arr = [];
        data_arr[dvalue.purchdate] = dvalue.sales_count;
    });
    place_lead_count(data_arr);
}

function place_lead_count(data_arr) {
    //console.log(data_arr);
    $('#fullCalendar').fullCalendar({
        header: {
            left: 'prev',
            center: 'title',
            right: 'today next'
        },

        viewRender: function (view, element) {
            var lead_cnt;
            $.each($(".fc-day-top"), function (key, val) {
                let cal_date = $(this).attr("data-date");
                let temp_date = new Date(cal_date)
                //console.log(temp_date.getMonth());
                if (temp_date.getMonth() + 1 == window.ac_mm) {
                    if (cal_date != window.today) {
                        keys = Object.keys(data_arr);
                        if (keys.indexOf(cal_date) != -1) {
                            $.each(keys, function (k, key_date) {
                                if (cal_date == key_date) {
                                    lead_cnt = data_arr[key_date];
                                }
                            });
                            $(this).append("<div class='fc-lead'>Lead: <strong>" + lead_cnt + "</strong></div>");
                        }
                        else
                            $(this).append("<div class='fc-lead'>Lead: <strong> 0 </strong></div>");
                    }
                    else {
                        $(this).append("<div class='fc-lead'><strong> counting... </strong></div>");
                        return false;
                    }  
                }
                
            });
        },
    });
    $(".calen").show();
}


