@include('common/header')

<div class="slim-mainpanel">
      <div class="container pd-t-50">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="tx-inverse tp-h mg-b-15">Welcome back, {{Auth::user()->name}}!</h3>
            
            <div class="m-lead">
            <h5 class="m-lead-hd"><span class="name_topper">Today</span>'s Leaderboard</h5>
            <div class="row">
              <div class="col-md-8">
                <h6>Salemen Report</h6>
                <div class="l-board">
                  <div class="top-left-tab table-responsive">
                      <table class="table table-dark table-hover table-striped">
                        <thead>
                          <tr>
                            <th>Rank</th>
                            <th>Sales Man</th>
                            <th>Lead count</th>
                          </tr>
                        </thead>
                        <tbody id="ldr_tbl">
                  @if($result['today_top'] != '[]')
                    @foreach(json_decode($result['today_top'],true) as $key=>$value)
                      @if($value['sales_count'] != 0)
                        <tr>
                          <td>{{ $key + 1 }}</td>
                          <td>
                            <div class="@if($key <= 2)top-slm @endif">
                            <img src="{{asset('images/profile.png')}}" class="img-fluid t-user card-img" alt="Profile Img">
                            </div>
                            <span class="sl-name"> {{$value['salesman']}} </span>
                          </td>
                          <td>{{$value['sales_count']}}</td>
                        </tr>
                      @endif
                    @endforeach
                  @else
                  <tr>
                    <td colspan="3">
                      <div class="mem-1">
                        <div class="tx-center">
                          Nothing to be displayed
                        </div>
                      </div>
                    </td>
                  </tr>
                  @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
              </div>
              <div class="col-md-4">
                <h6> Team Report</h6>
                <div class="l-board">
                  <div class="top-left-tab table-responsive">
                      <table class="table table-dark table-hover table-striped">
                        <thead>
                          <tr>
                            <th>Rank</th>
                            <th>Team</th>
                            <th>Lead count</th>
                          </tr>
                        </thead>
                        <tbody id="team_ldr_tbl">
                  @if($result['today_top_team'] != '[]')

                    @foreach($result['today_top_team'] as $key=>$value)
                        <tr>
                          <td>{{ $key + 1 }}</td>
                          <td>{{$value->team}}</td>
                          <td>{{$value->team_deal_count}}</td>
                        </tr>
                    @endforeach
                  @else
                  <tr>
                    <td colspan="3">
                      <div class="mem-1">
                        <div class="tx-center">
                          Nothing to be displayed
                        </div>
                      </div>
                    </td>
                  </tr>
                  @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
              </div>
            </div>
            
            
            </div>

            <div class="adv_srch">
                <div class="frm-box">
                       <form action="" method="post">
                      <!--  Details -->
                      @csrf
                      <div class="form-group mb-cstm">
                      <h6 class="slim-card-title">Your Sales Summary</h6>
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
                        <div class="s-time">
                        <label for="">/ Select Day:</label>
                         <select class="test sl-date" id="changedays"> 
                            <option value="">Select</option>
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="weekly">Week-to-date</option>
                            <option value="last_week">Last Week</option>
                            <option value="monthly">Month-To-Date</option>
                            <option value="last_month">Last Month</option>
                          </select>
                        </div>
                      </div>
                    </form>
                    @if(Auth::user()->type == 'admin')
                    <div>
                      <select class="slty" id="sales_type">
                        <option value="gross" @if(Route::currentRouteName() == 'dashboard' )selected @endif>Gross Sales</option>
                        <option value="net" @if(Route::currentRouteName() == 'dashboard-active' )selected @endif>Net Sales</option>
                      </select>
                    </div>
                    @endif
                    <P class="dtxt" style="display: none;">Showing Data from <span class='drng'>'{{ @$result['start_date'] }}'</span> to <span class='drng'>'{{ @$result['end_date'] }}'</span></p>

                    <div class="pif-input">
                      <input type="checkbox" name="pifs" id="pifs_checkbox" checked="checked">PIFs
                    </div>

                   </div>
            </div>


            <div class="no-gutters">
              <div class="top-smry">
                <div class="row no-gutters">
                  <div class="col-sm-4 span" id="daily" style="cursor:pointer;">
                    <div class="card card-earning-summary active top-card">
                      <div class="top-part">
                        <h6>Today's Sales</h6>
                        <h3>{{ $result['todaycount'] }} <span class="hdls"> deals</span></h3> 
                        <p class="pifs-cnt">{{ $result['today_pifs_count'] }} <span class="pifs"> PIFs</span></p>
                        @if(Route::currentRouteName() == 'dashboard' && Auth::user()->type == 'admin')
                        <p class="can-cnt">{{ @$result['today_cancel_count'] }} <span class="can_sale"> Cancel deals</span></p> 
                        @endif 
                        <!-- {{$result['dailydata'][2]}}
                        <span class="valign-middle"><span class="@if($result['dailydata'][0]=='pos')tx-success @else tx-danger @endif">
                        <i class="icon @if($result['dailydata'][0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i>{{$result['dailydata'][1]}}%</span><br/>from last Day</span> -->
                      </div>
                      <div class="crd-tab">
                        <div class="d-flex">
                          <div class="ctab-bx">
                            <p>Calls</p>
                            <p class="text-black">{{ $result['today_total_calls'] }}</p>
                          </div>
                          <div class="ctab-bx bl-1">
                            <p>Closing %</p>
                            <p class="text-black">
                              @if($result['today_total_calls'] == 0) 
                                0
                              @else
                                {{ number_format(($result['todaycount']/$result['today_total_calls'])*100,2)}}
                              @endif
                              %
                            </p>
                          </div>
                        </div>
                        <div class="d-flex bt-1">
                          <div class="ctab-bx">
                            <p>Finance term</p>
                            <p class="text-black today-finterm">
                              @if($result['todaycount'] == 0) 
                                0.00
                              @else
                                {{ number_format($result['today_base_details'][0]['finterm']/$result['todaycount'],2) }}
                              @endif
                            </p>
                          </div>
                          <div class="ctab-bx bl-1">
                            <p>Discount</p>
                            <p class="text-black">
                              @if($result['todaycount'] == 0) 
                                0.00
                              @else
                              ${{ round(($result['today_base_details'][0]['retail']-$result['today_base_details'][0]['cuscost'])/$result['todaycount']) }}
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
                        <h6>This Week's Sales</h6>
                        <h3>{{ $result['weeklycount'] }} <span class="hdls"> deals</span></h3> 
                        <p class="pifs-cnt">{{ $result['weekly_pifs_count'] }} <span class="pifs"> PIFs</span></p>
                        @if(Route::currentRouteName() == 'dashboard' && Auth::user()->type == 'admin')
                        <p class="can-cnt">{{ @$result['weekly_cancel_count'] }} <span class="can_sale"> Cancel deals</span></p>
                        @endif
                        <!-- {{$result['weeklydata'][2]}}
                        <span class="valign-middle"><span class="@if($result['weeklydata'][0]=='pos')tx-success @else tx-danger @endif">
                        <i class="icon @if($result['weeklydata'][0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i> {{$result['weeklydata'][1]}}%</span><br/>from last week</span> -->
                      </div>
                      <div class="crd-tab">
                        <div class="d-flex">
                          <div class="ctab-bx">
                            <p>Calls</p>
                            <p class="text-black">{{ $result['weekly_total_calls'] }}</p>
                          </div>
                          <div class="ctab-bx bl-1">
                            <p>Closing %</p>
                            <p class="text-black">
                              @if($result['weekly_total_calls'] == 0) 
                                0
                              @else
                                {{ number_format(($result['weeklycount']/$result['weekly_total_calls'])*100,2)}}
                              @endif
                              %
                            </p>
                          </div>
                        </div>
                        <div class="d-flex bt-1">
                          <div class="ctab-bx">
                            <p>Finance term</p>
                            <p class="text-black week-finterm">
                              @if($result['weeklycount'] == 0) 
                                0.00
                              @else
                                {{ number_format($result['weekly_base_details'][0]['finterm']/$result['weeklycount'],2) }}
                              @endif
                            </p>
                          </div>
                          <div class="ctab-bx bl-1">
                            <p>Discount</p>
                            <p class="text-black">
                              @if($result['weeklycount'] == 0) 
                                0.00
                              @else
                              ${{ round(($result['weekly_base_details'][0]['retail']-$result['weekly_base_details'][0]['cuscost'])/$result['weeklycount']) }}
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
                        <h6>This Month's Sales</h6>
                        <h3>{{ $result['monthlycount'] }} <span class="hdls"> deals</span></h3> 
                        <p class="pifs-cnt">{{ $result['monthly_pifs_count'] }} <span class="pifs"> PIFs</span></p>
                        @if(Route::currentRouteName() == 'dashboard' && Auth::user()->type == 'admin') 
                        <p class="can-cnt">{{ @$result['monthly_cancel_count'] }} <span class="can_sale"> Cancel deals</span></p>
                        @endif
                        <!-- {{$result['monthlydata'][2]}}
                        <span class="valign-middle"><span class="@if($result['monthlydata'][0]=='pos')tx-success @else tx-danger @endif">
                        <i class="icon @if($result['monthlydata'][0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i>{{$result['monthlydata'][1]}}%</span><br/>from last Month</span> -->
                      </div>
                      <div class="crd-tab">
                        <div class="d-flex">
                          <div class="ctab-bx">
                            <p>Calls</p>
                            <p class="text-black">{{ $result['monthly_total_calls'] }}</p>
                          </div>
                          <div class="ctab-bx bl-1">
                            <p>Closing %</p>
                            <p class="text-black">
                              @if($result['monthly_total_calls'] == 0) 
                                0
                              @else
                                {{ number_format(($result['monthlycount']/$result['monthly_total_calls'])*100,2)}}
                              @endif
                              %
                            </p>
                          </div>
                        </div>
                        <div class="d-flex bt-1">
                          <div class="ctab-bx">
                            <p>Finance term</p>
                            <p class="text-black month-finterm">
                              @if($result['monthlycount'] == 0) 
                                0.00
                              @else
                                {{ number_format($result['monthly_base_details'][0]['finterm']/$result['monthlycount'],2) }}
                              @endif
                            </p>
                          </div>
                          <div class="ctab-bx bl-1">
                            <p>Discount</p>
                            <p class="text-black">
                              @if($result['monthlycount'] == 0) 
                                0.00
                              @else
                              ${{ round(($result['monthly_base_details'][0]['retail']-$result['monthly_base_details'][0]['cuscost'])/$result['monthlycount']) }}
                              @endif
                            </p>
                          </div>
                        </div>
                      </div>
                    </div><!-- card -->
                  </div><!-- col-6 -->
                </div>
              </div>
              <div class="row no-gutters">
                <div class="col-sm-4 adv_time_span mx-auto mt-3" id="adv_time_span" style="cursor:pointer;display: none;">
                    <div class="card card-earning-summary mg-sm-l--1 bd-t-0 bd-sm-t top-card">
                      
                    </div><!-- card -->
                </div><!-- col-6 -->
              </div>
            </div>
              

            <!-- row -->

            <div class="back-btn" style="display: none;">
              <span><i class="fa fa-chevron-left" aria-hidden="true"></i> back to basic board </span>
            </div>

          </div>
          <!-- col-6 -->
         
        </div><!-- row -->
        
        <div class="section-opts mt-4">
          <div class="opts">
            <span class="active-opt opt" data-value='sales_man'>Sales Man Info</span>
            <span class="opt" data-value='manager'>Manager Info</span>
          </div>
        </div>
        <div class="section-wrapper sales_info mt-0">
          <div class="dt-head">
            <div class="sales-values">
              <label class="section-title">Sales Info</label>
              <p>Total Sales: <span class="font-weight-bold" id="datacountlead">{{ $result['todaycount'] }}</span> </p>
            </div>
            <div class="reload">
              <button>Reload  <i class="fa fa-refresh" aria-hidden="true"></i></button>
            </div>
            @if(Auth::user()->type == 'admin')
            <div class="cal-tab">
              <button> <span>Calendar </span></button>
            </div>
            @endif

            <div class="export-sec">
              <select name="export_select" id="exprt_frmt">
                <option value="xlsx">XLS format</option>
                <option value="csv">CSV format</option>
              </select>

              <div class="export-btn">
                <button> <span>Export </span></button>
              </div>
            </div>

          </div>
            
            <!--<p class="mg-b-20 mg-sm-b-40"></p>-->

            <div class="table-responsive table-wrapper dash_table sm-table">
            <table id="datatable1" class="table mg-b-0 table display responsive nowrap">
                <thead>
                <tr>
                    <th>SalesMan</th>
                    <th>Sales</th>
                    <th>DOWN PAYMENT</th>
                    <th>FINANCE TERM</th>
                    <th>DISCOUNT</th>
                    <th>Calls</th>
                    <th>Closing %</th>
                </tr>
                </thead>
                <tbody id="sales_info_data">
                    
                </tbody>
            </table>
            </div>


            <div class="table-responsive table-wrapper dash_table mng-table" style="display:none;">
            <table id="datatable1m" class="table mg-b-0 table display responsive nowrap">
                <thead>
                <tr>
                    <th>Manager</th>
                    <th>Sales</th>
                    <th>DOWN PAYMENT</th>
                    <th>FINANCE TERM</th>
                    <th>DISCOUNT</th>
                    <th>Calls</th>
                    <th>Closing %</th>
                </tr>
                </thead>
                <tbody id="sales_info_data_manager">
                    
                </tbody>
            </table>
            </div>
            
            <div class="card pd-25 calen" style="display:none;">
              <div id="fullCalendar"></div>
            </div>
            
        </div>

      </div><!-- container -->
    </div><!-- slim-mainpanel -->

    @include('common/footer')

    <script>
      $(document).ready(function(){
        var data;
        var sm_leaderboard, team_leaderboard;
        var calendar_data = "{{ $result['calendar_data'] }}";
        window.current_page = "{{ Route::currentRouteName() }}";
        window.adv_range_flag = "{{ $result['adv_range_flag'] }}";

        if("{{ $result['adv_range_flag'] }}" == true){
            $('.span').children('div').removeClass('active');
            $('.dtxt').show();
            $('.top-smry').fadeOut('slow');
            setSalesBoard();
            setTimeout(function(){
                $('.adv_time_span').fadeIn('slow');
                $('.back-btn').fadeIn('slow');
            },500);
        }
        
        setSalesRecord();
        deal_calendar(calendar_data,window.current_page);
        $('.span').click(function(){
            window.adv_range_flag = false;
            $('.dtxt').hide();
            $('.span').children('div').removeClass('active');
            $(this).children('div').addClass('active');
            if($(this).attr('id') == 'monthly'){
                sm_data = "{{ $result['monthly_details'] }}";
                manager_data = "{{ $result['monthly_manager_details'] }}";
                sm_leaderboard = "{{$result['monthly_top']}}";
                team_leaderboard = "{{$result['monthly_top_team']}}";
                $('.name_topper').html('Monthly');
                $('#datacountlead').html("{{ $result['monthlycount'] }}");
            }
            else if($(this).attr('id') == 'weekly'){
                sm_data = "{{ $result['weekly_details'] }}";
                manager_data = "{{ $result['weekly_manager_details'] }}";
                sm_leaderboard = "{{$result['weekly_top']}}";
                team_leaderboard = "{{$result['weekly_top_team']}}";
                $('.name_topper').html('Weekly');
                $('#datacountlead').html("{{ $result['weeklycount'] }}");
            }
            else{
                sm_data = "{{ $result['today_details'] }}";
                manager_data = "{{ $result['today_manager_details'] }}";
                sm_leaderboard = "{{$result['today_top']}}";
                team_leaderboard = "{{$result['today_top_team']}}";
                $('.name_topper').html('Today');
                $('#datacountlead').html("{{ $result['todaycount'] }}");
            }
            leader_board_update(sm_leaderboard,team_leaderboard);

            setSalesRecord();

        });


        $('.opt').click(function(){
          // console.log(2);
          $('.opt').removeClass('active-opt');
          $(this).addClass('active-opt');

          if(!$('.cal-tab').find('button').hasClass('caledar-btn')){

            if($(this).data('value') == 'sales_man'){
              $(".mng-table").fadeOut();
              
              setTimeout(function(){
                $(".sm-table").fadeIn();
              },200);
              $('#datacountlead').parent('p').show();
            }
            else{
              $(".sm-table").fadeOut();
              setTimeout(function(){
                $(".mng-table").fadeIn();
              },200);
              $('#datacountlead').parent('p').hide();
            }

          }
          
        });

        $('#changedays').change(function(){
          //console.log(this.value);
          var data,custom_data = [] ;
          window.adv_range_flag = false;
          var pifs_check = $("#pifs_checkbox").prop("checked");
          setSalesBoard();
          setSalesRecord();
          $('.dtxt').fadeOut('slow');
          $('.top-smry').fadeOut('slow');
          setTimeout(function(){
              $('.adv_time_span').fadeIn('slow');
              $('.back-btn').fadeIn('slow');
          },500);

        });
        
        setTimeout(function(){
          $('.calen').hide()
        },1);

      });

      
      $(document).on('click','.sm_name',function(){
            var salesman = $(this).text();
            if(salesman.indexOf('-') != -1 ){
              salesman = salesman.replaceAll('-','_');
            }
            var name = (salesman.replaceAll(' ','-'));
            var url = "{{ route('salesman-details',':name') }}";
            url = url.replace(':name',name);
            window.location.href = url;
            
       });


      $(document).on('click','.fc-prev-button,.fc-next-button',function () {

          var src_url = "{{ route('dashboard') }}";

          var cal_date = get_calendar_date();

          $.ajax({
              url : src_url,
              method: 'post',
              data : {
                  month : cal_date['month'],
                  year : cal_date['year'],
                  _token : "{{ csrf_token() }}"
              },
              success: function(result){
                  //console.log(result);
                  deal_calendar(result,window.current_page);
              },
              error: function(err){
                  console.log(err);
              }

          });
      });

      $(document).on('change','#sales_type',function(){

        if($(this).val() == 'net'){
          window.location.href = "{{ route('dashboard-active') }}";
        }
        else{
            window.location.href = "{{ route('dashboard') }}";
        }
      });


      // $(document).on('click','.export-btn',function(){

      //   var date_range,file_type,tab_type;

      //   if(window.adv_range_flag == true){
      //     // console.log(1);
      //       date_range = "{{ @$result['start_date'] }}-" + "{{ @$result['end_date'] }}";
      //   }

      //   else{
      //     if($("#changedays").val() != ''){
      //       date_range = $("#changedays").val();
      //     }
      //     else{
      //       date_range = $(".active").parent('div').attr("id");
      //     }
      //   }

      //   tab_type = $('.active-opt').data('value');
      //   file_type = $("#exprt_frmt").val();

      //   var pifs_check = $("#pifs_checkbox").prop("checked");

      //   // console.log(date_range);

      //   const mapObj =  {date_range: date_range, file_type: file_type, tab_type: tab_type, pifs_check};

      //   var export_url = "{{ route('export',['date_range','file_type','tab_type','pifs_check']) }}";
      //   export_url = export_url.replace(/\b(?:date_range|file_type|tab_type|pifs_check)\b/gi, matched => mapObj[matched]);
      //   // console.log(export_url);
      //   window.location.href = export_url;


      // });

      $(document).on('change','#pifs_checkbox',function(){
        setSalesBoard();
        setSalesRecord();
      });

      function setSalesRecord(){

        var sales_man_data, manager_data, date_range;
        var pifs_check = $("#pifs_checkbox").prop("checked");

        if(window.adv_range_flag == true){
          date_range = "advance";
        }
        else{
          if($("#changedays").val() != ''){
            date_range = $("#changedays").val();
          }
          else{
            date_range = $(".active").parent('div').attr("id");
          }
        }

        if(pifs_check == true){

          if(date_range == 'advance'){
            sales_man_data = "{{ @$result['adv_range_sales_details'] }}";
            manager_data = "{{ @$result['adv_range_manager_details'] }}";
          }
          else if(date_range == 'monthly'){
            sales_man_data = "{{ @$result['monthly_details'] }}";
            manager_data = "{{ @$result['monthly_manager_details'] }}";
          }
          else if(date_range == 'last_month'){
            sales_man_data = "{{ @$result['secondmonthly_details'] }}";
            manager_data = "{{ @$result['secondmonthly_manager_details'] }}";
          }
          else if(date_range == 'weekly'){
            sales_man_data = "{{ @$result['weekly_details'] }}";
            manager_data = "{{ @$result['weekly_manager_details'] }}";
          }
          else if(date_range == 'last_week'){
            sales_man_data = "{{ @$result['secondweekly_details'] }}";
            manager_data = "{{ @$result['secondweekly_manager_details'] }}";
          }
          else if(date_range == 'yesterday'){
            sales_man_data = "{{ @$result['yesterday_details'] }}";
            manager_data = "{{ @$result['yesterday_manager_details'] }}";
          }
          else{
            sales_man_data = "{{ @$result['today_details'] }}";
            manager_data = "{{ @$result['today_manager_details'] }}";
          }

        }
        else{

          if(date_range == 'advance'){
            sales_man_data = "{{ @$result['adv_range_pifs_details'] }}";
            manager_data = "{{ @$result['adv_range_pifs_manager_details'] }}";
          }
          else if(date_range == 'monthly'){
            sales_man_data = "{{ @$result['monthly_pifs_details'] }}";
            manager_data = "{{ @$result['monthly_pifs_manager_details'] }}";
          }
          else if(date_range == 'last_month'){
            sales_man_data = "{{ @$result['secondmonthly_pifs_details'] }}";
            manager_data = "{{ @$result['secondmonthly_pifs_manager_details'] }}";
          }
          else if(date_range == 'weekly'){
            sales_man_data = "{{ @$result['weekly_pifs_details'] }}";
            manager_data = "{{ @$result['weekly_pifs_manager_details'] }}";
          }
          else if(date_range == 'last_week'){
            sales_man_data = "{{ @$result['secondweekly_pifs_details'] }}";
            manager_data = "{{ @$result['secondweekly_pifs_manager_details'] }}";
          }
          else if(date_range == 'yesterday'){
            sales_man_data = "{{ @$result['yesterday_pifs_details'] }}";
            manager_data = "{{ @$result['yesterday_pifs_manager_details'] }}";
          }
          else{
            sales_man_data = "{{ @$result['today_pifs_details'] }}";
            manager_data = "{{ @$result['today_pifs_manager_details'] }}";
          }

        }

        insert_table_data(sales_man_data,'sales_man',pifs_check);
        insert_table_data(manager_data,'manager',pifs_check);

      }

      function setSalesBoard(){

        var pifs_check = $("#pifs_checkbox").prop("checked"); 
        var custom_data = [];
        if(window.adv_range_flag == true ){
          custom_data['custom_sales_details'] = "{{ @$result['adv_range_base_details'] }}";
          custom_data['custom_sales_count'] = "{{ @$result['adv_range_sales_count'] }}";
          custom_data['custom_ws_count'] = "{{ @$result['adv_range_wholesales_count'] }}";
          custom_data['custom_pifs_count'] = "{{ @$result['adv_range_pifs_count'] }}";
          custom_data['custom_cancel_count'] = "{{ @$result['adv_range_cancel_count'] }}";
          custom_data['custom_total_calls'] = "{{ @$result['adv_range_total_calls'] }}";
          custom_data['custom_text'] = "Result for {{ @$result['start_date'] }} to {{ @$result['end_date'] }} ";
          $("#datacountlead").html("{{ @$result['adv_range_sales_count'] }}");
          set_custom_sales_board(custom_data,window.current_page,pifs_check);
        } 

        else if($("#changedays").val() != ''){

          let days_span = $("#changedays").val();

          if(days_span == 'monthly'){
              sm_data = "{{ $result['monthly_details'] }}";
              manager_data = "{{ $result['monthly_manager_details'] }}";
              custom_data['custom_sales_details'] = "{{ $result['monthly_base_details'] }}";
              custom_data['custom_sales_count'] = "{{ $result['monthlycount'] }}";
              custom_data['custom_ws_count'] = "{{ $result['monthly_wholesales_count'] }}";
              custom_data['custom_pifs_count'] = "{{ $result['monthly_pifs_count'] }}";
              custom_data['custom_total_calls'] = "{{ $result['monthly_total_calls'] }}";
              custom_data['custom_cancel_count'] = "{{ @$result['monthly_cancel_count'] }}";
              custom_data['custom_text'] = "This Month's";
              $('#datacountlead').html("{{ $result['monthlycount'] }}");
          }
          else if(days_span == 'last_month'){
              sm_data = "{{ $result['secondmonthly_details'] }}";
              manager_data = "{{ $result['secondmonthly_manager_details'] }}";
              custom_data['custom_sales_details'] = "{{ $result['secondmonthly_base_details'] }}";
              custom_data['custom_sales_count'] = "{{ $result['secondmonthlycount'] }}";
              custom_data['custom_ws_count'] = "{{ $result['secondmonthly_wholesales_count'] }}";
              custom_data['custom_pifs_count'] = "{{ $result['secondmonthly_pifs_count'] }}";
              custom_data['custom_total_calls'] = "{{ $result['secondmonthly_total_calls'] }}";
              custom_data['custom_cancel_count'] = "{{ @$result['secondmonthly_cancel_count'] }}";
              custom_data['custom_text'] = "Last Month's";
              $('#datacountlead').html("{{ $result['secondmonthlycount'] }}");
          }
          else if(days_span == 'weekly'){
              sm_data = "{{ $result['weekly_details'] }}";
              manager_data = "{{ $result['weekly_manager_details'] }}";
              custom_data['custom_sales_details'] = "{{ $result['weekly_base_details'] }}";
              custom_data['custom_sales_count'] = "{{ $result['weeklycount'] }}";
              custom_data['custom_ws_count'] = "{{ $result['weekly_wholesales_count'] }}";
              custom_data['custom_pifs_count'] = "{{ $result['weekly_pifs_count'] }}";
              custom_data['custom_total_calls'] = "{{ $result['weekly_total_calls'] }}";
              custom_data['custom_cancel_count'] = "{{ @$result['weekly_cancel_count'] }}";
              custom_data['custom_text'] = "This Week's";
              $('#datacountlead').html("{{ $result['weeklycount'] }}");
            }
          else if(days_span == 'last_week'){
             sm_data = "{{ $result['secondweekly_details'] }}";
             manager_data = "{{ $result['secondweekly_manager_details'] }}";
             custom_data['custom_sales_details'] = "{{ $result['secondweekly_base_details'] }}";
             custom_data['custom_sales_count'] = "{{ $result['secondweeklycount'] }}";
             custom_data['custom_ws_count'] = "{{ $result['secondweekly_wholesales_count'] }}";
             custom_data['custom_pifs_count'] = "{{ $result['secondweekly_pifs_count'] }}";
             custom_data['custom_total_calls'] = "{{ $result['secondweekly_total_calls'] }}";
             custom_data['custom_cancel_count'] = "{{ @$result['secondweekly_cancel_count'] }}";
             custom_data['custom_text'] = "Last Week's";
             $('#datacountlead').html("{{ $result['secondweeklycount'] }}");
            }
          else if(days_span == 'yesterday'){
              sm_data = "{{ $result['yesterday_details'] }}";
              manager_data = "{{ $result['yesterday_manager_details'] }}";
              custom_data['custom_sales_details'] = "{{ $result['yesterday_base_details'] }}";
              custom_data['custom_sales_count'] = "{{ $result['yesterdaycount'] }}";
              custom_data['custom_ws_count'] = "{{ $result['yesterday_wholesales_count'] }}";
              custom_data['custom_pifs_count'] = "{{ $result['yesterday_pifs_count'] }}";
              custom_data['custom_total_calls'] = "{{ $result['yesterday_total_calls'] }}";
              custom_data['custom_cancel_count'] = "{{ @$result['yesterday_cancel_count'] }}";
              custom_data['custom_text'] = "Yesterday's";
              $('#datacountlead').html("{{ $result['yesterdaycount'] }}");
            }
          else{
              sm_data = "{{ $result['today_details'] }}";
              manager_data = "{{ $result['today_manager_details'] }}";
              custom_data['custom_sales_details'] = "{{ $result['today_base_details'] }}";
              custom_data['custom_sales_count'] = "{{ $result['todaycount'] }}";
              custom_data['custom_ws_count'] = "{{ $result['today_wholesales_count'] }}";
              custom_data['custom_pifs_count'] = "{{ $result['today_pifs_count'] }}";
              custom_data['custom_total_calls'] = "{{ $result['today_total_calls'] }}";
              custom_data['custom_cancel_count'] = "{{ @$result['today_cancel_count'] }}";
              custom_data['custom_text'] = "Today's";
              $('#datacountlead').html("{{ $result['todaycount'] }}");
          }
          set_custom_sales_board(custom_data,window.current_page,pifs_check);
        }

        else{

          var today_total_sales = "{{ $result['todaycount'] }}";
          var today_sales_pifs = "{{ $result['today_pifs_count'] }}";
          var today_finterm = "{{ $result['today_base_details'][0]['finterm'] }}";
          var weekly_total_sales = "{{ $result['weeklycount'] }}";
          var weekly_sales_pifs = "{{ $result['weekly_pifs_count'] }}";
          var weekly_finterm = "{{ $result['weekly_base_details'][0]['finterm'] }}";
          var monthly_total_sales = "{{ $result['monthlycount'] }}";
          var monthly_sales_pifs = "{{ $result['monthly_pifs_count'] }}";
          var monthly_finterm = "{{ $result['monthly_base_details'][0]['finterm'] }}";

          if(pifs_check == false){
            $(".pifs-cnt").hide();
            $(".today-finterm").text(today_finterm != '' ? (today_finterm/(today_total_sales - today_sales_pifs)).toFixed(2) : '0.00');
            $(".week-finterm").text(weekly_finterm != '' ? (weekly_finterm/(weekly_total_sales - weekly_sales_pifs)).toFixed(2) : '0.00');
            $(".month-finterm").text(monthly_finterm != '' ? (monthly_finterm/(monthly_total_sales - monthly_sales_pifs)).toFixed(2) : '0.00');
          }
          else{
            $(".pifs-cnt").show();
            $(".today-finterm").text(today_finterm != '' ? (today_finterm/today_total_sales ).toFixed(2) : '0.00');
            $(".week-finterm").text(weekly_finterm != '' ? (weekly_finterm/weekly_total_sales ).toFixed(2) : '0.00');
            $(".month-finterm").text(monthly_finterm != '' ? (monthly_finterm/monthly_total_sales ).toFixed(2) : '0.00');
          }

        }
        

      }
      
  </script>
