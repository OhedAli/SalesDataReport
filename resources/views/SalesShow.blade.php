@include('common/header')
@include('common/navbar')
    <div class="slim-mainpanel">
    <div class="container-fluid">

    <div class="slim-pageheader">
          <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{Route('dashboard')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{Route('saleslogs')}}">Saleslog</a></li>
            <li class="breadcrumb-item"><a href="#">Details</a></li>
          </ol>
          <h6 class="slim-pagetitle">Saleslog Details</h6>
        </div><!-- slim-pageheader -->

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Records</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
             
                <tbody>
                    <tr>
                        <td>App Number</td>
                        <td>{{$customer->app_number}}</td>
                    </tr>
                    <tr>
                        <td>First Name</td>
                        <td>{{$customer->first_name}}</td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td>{{$customer->last_name}}</td>
                    </tr>
                    <tr>
                        <td>Vin</td>
                        <td>{{$customer->vin}}</td>
                    </tr>
                    <tr>
                        <td>Year</td>
                        <td>{{$customer->year}}</td>
                    </tr>
                    <tr>
                        <td>Make</td>
                        <td>{{$customer->make}}</td>
                    </tr>
                    <tr>
                        <td>Model</td>
                        <td>{{$customer->model}}</td>
                    </tr>
                    <tr>
                        <td>Rate Date</td>
                        <td>{{$customer->rate_date}}</td>
                    </tr>
                    <tr>
                        <td>New Or Used</td>
                        <td> {{$customer->new_or_used}}  </td>
                    </tr>
                    <tr>
                        <td>Coverage</td>
                        <td>{{$customer->coverage}}</td>
                    </tr>
                    <tr>
                        <td>Termmonth</td>
                        <td>{{$customer->termmonth}}</td>
                    </tr>
                    <tr>
                        <td>Termmiles</td>
                        <td>{{$customer->termmiles}}</td>
                    </tr>
                    <tr>
                        <td>Deduct</td>
                        <td>{{$customer->deduct}}</td>
                    </tr>
                    
                    <tr>
                        <td>Sales Class</td>
                        <td>{{$customer->sales_class}}</td>
                    </tr>
                    <tr>
                        <td>Purchase Date</td>
                        <td>{{$customer->purchdate}}</td>
                    </tr>
                    <tr>
                        <td>Expiry Date</td>
                        <td>{{$customer->expdate}}</td>
                    </tr>
                    <tr>
                        <td>Purchodom</td>
                        <td>{{$customer->purchodom}}</td>
                    </tr>
                    <tr>
                        <td>Expodom</td>
                        <td>{{$customer->expodom}}</td>
                    </tr>
                    <tr>
                        <td>Retail</td>
                        <td>{{$customer->retail}}</td>
                    </tr>
                    <tr>
                        <td>Cuscost</td>
                        <td>{{$customer->cuscost}}</td>
                    </tr>
                    <tr>
                        <td>Dlrcost</td>
                        <td>{{$customer->dlrcost}}</td>
                    </tr>
                    <tr>
                        <td>Salesman</td>
                        <td>{{$customer->salesman}}</td>
                    </tr>   

                    <tr>
                        <td>User</td>
                        <td>{{$customer->user}}</td>
                    </tr>
                    <tr>
                        <td>Sur4wd</td>
                        <td>{{$customer->sur4wd}}</td>
                    </tr>
                    <tr>
                        <td>Sur Turbo</td>
                        <td>{{$customer->sur_turbo}}</td>
                    </tr>
                    <tr>
                        <td>Dur Diesel</td>
                        <td>{{$customer->sur_diesel}}</td>
                    </tr>
                    <tr>
                        <td>Sur Oneton</td>
                        <td>{{$customer->sur_oneton}}</td>
                    </tr>
                    <tr>
                        <td>Sur Tencyl</td>
                        <td>{{$customer->sur_tencyl}}</td>
                    </tr>
                    <tr>
                        <td>Sur Drw</td>
                        <td>{{$customer->sur_drw}}</td>
                    </tr>
                    <tr>
                        <td>Sur Bus</td>
                        <td>{{$customer->sur_bus}}</td>
                    </tr>
                    <tr>
                        <td>Sur Com</td>
                        <td>{{$customer->sur_com}}</td>
                    </tr>
                    <tr>
                        <td>Sur Convan</td>
                        <td>{{$customer->sur_convan}}</td>
                    </tr>
                    <tr>
                        <td>Downpay</td>
                        <td>{{$customer->downpay}}</td>
                    </tr>
                    <tr>
                        <td>List Code</td>
                        <td>{{$customer->listcode}}</td>
                    </tr>
                    <tr>
                        <td>Promo Code</td>
                        <td>{{$customer->promocode}}</td>
                    </tr>
                    <tr>
                        <td>Gross Profit</td>
                        <td>{{$customer->grossprofit}}</td>
                    </tr>
                    <tr>
                        <td>Net Profit</td>
                        <td>{{$customer->netprofit}}</td>
                    </tr>
                    <tr>
                        <td>Admin Code</td>
                        <td>{{$customer->admincode}}</td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td>{{$customer->emailaddress}}</td>
                    </tr>
                    <tr>
                        <td>Commission</td>
                        <td>{{$customer->commission}}</td>
                    </tr> 
                    <tr>
                        <td>First Billdate</td>
                        <td>{{ date('d M Y', strtotime($customer->first_billdate)) }}</td>
                    </tr>
                    <tr>
                        <td>Cancel Date</td>
                        <td>{{ date('d M Y', strtotime($customer->cancel_date)) }}</td>
                    </tr> 
                    <tr>
                        <td>Created At</td>
                        <td>{{ date('d M Y', strtotime($customer->create_at)) }}</td>
                    </tr>
                    <tr>
                        <td>Updated At</td>
                        <td>{{ date('d M Y', strtotime($customer->updated_at)) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

    
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