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
                <li class="breadcrumb-item active" aria-current="page">GST</li>
            </ol>
        </nav>

        <div class="row">
          


            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">GST List</h6>
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

    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>


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
            dataTable.ajax.url('{{ route('gst.DataTable') }}?start_date=' +
                startDate + '&end_date=' +
                endDate).load();
            // }
        }


        $(document).ready(function() {
            $('.gst').addClass('active');
            $('#userId').prop('disabled', false);

            dataTable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('gst.DataTable') }}',
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
                        data: 'transaction.transaction_date',
                        name: 'transaction.transaction_date',
                        title: 'date',
                        width: 50,
                        render: function(data, type, row, meta) {
                            // Convert the date to d-m-y format
                            const formattedDate = moment(data).format('DD-MM-YYYY');
                            return formattedDate;
                        }
                    }, {
                        data: 'transaction.transaction_id',
                        name: 'transaction.transaction_id',
                        title: 'Transaction ID',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    }, {
                        data: 'transaction.transaction_type',
                        name: 'transaction.transaction_type',
                        title: 'Type',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    }, {
                        data: 'transaction.transaction_details',
                        name: 'transaction.transaction_details',
                        title: 'Details',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;
                        }
                    }, {
                        data: 'transaction.transaction_coin',
                        name: 'transaction.transaction_coin',
                        title: 'Coin',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        title: 'Total',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    },
                    {
                        data: 'gst_amount',
                        name: 'gst_amount',
                        title: 'GST',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    },
                    {
                        data: 'transaction.transaction_amount',
                        name: 'transaction.transaction_amount',
                        title: 'INR',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    },
                    {
                        data: 'transaction.transaction_status',
                        name: 'transaction.transaction_status',
                        title: 'Status',
                        width: 25,
                        render: function(data, type, row, meta) {
                            if (data == 0) {
                                return '<span class="badge bg-danger">Error</span>';
                            } else if (data == 1) {
                                return '<span class="badge bg-success">Success</span>';
                            } else {
                                return '<span class="badge bg-secondary">Unknown</span>';
                            }
                        }
                    },
                    {
                        data: null,
                        name: 'transaction.transaction.id',
                        title: 'Action',
                        width: 25,
                        render: function(data, type, row, meta) {

                            var viewBtn =
                                '<a class="btn btn-primary btn-xs view-btn" href="#" data-route="{{ route('gst.invoice', ['transactionId' => ':data']) }}">View</a>';
                            viewBtn = viewBtn.replace(':data', data.transaction_id);


                            // var viewBtn =
                            //     '<button class="btn btn-primary btn-xs view-btn" data-transaction_id="' +
                            //     data
                            //     .transaction_id + '" data-data=\'' +
                            //     JSON.stringify(data) + '\'>View</button>';

                            var deleteBtn =
                                '<button class="btn btn-danger btn-xs delete-btn" data-data=\'' +
                                JSON.stringify(data) + '\'>Delete</button>';


                            return viewBtn + ' ' + deleteBtn;

                        }
                    }

                ],
                order: [
                    [0, 'desc'] // '0' refers to the first column, 'desc' means descending order
                ]
            });

       

            $('table').on('click', '.view-btn', function(e) {
                e.preventDefault();
                var route = $(this).data('route');
                // window.location.href = route;
                window.open(route, '_blank'); // Open in a new tab/window

            });


            $('table').on('click', '.delete-btn', function(e) {
                e.preventDefault();

                // Show confirmation dialog
                const isConfirmed = confirm('Are you sure you want to delete this?');

                if (!isConfirmed) {
                    return; // Exit if the user does not confirm
                }

                const data = $(this).data('data');
                console.log(data);
                const transactionId = data.transaction_id;

                $('.delete-btn').prop('disabled', true);

                $.ajax({
                    url: "{{ route('admin.delete.transaction') }}", //backend url
                    data: {
                        _token: '{{ csrf_token() }}',
                        transactionId: transactionId,
                    },
                    type: "post",
                    async: true, //hold the next execution until the previous execution complete
                    dataType: 'json',
                    success: function(response) {
                        console.log(response); //error occurs
                        Swal.fire({
                            title: response.message,
                            // text: '',
                            icon: "success",
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                dataTable.ajax.reload();
                            }
                        });
                    },
                    error: function(response) {
                        $('#submitButton').prop('disabled', false);
                        console.log(response); //error occurs
                        // console.log(response.responseJSON.errors); //error occurs
                        Swal.fire({
                            title: response.responseJSON.errors,
                            // text: '',
                            icon: "danger",
                            allowOutsideClick: false
                        });
                    },
                    always: function(response) {
                        $('.delete-btn').prop('disabled', false);
                    }
                });

            });

            $(document).on('submit', '#tdsUploadForm', function(event) {

                $('#uploadBtn').prop('disabled', true);
                event.preventDefault();

                // Create a FormData object
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.tds.store') }}", // backend url
                    data: formData,
                    type: "post",
                    processData: false, // Prevent jQuery from automatically transforming the data into a query string
                    contentType: false, // Prevent jQuery from overriding the Content-Type header
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val() // Include the CSRF token
                    },
                    success: function(response) {
                        console.log(response);

                        Swal.fire({
                            title: response.message,
                            // text: '',
                            icon: "success",
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });

                    },
                    error: function(response) {
                        $('#uploadBtn').prop('disabled', false);
                        console.log(response); //error occurs
                        Swal.fire({
                            title: response.responseJSON.errors,
                            // text: '',
                            icon: "danger",
                            allowOutsideClick: false
                        });
                    }
                });
            });

            $(document).on('change', '#userId', function() {
                var selectedOption = $(this).find('option:selected');
                var userData = selectedOption.data('user_data');

                if (userData.pan_no) {
                    $('#panNo').val(userData.pan_no);
                    $('.tds-btn').prop('disabled', false);
                    $('#panNoAlert').hide();
                } else {
                    $('#panNo').val('');
                    $('.tds-btn').prop('disabled', true);
                    $('#panNoAlert').show();
                }
            });



            $(document).on('keyup', '.onlyNumberAllowed', function() {

                var inputValue = $(this).val();

                // Remove non-numeric characters using a regular expression
                var numericValue = inputValue.replace(/[^\d.]/g, '');

                // Update the input field with the numeric value
                $(this).val(numericValue);

                // If the numeric value is empty, don't perform further actions
                if (numericValue === '') {
                    return;
                }
            });


        });
    </script>
@endsection
