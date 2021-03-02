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
                    <th>Team</th>
                    <th>Calls</th>
                    <th>Converstion</th>
                </tr>
                </thead>
                <tbody id="">
                    
                </tbody>
            </table>
            </div><!-- table-responsive -->
        </div>

      </div><!-- container -->
    </div><!-- slim-mainpanel -->

    @include('common/footer')
