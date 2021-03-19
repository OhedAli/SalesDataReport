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
          </div>
          <!-- col-6 -->
         
        </div><!-- row -->

        <div class="section-wrapper sales_info">

            <div class="sales-values">
              <label class="section-title">Sales Info</label>
              <p>Total Lead: <span class="font-weight-bold" id="datacountlead">{{ $result['todaycount'] }}</span> </p>
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
        </div>

      </div><!-- container -->
    </div><!-- slim-mainpanel -->

    @include('common/footer')

    <script>
      $(document).ready(function(){
        var data;
        var leaderboard;
        data = "{{ $result['today_details'] }}";
        insert_table_data(data);
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
        
        setInterval(function(){
          location.reload();
        },60000);

      });

      
      $(document).on('click','.sm_name',function(){
            
            var name = ($(this).text()).replace(' ','-');
            var url = "{{ route('salesman-details',':name') }}";
            url = url.replace(':name',name);
            window.location.href = url;
            
       });
        

    </script>
