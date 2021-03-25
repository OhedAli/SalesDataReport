@include('common/header')

<div class="slim-mainpanel">
      <div class="container pd-t-50">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="tx-inverse tp-h mg-b-15">Welcome back, {{Auth::user()->name}}!</h3>
            
            <div class="m-lead">
            <h5 class="m-lead-hd">Leader Board: <span class="name_topper">Today</span> Toppers</h5>
            <div  class="l-board">
            
            @foreach($result['today_top'] as $key=>$value)
            
              <div class="mem-1">
                <div class="tx-center">
                  <a href="javascript:void(0);" class="img-a"><img src="{{asset('images/profile.png')}}" class="card-img" alt="">
                    <div class="hexa ">
                      <img src="{{asset('images/hexa'.($key+1). '.png')}}" class="hexa1-bg" alt="">
                        <p>{{ $key + 1 }}</p>
                      </div> 
                  </a>
                  <h5 class="mg-t-10 mg-b-5"><a href="javascript:void(0);" class="contact-name sm_name leader_name{{$key}}">{{$value['salesman']}}</a></h5>
                  <p><span class="leaderboardcount{{$key}}">{{$value['sales_count']}}</span> Sales</p>
                </div>
              </div>
              @endforeach
            </div>
            </div>

            <div class="adv_srch">
                <div class="frm-box">
                       <form action="" method="post">
                      <!--  Details -->
                      @csrf
                      <div class="form-group">
                      <h6 class="slim-card-title">Your Lead Summary</h6>
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

                    <P class="dtxt" style="display: none;">Showing Data from <span class='drng'>'{{ $result['start_date'] }}'</span> to <span class='drng'>'{{ $result['end_date'] }}'</span></p>

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
                        <p class="text-black">{{ $result['today_total_calls'] }}</p>
                      </div>
                      <div class="ctab-bx bl-1">
                        <p>Conversion</p>
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
                        <p class="text-black">
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
                        <p class="text-black">{{ $result['weekly_total_calls'] }}</p>
                      </div>
                      <div class="ctab-bx bl-1">
                        <p>Conversion</p>
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
                        <p class="text-black">
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
                        <p class="text-black">{{ $result['monthly_total_calls'] }}</p>
                      </div>
                      <div class="ctab-bx bl-1">
                        <p>Conversion</p>
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
                        <p class="text-black">
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
            </div><!-- row -->

            <div class="back-btn" style="display: none;">
              <span><i class="fa fa-chevron-left" aria-hidden="true"></i> back to board </span>
            </div>

          </div>
          <!-- col-6 -->
         
        </div><!-- row -->
      
        <div class="section-wrapper sales_info">
          <div class="dt-head">
            <div class="sales-values">
              <label class="section-title">Sales Info</label>
              <p>Total Lead: <span class="font-weight-bold" id="datacountlead">{{ $result['todaycount'] }}</span> </p>
            </div>
            <div class="reload">
              <button>Reload  <i class="fa fa-refresh" aria-hidden="true"></i></button>
            </div>
            @if(Auth::user()->type == 'admin')
            <div class="cal-tab">
              <button> <span>Calendar </span></button>
            </div>
            @endif
          </div>
            
            <!--<p class="mg-b-20 mg-sm-b-40"></p>-->

            <div class="table-responsive table-wrapper dash_table">
            <table id="datatable1" class="table mg-b-0 table display responsive nowrap">
                <thead>
                <tr>
                    <th>Sales Man</th>
                    <th>Sales</th>
                    <th>DOWN PAYMENT</th>
                    <th>FINANCE TERM</th>
                    <th>DISCOUNT</th>
                    <th>Calls</th>
                    <th>Conversion</th>
                </tr>
                </thead>
                <tbody id="sales_info_data">
                    
                </tbody>
            </table>
            </div><!-- table-responsive -->
            
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
        var leaderboard;
        var calendar_data = "{{ $result['calendar_data'] }}";
        if("{{ $result['adv_range_flag'] }}" == false){
            data = "{{ $result['today_details'] }}";
        }
        else{
            data = "{{ $result['adv_range_sales_details'] }}";
            $('.span').children('div').removeClass('active');
            $('.dtxt').show();
            $('.no-gutters').fadeOut('slow');
            $("#datacountlead").html("{{ $result['adv_range_sales_count'] }}");
        }
        
        insert_table_data(data);
        deal_calendar(calendar_data);
        $('.span').click(function(){
            $('.dtxt').hide();
            $('.span').children('div').removeClass('active');
            $(this).children('div').addClass('active');
            if($(this).attr('id') == 'monthly'){
                data = "{{ $result['monthly_details'] }}";
                leaderboard = "{{$result['monthly_top']}}";
                $('.name_topper').html('Monthly');
                $('#datacountlead').html("{{ $result['monthlycount'] }}");
            }
            else if($(this).attr('id') == 'weekly'){
                data = "{{ $result['weekly_details'] }}";
                leaderboard = "{{$result['weekly_top']}}";
                $('.name_topper').html('Weekly');
                $('#datacountlead').html("{{ $result['weeklycount'] }}");
            }
            else{
                data = "{{ $result['today_details'] }}";
                leaderboard = "{{$result['today_top']}}";
                $('.name_topper').html('Today');
                $('#datacountlead').html("{{ $result['todaycount'] }}");
            }
            leader_board_update(leaderboard);
            insert_table_data(data);
            
        });

        $('#changedays').change(function(){
          //console.log(this.value);
          $('.no-gutters').fadeOut('slow');
          setTimeout(function(){
              $('.back-btn').fadeIn('slow');
          },500);
          if(this.value == 'monthly'){
              data = "{{ $result['monthly_details'] }}";
              $('#datacountlead').html("{{ $result['monthlycount'] }}");
          }
          else if(this.value == 'last_month'){
              data = "{{ $result['Secondmonthly_details'] }}";
              $('#datacountlead').html("{{ $result['Secondmonthlycount'] }}");
          }
          else if(this.value == 'weekly'){
              data = "{{ $result['weekly_details'] }}";
              $('#datacountlead').html("{{ $result['weeklycount'] }}");
            }
          else if(this.value == 'last_week'){
             data = "{{ $result['Secondweekly_details'] }}";
             $('#datacountlead').html("{{ $result['Secondweeklycount'] }}");
            }
          else if(this.value == 'yesterday'){
              data = "{{ $result['yesterday_details'] }}";
              $('#datacountlead').html("{{ $result['yesterdaycount'] }}");
            }
          else{
              data = "{{ $result['today_details'] }}";
              $('#datacountlead').html("{{ $result['todaycount'] }}");
          }
          insert_table_data(data);
        })
        
        setTimeout(function(){
          $('.calen').hide()
        },1);

      });

      
      $(document).on('click','.sm_name',function(){
            
            var name = ($(this).text()).replace(' ','-');
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
                  deal_calendar(result);
              },
              error: function(err){
                  console.log(err);
              }

          });
      });
        

    </script>
