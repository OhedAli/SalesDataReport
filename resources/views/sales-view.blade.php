@include('common/header')
@include('common/navbar')

<div class="" id="view-page-top">
    <a class="scroll-to-top rounded" style="display: inline;cursor:pointer;">
        <i class="fa fa-chevron-up" aria-hidden="true"></i>
    </a>
    <p><h3 class="hdr">Saleslog Details</h3></p>
    <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Records</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                        @foreach( $customer_details as $datavalues )
                            @foreach( $datavalues as $datakey=>$datavalue )
                                @if( $datakey != 'id'  )
                                <tr>
                                    <td class="field">{{ $datakey }}</td>
                                    <td>{{ $datavalue }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

@include('common/footer')
