@extends('layout.app')
@section('title','Payments')
@section('content')
 <!-- Main content -->
            <div class="content-wrapper">
                   <div class="page-header">
                    <div class="page-header-content">
                        <div class="page-title">
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Payments</h4>

                        <div class="heading-elements">
                            <a href="{{ route('payment.create') }}" class="btn bg-blue btn-labeled heading-btn"><b><i class="icon-plus3"></i></b> Add New Records</a>&nbsp;&nbsp;&nbsp; <a href="{{ url('payment/payrecord') }}" class="btn bg-green btn-labeled heading-btn"><b><i class="icon-plus3"></i></b> Make New Payment</a>

                               
                            
                        </div>
                    </div>
                </div>
                <!-- /page header -->


                <!-- Content area -->
                <div class="content">
                    <!-- Basic table -->
                    <div class="panel panel-flat">
                         <button type="button" onclick="ExportCsv()" class="btn btn-primary pull-right"><i class="zmdi zmdi-download"></i> Export Excel</button>
                        <div class="table-responsive">
                        <table class="table datatable-basic" id="datatable">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>GSM Net</th>
                                    <th>GSM No</th>
                                    <th>State</th>
                                    <th>LGA</th>
                                    <th>Ward</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Status </th>
                                    <th>Edit/Delete</th>
                                </tr>
                            </thead>
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
    $('.datatable-basic').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ url('payment/getData') }}',
            columns: [
            { data: 'fname', name: 'fname' },
            { data: 'lname', name: 'lname' },
            { data: 'gsm_name', name: 'gsms.gsm_name' },
            { data: 'gsm_no', name: 'gsm_no' },
            { data: 'state_name', name: 'states.state_name' },
            { data: 'lga_name', name: 'lgas.lga_name' },
            { data: 'ward_name', name: 'wards.ward_name' },
            { data: 'category_name', name: 'categories.category_name' },
            { data: 'total', name: 'total' },
            {data: 'status',name:'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
       });
});
   
</script>
<script>
     // Alert combination
    function deleteit(id){
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel pls!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                $.ajax({
                url: "payment"+'/'+id,
                type: 'delete',
                dataType: "JSON",
                data: {
                    "id": id,
                    "_token":"{{ csrf_token() }}"
                },
            });
            $('.datatable-basic').DataTable().draw(false);
            swal("Deleted!", "User has been deleted!", "success");
            }
            else {
                swal({
                    title: "Cancelled",
                    text: "Your imaginary file is safe :)",
                    confirmButtonColor: "#2196F3",
                    type: "error"
                });
            }
        });
    }
</script>   
@endpush  
@endsection