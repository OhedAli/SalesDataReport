@include('common/header')
@include('common/navbar')

 
    <div class="slim-mainpanel">
      <div class="container">
        <div class="slim-pageheader">
          <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">User Report</a></li>
          </ol>
          <h6 class="slim-pagetitle"><a href="{{route('user.create')}}"> Add Users</a></h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mg-t-20">
          <label class="section-title">All Users</label>
        
          <div class="table-wrapper">
            <table id="datatable2" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-15p">Users</th>
                  <!-- <th class="wd-15p">User Type</th> -->
                  <th class="wd-20p">Email</th>
                  <th class="wd-20p">Report</th>
            <!--  <th class="wd-15p">Create Date</th>
                  <th class="wd-10p">Update Date</th>
                  <th class="wd-10p">Method</th> -->
                </tr>
              </thead>
              <tbody>
              @foreach($users as $key=>$user)
                <tr>
                  <td><span class="all-users"><img src="{{ asset('/public/images/uploads/avatars/'.$user->avatar)}}"></span>{{$user->name}}</td>
                  <!-- <td>{{$user->type}}</td> -->
                  <td>{{$user->email}}</td>
                  <td><?php echo (($user->data_report!=null)?$user->data_report:'N/A')?></td>

                 <!--  <td>{{$user->created_at}}</td>
                  <td>{{$user->updated_at}}</td> -->
                 <!--  <td>@if(Auth::user()->id != $user->id)
                            <a href="{{ route('user.edit', [$user->id]) }}" class="btn btn-outline-primary edit-btn">Edit <i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <button data-toggle="modal" data-target="#deleteModal" class="btn btn-outline-danger delete-btn" data-route="{{ route('user.destroy', [$user->id]) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            @else
                            <a href="{{ route('user.edit', [$user->id]) }}" class="btn btn-outline-primary edit-btn">Edit <i class="fa fa-pencil" aria-hidden="true"></i></a>
                            @endif
                  </td> -->
                </tr>
                @endforeach
                
              </tbody>
            </table>
          </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Press "Delete" below if you are ready to delete.
            </div>
            <div class="modal-footer">
                <form id="delete" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
      </div><!-- container -->
    </div><!-- slim-mainpanel -->

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
    <script>
    $(document).on("click", "button.delete-btn", function() {
        var deleteRoute = $(this).data("route");
        console.log(deleteRoute);
        $("form#delete").attr("action", deleteRoute);
        return true;
    });
</script>
  
