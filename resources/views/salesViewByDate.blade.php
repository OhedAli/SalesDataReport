@include('common/header')
@include('common/navbar')
<div class="slim-mainpanel">
      <div class="container pd-t-50">
        
        <div class="section-wrapper">
          <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-15p">App Number</th>
                  <th class="wd-15p">Name</th>
                  <th class="wd-20p">Model</th>
                  <th class="wd-15p">Created At</th>
                  <th class="wd-10p">Details</th>
                </tr>
              </thead>
              <tbody>
              @foreach($allDateByTime as $key=>$customer)
            
                <tr>
                  <td>{{$customer->app_number}}</td>
                  <td>{{$customer->first_name}} {{$customer->last_name}}</td>
                  <td>{{$customer->model}}</td>
                  <td>{{ date('d M Y', strtotime($customer->create_at)) }}</td>
                  <td><a href="{{route('sales-view',$customer->id)}}" class="dt-btn">View Details</a></td>
                </tr>
                @endforeach
              
              </tbody>
            </table>
          </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->
      </div><!-- container -->
    </div><!-- slim-mainpanel -->

    @include('common/footer')

