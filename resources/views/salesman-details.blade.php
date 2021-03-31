@include('common/header')
@include('common/navbar')

<div class="slim-mainpanel">
      <div class="container pd-t-50">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="tx-inverse mg-b-15">Salesman : {{$result['sm_name']}}!</h3>
            <p class="mg-b-40">Please see the reports given below :</p>

            

            <div class="adv_srch">
                <div class="frm-box">
                       <form action="" method="post">
                      <!--  Details -->
                      @csrf
                      <div class="form-group">
                      <h6 class="slim-card-title mg-b-15">Lead Summary</h6>
                          <div class="controls f1">
                             <label for="arrive" class="label-date"><i class="fa fa-calendar"></i>&nbsp;&nbsp;From:</label>
                            <input type="date" name="start_date" placeholder="Select date" class="adv_date" data-polyfill="all" required>
                          </div>      
                          <div class="controls">
                             <label for="depart" class="label-date"><i class="fa fa-calendar"></i>&nbsp;&nbsp;To:</label>
                            <input type="date" name="end_date" placeholder="Select date" class="adv_date" data-polyfill="all" required>
        
                          </div>      
                        <div class="btn">
                           <input type="submit" value="SUBMIT" class="btn-frm" name="">
                        </div>
                      </div>
                    </form>

                    <P class="dtxt" style="display: none;">Total sales made by <span>{{$result['sm_name']}}</sapn> from <span class='drng'>'{{ $result['start_date'] }}'</span> to <span class='drng'>'{{ $result['end_date'] }}' </span> is <span class='drng'>{{ $result['adv_range_sales_count'] }}</span></p>

                   </div>

            </div>


            <div class="row no-gutters">
              <div class="col-sm-4 span" id="daily" style="cursor:pointer;">
                <div class="card card-earning-summary active top-card">
                  <div class="top-part">
                    <h6>Today's Lead</h6>
                    <h1>{{$result['todaycount']}}</h1>
                    {{$result['dailydata'][2]}}
                    <span class="valign-middle"><span class="@if($result['dailydata'][0]=='pos')tx-success @else tx-danger @endif">
                    <i class="icon @if($result['dailydata'][0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i>{{$result['dailydata'][1]}}%</span><br/>from last Day</span>
                  </div>
                  <div class="crd-tab">
                    <div class="d-flex">
                      <div class="ctab-bx">
                        <p>Calls</p>
                        <p class="text-black">
                           {{ $result['today_details'][0]['total_calls'] }}
                        </p>
                      </div>
                      <div class="ctab-bx bl-1">
                        <p>Conversion</p>
                        <p class="text-black">
                          @if($result['today_details'][0]['total_calls'] == 0) 
                            N/A
                          @else
                            {{ number_format(($result['todaycount']/$result['today_details'][0]['total_calls'])*100,2)}} %
                          @endif
                          
                        </p>
                      </div>
                    </div>
                    <div class="d-flex bt-1">
                      <div class="ctab-bx">
                        <p>Finance term</p>
                        <p class="text-black">
                          @if($result['todaycount'] == 0) 
                            0.00
                          @else
                            {{ number_format($result['today_details'][0]['finterm_add']/$result['todaycount'],2) }}
                          @endif
                        </p>
                      </div>
                      <div class="ctab-bx bl-1">
                        <p>Discount</p>
                        <p class="text-black">
                          @if($result['todaycount'] == 0) 
                            $0
                          @else
                          ${{ round(($result['today_details'][0]['retail_add']-$result['today_details'][0]['cuscost_add'])/$result['todaycount']) }}
                          @endif
                        </p>
                      </div>
                    </div>
                  </div>
                </div><!-- card -->
              </div><!-- col-6 -->
              <div class="col-sm-4 weekly span" id="weekly" style="cursor:pointer;">
                <div class="card card-earning-summary mg-sm-l--1 bd-t-0 bd-sm-t top-card">
                  <div class="top-part">
                    <h6>This Week's Lead</h6>
                    <h1>{{$result['weeklycount']}}</h1>
                    {{$result['weeklydata'][2]}}
                    <span class="valign-middle"><span class="@if($result['weeklydata'][0]=='pos')tx-success @else tx-danger @endif">
                    <i class="icon @if($result['weeklydata'][0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i> {{$result['weeklydata'][1]}}%</span><br/>from last week</span>
                  </div>
                  <div class="crd-tab">
                    <div class="d-flex">
                      <div class="ctab-bx">
                        <p>Calls</p>
                        <p class="text-black">
                           {{ $result['weekly_details'][0]['total_calls'] }}
                        </p>
                      </div>
                      <div class="ctab-bx bl-1">
                        <p>Conversion</p>
                        <p class="text-black">
                          @if($result['weekly_details'][0]['total_calls'] == 0) 
                            N/A
                          @else
                            {{ number_format(($result['weeklycount']/$result['weekly_details'][0]['total_calls'])*100,2)}} %
                          @endif
                          
                        </p>
                      </div>
                    </div>
                    <div class="d-flex bt-1">
                      <div class="ctab-bx">
                        <p>Finance term</p>
                        <p class="text-black">
                          @if($result['weeklycount'] == 0) 
                            0.00
                          @else
                            {{ number_format($result['weekly_details'][0]['finterm_add']/$result['weeklycount'],2) }}
                          @endif
                        </p>
                      </div>
                      <div class="ctab-bx bl-1">
                        <p>Discount</p>
                        <p class="text-black">
                          @if($result['weeklycount'] == 0) 
                            $0
                          @else
                          ${{ round(($result['weekly_details'][0]['retail_add']-$result['weekly_details'][0]['cuscost_add'])/$result['weeklycount']) }}
                          @endif
                        </p>
                      </div>
                    </div>
                  </div>
                </div><!-- card -->
              </div><!-- col-6 -->
              <div class="col-sm-4 span" id="monthly" style="cursor:pointer;">
                <div class="card card-earning-summary mg-sm-l--1 bd-t-0 bd-sm-t top-card">
                  <div class="top-part">
                    <h6>This Month's Lead</h6>
                    <h1>{{$result['monthlycount']}}</h1>
                    {{$result['monthlydata'][2]}}
                    <span class="valign-middle"><span class="@if($result['monthlydata'][0]=='pos')tx-success @else tx-danger @endif">
                    <i class="icon @if($result['monthlydata'][0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i>{{$result['monthlydata'][1]}}%</span><br/>from last Month</span>
                  </div>
                  <div class="crd-tab">
                    <div class="d-flex">
                      <div class="ctab-bx">
                        <p>Calls</p>
                        <p class="text-black">
                           {{ $result['monthly_details'][0]['total_calls'] }}
                        </p>
                      </div>
                      <div class="ctab-bx bl-1">
                        <p>Conversion</p>
                        <p class="text-black">
                          @if($result['monthly_details'][0]['total_calls'] == 0) 
                            N/A
                          @else
                            {{ number_format(($result['monthlycount']/$result['monthly_details'][0]['total_calls'])*100,2)}} %
                          @endif
                          
                        </p>
                      </div>
                    </div>
                    <div class="d-flex bt-1">
                      <div class="ctab-bx">
                        <p>Finance term</p>
                        <p class="text-black">
                          @if($result['monthlycount'] == 0) 
                            0.00
                          @else
                            {{ number_format($result['monthly_details'][0]['finterm_add']/$result['monthlycount'],2) }}
                          @endif
                        </p>
                      </div>
                      <div class="ctab-bx bl-1">
                        <p>Discount</p>
                        <p class="text-black">
                          @if($result['monthlycount'] == 0) 
                            $0
                          @else
                          ${{ round(($result['monthly_details'][0]['retail_add']-$result['monthly_details'][0]['cuscost_add'])/$result['monthlycount']) }}
                          @endif
                        </p>
                      </div>
                    </div>
                  </div>
                </div><!-- card -->
              </div><!-- col-6 -->
            </div><!-- row -->
          </div><!-- col-6 -->
         
        </div><!-- row -->


        <div class="card pd-25 calen" style="display:none;">
          <div id="fullCalendar"></div>
        </div><!-- card -->

        <br>


        <div class="section-opts" style="display: block;">
          <div class="opts">
            <span class="active-opt opt">Sales</span>
            <span class="opt">Opportunities</span>
          </div>
        </div>
        
        <div class="section-wrapper lead_data_info">
          
            <label class="section-title">Sales Info for: <span class="fill_dt"></span></label>

            <div class="table-responsive table-wrapper dash_table">

              <table id="datatable2" class="table mg-b-0 table display responsive nowrap">
                  <thead>
                  <tr>
                      <th>APP NUMBER</th>
                      <th>NAME</th>
                      <th>DOWN PAYMENT</th>
                      <th>FINANCE TERM</th>
                      <th>DISCOUNT</th>
                      <th>Type</th>
                      <th>Purchase At</th>
                      <th>Recording</th>
                  </tr>
                  </thead>
                  <tbody id="lead_data">
                      
                  </tbody>
              </table>

            </div><!-- table-responsive -->

        </div>

        <div class="section-wrapper opprt_info" style="display: none;">
          
              <label class="section-title">Opportunities Info for: <span class="fill_dt"></span></label>
              <!--<p class="mg-b-20 mg-sm-b-40"></p>-->

              <div class="table-responsive table-wrapper dash_table">
                <table id="datatable3" class="table mg-b-0 table display responsive nowrap" style="max-width: 100% !important;">
                    <thead>
                    <tr>
                        <th>LEAD ID</th>
                        <th>NAME</th>
                        <th>PHONE </th>
                        <th>EMAIL</th>
                        <th>CALL TIME</th>
                        <th>Recording</th>
                    </tr>
                    </thead>
                    <tbody id="lead_data_oppprt">
                        
                    </tbody>
                </table>
              </div><!-- table-responsive -->

          </div>

      </div><!-- container -->
    </div><!-- slim-mainpanel -->

    @include('common/footer')

    <script>
     
      var name = ("{{$result['sm_name']}}").replace(' ','-');
      var src_url = "{{ route('salesman-details',':name') }}";
      src_url = src_url.replace(':name',name);
      
      

      $(document).ready(function(){

        datatable_reset();

        if("{{ $result['adv_range_flag'] }}" == true){
            //$('.dtxt').show();
            $('.span').children('div').removeClass('active');
            $('.no-gutters').hide();
            tble_lead_info("{{ $result['lead_info_details'] }}", search_flag=true);
            tble_oppprt_info("{{ $result['adv_range_oppurtunites'] }}",search_flag=true);
            $('.span').children('div').removeClass('active');
            $(".fc-add_event-button").click();
            $(".fill_dt").text("{{$result['start_date']}}" + '-' + "{{$result['end_date']}}");
        }
        else{
          $(".fill_dt").text('Today');
          tble_lead_info("{{ $result['today_sales_data'] }}", search_flag=true);
          tble_oppprt_info("{{ $result['today_oppurtunites'] }}", search_flag=true);

         }
        
        var cal_data = "{{ $result['calendar_data'] }}";
        deal_calendar(cal_data);
        

        $('.span').on('click',function(){
          var sl_data,time_span;
          $('.section-wrapper').hide();
          $('.span').children('div').removeClass('active');
            $(this).children('div').addClass('active');
            // datatable_place();
            time_span = $(this).attr('id');
            if(time_span == 'monthly'){
              tble_lead_info("{{ $result['monthly_sales_data'] }}", search_flag=true);
              tble_oppprt_info("{{ $result['monthly_oppurtunites'] }}", search_flag=true);
              $(".fill_dt").html('THIS MONTH');
            }
            else if(time_span == 'weekly'){
              tble_lead_info("{{ $result['weekly_sales_data'] }}", search_flag=true);
              tble_oppprt_info("{{ $result['weekly_oppurtunites'] }}", search_flag=true);
              $(".fill_dt").html('THIS WEEK');
            }
            else{
              tble_lead_info("{{ $result['today_sales_data'] }}", search_flag=true);
              tble_oppprt_info("{{ $result['today_oppurtunites'] }}", search_flag=true);
              $(".fill_dt").html('TODAY');
            }

            setTimeout(function(){
              click_tab();
            },100);
            
            $("html,body").animate({scrollTop : $(".opts").offset().top },2000);
            
        });


        $('.opt').click(function(){
          
          $('.opt').removeClass('active-opt');
          $(this).addClass('active-opt');

          if($(this).text() == 'Sales'){
            $(".opprt_info").fadeOut();
            
            setTimeout(function(){
              $(".lead_data_info").fadeIn();
            },200);
            
          }
          else{
            $(".lead_data_info").fadeOut();
            setTimeout(function(){
              $(".opprt_info").fadeIn();
            },200);
            
          }


          
        });

        
      });

      

    $(document).on('click','.fc-prev-button,.fc-next-button',function () {

        var cal_date = get_calendar_date();

        $.ajax({
            url : window.src_url,
            method: 'post',
            data : {
                month : cal_date['month'],
                year : cal_date['year'],
                _token : "{{ csrf_token() }}"
            },
            success: function(result){
                //console.log(result);
                deal_calendar(result);
            },
            error: function(err){
                console.log(err);
            }

        });
    });

  $(document).on('click', '.fc-day-top', function(){
    //console.log("1");
    $('.section-wrapper').hide();
    click_date = $(this).data("date");
    let day = $(this).children('.fc-day-number').text(); 
    let monthYear = $(".fc-center").children('h2').text();
    $(".fill_dt").text(day + ' ' + monthYear );

    sales_info_salesman(click_date);
    $('.span').children('div').removeClass('active');

  });



function sales_info_salesman(click_date)
{
  
  $.ajax({
      url : window.src_url,
      method: 'post',
      data : {
           leadDate: click_date,
           _token : "{{ csrf_token() }}"
          
      },
      success: function(result){

            cal_date_data = JSON.parse(result);
            // console.log(cal_date_data.lead_info);
            tble_lead_info(cal_date_data.lead_info,search_flag=false);
            tble_oppprt_info(cal_date_data.opprt_info,search_flag=false);
            setTimeout(function(){
              click_tab();
              $("html,body").animate({scrollTop : $(".opts").offset().top },2000);
            },100);

      },
      error: function(err){
          console.log(err);
      }

  });
}

function click_tab()
{
  $('.opt').each(function(){
    if($(this).hasClass('active-opt'))
      $(this).click();
  });
}

</script>
