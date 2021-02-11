@include('common/header')
@include('common/navbar')
    <div class="slim-mainpanel">
      <div class="container">
        <div class="slim-pageheader">
          <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Saleslog</a></li>
          </ol>
          <h6 class="slim-pagetitle">Saleslog Query List</h6>
        </div><!-- slim-pageheader -->

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
              @foreach($total_customers as $key=>$customer)
            
                <tr>
                  <td>{{$customer->app_number}}</td>
                  <td>{{$customer->first_name}} {{$customer->last_name}}</td>
                  <td>{{$customer->model}}</td>
                  <td>{{ date('d M Y', strtotime($customer->create_at)) }}</td>
                  <td><a href="{{Route('saleslogs-show',[$customer->id])}}" class="btn btn-primary btn-sm">View Details</a></td>
                </tr>
                @endforeach;
              
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