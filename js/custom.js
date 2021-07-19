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
    $('.adv_time_span').children('.top-card').removeClass('active');
    $('.back-btn').fadeOut('slow');
    $('.adv_time_span').fadeOut('slow');
    $('.dtxt').fadeOut('slow');
    $('#changedays').val('');
    setTimeout(function(){
        $('.top-smry').fadeIn('slow');
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

function tble_lead_info(result_data, search_flag, pifs_check){

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
   var ws_arr = ['WHOLESALE','WSINBOUND','WSOUTBOUND','WSPDCLRD'];
   if (resultObj.length != 0) {
        resultObj.forEach(function(arrData){
            let discount = (arrData.retail - arrData.cuscost);
            if(discount < 0)
                discount = 0;

            if(ws_arr.indexOf(arrData.label1) != -1 || ws_arr.indexOf(arrData.label2) != -1 || ws_arr.indexOf(arrData.label3) != -1)
                type = 'WHOLESALE';
            else
                type = 'SALE';

            if((pifs_check == false && arrData.finterm != 0) ||  pifs_check == true){

                data += '<tr '+ (arrData.cancelled_flag == 1 ? 'class="cancel_rec"' : '' ) + '>' +
                '<td>' + arrData.app_number + '</td>' +
                '<td>' + arrData.first_name + ' '+ arrData.last_name + '</td>' +
                '<td>$' + Math.round(arrData.downpay) + '</td>' +
                '<td>' + arrData.finterm  + '</td>' +
                '<td>$' + Math.round(discount) + '</td>' +
                '<td>' + type + '</td>' +
                '<td>' + arrData.purchdate + '</td>' +
                '<td>' + (arrData.filename != undefined ? '<a href="' + arrData.location + '" target="_blank">' + arrData.filename + '</a>' : 'N/A') + '</td>' +
                '</tr>';

            }
             
        });

    
        $("#lead_data").html(data);
    }
    else {
        $("#lead_data").html('');
    }

    datatable_reset(table_id=2);
}



function insert_table_data(res_details,type,pif_check) {
    
    if(type == 'sales_man'){
        if ($.fn.DataTable.isDataTable("#datatable1")){
            $('#datatable1').DataTable().clear().destroy();
            $("#sales_info_data").empty();
        }
    }
    else{
        if ($.fn.DataTable.isDataTable("#datatable1m")){
            $('#datatable1m').DataTable().clear().destroy();
            $("#sales_info_data_manager").empty();
        }
    }
    
    // console.log(res_details);
    var result;

    if(res_details != '')
        result = JSON.parse($("<div/>").html(res_details).text());
    else
        result = '';
    
    html_data = '';
    if (result.length != 0) {
        $.each(result, function (datakey, datavalue) {
            
            if (datavalue.salesman != ''){
                html_data += table_data_insertion(datavalue, type, pif_check);
            }

        });

        if(type == 'sales_man')
            $("#sales_info_data").html(html_data);
        else
            $("#sales_info_data_manager").html(html_data);
    }
    else {
        if(type == 'sales_man')
            $("#sales_info_data").html('');
        else
            $("#sales_info_data_manager").html('');
    }

    if(type == 'sales_man')
        datatable_reset(table_id=1);
    else
        datatable_reset(table_id='1m');
    
}

function leader_board_update(slm_res_details,team_res_details){
   
    let sm_result = JSON.parse($("<div/>").html(slm_res_details).text());
    let team_result = JSON.parse($("<div/>").html(team_res_details).text());
    //console.log(result);
    $("#ldr_tbl").html('');
    $("#team_ldr_tbl").html('');
    let path = window.location.href;
    //let path = window.location.origin;
    path = path.substr(0,path.indexOf('public')) + 'public';
    var slm_data_html,team_data_html,pro_img;
    if (sm_result.length != 0) {
        $.each(sm_result, function (datakey, datavalue) {
            
            if (datavalue.sales_count != ''){
                
                pro_img = ((datavalue.avatar != null && datavalue.avatar != undefined && datavalue.avatar != '') ? datavalue.avatar : 'profile.png');
                // data_html = '<div class="mem-1"><div class="tx-center">'+
                //             '<a href="javascript:void(0);" class="img-a"><img src="'+ path +'/images/uploads/avatars/'+ pro_img +'" class="card-img" alt="">'+
                //             '<div class="hexa"><img src="'+ path + '/images/hexa'+ (datakey + 1) +'.png" class="hexa1-bg" alt="">'+
                //             '<p>'+ (datakey + 1) +'</p></div></a>'+
                //             '<h5 class="mg-t-10 mg-b-5">'+
                //             '<a href="javascript:void(0);" class="contact-name sm_name leader_name_'+ datakey + '">'+ datavalue.salesman + '</a>'+
                //             '<p><span class="leaderboardcount'+ datakey +'">'+ datavalue.sales_count + '</span> Sales</p>'+
                //             '</div></div>';


                slm_data_html = '<tr><td>'+ (datakey + 1) +'</td><td><div class="'+ (datakey <= 2 ? 'top-slm' : '')  +'">' + 
                                '<img src="' + path +'/images/uploads/avatars/'+ pro_img +'" class="img-fluid t-user card-img" alt="Profile Img">' +
                                '</div><span class="sl-name">' + datavalue.salesman + '</span></td><td>'+ datavalue.sales_count +'</td></tr>';

                $("#ldr_tbl").append(slm_data_html);    
            }

        });

    }

    else{
        slm_data_html = '<tr><td colspan="3"><div class="mem-1"><div class="tx-center">Nothing to be displayed</div></div></td></tr>';
        $("#ldr_tbl").html(slm_data_html);
    }

    if (team_result.length != 0) {
        $.each(team_result, function (datakey, datavalue) {
            
            if (datavalue.team_deal_count != ''){
                
                team_data_html = '<tr><td>'+ (datakey + 1) +'</td><td>' + datavalue.team + 
                                 '</td><td>'+ datavalue.team_deal_count +'</td></tr>';

                $("#team_ldr_tbl").append(team_data_html);    
            }

        });

    }

    else{
        team_data_html = '<tr><td colspan="3"><div class="mem-1"><div class="tx-center">Nothing to be displayed</div></div></td></tr>';
        $("#team_ldr_tbl").html(team_data_html);
    }

}

function datatable_reset(table_id) {
    
    if(table_id == 1){
    	$('#datatable1').DataTable({
	        responsive: true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'csv', className: 'csv-salesman', title: 'Autoprotect USA Sales Agent Info' },
                { extend: 'excel', className: 'excel-salesman', title: 'Autoprotect USA Sales Agent Info'}
            ],
	        "order": [[1, "desc"]],
	        "columnDefs" : [{targets:5, type:"num-html"},{targets:6, type:"num-html"}],
	        language: {
	            searchPlaceholder: 'Search...',
	            sSearch: '',
	            lengthMenu: '_MENU_ items/page',
	        }
    	});
    }
    else if(table_id == '1m'){
        $('#datatable1m').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'csv', className: 'csv-manager', title: 'Autoprotect USA Manager Info'},
                { extend: 'excel', className: 'excel-manager', title: 'Autoprotect USA Manager Info'}
            ],
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

function table_data_insertion(data_value, type, pif_check) {
    
    var downpayment,finterm,discount,rec_class_name,calls,conv_rate;
    // console.log(downpay_add);

    if(pif_check == true){
        if(data_value.sales_count != 0){
            downpayment = ((data_value.downpay_add / data_value.cuscost_add) * 100).toFixed(2) + '%';
            finterm = (data_value.finterm_add / data_value.sales_count).toFixed(2);
            discount = data_value.retail_add - data_value.cuscost_add;
            if(discount < 0)
                discount = 0;
            discount = '$' + Math.round(discount / data_value.sales_count);
        }
        else{
            downpayment = 'N/A';
            finterm = 'N/A';
            discount = 'N/A';
        }
    }

    else{
        downpayment = ((data_value.pifs_downpay_add / data_value.pifs_cuscost_add) * 100).toFixed(2) + '%';
        finterm = (data_value.pifs_finterm_add / data_value.pifs_sales_count).toFixed(2);
        discount = data_value.retail_add - data_value.cuscost_add;
        if(discount < 0)
            discount = 0;
        discount = '$' + Math.round(discount / data_value.sales_count);
    }
    
    
    if(data_value.total_calls !== undefined  && data_value.total_calls != 0){

        calls = data_value.total_calls;
        conv_rate = ((data_value.sales_count/data_value.total_calls) * 100).toFixed(2);
        if(conv_rate < 7)
            rec_class_name = 'lower';
        else if(conv_rate > 8)
            rec_class_name = 'higher';
        else
            rec_class_name = 'normal';

        conv_rate = conv_rate + '%';
    }
    else if(data_value.total_calls == 0){
        calls = data_value.total_calls;
        conv_rate = 'N/A';
        rec_class_name = 'normal';
    }
    else{
        calls = 'N/A';
        conv_rate = 'N/A';
        rec_class_name = 'normal';
    }


    data = '';

    data += '<tr class=' + rec_class_name + '>' +
        '<td>'+ (type == 'sales_man' ? '<a class="sm_name" href="javascript:void(0);">' + data_value.salesman + '</a>' : data_value.manager) + '</td>' +
        '<td>' + data_value.sales_count + '</td>' +
        '<td>' + downpayment + '</td>' +
        '<td>' + finterm + '</td>' +
        '<td>' + discount + '</td>' +
        '<td>' + calls + '</td>' +
        '<td>' + conv_rate + '</td>' +
        '</tr>';
    return data;
}

function deal_calendar(res, cpage) {
    var data_call = [];
    var data_lead = [];
    var data_cancel = [];
    var result;
    // console.log(1);
    result = JSON.parse($("<div/>").html(res).text());

    // console.log(result);
    $.each(result, function (dkey, dvalue) {
        if(dvalue.hasOwnProperty('call_date')){
            data_call[dvalue.call_date] = dvalue.total_calls;
        }
        else if(dvalue.hasOwnProperty('CanDate')){
            data_cancel[dvalue.CanDate] = dvalue.cancel_count;
        }
        else{
            data_lead[dvalue.purchdate] = dvalue.sales_count;
        }
        
    });

    var cal_date = get_calendar_date();
    place_lead_count(data_call, data_lead, data_cancel, cpage, cal_date);
}

function place_lead_count(data_call, data_lead, data_cancel, cpage, date) {
    //console.log(data_arr);
    var keys_call = Object.keys(data_call);
    var keys_lead = Object.keys(data_lead);
    var keys_cancel = Object.keys(data_cancel);
    //console.log(keys);
    var call_cnt,cancel_cnt;
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
                    else if(keys_cancel.indexOf(cal_date) != -1){
                        keys = keys_cancel;
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

                                if(data_cancel[key_date] != undefined)
                                    cancel_cnt = data_cancel[key_date];
                                else
                                    cancel_cnt = 'N/A';

                            }
                        });

                        html = "<div class='fc-lead fc-data'><a style='cursor:pointer'>Sales: <strong>" + lead_cnt + "</strong></a></div>" +
                               (cpage != 'dashboard-active' ? "<div class='fc-cancel fc-data'><a style='cursor:pointer'>Cancel: <strong>" + cancel_cnt + "</strong></a></div>" : "" )  +
                               "<div class='fc-call fc-data'><a style='cursor:pointer'>Calls: <strong>" + call_cnt + "</strong></a></div>" +
                               "<div class='fc-cvr fc-data'><a style='cursor:pointer'>Closing %: <strong>" + (call_cnt != 'N/A' ? ((lead_cnt/call_cnt) * 100).toFixed(2) + '%' : 'N/A') + "</strong></a></div>";

                        $(this).append(html);
                        
                    }
                    else{
                        html = "<div class='fc-lead fc-data'><a style='cursor:pointer'>Sales: <strong>0</strong></a></div>" +
                               (cpage != 'dashboard-active' ? "<div class='fc-cancel fc-data'><a style='cursor:pointer'>Cancel: <strong>0</strong></a></div>" : "" ) +
                               "<div class='fc-call fc-data'><a style='cursor:pointer'>Calls: <strong>0</strong></a></div>" +
                               "<div class='fc-cvr fc-data'><a style='cursor:pointer'>Closing %: <strong>0%</strong></a></div>";
                        
                        $(this).append(html);
                    }

                }
                else {
                    lead_cnt = data_lead[window.today];
                    call_cnt = data_call[window.today];
                    cancel_cnt = data_cancel[window.today];
                    if (lead_cnt == undefined)
                        lead_cnt = 0;

                    if (call_cnt == undefined)
                        call_cnt = 0;

                    if (cancel_cnt == undefined)
                        cancel_cnt = 0;

                    html = "<div class='fc-lead fc-data'><a style='cursor:pointer'>Sales: <strong>" + lead_cnt + "</strong></a></div>" +
                           (cpage != 'dashboard-active' ? "<div class='fc-cancel fc-data'><a style='cursor:pointer'>Cancel: <strong>" + cancel_cnt + "</strong></a></div>" : "" ) +
                           "<div class='fc-call fc-data'><a style='cursor:pointer'>Calls: <strong>" + call_cnt + "</strong></a></div>" +
                           "<div class='fc-cvr fc-data'><a style='cursor:pointer'>Closing %: <strong>" + (call_cnt != 0 ? ((lead_cnt/call_cnt) * 100).toFixed(2) + '%' : 'N/A') + "</strong></a></div>";


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
                else if(keys_cancel.indexOf(cal_date) != -1){
                    keys = keys_cancel;
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

                            if(data_cancel[key_date] != undefined)
                                cancel_cnt = data_cancel[key_date];
                            else
                                cancel_cnt = 'N/A';

                        }
                    });

                    html = "<div class='fc-lead fc-data'><a style='cursor:pointer'>Sales: <strong>" + lead_cnt + "</strong></a></div>" +
                           (cpage != 'dashboard-active' ? "<div class='fc-cancel fc-data'><a style='cursor:pointer'>Cancel: <strong>" + cancel_cnt + "</strong></a></div>" : "" ) +
                           "<div class='fc-call fc-data'><a style='cursor:pointer'>Calls: <strong>" + call_cnt + "</strong></a></div>" +
                           "<div class='fc-cvr fc-data'><a style='cursor:pointer'>Closing %: <strong>" + ((lead_cnt/call_cnt) * 100).toFixed(2) + "%</strong></a></div>";

                    $(this).append(html);
                }

                else{

                    html =  "<div class='fc-lead fc-data'><a style='cursor:pointer'>Sales: <strong>0</strong></a></div>" +
                            (cpage != 'dashboard-active' ? "<div class='fc-cancel fc-data'><a style='cursor:pointer'>Cancel: <strong>0</strong></a></div>" : "" ) +
                            "<div class='fc-call fc-data'><a style='cursor:pointer'>Calls: <strong>0</strong></a></div>" +
                            "<div class='fc-cvr fc-data'><a style='cursor:pointer'>Closing %: <strong>0%</strong></a></div>";
                        
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

    var tab_type = $('.active-opt').data('value');
    if($(this).hasClass('caledar-btn')){
        $('.calen').fadeOut('fast');
        if(tab_type == 'sales_man')
            $('.sm-table').fadeIn('slow');
        else
            $('.mng-table').fadeIn('slow');
        $(this).children('span').html('Calendar');
    }
    else{
        $('.calen').fadeIn('slow');
        if(tab_type == 'sales_man')
            $('.sm-table').fadeOut('fast');
        else
            $('.mng-table').fadeOut('fast');
        $(this).children('span').html('Sales Table');
    }
    $(this).toggleClass('caledar-btn');
});


function tble_oppprt_info(res_data, search_flag)
{

    if ($.fn.DataTable.isDataTable("#datatable3")){
        $('#datatable3').DataTable().clear().destroy();
        $("#lead_data_oppprt").empty();
    }

    
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

        	hour = Math.trunc(arrData.call_time / 3600) ;
        	min = Math.trunc((arrData.call_time % 3600) / 60);
        	sec = arrData.call_time % 60 ;

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
            '<td>' + (arrData.filename != undefined ? '<a href="' + arrData.location + '" target="_blank">' + arrData.filename + '</a>' : 'N/A') + '</td>' +
            '</tr>';
             
        });

    
        $("#lead_data_oppprt").html(data);
    }
    else {
        $("#lead_data_oppprt").html('');
    }

    datatable_reset(table_id=3);
}

function tble_cancel_info(result_data, search_flag, pifs_check){

    if ($.fn.DataTable.isDataTable("#datatable4"))
        $('#datatable4').DataTable().clear().destroy();

    
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


            if((pifs_check == false && arrData.finterm != 0) ||  pifs_check == true){

                data += '<tr class="cancel_rec">' +
                '<td>' + arrData.appNumber + '</td>' +
                '<td>' + arrData.custFirstName + ' '+ arrData.custLastName + '</td>' +
                '<td>$' + Math.round(arrData.downpay) + '</td>' +
                '<td>' + arrData.finterm  + '</td>' +
                // '<td>$' + Math.round(discount) + '</td>' +
                '<td>' + type + '</td>' +
                '<td>' + arrData.purchdate + '</td>' +
                '<td>' + (arrData.filename != undefined ? '<a href="' + arrData.location + '" target="_blank">' + arrData.filename + '</a>' : 'N/A') + '</td>' +
                '</tr>';
            }

        });

    
        $("#cancel_data").html(data);
    }
    else {
        $("#cancel_data").html('');
    }

    datatable_reset(table_id=4);
}


function set_custom_sales_board(custom_data, cpage, pifs_check){


    var base_data = JSON.parse($("<div/>").html(custom_data['custom_sales_details']).text());
    $('.adv_time_span').children('.top-card').html('');

    var cuscost = ((base_data[0].cuscost != undefined && base_data[0].cuscost != null) ? base_data[0].cuscost : 0);
    var retail = ((base_data[0].retail != undefined && base_data[0].retail != null) ? base_data[0].retail : 0);
    var finterm = ((base_data[0].retail != undefined && base_data[0].retail != null) ? base_data[0].finterm : 0.00) ;

    if(custom_data['custom_text'].indexOf('Result') == -1)
        custom_data['custom_text'] = custom_data['custom_text'] +' Sales';

    // console.log(custom_total_calls);

    if(custom_data['custom_sales_count'] != 0){
        discount = Math.round((retail - cuscost)/custom_data['custom_sales_count']);
        if(pifs_check == true)
            finterm = (finterm /custom_data['custom_sales_count']).toFixed(2);
        else
            finterm = (finterm /(custom_data['custom_sales_count'] - custom_data['custom_pifs_count'])).toFixed(2);
    }
    else{

        finterm = 0.00;
        discount = 0;
    }

    conv_rate = ((custom_data['custom_total_calls'] != 0 && custom_data['custom_total_calls'] != undefined ) ? ((custom_data['custom_sales_count'] / custom_data['custom_total_calls']) * 100).toFixed(2) + '%' : 'N/A');

    var html = '<div class="top-part"><h6>'+ custom_data['custom_text'] +'</h6><h3>'+ custom_data['custom_sales_count'] +
                '<span class="hdls"> deals</span></h3>'+(pifs_check == true ? '<p class="pifs-cnt">'+ custom_data['custom_pifs_count'] +'<span class="pifs"> PIFs</span>' + 
                '</p>' : '' )+ (cpage != 'dashboard-active' ? '<p class="can-cnt">'+ custom_data['custom_cancel_count'] +'<span class="can_sale"> Cancel deals</span></p>' : '' ) + 
                '</div><div class="crd-tab"><div class="d-flex"><div class="ctab-bx"><p>Calls</p><p class="text-black">'+ custom_data['custom_total_calls'] +
                '</p></div><div class="ctab-bx bl-1"><p>Closing %</p><p class="text-black">' + conv_rate + '</p></div></div>'+
                '<div class="d-flex bt-1"><div class="ctab-bx"><p>Finance term</p><p class="text-black">' + finterm + '</p></div>' +
                '<div class="ctab-bx bl-1"><p>Discount</p><p class="text-black">$' + discount + '</p></div></div></div>';

    $('.adv_time_span').children('.top-card').html(html);

    $('.adv_time_span').children('.top-card').addClass('active');

}

$(document).on('click','.export-btn',function(){
    var export_format = $("#exprt_frmt").val();
    var tab_type = $(".active-opt").data('value');
    if(export_format == 'xlsx' && tab_type == 'manager')
        $(".excel-manager").click();
    else if(export_format == 'csv' && tab_type == 'manager')
        $(".csv-manager").click();
    else if(export_format == 'xlsx' && tab_type == 'sales_man')
        $(".excel-salesman").click();
    else
        $(".csv-salesman").click();
});
