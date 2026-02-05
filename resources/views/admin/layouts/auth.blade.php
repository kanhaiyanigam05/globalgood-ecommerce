<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('admins/images/logo/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('admins/images/logo/favicon.png') }}" type="image/x-icon">

    <title>{{ config('app.name') }} - Authentication page</title>


    <!-- Animation css -->
    <link rel="stylesheet" href="{{ asset('admins/vendor/animation/animate.min.css') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com/" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&amp;display=swap"
        rel="stylesheet">

    <!-- Weather icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/vendor/weather/weather-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/vendor/weather/weather-icons-wind.css') }}">

    <!--font-awesome-css-->
    <link rel="stylesheet" href="{{ asset('admins/vendor/fontawesome/css/all.css') }}">

    <!--Flag Icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/vendor/flag-icons-master/flag-icon.css') }}">

    <!-- Tabler icons-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/vendor/tabler-icons/tabler-icons.css') }}">

    <!-- Prism css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/vendor/prism/prism.min.css') }}">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/vendor/bootstrap/bootstrap.min.css') }}">

    <!-- Simplebar css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/vendor/simplebar/simplebar.css') }}">

    <!-- phosphor-icon css-->
    <link href="{{ asset('admins/vendor/phosphor/phosphor-bold.css') }}" rel="stylesheet">

    <!--css & Plugins-->

    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/css/style.css') }}">

    <!-- Responsive css  -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/css/responsive.css') }}">
    @stack('styles:after')
</head>

<body class="sign-in-bg">
    <div class="app-wrapper d-block">
        <div class="">
            <!-- Body main section starts -->
            <div class="container main-container">
                <div class="row main-content-box">
                    @yield('content')
                </div>
            </div>
            <!-- Body main section ends -->
        </div>
    </div>
    <!--jquery-->
    <script src="{{ asset('admins/js/jquery-3.6.3.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('admins/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts:after')
</body>

</html>
