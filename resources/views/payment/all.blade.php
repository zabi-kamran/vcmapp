@extends('layout.app')
@section('title','Payments')
@section('content')
 <!-- Main content -->
            <div class="content-wrapper">
                   <div class="page-header">

                <!-- /page header -->


                <!-- Content area -->
                <div class="content">
                    <!-- Basic table -->
                    <div class="panel panel-flat">
                          <div class="table-responsive">
                        <table class="table datatable-basic" id="datatable">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th> Last Name</th>
                                    <th> State </th>
                                    <th> Ward</th>
                                    <th> Phone </th>
                                    <th>Payment Made by</th>
                                    <th>Description </th>
                                    <th> Date of Payement Made </th>
                                    <th>Certificate </th>
                                    <th> Detail </th>
                                </tr>
                            </thead>

                            <tbody>

                            @foreach($payments as $payment)

                            <tr>

                            <td> {{ $payment->fname }}</td>
                            <td> {{ $payment->	lname }}</td>
                            <td>{{ $payment->state_name }}</td>
                            <td>{{ \App\Model\Ward::find($payment->ward_id)->ward_name }}</td>
                            <td>{{ $payment->phone }}</td>
                             <td> {{ $payment->create_name }} </td>
                              <td>{{ $payment->description }} </td>
                               <td>{{ $payment->created_at }} </td>
                              <td>
                              <a class="btn btn-success"
                              @if($payment->certificate!=null)
                               href="{{ $payment->certificate }}" @else @endif download>

                              <i class="icon-download"></i> Download</a> </td>
                                <td><a target="_blank" href="../payment/{{ $payment->id }}/edit"><button class="btn btn-primary"><i class="icon-eye"></i> Detail</button> </a></td>

                            </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <!-- /basic table -->

                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->
@push('footerscript')
<script type="text/javascript" src="{{ asset('theme/assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('theme/assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('theme/assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
<script type="text/javascript">
    function ExportCsv(){
        window.location.href = "{{ url('payment/exportexcel') }}";
    }
</script>
<script>
    $(function() {
    // Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {

        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
    });
    // Basic datatable
    $('.datatable-basic').DataTable();
});

</script>

@endpush
@endsection