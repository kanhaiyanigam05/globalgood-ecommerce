<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset($data->site_favicon) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset($data->site_favicon) }}" type="image/x-icon">
    <title>Admin - {{ $data->site_name }}</title>
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
    {!! \Devrabiul\ToastMagic\Facades\ToastMagic::styles() !!}
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
        @include('admin.layouts.sidebar')

        <div class="app-content">
            <!-- Header Section starts -->
            <header class="header-main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-8 col-sm-6 d-flex align-items-center header-left p-0">
                            <span class="header-toggle ">
                                <i class="ph ph-squares-four"></i>
                            </span>

                            <div class="header-searchbar w-100">
                                <form action="#" class="mx-sm-3 app-form app-icon-form ">
                                    <div class="position-relative">
                                        <input aria-label="Search" class="form-control" placeholder="Search..."
                                            type="search">
                                        <i class="ti ti-search text-dark"></i>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-4 col-sm-6 d-flex align-items-center justify-content-end header-right p-0">

                            <ul class="d-flex align-items-center">

                                <li class="header-dark">
                                    <div class="sun-logo head-icon bg-light-dark rounded-circle f-s-22 p-2">
                                        <i class="ph ph-moon-stars"></i>
                                    </div>
                                    <div class="moon-logo head-icon bg-light-dark rounded-circle f-s-22 p-2">
                                        <i class="ph ph-sun-dim"></i>
                                    </div>
                                </li>

                                <li class="header-notification">
                                    <a aria-controls="notificationcanvasRight"
                                        class="d-block head-icon position-relative bg-light-dark rounded-circle f-s-22 p-2"
                                        data-bs-target="#notificationcanvasRight" data-bs-toggle="offcanvas"
                                        href="#" role="button">
                                        <i class="ph ph-bell"></i>
                                        <span
                                            class="position-absolute translate-middle p-1 bg-primary border border-light rounded-circle animate__animated animate__fadeIn animate__infinite animate__slower"></span>
                                    </a>
                                    <div aria-labelledby="notificationcanvasRightLabel"
                                        class="offcanvas offcanvas-end header-notification-canvas"
                                        id="notificationcanvasRight" tabindex="-1">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title" id="notificationcanvasRightLabel">
                                                Notification</h5>
                                            <button aria-label="Close" class="btn-close" data-bs-dismiss="offcanvas"
                                                type="button"></button>
                                        </div>
                                        <div class="offcanvas-body app-scroll p-0">
                                            <div class="head-container">
                                                {{-- <div class="notification-message head-box">

                                                    <div class="message-content-box flex-grow-1 pe-2">

                                                        <a class="f-s-15 text-dark mb-0" href="read-email.html"><span
                                                                class="f-w-500 text-dark">Gene Hart</span> wants to
                                                            edit <span class="f-w-500 text-dark">Report.doc</span></a>
                                                        <div>
                                                            <a class="d-inline-block f-w-500 text-success me-1"
                                                                href="#">Approve</a>
                                                            <a class="d-inline-block f-w-500 text-danger"
                                                                href="#">Deny</a>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <i class="ph ph-trash f-s-18 text-danger close-btn"></i>
                                                        <div>
                                                            <span class="badge text-light-primary"> sep 23 </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="notification-message head-box">

                                                    <div class="message-content-box flex-grow-1 pe-2">
                                                        <a class="f-s-15 text-dark mb-0" href="read-email.html">Hey
                                                            <span class="f-w-500 text-dark">Emery McKenzie</span>,
                                                            get ready: Your order from <span
                                                                class="f-w-500 text-dark">@Shopper.com</span></a>
                                                    </div>
                                                    <div class="text-end">
                                                        <i class="ph ph-trash f-s-18 text-danger close-btn"></i>
                                                        <div>
                                                            <span class="badge text-light-primary"> sep 23 </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="notification-message head-box">
                                                    <div class="message-content-box flex-grow-1 pe-2">
                                                        <a class="f-s-15 text-dark mb-0" href="read-email.html"><span
                                                                class="f-w-500 text-dark">Simon Young</span> shared
                                                            a file called <span
                                                                class="f-w-500 text-dark">Dropdown.pdf</span></a>
                                                    </div>
                                                    <div class="text-end">
                                                        <i class="ph ph-trash f-s-18 text-danger close-btn"></i>
                                                        <div>
                                                            <span class="badge text-light-primary"> 30 min</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="notification-message head-box">
                                                    <div class="message-content-box flex-grow-1 pe-2">
                                                        <a class="f-s-15 text-dark mb-0" href="read-email.html"><span
                                                                class="f-w-500 text-dark">Becky G. Hayes</span> has
                                                            added a comment to <span
                                                                class="f-w-500 text-dark">Final_Report.pdf</span></a>
                                                    </div>
                                                    <div class="text-end">
                                                        <i class="ph ph-trash f-s-18 text-danger close-btn"></i>
                                                        <div>
                                                            <span class="badge text-light-primary"> 45 min</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="notification-message head-box">
                                                    <div class="message-content-box  flex-grow-1 pe-2">
                                                        <a class="f-s-15 text-dark mb-0 " href="read-email.html"><span
                                                                class="f-w-600 text-dark">@Romaine</span>
                                                            invited you to a meeting
                                                        </a>
                                                        <div>
                                                            <a class="d-inline-block f-w-500 text-success me-1"
                                                                href="#">Join</a>
                                                            <a class="d-inline-block f-w-500 text-danger"
                                                                href="#">Decline</a>
                                                        </div>

                                                    </div>
                                                    <div class="text-end">
                                                        <i class="ph ph-trash f-s-18 text-danger close-btn"></i>
                                                        <div>
                                                            <span class="badge text-light-primary"> 1 hour ago </span>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                                <div class="hidden-massage py-4 px-3">
                                                    <div>
                                                        <i class="ph-duotone  ph-bell-ringing f-s-50 text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Notification Not Found</h6>
                                                        <p class="text-dark">When you have any notifications added
                                                            here,will
                                                            appear here.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

      

        <footer>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9 col-12">
                        <p class="footer-text f-w-600 mb-0">Copyright © {{ date('Y') . ' ' . $data->site_name }}. All
                            rights reserved
                        </p>
                    </div>
                </div>
            </div>
        </footer>

    </div>


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
    @stack('scripts:after')
</body>

</html>
