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
                <div class="card card-earning-summary">
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


        <div class="card pd-25 calen" style="display:none;">
          <div id="fullCalendar"></div>
        </div><!-- card -->

      </div><!-- container -->
    </div><!-- slim-mainpanel -->

    @include('common/footer')

    <script>
      $(document).ready(function(){
        var data;
        if("{{ $result['adv_range_flag'] }}" == true){
            $('.dtxt').show();
            $('.span').children('div').removeClass('active');
        }
        
        data = "{{ $result['monthly_sm_details'] }}";
        deal_calendar(data,prev=0);
        
      });

      

    $(document).on('click','.fc-button',function () {

        var cal_date = get_calendar_date();

        let name = ("{{$result['sm_name']}}").replace(' ','_');
        var src_url = "{{ route('salesman-details',':name') }}";
        src_url = src_url.replace(':name',name);
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
                deal_calendar(result,prev=1);
            },
            error: function(err){
                console.log(err);
            }

        });
    });
      
    </script>
