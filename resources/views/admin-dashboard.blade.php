@include('common/header')
@include('common/navbar')
<div class="slim-mainpanel">
      <div class="container pd-t-50">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="tx-inverse mg-b-15">Welcome back, {{Auth::user()->name}}!</h3>
            <p class="mg-b-40">Please see the reports given below :</p>

            <h6 class="slim-card-title mg-b-15">Your Lead Summary</h6>
            <div class="row no-gutters">
              <div class="col-sm-4 span" id="daily" style="cursor:pointer;">
                <div class="card card-earning-summary active">
                  <h6>Today's Lead</h6>
                  <h1>{{$todaycount}}</h1>
                  {{$dailydata[2]}}
                  <span class="valign-middle"><span class="@if($dailydata[0]=='pos')tx-success @else tx-danger @endif">
                  <i class="icon @if($dailydata[0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i>{{$dailydata[2]}}%</span> from last Day</span>
                </div><!-- card -->
              </div><!-- col-6 -->
              <div class="col-sm-4 weekly span" id="weekly" style="cursor:pointer;">
                <div class="card card-earning-summary mg-sm-l--1 bd-t-0 bd-sm-t">
                  <h6>This Week's Lead</h6>
                  <h1>{{$weeklycount}}</h1>
                  {{$weeklydata[2]}}
                  <span class="valign-middle"><span class="@if($weeklydata[0]=='pos')tx-success @else tx-danger @endif">
                  <i class="icon @if($weeklydata[0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i> {{$weeklydata[1]}}%</span> from last week</span>
                </div><!-- card -->
               
              </div><!-- col-6 -->
              <div class="col-sm-4 span" id="monthly" style="cursor:pointer;">
                <div class="card card-earning-summary mg-sm-l--1 bd-t-0 bd-sm-t">
                  <h6>This Month's Lead</h6>
                  <h1>{{$monthlycount}}</h1>
                  {{$monthlydata[2]}}
                  <span class="valign-middle"><span class="@if($monthlydata[0]=='pos')tx-success @else tx-danger @endif">
                  <i class="icon @if($monthlydata[0]=='pos')ion-android-arrow-up @else ion-android-arrow-down @endif mg-r-5"></i>{{$monthlydata[1]}}%</span> from last Month</span>
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
                    <th>Team</th>
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
        data = "{{ $today_details }}";
        insert_table_data(data);
        $('.span').click(function(){
            $('.span').children('div').removeClass('active');
            $(this).children('div').addClass('active');
            if($(this).attr('id') == 'monthly')
                data = "{{ $monthly_details }}";
            else if($(this).attr('id') == 'weekly')
                data = "{{ $weekly_details }}";
            else
                data = "{{ $today_details }}";

            insert_table_data(data);
        });
      });

    </script>
