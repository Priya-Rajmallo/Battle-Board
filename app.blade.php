<!DOCTYPE HTML>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- style -->
    {{-- <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/jquery-confirm/jquery-confirm.min.css"> --}}


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- Child Page css goes here  -->
    @yield('extraStyle')


    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/core.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr.min.css') }}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flag-icon.min.css') }}">
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/css/materialdesignicons.min.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />




</head>

<body>
    <!-- Preloader Start -->
    {{-- <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="../../assets/img/logo/logo.svg" alt="">
                    <!-- <img src="../../assets/img/logo/logo.gif" alt=""> -->
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Preloader Start -->

    <div class="main-wrapper">
        @include('includes.sidebar')

        <div class="page-wrapper">
            @include('includes.navbar')

            <div class="page-content">

                @include('includes.header')

                @yield('content')

            </div>

            @include('includes.footer')

        </div>
    </div>

</body>

{{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>


<script>
    $('#spanYear').html(new Date().getFullYear());
</script>

<!-- core:js -->
<script src="{{ asset('assets/js/core.js') }}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('assets/js/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<!-- endinject -->

<!-- Custom js for this page -->
<script src="{{ asset('assets/js/dashboard-light.js') }}"></script>
<!-- End custom js for this page -->
{{-- <script src="../../plugins/jquery-confirm/jquery-confirm.min.js"></script> --}}


<!-- Extra js from child page -->
@yield('extraScript')
<!-- END JAVASCRIPT -->



</html>
