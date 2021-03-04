@include('common/header')
@include('common/navbar')
<div class="slim-mainpanel">
      <div class="container pd-t-50">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="tx-inverse tp-h mg-b-15">Welcome back, {{Auth::user()->name}}!</h3>
            <h3 class="h3-txt">Leader Board</h3>

           <div class="user-box">
              @if(!empty($result['montly_top']))
              <div class="card-contact">
                <div class="tx-center">
                  <a href=""><img src="{{asset('images/profile.png')}}" class="card-img" alt=""></a>
                  <h5 class="mg-t-10 mg-b-5"><a href="{{route('salesman-details',str_replace(' ','_',$result['montly_top'][0]['salesman']))}}" class="contact-name">{{ $result['montly_top'][0]['salesman'] }}</a></h5>
                  <p>Salesman of the Month</p>
                </div><!-- tx-center -->

                <p class="contact-item">
                  <span>Lead:</span>
                  <span>{{ $result['montly_top'][0]['sales_count'] }}</span>
                </p><!-- contact-item -->
              </div>
              @endif
              @if(!empty($result['weekly_top']))
              <div class="card-contact">
                <div class="tx-center">
                  <a href=""><img src="{{asset('images/profile.png')}}" class="card-img" alt=""></a>
                  <h5 class="mg-t-10 mg-b-5"><a href="{{route('salesman-details',str_replace(' ','_',$result['weekly_top'][0]['salesman']))}}" class="contact-name">{{ $result['weekly_top'][0]['salesman'] }}</a></h5>
                  <p>Salesman of the Week</p>
                </div><!-- tx-center -->

                <p class="contact-item">
                  <span>Lead:</span>
                  <span>{{ $result['weekly_top'][0]['sales_count'] }}</span>
                </p><!-- contact-item -->
              </div>
              @endif
            </div>

            <p class="mg-b-20">Please see the reports given below :</p>

            

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
                      </div>
                    </form>

                    <P class="dtxt" style="display: none;">Showing Data from <span class='drng'>'{{ $result['start_date'] }}'</span> to <span class='drng'>'{{ $result['end_date'] }}'</span></p>

                   </div>

            </div>


            <div class="row no-gutters">
              <div class="col-sm-4 span" id="daily" style="cursor:pointer;">
                <div class="card card-earning-summary active">
                  <h6>Today's Lead</h6>
                  <h1>{{$result['todaycount']}}</h1>
                  {{$result['dailydata'][2]}}
                  <span class="valign-middle"><span class="@if($result['dailydata'][0]=='pos')tx-success @else tx-danger @endif">
                  <i class="icon @if($result['dailydata'][0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i>{{$result['dailydata'][2]}}%</span> from last Day</span>
                </div><!-- card -->
              </div><!-- col-6 -->
              <div class="col-sm-4 weekly span" id="weekly" style="cursor:pointer;">
                <div class="card card-earning-summary mg-sm-l--1 bd-t-0 bd-sm-t">
                  <h6>This Week's Lead</h6>
                  <h1>{{$result['weeklycount']}}</h1>
                  {{$result['weeklydata'][2]}}
                  <span class="valign-middle"><span class="@if($result['weeklydata'][0]=='pos')tx-success @else tx-danger @endif">
                  <i class="icon @if($result['weeklydata'][0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i> {{$result['weeklydata'][1]}}%</span> from last week</span>
                </div><!-- card -->
               
              </div><!-- col-6 -->
              <div class="col-sm-4 span" id="monthly" style="cursor:pointer;">
                <div class="card card-earning-summary mg-sm-l--1 bd-t-0 bd-sm-t">
                  <h6>This Month's Lead</h6>
                  <h1>{{$result['monthlycount']}}</h1>
                  {{$result['monthlydata'][2]}}
                  <span class="valign-middle"><span class="@if($result['monthlydata'][0]=='pos')tx-success @else tx-danger @endif">
                  <i class="icon @if($result['monthlydata'][0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i>{{$result['monthlydata'][1]}}%</span> from last Month</span>
                </div><!-- card -->
                
              </div><!-- col-6 -->
            </div><!-- row -->
          </div><!-- col-6 -->
         
        </div><!-- row -->

        <div class="section-wrapper sales_info">
            <label class="section-title">Sales Info</label>
            <!--<p class="mg-b-20 mg-sm-b-40"></p>-->

            <div class="table-responsive table-wrapper dash_table">
            <table id="datatable1" class="table mg-b-0 table display responsive nowrap">
                <thead>
                <tr>
                    <th>Sales Man</th>
                    <th>Sales</th>
                    <th>downpayment</th>
                    <th>finance term</th>
                    <th>discount</th>
                    <th>Calls</th>
                    <th>Converstion</th>
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
        if("{{ $result['adv_range_flag'] }}" == false){
            data = "{{ $result['today_details'] }}";
        }
        else{
            data = "{{ $result['adv_range_sales_details'] }}";
            $('.span').children('div').removeClass('active');
            $('.dtxt').show();
        }
        
        insert_table_data(data);
        $('.span').click(function(){
            $('.dtxt').hide();
            $('.span').children('div').removeClass('active');
            $(this).children('div').addClass('active');
            if($(this).attr('id') == 'monthly')
                data = "{{ $result['monthly_details'] }}";
            else if($(this).attr('id') == 'weekly')
                data = "{{ $result['weekly_details'] }}";
            else
                data = "{{ $result['today_details'] }}";

            insert_table_data(data);
        });
        
      });

      
      $(document).on('click','.sm_name',function(){
            var name = ($(this).text()).replace(' ','_');
            var url = "{{ route('salesman-details',':name') }}";
            url = url.replace(':name',name);
            window.location.href = url;
            
       });
        

    </script>
