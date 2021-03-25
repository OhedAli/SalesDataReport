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


$('.back-btn span').on('click',function(){
    $('.back-btn').fadeOut('slow');
    setTimeout(function(){
        $('.no-gutters').fadeIn('slow');
    },500);
    
});

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

function tble_lead_info(result_data, search_flag){

    if ($.fn.DataTable.isDataTable("#datatable2"))
        $('#datatable2').DataTable().clear().destroy();

    
    if(search_flag == true){
     resultObj = JSON.parse($("<div/>").html(result_data).text());
    } 
    else {
      resultObj = result_data;
    }
    // console.log(resultObj);
   var data = '';
   var type;
   if (resultObj.length != 0) {
        resultObj.forEach(function(arrData){
            let discount = (arrData.retail - arrData.cuscost);
            if(discount < 0)
                discount = 0;

            if(arrData.label1 != 'WHOLESALE')
                type = 'SALE';
            else
                type = 'WHOLESALE';

            data += '<tr>' +
            '<td>' + arrData.app_number + '</td>' +
            '<td>' + arrData.first_name + ' '+ arrData.last_name + '</td>' +
            '<td>$' + Math.round(arrData.downpay) + '</td>' +
            '<td>' + arrData.finterm  + '</td>' +
            '<td>$' + Math.round(discount) + '</td>' +
            '<td>' + type + '</td>' +
            '<td>' + arrData.purchdate + '</td>' +
            '</tr>';
             
        })

    
        $("#lead_data").html(data);
    }
    else {
        $("#lead_data").html('');
    }

    datatable_reset(table_id=2);
}



function insert_table_data(res_details) {
    
    if ($.fn.DataTable.isDataTable("#datatable1"))
        $('#datatable1').DataTable().clear().destroy();

    let result = JSON.parse($("<div/>").html(res_details).text());
    
    html_data = '';
    if (result.length != 0) {
        $.each(result, function (datakey, datavalue) {
            
            if (datavalue.salesman != '')
                html_data += table_data_insertion(datavalue.salesman, datavalue.sales_count, datavalue.downpay_add, datavalue.cuscost_add, datavalue.finterm_add, datavalue.retail_add,datavalue.total_calls);

        });

        $("#sales_info_data").html(html_data);
    }
    else {
        $("#sales_info_data").html('');
    }

    datatable_reset(table_id=1);
    
}

function leader_board_update(res_details){
   
    let result = JSON.parse($("<div/>").html(res_details).text());
    //console.log(result);
    $(".l-board").html('');
    let path = window.location.href;
    //let path = window.location.origin;
    path = path.substr(0,path.indexOf('public')) + 'public';
    if (result.length != 0) {
        $.each(result, function (datakey, datavalue) {
            
            if (datavalue.sales_count != ''){
                    
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
                

        });

    }

    else{
        $(".l-board").html('');
    }

}

function datatable_reset(table_id) {
    
    if(table_id == 1){
    	$('#datatable1').DataTable({
	        responsive: true,
	        "order": [[1, "desc"]],
	        "columnDefs" : [{targets:5, type:"num-html"},{targets:6, type:"num-html"}],
	        language: {
	            searchPlaceholder: 'Search...',
	            sSearch: '',
	            lengthMenu: '_MENU_ items/page',
	        }
    	});
    }
    else{
    	$('#datatable'+table_id).DataTable({
	        responsive: true,
	        language: {
	          searchPlaceholder: 'Search...',
	          sSearch: '',
	          lengthMenu: '_MENU_ items/page',
	        }
	    });
    }
    

    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });


}

function table_data_insertion(salesman, sales_count, downpay_add, cuscost_add, finterm_add, retail_add,total_calls) {
    
    var downpayment = (downpay_add / cuscost_add) * 100;
    var finterm = finterm_add / sales_count;
    var discount = retail_add - cuscost_add;
    discount = '$' + Math.round(discount / sales_count);
    var calls = (total_calls !== undefined ? total_calls : 'N/A');
    var conv_rate = ((total_calls !== undefined  && total_calls != 0) ? (((sales_count/total_calls) * 100).toFixed(2)) + '%' : 'N/A');

    data = '';

    data += '<tr>' +
        '<td><a class="sm_name" href="javascript:void(0);">' + salesman + '</a></td>' +
        '<td>' + sales_count + '</td>' +
        '<td>' + downpayment.toFixed(2) + '%</td>' +
        '<td>' + finterm.toFixed(2) + '</td>' +
        '<td>' + discount + '</td>' +
        '<td>' + calls + '</td>' +
        '<td>' + conv_rate + '</td>' +
        '</tr>';
    return data;
}

function deal_calendar(res, prev) {
    var data_call = [];
    var data_lead = [];
    var result;
    // console.log(1);
    result = JSON.parse($("<div/>").html(res).text());

    // console.log(result);
    $.each(result, function (dkey, dvalue) {
        if(dvalue.hasOwnProperty('call_date')){
            data_call[dvalue.call_date] = dvalue.total_calls;
        }
        else{
            data_lead[dvalue.purchdate] = dvalue.sales_count;
        }
        
    });

    var cal_date = get_calendar_date();
    place_lead_count(data_call, data_lead, cal_date);
}

function place_lead_count(data_call, data_lead, date) {
    //console.log(data_arr);
    var keys_call = Object.keys(data_call);
    var keys_lead = Object.keys(data_lead);
    //console.log(keys);
    var lead_cnt=0;
    var html='';
    var flag = false;

        $.each($(".fc-day-top"), function (key, val) {
            let cal_date = $(this).attr("data-date");
            let temp_date = new Date(cal_date);
            //console.log(temp_date.getMonth());
            if (temp_date.getMonth() + 1 == date['month'] && date['month'] <= window.ac_mm && date['year'] == window.yyyy) {
                if (cal_date != window.today) {
                    if (keys_call.indexOf(cal_date) != -1){
                        keys = keys_call;
                        flag = true;
                    }
                    else if(keys_lead.indexOf(cal_date) != -1){
                        keys = keys_lead;
                        flag = true;
                    }
                    else{
                        flag = false;
                    }

                    if(flag == true) {

                        $.each(keys, function (k, key_date) {
                            if (cal_date == key_date) {

                                if(data_lead[key_date] != undefined)
                                    lead_cnt = data_lead[key_date];
                                else
                                    lead_cnt = 0;
                                
                                if(data_call[key_date] != undefined)
                                    call_cnt = data_call[key_date];
                                else
                                    call_cnt = 'N/A';
                            }
                        });

                        html = "<div class='fc-lead fc-data'><a style='cursor:pointer'>Sales: <strong>" + lead_cnt + "</strong></a></div>" +
                               "<div class='fc-call fc-data'><a style='cursor:pointer'>Calls: <strong>" + call_cnt + "</strong></a></div>" +
                               "<div class='fc-cvr fc-data'><a style='cursor:pointer'>Cvr: <strong>" + (call_cnt != 'N/A' ? ((lead_cnt/call_cnt) * 100).toFixed(2) + '%' : 'N/A') + "</strong></a></div>";

                        $(this).append(html);
                        
                    }
                    else{
                        html = "<div class='fc-lead fc-data'><a style='cursor:pointer'>Sales: <strong>0</strong></a></div>" +
                               "<div class='fc-call fc-data'><a style='cursor:pointer'>Calls: <strong>0</strong></a></div>" +
                               "<div class='fc-cvr fc-data'><a style='cursor:pointer'>Cvr: <strong>0%</strong></a></div>";
                        
                        $(this).append(html);
                    }

                }
                else {
                    lead_cnt = data_lead[window.today];
                    call_cnt = data_call[window.today];
                    if (lead_cnt == undefined)
                        lead_cnt = 0

                    if (call_cnt == undefined)
                        call_cnt = 0

                    html = "<div class='fc-lead fc-data'><a style='cursor:pointer'>Sales: <strong>" + lead_cnt + "</strong></a></div>" +
                               "<div class='fc-call fc-data'><a style='cursor:pointer'>Calls: <strong>" + call_cnt + "</strong></a></div>" +
                               "<div class='fc-cvr fc-data'><a style='cursor:pointer'>Cvr: <strong>" + (call_cnt != 0 ? ((lead_cnt/call_cnt) * 100).toFixed(2) + '%' : 'N/A') + "</strong></a></div>";


                    $(this).append(html);

                    return false;
                }
            }

            else if (temp_date.getMonth() + 1 == date['month'] && date['year'] < window.yyyy) {

                if (keys_call.indexOf(cal_date) != -1){
                    keys = keys_call;
                    flag = true;
                }
                else if(keys_lead.indexOf(cal_date) != -1){
                    keys = keys_lead;
                    flag = true;
                }
                else{
                    flag = false;
                }

                if(flag == true){
                    $.each(keys, function (k, key_date) {
                        if (cal_date == key_date) {

                            if(data_lead[key_date] != undefined)
                                lead_cnt = data_lead[key_date];
                            else
                                lead_cnt = 0;
                            
                            if(data_call[key_date] != undefined)
                                call_cnt = data_call[key_date];
                            else
                                call_cnt = 'N/A';
                        }
                    });

                    html = "<div class='fc-lead fc-data'><a style='cursor:pointer'>Sales: <strong>" + lead_cnt + "</strong></a></div>" +
                           "<div class='fc-call fc-data'><a style='cursor:pointer'>Calls: <strong>" + call_cnt + "</strong></a></div>" +
                           "<div class='fc-cvr fc-data'><a style='cursor:pointer'>Cvr: <strong>" + ((lead_cnt/call_cnt) * 100).toFixed(2) + "%</strong></a></div>";

                    $(this).append(html);
                }

                else{

                    html =  "<div class='fc-lead fc-data'><a style='cursor:pointer'>Sales: <strong>0</strong></a></div>" +
                            "<div class='fc-call fc-data'><a style='cursor:pointer'>Calls: <strong>0</strong></a></div>" +
                            "<div class='fc-cvr fc-data'><a style='cursor:pointer'>Cvr: <strong>0%</strong></a></div>";
                        
                    $(this).append(html);
                }
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



$(function () {

    $('#fullCalendar').fullCalendar({

        customButtons: {
            add_event: {
              text: ' ',
              click: function() {
                $('.fc-view-container').toggle('slow');
                $('.fc-add_event-button').toggleClass('minimize');
              }
            }
        },
        header: {
            left: 'prev',
            center: 'title',
            right: 'add_event next'
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

    $.extend($.fn.dataTableExt.oSort, {
        "num-html-pre": function ( a ) {
            var x = String(a).replace( /<[\s\S]*?>/g, "" );
            return parseFloat( x );
        },
     
        "num-html-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },
     
        "num-html-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );

});

$('.reload').on('click',function(){
    location.reload();
});

$('.cal-tab button').on('click',function(){
    if($(this).hasClass('caledar-btn')){
        $('.calen').fadeOut('fast');
        $('.dash_table').fadeIn('slow');
        $(this).children('span').html('Calendar');
    }
    else{
        $('.calen').fadeIn('slow');
        $('.dash_table').fadeOut('fast');
        $(this).children('span').html('Sales Table');
    }
    $(this).toggleClass('caledar-btn');
});


function tble_oppprt_info(res_data, search_flag)
{

    if ($.fn.DataTable.isDataTable("#datatable3"))
        $('#datatable3').DataTable().clear().destroy();

    
    if(search_flag == true){
     resultObj = JSON.parse($("<div/>").html(res_data).text());
    } 
    else {
      resultObj = res_data;
    }
    // console.log(resultObj);
    var data = '';
    let min,sec;
    if (resultObj.length != 0) {
        resultObj.forEach(function(arrData){

        	if(arrData.first_name == null)
        		arrData.first_name = '';

        	if(arrData.last_name == null)
        		arrData.last_name = '';

        	if(arrData.email == null)
        		arrData.email = '';

        	hour = Math.trunc(arrData.length_in_sec / 3600) ;
        	min = Math.trunc((arrData.length_in_sec % 3600) / 60);
        	sec = arrData.length_in_sec % 60 ;

        	if(hour < 10)
        		hour = '0' + hour;

        	if(min < 10)
        		min = '0' + min;

        	if(sec < 10)
        		sec = '0' + sec;



            data += '<tr>' +
            '<td>' + arrData.lead_id + '</td>' +
            '<td>' + arrData.first_name + ' '+ arrData.last_name + '</td>' +
            '<td>' + arrData.phone_number + '</td>' +
            '<td>' + arrData.email  + '</td>' +
            '<td>' + hour + ':' + min + ':' + sec + '</td>' +
            '</tr>';
             
        });

    
        $("#lead_data_oppprt").html(data);
    }
    else {
        $("#lead_data_oppprt").html('');
    }

    datatable_reset(table_id=3);
}