var today = new Date();
var ac_dd = today.getDate();

var ac_mm = today.getMonth() + 1;

var yyyy = today.getFullYear();
if (ac_dd < 10) {
dd = '0' + ac_dd;
}
else{
    dd = ac_dd;
}

if (ac_mm < 10) {
    mm = '0' + ac_mm;
}
else{
    mm = ac_mm;
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
        $("a[name='" + page + "']").parent().addClass('active');
}

function tble_lead_info(result_data, adv_search_flag){

    if ($.fn.DataTable.isDataTable("#datatable2"))
        $('#datatable2').DataTable().clear().destroy();

    
    if(adv_search_flag == true){
     resultObj = JSON.parse($("<div/>").html(result_data).text());
    } 
    else {
      resultObj = result_data;
    }
    //console.log(resultObj);
   data = '';
   if (resultObj.length != 0) {
        resultObj.forEach(function(arrData){
            let discount = (arrData.retail - arrData.cuscost);
            if(discount < 0)
                discount = 0;

            data += '<tr>' +
            '<td>' + arrData.app_number + '</td>' +
            '<td>' + arrData.first_name + ' '+ arrData.last_name + '</td>' +
            '<td>' + arrData.downpay + '</td>' +
            '<td>' + arrData.finterm  + '</td>' +
            '<td>' + discount.toFixed(2) + '</td>' +
            '<td>' + arrData.purchdate + '</td>' +
            '</tr>';
             
        })

    
        $("#lead_data").html(data);
    }
    else {
        $("#lead_data").html('');
    }

    datatable_reset();
}



function insert_table_data(res_details) {
    
    if ($.fn.DataTable.isDataTable("#datatable1"))
        $('#datatable1').DataTable().clear().destroy();

    let result = JSON.parse($("<div/>").html(res_details).text());
    
    html_data = '';
    if (result.length != 0) {
        $.each(result, function (datakey, datavalue) {
            
            if (datavalue.salesman != '')
                html_data += table_data_insertion(datavalue.salesman, datavalue.sales_count, datavalue.downpay_add, datavalue.cuscost_add, datavalue.finterm_add, datavalue.retail_add);

        });

        $("#sales_info_data").html(html_data);
    }
    else {
        $("#sales_info_data").html('');
    }

    datatable_reset();
    
}

function leader_board_update(res_details){
   
    let result = JSON.parse($("<div/>").html(res_details).text());
    //console.log(result);
    //let path = window.location.href;
    let path = window.location.origin;
    //path = path.substr(0,path.indexOf('public')) + 'public';
    if (result.length != 0) {
        $.each(result, function (datakey, datavalue) {
            
            if (datavalue.sales_count != ''){

                if($(".l-board").children().length < 3 ){
                    
                    let data_html = '<div class="mem-1"><div class="tx-center">'+
                                    '<a href="javascript:void(0);" class="img-a"><img src="'+ path +'/images/profile.png" class="card-img" alt="">'+
                                    '<div class="hexa"><img src="'+ path + '/images/hexa'+ (datakey + 1) +'.png" class="hexa1-bg" alt="">'+
                                    '<p>'+ (datakey + 1) +'</p></div></a>'+
                                    '<h5 class="mg-t-10 mg-b-5">'+
                                    '<a href="javascript:void(0);" class="contact-name sm_name leader_name_'+ datakey + '">'+ datavalue.salesman + '</a>'+
                                    '<p><span class="leaderboardcount'+ datakey +'">'+ datavalue.sales_count + '</span> Sales</p>'+
                                    '</div></div>';

                    $(".l-board").append(data_html);
                }
                else{
                    $('.leaderboardcount'+datakey).html(datavalue.sales_count);
                    $('.leader_name'+datakey).html(datavalue.salesman);
                    //console.log(datakey);
                }
                
            }
                

        });

    }

    else{
        $(".l-board").html('');
    }

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

    $('#datatable2').DataTable({
        responsive: true,
        language: {
          searchPlaceholder: 'Search...',
          sSearch: '',
          lengthMenu: '_MENU_ items/page',
        }
      });

    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });


}

function table_data_insertion(salesman, sales_count, downpay_add, cuscost_add, finterm_add, retail_add) {
    
    var downpayment = (downpay_add / cuscost_add) * 100;
    var finterm = finterm_add / sales_count;
    var discount = retail_add - cuscost_add;
    discount = discount / sales_count;

    data = '';

    data += '<tr>' +
        '<td><a class="sm_name" href="javascript:void(0);">' + salesman + '</a></td>' +
        '<td>' + sales_count + '</td>' +
        '<td>' + downpayment.toFixed(2) + '%</td>' +
        '<td>' + finterm.toFixed(2) + '</td>' +
        '<td>' + discount.toFixed(2) + '</td>' +
        '<td>' + '' + '</td>' +
        '<td>' + '' + '</td>' +
        '</tr>';
    return data;
}

function deal_calendar(res, prev) {
    var data_arr = [];
    var result;
    if (prev == 0)
        result = JSON.parse($("<div/>").html(res).text());
    else
        result = res;
    //console.log(result);
    $.each(result, function (dkey, dvalue) {
        let temp_arr = [];
        data_arr[dvalue.purchdate] = dvalue.sales_count;
    });

    var cal_date = get_calendar_date();
    place_lead_count(data_arr, cal_date);
}

function place_lead_count(data_arr,date) {
    //console.log(data_arr);
    var keys = Object.keys(data_arr);
    //console.log(keys);
    var lead_cnt;

        $.each($(".fc-day-top"), function (key, val) {
            let cal_date = $(this).attr("data-date");
            let temp_date = new Date(cal_date);
            //console.log(temp_date.getMonth());
            if (temp_date.getMonth() + 1 == date['month'] && date['month'] <= window.ac_mm && date['year'] == window.yyyy) {
                if (cal_date != window.today) {
                    if (keys.indexOf(cal_date) != -1) {
                        $.each(keys, function (k, key_date) {
                            if (cal_date == key_date) {
                                lead_cnt = data_arr[key_date];
                            }
                        });
                        $(this).append("<div class='fc-lead fc-data'>Lead: <strong>" + lead_cnt + "</strong></div>");
                    }
                    else
                        $(this).append("<div class='fc-lead'>Lead: <strong> 0 </strong></div>");
                }
                else {
                    lead_cnt = data_arr[window.today];
                    if (lead_cnt == undefined)
                        lead_cnt = 0
                    $(this).append("<div class='fc-lead fc-data'>Lead: <strong>" + lead_cnt +"</strong></div>");
                    return false;
                }
            }

            else if (temp_date.getMonth() + 1 == date['month'] && date['year'] < window.yyyy) {
                if (keys.indexOf(cal_date) != -1) {
                    $.each(keys, function (k, key_date) {
                        if (cal_date == key_date) {
                            lead_cnt = data_arr[key_date];
                        }
                    });
                    $(this).append("<div class='fc-lead fc-data'>Lead: <strong>" + lead_cnt + "</strong></div>");
                }
                else
                    $(this).append("<div class='fc-lead'>Lead: <strong> 0 </strong></div>");
            }

            else {
                // do nothing
            }

        });
    
}

function get_calendar_date() {

    var date_arr = [];
    var cal_date_val = $('.fc-center h2').text();
    var d_obj = new Date(cal_date_val);
    var mnth = d_obj.getMonth() + 1;
    var yr = d_obj.getFullYear();
    date_arr['month'] = mnth;
    date_arr['year'] = yr;
    return date_arr;

}



$(function(){

    $('#fullCalendar').fullCalendar({
        header: {
            left: 'prev',
            center: 'title',
            right: 'next'
        }

    });
    $(".calen").show();
});

$(function () {

    $.extend($.fn.dataTableExt.oSort, {
        "stringMonthYear-pre": function (s) {
            var monthArr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var dateComponents = s.split(" ");
            var yr = dateComponents[2];
            var month = monthArr.indexOf(dateComponents[1]);
            var day = dateComponents[0];



            return new Date(yr, month, day);
        },
        "stringMonthYear-asc": function (a, b) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },
        "stringMonthYear-desc": function (a, b) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    });

});


