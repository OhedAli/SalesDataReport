@include('common/header')
@include('common/navbar')
    <div class="slim-mainpanel">
      <div class="container">
        <div class="slim-pageheader">
          <ol class="breadcrumb slim-breadcrumb" style="display:none;">
            <li class="breadcrumb-item"><a href="{{Route('dashboard')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{Route('wholesaleslogs')}}">Saleslog</a></li>
          </ol>
          <h6 class="slim-pagetitle">WholeSales log Query List</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
          <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-15p">App Number</th>
                  <th class="wd-15p">Name</th>
                  <th class="wd-20p">Model</th>
                  <th class="wd-15p">Purchase At</th>
                  <th class="wd-10p">Details</th>
                </tr>
              </thead>
              <tbody>
              @foreach($total_customers as $key=>$customer)
            
                <tr>
                  <td>{{$customer->app_number}}</td>
                  <td>{{$customer->first_name}} {{$customer->last_name}}</td>
                  <td>{{$customer->model}}</td>
                  <td>{{ date('d M Y', strtotime($customer->purchdate)) }}</td>
                  <td><a href="{{route('sales-view',$customer->id)}}" class="dt-btn">View Details</a></td>
                </tr>
                @endforeach
              
              </tbody>
            </table>
          </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    
@include('common/footer')
<script>
      $(function(){
        'use strict';

        $('#datatable1').DataTable({
          responsive: true,
          language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
          }
        });

        $('#datatable2').DataTable({
          bLengthChange: false,
          searching: false,
          responsive: true
        });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>
