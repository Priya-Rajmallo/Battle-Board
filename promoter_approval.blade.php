@extends('layouts.app')

@section('extraStyle')
    <link rel="stylesheet" href="{{ asset('assets/datatables/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables/css/dataTables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables/css/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="assets/css/sweetalert2.min.css">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            {{-- <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                <div>
                    <h4 class="mb-3 mb-md-0">User List</h4>
                </div>
            </div> --}}
        </div>
    </section>
    <section class="content">


        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Promoter Approval</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Pending List</h6>
                    </div>
                    <div class="card-body">
                        <form id="searchForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="startDate" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="startDate" name="startDate"
                                            value="" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="endDate" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="endDate" name="endDate"
                                            value="" required>
                                    </div>
                                </div>
                                <div class="col-md-6 align-self-center mt-4">
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-primary" id="searchBtn"
                                            onclick="searchData();">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <h6 class="card-title"></h6>
                        <div class="table-responsive">
                            <table id="dataTable" class="table">
                                <!-- table body here -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection


@section('extraScript')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.js') }}"></script>

    <script src="{{ asset('assets/datatables/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/js/dataTables.js') }}"></script>
    <script src="{{ asset('assets/datatables/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/datatables/js/dataTables.buttons.js') }}"></script>
    <script src="{{ asset('assets/datatables/js/buttons.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/datatables/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/datatables/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/js/buttons.colVis.min.js') }}"></script>
    {{-- <script src="assets/js/sweet-alert.js"></script> --}}

    <script src="assets/js/sweetalert2.min.js"></script>
    <script type="text/javascript">
        var dataTable = null;

        function searchData() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            // console.log('start_date=' + startDate + '&end_date=' + endDate);
            // if (!startDate || !endDate) {
            //     alert("Both dates are required!");
            // } else {
            console.log('startDate=' + startDate + '&endDate=' + endDate);
            var dataTable = $('#dataTable').DataTable();

            // Update the DataTable's ajax URL with the new parameters
            dataTable.ajax.url('{{ route('promotersApproval.DataTable') }}?start_date=' +
                startDate + '&end_date=' +
                endDate).load();
            // }
        }


        $(document).ready(function() {
            $('.promoters').addClass('active');

            dataTable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('promotersApproval.DataTable') }}',
                },
                columns: [{
                        title: 'SN', // Title for the serial number column
                        width: 25,
                        render: function(data, type, row, meta) {
                            // meta.row is the index of the row in the current page
                            // meta.settings._iDisplayStart is the index of the first row displayed on the current page
                            // meta.row + meta.settings._iDisplayStart + 1 will give the correct serial number
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, {
                        data: 'created_at',
                        name: 'created_at',
                        title: 'Date',
                        width: 50,
                        render: function(data, type, row, meta) {
                            // Convert the date to d-m-y format
                            const formattedDate = moment(data).format('DD-MM-YYYY h:mm:ss A');
                            return formattedDate;
                        }
                    },
                    
                    

                    {
                    data: 'str_user_id',
                    name: 'str_user_id',
                    title: 'User ID',
                    width: 50,
                    render: function(data, type, row, meta) {
                    if (!data) return 'N/A'; // If no User ID, return N/A

                    // Replace ':userId' in the profile route with the actual user ID
                    var route = profileRoute.replace(':userId', data);

                     //return '<a href="' + route + '" class="text-primary">' + data + '</a>';
                     return '<a href="' + route + '" class="text-primary" target="_blank" rel="noopener noreferrer">' + data + '</a>';
                     }
                    },
                    
                    
                    
                    
                    {
                        data: 'name',
                        name: 'name',
                        title: 'Name',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    }, {
                        data: 'phone',
                        name: 'phone',
                        title: 'Phone',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    }, {
                        data: 'email',
                        name: 'email',
                        title: 'Email',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    }, {
                        data: 'address',
                        name: 'address',
                        title: 'Address',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    }, {
                        data: 'country',
                        name: 'country',
                        title: 'Country',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    },
                    {
                        data: 'apply_for_promoter',
                        name: 'apply_for_promoter',
                        title: 'Status',
                        width: 25,
                        render: function(data, type, row, meta) {
                            if (data == 2) {
                                return '<span class="badge bg-warning">Pending</span>';
                            } else if (data == 1) {
                                return '<span class="badge bg-success">Approved</span>';
                            } else {
                                return '<span class="badge bg-secondary">Unknown</span>';
                            }
                        }
                    },
                    {
                        data: null,
                        name: 'id',
                        title: 'Action',
                        width: 25,
                        render: function(data, type, row, meta) {
                            if (data.apply_for_promoter == 2) {
                                var viewButton =
                                    '<button type="button" class="btn btn-secondary btn-xs approve-btn" data-user_id="' +
                                    data.str_user_id + '">Approval</button>';
                                return viewButton;

                            } else {

                                var viewButton =
                                    '<button type="button" class="btn btn-secondary btn-xs" disabled>Approval</button>';
                            }
                            return viewButton;
                        }
                    }

                ],
                order: [
                    [0, 'desc'] // '0' refers to the first column, 'desc' means descending order
                ],
                dom: 'Blfrtip', // Include the Buttons extension controls
                buttons: [
                    'excel', 'pdf',
                ],
            });



            $('table').on('click', '.view-profile', function(e) {
                e.preventDefault();
                var route = $(this).data('route');
                window.location.href = route;
            });


            $('table').on('click', '.approve-btn', function(e) {
                const thisBtn = $(this);
                const user_id = $(this).data('user_id');
                const _token = $("input[name=_token]").val();
                let approval_value = null;

                console.log(user_id);
                // console.log('clicked');
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger me-2'
                    },
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'me-2',
                    confirmButtonText: 'Yes, approve it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $(thisBtn).prop('disabled', true);
                        approval_value = 1;
                        event.preventDefault();
                        $.ajax({
                            url: "{{ route('promoterApprovalAction') }}", //backend url
                            data: {
                                _token: _token,
                                user_id: user_id,
                                approval_value: approval_value
                            }, //sending form data in a serialize way
                            type: "put",
                            async: false, //hold the next execution until the previous execution complete
                            dataType: 'json',
                            success: function(response) {
                                console.log(response); //error occurs
                                const message = response.message;

                                swalWithBootstrapButtons.fire(
                                    'Success!',
                                    message,
                                    'success'
                                )
                                $(thisBtn).prop('disabled', false);
                            },
                            error: function(response) {
                                console.log(response); //error occurs
                                const message = response.responseJSON.message;

                                swalWithBootstrapButtons.fire(
                                    'Error!',
                                    message,
                                    'error'
                                )
                                $(thisBtn).prop('disabled', false);
                            }
                        });

                        dataTable.ajax.reload();
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        $(thisBtn).prop('disabled', true);
                        approval_value = 0;
                        event.preventDefault();
                        $.ajax({
                            url: "{{ route('promoterApprovalAction') }}", //backend url
                            data: {
                                _token: _token,
                                user_id: user_id,
                                approval_value: approval_value
                            }, //sending form data in a serialize way
                            type: "put",
                            async: false, //hold the next execution until the previous execution complete
                            dataType: 'json',
                            success: function(response) {
                                console.log(response); //error occurs
                                const message = response.message;

                                swalWithBootstrapButtons.fire(
                                    'Cancelled',
                                    message,
                                    'error'
                                )
                                $(thisBtn).prop('disabled', false);
                            },
                            error: function(response) {
                                console.log(response); //error occurs
                                const message = response.responseJSON.message;

                                swalWithBootstrapButtons.fire(
                                    'Error!',
                                    message,
                                    'error'
                                )
                                $(thisBtn).prop('disabled', false);
                            }
                        });

                        dataTable.ajax.reload();
                    }
                })
            });
        });
    </script>

<script>
    var profileRoute = "{{ route('profile', ['userId' => ':userId']) }}";
</script>
@endsection
