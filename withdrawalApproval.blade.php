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
                <li class="breadcrumb-item active" aria-current="page">Withdrawal Approval</li>
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
                                    <div class="md-3">
                                        <button type="button" class="btn btn-primary" id="searchBtn"
                                            onclick="searchData();" style="display: none;">Search</button>
                                        <button type="button" class="btn btn-danger" id="withdrawalBtn"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            style="display: none;">Withdrawal</button>
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


    <!-- Modal with Form -->
    <form id="withdrawalForm" class="forms-sample">
        @csrf
        {{-- <input type="hidden" name="walletId" value="{{ $wallet->id }}"> --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Wallet Withdrawal </h5>
                        <button type="button" class="btn-close model-close" data-bs-dismiss="modal"
                            aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="userId" class="form-label">User</label><span class="text-danger">*</span>
                                    <select class="form-control" id="userId" name="userId" required>
                                        <option value="" selected disabled>--Select--</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->str_user_id }}" data-data="{{ $user }}">
                                                {{ $user->str_user_id }}( {{ $user->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="walletSection" class="form-label">Wallet</label><span
                                        class="text-danger">*</span>
                                    <select class="form-control" id="walletSection" name="walletSection" required>
                                        <option value="" selected disabled>--Select--</option>
                                        {{-- <option value="Bonus">Bonus</option> --}}
                                        <option value="Deposit">Deposit</option>
                                        <option value="Earn">Earn</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="transaction-body" style="display:none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="withdrawalDate" class="form-label">Date</label><span
                                            class="text-danger">*</span>
                                        <input type="date" class="form-control" id="withdrawalDate"
                                            name="withdrawalDate" placeholder="Withdrawal Date" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="coin" class="form-label">Coin</label><span
                                            class="text-danger">*</span>
                                        <input type="text" class="form-control" id="coin" name="coin"
                                            placeholder="Withdrawal Coin" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="transaction_method" class="form-label">Transaction Method</label><span
                                            class="text-danger">*</span>
                                        <input type="text" class="form-control" id="transaction_method"
                                            name="transaction_method" maxlength="255"
                                            placeholder="Enter Transaction Method" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="transaction_ref" class="form-label">Transaction Reference</label>
                                        <input type="text" class="form-control" id="transaction_ref"
                                            name="transaction_ref" placeholder="Enter Transaction Reference">
                                    </div>
                                </div>
                            </div>
                          <div class="row">
                                <!-- Generate TDS Invoice -->
                                <div class="col-md-6">
                                    <label for="btnGroupInvoice" class="form-label">Generate TDS Invoice</label><span class="text-danger">*</span>
                                    <div class="mb-3">
                                        <div class="btn-group d-flex" role="group" aria-label="Generate TDS Invoice">
                                            <input type="radio" class="btn-check" name="GenerateTdsInvoice" id="btnradio1" autocomplete="off" value="yes">
                                            <label class="btn btn-outline-primary w-50" for="btnradio1">Yes</label>

                                            <input type="radio" class="btn-check" name="GenerateTdsInvoice" id="btnradio2" autocomplete="off" value="no" checked>
                                            <label class="btn btn-outline-primary w-50" for="btnradio2">No</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- TDS Deduction -->
                                <div class="col-md-6">
                                    <label for="btnGroupDeduction" class="form-label">TDS Deduction</label><span class="text-danger">*</span>
                                    <div class="mb-3">
                                        <div class="btn-group d-flex" role="group" aria-label="TDS Deduction">
                                            <input type="radio" class="btn-check" name="TdsDeduction" id="btnDeductionYes" autocomplete="off" value="yes">
                                            <label class="btn btn-outline-primary w-50" for="btnDeductionYes">Yes</label>

                                            <input type="radio" class="btn-check" name="TdsDeduction" id="btnDeductionNo" autocomplete="off" value="no" checked>
                                            <label class="btn btn-outline-primary w-50" for="btnDeductionNo">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>


                        <div id="error-message" class="alert alert-danger" role="alert" style="display: none;">

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary model-close"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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


    <script src="{{ asset('assets/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>


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
            dataTable.ajax.url('{{ route('withdrawalsApproval.DataTable') }}?start_date=' +
                startDate + '&end_date=' +
                endDate).load();
            // }
        }


        $(document).ready(function() {
            $('.withdrawal-approval').addClass('active');
            $('#searchBtn').show();
            $('#withdrawalBtn').show();

            dataTable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('withdrawalsApproval.DataTable') }}',
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
                    }, {
                        data: 'user_id',
                        name: 'user_id',
                        title: 'User Id',
                        width: 50,
                        render: function(data, type, row, meta) {

                            var viewLink =
                                '<a class="view-link view-profile" href="#" data-route="{{ route('profile', ['userId' => ':data']) }}">' +
                                data + '</a>';
                            viewLink = viewLink.replace(':data', data);
                            return viewLink;

                            // return data == "" ? 'N/A' : data;

                        }
                    }, {
                        data: 'name',
                        name: 'name',
                        title: 'Name',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    }, {
                        data: 'inr',
                        name: 'inr',
                        title: 'inr',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    },
                    {
                        data: 'coin',
                        name: 'coin',
                        title: 'coin',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    },
                    {
                        data: 'wallet_section',
                        name: 'wallet',
                        title: 'Wallet',
                        width: 50,
                        render: function(data, type, row, meta) {
                            return data == "" ? 'N/A' : data;

                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        title: 'Status',
                        width: 25,
                        render: function(data, type, row, meta) {
                            if (data == 2) {
                                return '<span class="badge bg-warning">Pending</span>';
                            } else if (data == 1) {
                                return '<span class="badge bg-success">Approved</span>';
                            } else if (data == 0) {
                                return '<span class="badge bg-danger">Rejected</span>';
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
                            if (data.status == 2) {
                                var viewButton =
                                    '<button type="button" class="btn btn-secondary btn-xs approve-btn" data-user_id="' +
                                    data.str_user_id + '" data-withdrawal_id="' +
                                    data.id + '">Approval</button>';
                                return viewButton;
                            } else {

                                var deleteBtn =
                                    '<button class="btn btn-danger btn-xs delete-btn" data-data=\'' +
                                    JSON.stringify(data) + '\'>Delete</button>';
                                return deleteBtn;
                            }
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



            $('table').on('click', '.delete-btn', function(e) {
                e.preventDefault();
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


            $('table').on('click', '.approve-btn', function(e) {
                const thisBtn = $(this);
                const user_id = $(this).data('user_id');
                const withdrawal_id = $(this).data('withdrawal_id');
                const _token = $("input[name=_token]").val();
                let approval_value = null;

                // console.log(user_id);
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
                            url: "{{ route('withdrawalApprovalAction') }}", //backend url
                            data: {
                                _token: _token,
                                withdrawal_id: withdrawal_id,
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
                            url: "{{ route('withdrawalApprovalAction') }}", //backend url
                            data: {
                                _token: _token,
                                withdrawal_id: withdrawal_id,
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


            // When the button is clicked, toggle the modal display
            $('#withdrawalBtn').on('click', function(e) {
                $(this).attr('disabled', true);
            });

            // When the modal close button is clicked, hide the modal and add the class
            $(document).on('click', '.model-close', function() {
                console.log('Clicked');
                $('#withdrawalBtn').attr('disabled', false);
            });

            // Initialize form validation on the withdrawalForm form.
            $.validator.setDefaults({
                submitHandler: function() {
                    $('#submitButton').prop('disabled', true);

                    // event.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.wallet-withdrawal') }}", //backend url
                        data: $("#withdrawalForm")
                            .serialize(), //sending form data in a serialize way
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
                                    location.reload();
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
                        }
                    });

                }
            });
            $(function() {
                // validate signup form on keyup and submit
                $("#withdrawalForm").validate({
                    rules: {
                        userId: {
                            required: true,
                        },
                        walletSection: {
                            required: true,
                        },
                        withdrawalDate: {
                            required: true,
                            date: true,
                        },
                        coin: {
                            required: true,
                            digits: true,
                        },
                        transaction_method: {
                            required: true,
                            maxlength: 255
                        },
                        transaction_ref: {
                            required: false,
                            maxlength: 255
                        },

                    },
                    messages: {
                        withdrawalDate: {
                            required: "Please enter withdrawal date",
                            date: "Please enter valid date"
                        },
                        coin: {
                            required: "Please enter withdrawal coin",
                            digits: "Please enter a only number",
                        },
                    },
                    errorPlacement: function(error, element) {
                        error.addClass("invalid-feedback");

                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if (element.prop('type') === 'radio' && element.parent(
                                '.radio-inline').length) {
                            error.insertAfter(element.parent().parent());
                        } else if (element.prop('type') === 'checkbox' || element.prop(
                                'type') === 'radio') {
                            error.appendTo(element.parent().parent());
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    highlight: function(element, errorClass) {
                        if ($(element).prop('type') != 'checkbox' && $(element).prop('type') !=
                            'radio') {
                            $(element).addClass("is-invalid").removeClass("is-valid");
                        }
                    },
                    unhighlight: function(element, errorClass) {
                        if ($(element).prop('type') != 'checkbox' && $(element).prop('type') !=
                            'radio') {
                            $(element).addClass("is-valid").removeClass("is-invalid");
                        }
                    }
                });
            });

            let walletData = {};

            // Update wallet options and walletData when a user is selected
            $('#userId').on('change', function() {
                const userData = $(this).find('option:selected').data('data');

                if (userData && userData.wallet) {
                    walletData = userData.wallet;

                    // Update wallet options with coin values
                    $('#walletSection option').each(function() {
                        const optionValue = $(this).val().toLowerCase();
                        let coins = '';

                        switch (optionValue) {
                            // case 'bonus':
                            //     coins = walletData.bonus;
                            //     break;
                            case 'deposit':
                                coins = walletData.deposit;
                                break;
                            case 'earn':
                                coins = walletData.earn;
                                break;
                        }

                        if (coins !== '') {
                            $(this).text(
                                `${optionValue.charAt(0).toUpperCase() + optionValue.slice(1)} (${coins} coins)`
                            );
                            $('#walletSection option:first').prop("selected", true);
                        }

                        // Show the transaction body if coins are available, else hide it
                        if (coins > 0) {
                            $('.transaction-body').show();
                            $('.modal-footer').show();
                            $('#error-message').hide();
                        } else {
                            $('.transaction-body').hide();
                            $('.modal-footer').hide();
                            $('#error-message').text('Select wallet for transaction.').show();
                        }
                    });
                }
            });

            // Show or hide the transaction body when a wallet section is selected
            $('#walletSection').on('change', function() {
                const selectedWallet = $(this).val().toLowerCase();
                let coins = 0;

                switch (selectedWallet) {
                    // case 'bonus':
                    //     coins = walletData.bonus;
                    //     break;
                    case 'deposit':
                        coins = walletData.deposit;
                        break;
                    case 'earn':
                        coins = walletData.earn;
                        break;
                }

                // Show the transaction body if coins are available, else hide it
                if (coins > 0) {
                    $('.transaction-body').show();
                    $('.modal-footer').show();
                    $('#error-message').hide();
                } else {
                    $('.transaction-body').hide();
                    $('.modal-footer').hide();
                    $('#error-message').text('Not enough coins for transaction.').show();
                }
            });

            // Validate coin amount and toggle modal footer and error message
            $('#coin').on('input', function() {
                const enteredCoin = parseFloat($(this).val());
                const selectedWallet = $('#walletSection').val().toLowerCase();
                let availableCoins = 0;

                switch (selectedWallet) {
                    // case 'bonus':
                    //     availableCoins = walletData.bonus;
                    //     break;
                    case 'deposit':
                        availableCoins = walletData.deposit;
                        break;
                    case 'earn':
                        availableCoins = walletData.earn;
                        break;
                }

                if (enteredCoin > availableCoins) {
                    // Hide the footer and display an error message
                    $('.modal-footer').hide();
                    $('#error-message').text('The entered coin amount exceeds the available coins.').show();
                } else {
                    // Show the footer and hide the error message
                    $('.modal-footer').show();
                    $('#error-message').hide();
                }
            });

        });
    </script>
@endsection
