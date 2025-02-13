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
                <li class="breadcrumb-item"><a href="{{ route('admin.Dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Feedback</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Feedback</h6>
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
            dataTable.ajax.url('{{ route('game.feedback.DataTable') }}?start_date=' +
                startDate + '&end_date=' +
                endDate).load();
            // }
        }


        $(document).ready(function() {

            $('.game-feedback').addClass('active');


            dataTable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('game.feedback.DataTable') }}',
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
                        title: 'created_at',
                        width: 50,
                        render: function(data, type, row, meta) {
                            // Convert the date to d-m-y format
                            // console.log(data);
                            const formattedDate = moment(data).format('DD-MM-YYYY');

                            return formattedDate;
                        }
                    },  {
                    data: 'user_id',
                    name: 'user_id',
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
                        data: 'user.name',
                        name: 'user.name',
                        title: 'User Name',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data ?? 'N/A';

                        }
                    }, {
                        data: 'message',
                        name: 'message',
                        title: 'message',
                        width: 100,
                        render: function(data, type, row, meta) {
                            return data ?? 'N/A';

                        }
                    },
                    {
                        data: null,
                        name: 'id',
                        title: 'Action',
                        width: 25,
                        render: function(data, type, row, meta) {

                            var viewLink =
                                '<a class="btn btn-primary btn-xs view-user" href="#" data-route="{{ route('admin.game.feedback.view', ['feedbackId' => ':data']) }}">View</a>';
                            viewLink = viewLink.replace(':data', data.id);

                            // var bankModel =
                            //     '<button class="btn btn-primary btn-xs bank-details" data-data=\'' +
                            //     JSON.stringify(data) + '\'>Bank Details</button>';


                            return viewLink;

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
        });
    </script>

<script>
    var profileRoute = "{{ route('profile', ['userId' => ':userId']) }}";
</script>
@endsection
