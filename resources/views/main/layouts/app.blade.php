<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iconify/2.0.0/iconify.min.js"
        integrity="sha512-lYMiwcB608+RcqJmP93CMe7b4i9G9QK1RbixsNu4PzMRJMsqr/bUrkXUuFzCNsRUo3IXNUr5hz98lINURv5CNA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Globalgood Corporation- Building a strong foundation!</title>
    <link rel="icon" href="{{ asset('assets/images/ggc-fav.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind/tw.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <main class="max-w-site mx-auto">
        <header class="header-main">
            <!-- Mobile Header -->
            <div class="mobile-header">

                <!-- Top Row -->
                <div class="mobile-header__top">

                    <!-- Left Section -->
                    <div class="mobile-header__left">
                        <button type="button" class="mobile-header__menu-btn">
                            <i class="iconify" data-icon="ci:hamburger-md"></i>
                        </button>

                        <a href="{{ route('index') }}" class="mobile-header__logo">
                            <img src="{{ asset('assets/images/ggc-logo.png') }}" alt="GGC Logo" draggable="false">
                        </a>
                    </div>

                    <!-- Right Section -->
                    <div class="mobile-header__right">
                        <a href="###" class="mobile-header__signin">
                            <span class="mobile-header__signin-text">Sign in</span>
                            <i class="iconify" data-icon="solar:user-bold-duotone"></i>
                        </a>

                        <button type="button" class="mobile-header__cart-btn">
                            <small class="mobile-header__cart-count">10+</small>
                            <i class="iconify" data-icon="solar:bag-3-linear"></i>
                        </button>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="mobile-header__search">
                    <form action="###" class="mobile-header__search-form">
                        <input type="text" class="mobile-header__search-input" placeholder="Search products">
                        <button type="button" class="mobile-header__search-btn">
                            <i class="iconify" data-icon="tabler:search"></i>
                        </button>
                    </form>
                </div>

                <!-- Location -->
                <div class="mobile-header__location">
                    <button type="button" class="mobile-header__location-btn">
                        <i class="iconify" data-icon="tabler:location-filled"></i>
                        <span class="mobile-header__location-text">
                            Delivering to Delhi 110001 - Update location
                        </span>
                    </button>
                </div>
            </div>
            <!-- End of Mobile Header -->

            <!-- Desktop Header -->
            <div class="desktop-header">

                <!-- Top Header -->
                <ul class="desktop-header__top">

                    <!-- Logo -->
                    <li class="desktop-header__logo">
                        <a href="{{ route('index') }}" class="desktop-header__logo-link">
                            <img src="{{ asset('assets/images/ggc-logo.png') }}" alt="GGC Logo" draggable="false"
                                class="desktop-header__logo-img">
                        </a>
                    </li>

                    <!-- Location -->
                    <li class="desktop-header__location">
                        <button type="button" class="desktop-header__location-btn">
                            <i class="iconify desktop-header__location-icon" data-icon="tabler:location-filled"></i>

                            <div class="desktop-header__location-text">
                                <p class="desktop-header__location-line">
                                    Delivering to
                                    <span class="desktop-header__location-pin">Delhi 110001</span>
                                </p>
                                <p class="desktop-header__location-update">Update location</p>
                            </div>
                        </button>
                    </li>

                    <!-- Search -->
                    <li class="desktop-header__search">
                        <form action="###" class="desktop-header__search-form">
                            <input type="text" class="desktop-header__search-input" placeholder="Search products">
                            <button type="button" class="desktop-header__search-btn">
                                <i class="iconify desktop-header__search-icon" data-icon="tabler:search"></i>
                            </button>
                        </form>
                    </li>

                    <!-- Language -->
                    <li class="desktop-header__language">
                        <button type="button" class="desktop-header__language-btn">
                            <i class="iconify desktop-header__language-flag" data-icon="flagpack:us"></i>
                            <span class="desktop-header__language-text">EN</span>
                            <i class="iconify desktop-header__language-arrow" data-icon="icon-park-outline:down"></i>
                        </button>
                    </li>

                    <!-- Account -->
                    <li class="desktop-header__account">
                        <button type="button" class="desktop-header__account-btn">
                            <div class="desktop-header__account-text">
                                <span class="desktop-header__account-greeting">
                                    Hello, sign in
                                </span>
                                <p class="desktop-header__account-label">
                                    Account & Lists
                                </p>
                            </div>
                            <i class="iconify desktop-header__account-arrow" data-icon="icon-park-outline:down"></i>
                        </button>
                    </li>

                    <!-- Orders -->
                    <li class="desktop-header__orders">
                        <a href="###" class="desktop-header__orders-link">
                            <p class="desktop-header__orders-text">
                                Returns
                                <span class="desktop-header__orders-highlight">
                                    & Orders
                                </span>
                            </p>
                        </a>
                    </li>

                    <!-- Cart -->
                    <li class="desktop-header__cart">
                        <button type="button" class="desktop-header__cart-btn">
                            <small class="desktop-header__cart-count">10+</small>
                            <i class="iconify desktop-header__cart-icon" data-icon="solar:bag-3-linear"></i>
                        </button>
                    </li>

                </ul>

                <!-- Navigation Bar -->
                <ul class="desktop-header__nav">

                    <!-- All Menu -->
                    <li class="desktop-header__nav-item desktop-header__nav-item--all">
                        <button type="button" class="desktop-header__all-btn">
                            <i class="iconify desktop-header__all-icon" data-icon="ci:hamburger-md"></i>
                            <span class="desktop-header__all-text">All</span>
                        </button>
                    </li>

                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Fresh</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">MX Player</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Sell</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Bestsellers</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Mobile</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Prime</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">New Release</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Amazon Pay</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Fashion</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Electronics</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Home & Kitchen</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Books</a>
                    </li>
                    <li class="desktop-header__nav-item">
                        <a href="###" class="desktop-header__nav-link">Computers</a>
                    </li>

                </ul>

                <!-- Mega Menu -->
                <div class="desktop-header__mega-menu">
                    <a href="###" class="desktop-header__mega-trigger">
                        Electronics
                    </a>

                    <ul class="desktop-header__mega-list">
                        <li class="desktop-header__mega-item">
                            <a href="###" class="desktop-header__mega-link text-highlight">
                                Mobiles & Accessories
                            </a>
                        </li>
                        <li class="desktop-header__mega-item">
                            <a href="###" class="desktop-header__mega-link text-highlight">
                                Laptops & Accessories
                            </a>
                        </li>
                        <li class="desktop-header__mega-item">
                            <a href="###" class="desktop-header__mega-link text-highlight">
                                TV & Home Entertainment
                            </a>
                        </li>
                        <li class="desktop-header__mega-item">
                            <a href="###" class="desktop-header__mega-link text-highlight">
                                Audio
                            </a>
                        </li>
                        <li class="desktop-header__mega-item">
                            <a href="###" class="desktop-header__mega-link text-highlight">
                                Cameras
                            </a>
                        </li>
                        <li class="desktop-header__mega-item">
                            <a href="###" class="desktop-header__mega-link text-highlight">
                                Computer Peripherals
                            </a>
                        </li>
                        <li class="desktop-header__mega-item">
                            <a href="###" class="desktop-header__mega-link text-highlight">
                                Smart Technology
                            </a>
                        </li>
                        <li class="desktop-header__mega-item">
                            <a href="###" class="desktop-header__mega-link text-highlight">
                                Musical Instruments
                            </a>
                        </li>
                        <li class="desktop-header__mega-item">
                            <a href="###" class="desktop-header__mega-link text-highlight">
                                Office & Stationery
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
            <!-- End of Desktop Header -->
        </header>

        @yield('content')

    </main>

    <script src="{{ asset('assets/libraries/toolcool-range-slider@4.0.28/toolcool-range-slider.min.js') }}"></script>
    <script src="{{ asset('assets/libraries/gsap@3.12.7/gsap.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dropdownSelect = document.querySelector(".dropdown-select");
            new CustomDropdown(dropdownSelect);
        });
    </script>
</body>

</html>