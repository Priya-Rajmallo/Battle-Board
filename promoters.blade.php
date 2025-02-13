@extends('layouts.app')

@section('extraStyle')
    <link rel="stylesheet" href="{{ asset('assets/datatables/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables/css/dataTables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables/css/buttons.bootstrap5.css') }}">
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
                <li class="breadcrumb-item"><a href="{{ route('promoters') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Promoter</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Promoter List</h6>
                    </div>
                    <div class="card-body">
                        <form id="searchForm">
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


        <!-- Modal -->
        <div class="modal fade" id="bankModel" tabindex="-1" aria-labelledby="bankModelLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bankModelLabel">Bank Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-center" id="BankDetailsNotAvailable" style="display: none;">
                            <h3>
                                Bank details not available.
                            </h3>
                        </div>
                        <div class="row p-4" id="BankDetailsAvailable" style="display: none;">

                            <h4 class="text-center mb-2"><span id="bankName"></span></h4>
                            <table class="align-items-center">
                                <tbody>
                                    <tr>
                                        <td>
                                            <i class="mdi mdi-account"></i>
                                        </td>
                                        <td>
                                            <p> Account Holder:
                                                <strong><span id="AccountHolder"></span></strong>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi mdi-bank"></i>
                                        </td>
                                        <td>
                                            <p> Account Number:
                                                <strong><span id="AccountNumber"></span></strong>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi mdi-barcode"></i>
                                        </td>
                                        <td>
                                            <p> IFSC Code: <strong><span id="IFSC"></span></strong></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi mdi-radiobox-marked"></i>
                                        </td>
                                        <td>
                                            <p> Status:
                                                <strong><span id="bankStatus"></span></strong>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
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
            dataTable.ajax.url('{{ route('promoters.DataTable') }}?start_date=' +
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
                    url: '{{ route('promoters.DataTable') }}',
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
                        title: 'date',
                        width: 50,
                        render: function(data, type, row, meta) {
                            // Convert the date to d-m-y format
                            const formattedDate = moment(data).format('DD-MM-YYYY');
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
                            return data ?? 'N/A';

                        }
                    }, {
                        data: 'phone',
                        name: 'phone',
                        title: 'Phone',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data ?? 'N/A';

                        }
                    }, {
                        data: 'email',
                        name: 'email',
                        title: 'Email',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data ?? 'N/A';

                        }
                    }, {
                        data: 'address',
                        name: 'address',
                        title: 'Address',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data ?? 'N/A';

                        }
                    }, {
                        data: 'country',
                        name: 'country',
                        title: 'Country',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data ?? 'N/A';

                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        title: 'Status',
                        width: 25,
                        render: function(data, type, row, meta) {
                            if (data == 0) {
                                return '<span class="badge bg-danger">Inactive</span>';
                            } else if (data == 1) {
                                return '<span class="badge bg-success">Active</span>';
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

                            var viewLink =
                                '<a class="btn btn-primary btn-xs view-user" href="#" data-route="{{ route('profile', ['userId' => ':data']) }}">View</a>';
                            viewLink = viewLink.replace(':data', data.str_user_id);

                            // var bankModel =
                            //     '<button class="btn btn-primary btn-xs bank-details" data-data=\'' +
                            //     JSON.stringify(data) + '\'>Bank Details</button>';

                            var changeRole =
                                '<button class="btn btn-danger btn-xs downgrade-btn" data-data=\'' +
                                JSON.stringify(data) + '\'>Downgrade</button>';


                            return viewLink + ' ' + changeRole;

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

            $('table').on('click', '.view-user', function(e) {
                e.preventDefault();
                var route = $(this).data('route');
                window.location.href = route;
            });


            $('table').on('click', '.bank-details', function(e) {
                e.preventDefault();
                const data = $(this).data('data');
                // console.log(data);

                $('#bankModel').modal('show');

                if (data.account_number > 0) {

                    $('#BankDetailsNotAvailable').hide();
                    $('#BankDetailsAvailable').show();

                    $('#bankName').text(data.bank_name ?? 'N/A');
                    $('#AccountHolder').text(data.account_name ?? 'N/A');
                    $('#AccountNumber').text(data.account_number ?? 'N/A');
                    $('#IFSC').text(data.ifsc_code ?? 'N/A');
                    $('#bankStatus').text(data.bank_status == 1 ? 'Active' : 'Inactive');

                } else {

                    $('#BankDetailsAvailable').hide();
                    $('#BankDetailsNotAvailable').show();

                }

            });

            $('table').on('click', '.downgrade-btn', function(e) {
                e.preventDefault();
                const data = $(this).data('data');

                $('.downgrade-btn').prop('disabled', true);

                if (confirm('Are you sure you want to downgrade this promoter to normal user?')) {
                    console.log(data);
                    $.ajax({
                        url: '{{ route('promoter.downgrade') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: 1,
                            userData: data
                        },
                        success: function(response) {
                            console.log(response);
                            alert(response.message ?? 'N/A');
                            $('.downgrade-btn').prop('disabled', false);
                            dataTable.ajax.reload();
                        },
                        error: function(error) {
                            console.log(error);
                            $('.downgrade-btn').prop('disabled', false);
                        }
                    });
                } else {
                    // Action was cancelled by the user
                    console.log('Action was cancelled');
                    $('.downgrade-btn').prop('disabled', false);
                }
            });

        });
    </script>

<script>
    var profileRoute = "{{ route('profile', ['userId' => ':userId']) }}";
</script>
@endsection
