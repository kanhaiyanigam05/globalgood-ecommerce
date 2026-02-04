<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('admins/images/logo/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('admins/images/logo/favicon.png') }}" type="image/x-icon">
    <title>Vendor Portal - {{ config('app.name') }}</title>
    <!-- Animation css -->
    <link rel="stylesheet" href="{{ asset('admins/vendor/animation/animate.min.css') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com/" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">

    <!-- phosphor-icon css-->
    <link href="{{ asset('admins/vendor/phosphor/phosphor-bold.css') }}" rel="stylesheet">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/vendor/bootstrap/bootstrap.min.css') }}">

    <!-- Simplebar css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/vendor/simplebar/simplebar.css') }}">

    <!--font-awesome-css-->
    <link rel="stylesheet" href="{{ asset('admins/vendor/fontawesome/css/all.css') }}">

    @stack('styles:before')
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/css/style.css') }}">

    <!-- Responsive css  -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admins/css/responsive.css') }}">
    <!-- ToastMagic styles if available -->
    @if(class_exists('\Devrabiul\ToastMagic\Facades\ToastMagic'))
    {!! \Devrabiul\ToastMagic\Facades\ToastMagic::styles() !!}
    @endif
    @stack('styles:after')
</head>

<body>
    <div class="app-wrapper">
        <div class="loader-wrapper">
            <div class="app-loader">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        @include('vendor.layouts.sidebar')

        <div class="app-content">
            <!-- Header Section starts -->
            <header class="header-main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 d-flex align-items-center justify-content-end header-right p-0">
                            <ul class="d-flex align-items-center">
                                <li class="header-dark">
                                    <div class="sun-logo head-icon bg-light-dark rounded-circle f-s-22 p-2">
                                        <i class="ph ph-moon-stars"></i>
                                    </div>
                                    <div class="moon-logo head-icon bg-light-dark rounded-circle f-s-22 p-2">
                                        <i class="ph ph-sun-dim"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Header Section ends -->

            <main>
                @yield('content')
            </main>

        </div>

        <div class="go-top">
            <span class="progress-value">
                <i class="ti ti-arrow-up"></i>
            </span>
        </div>

        <footer>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9 col-12">
                        <p class="footer-text f-w-600 mb-0">Copyright © {{ date('Y') }}. All rights reserved
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!--customizer-->
    <div id="customizer"></div>

    <!-- latest jquery-->
    <script src="{{ asset('admins/js/jquery-3.6.3.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('admins/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- Simple bar js-->
    <script src="{{ asset('admins/vendor/simplebar/simplebar.js') }}"></script>

    <!-- phosphor icon -->
    <script src="{{ asset('admins/vendor/phosphor/phosphor.js') }}"></script>

    <!-- Customizer js-->
    <script src="{{ asset('admins/js/customizer.js') }}"></script>
    @stack('scripts:before')
    <!-- App js-->
    <script src="{{ asset('admins/js/script.js') }}"></script>
    
    @if(class_exists('\Devrabiul\ToastMagic\Facades\ToastMagic'))
    {!! \Devrabiul\ToastMagic\Facades\ToastMagic::scripts() !!}
    <script>
        window.toast = new ToastMagic();
        @if (session('success'))
            window.toast.success('{{ session('success') }}');
        @endif
        @if (session('error'))
            window.toast.error('{{ session('error') }}');
        @endif
        @if (session('warning'))
            window.toast.warning('{{ session('warning') }}');
        @endif
        @if (session('info'))
            window.toast.info('{{ session('info') }}');
        @endif
    </script>
    @endif
    @stack('scripts:after')
</body>

</html>
