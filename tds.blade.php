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
                <li class="breadcrumb-item active" aria-current="page">TDS</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-4">
                <form id="tdsUploadForm">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">TDS Upload</h6>
                        </div>
                        <div class="card-body">

                            <div class="row mb-3">
                                <div id="panNoAlert" class="alert alert-danger" role="alert" style="display: none;">
                                    PAN Number required.
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="userId" class="col-sm-3 col-form-label">Username</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="userId" name="userId" disabled>
                                        <option value="" selected disabled>-- Select --</option>
                                        @foreach ($users as $row)
                                            <option value="{{ $row->str_user_id }}"
                                                data-user_data="{{ json_encode($row) }}">
                                                {{ $row->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="panNo" class="col-sm-3 col-form-label">PAN No</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="panNo" name="panNo"
                                        autocomplete="off" maxlength="10" placeholder="Pan Number" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tdsDate" class="col-sm-3 col-form-label">Date</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" id="tdsDate" name="tdsDate"
                                        autocomplete="off" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tdsFile" class="col-sm-3 col-form-label">TDS File</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control" id="tdsFile" name="tdsFile" accept=".pdf"
                                        required>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" id="uploadBtn" class="btn btn-primary me-2 tds-btn"
                                disabled>Upload</button>
                            <button class="btn btn-secondary tds-btn" disabled>Reset</button>
                        </div>
                    </div>
                </form>
            </div>


            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">TDS List</h6>
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
            dataTable.ajax.url('{{ route('tds.DataTable') }}?start_date=' +
                startDate + '&end_date=' +
                endDate).load();
            // }
        }


        $(document).ready(function() {
            $('.tds').addClass('active');
            $('#userId').prop('disabled', false);

            dataTable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('tds.DataTable') }}',
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
                        data: 'date',
                        name: 'date',
                        title: 'Date',
                        width: 50,
                        render: function(data, type, row, meta) {
                            // Convert the date to d-m-y format
                            const formattedDate = moment(data).format('DD-MM-YYYY');
                            return formattedDate;
                        }
                    }, 
                    
                    
                    {
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
                        data: null,
                        name: 'name',
                        title: 'Name',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data.user.name == "" ? 'N/A' : data.user.name;

                        }
                    },
                    {
                        data: null,
                        name: 'id',
                        title: 'Action',
                        width: 25,
                        render: function(data, type, row, meta) {
                            var pdfLink = '/public/pdf/tds/' + data.tds_file;
                            var viewLink = '<a class="btn btn-success btn-xs view-user" href="' +
                                pdfLink + '" target="_blank">View</a>';
                            return viewLink;
                        }
                    }

                ],
                order: [
                    [1, 'desc'] // '0' refers to the first column, 'desc' means descending order
                ],
                dom: 'Blfrtip', // Include the Buttons extension controls
                buttons: [
                    'excel', 'pdf',
                ],
            });

            $('table').on('click', '.view-btn', function(e) {
                e.preventDefault();
                var route = $(this).data('route');
                window.location.href = route;
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
        <script>
    var profileRoute = "{{ route('profile', ['userId' => ':userId']) }}";
</script>
@endsection
