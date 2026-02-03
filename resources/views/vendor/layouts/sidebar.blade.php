<nav>
    <div class="app-logo">
        <a class="logo d-inline-block" href="{{ route('vendor.dashboard') }}">
            <img src="{{ asset('admins/images/logo/1.png') }}" alt="#">
        </a>

        <span class="bg-light-primary toggle-semi-nav d-flex-center">
            <i class="ti ti-chevron-right"></i>
        </span>
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">
            <li class="no-sub">
                <a href="{{ route('vendor.dashboard') }}"
                    class="{{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#home"></use>
                    </svg>
                    Dashboard
                </a>
            </li>

            <li class="no-sub">
                <a href="{{ route('vendor.products.index') }}"
                    class="{{ request()->routeIs('vendor.products.*') ? 'active' : '' }}">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#product"></use>
                    </svg>
                    Products
                </a>
            </li>
            
            <li class="menu-title"><span>Management</span></li>
            
            {{-- Example wrapper for documents if we want to add more later --}}
            <li>
                <a aria-expanded="{{ request()->routeIs('vendor.documents.*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#documents-menu">
                     <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#document"></use>
                    </svg>
                    Documents
                </a>
                <ul class="collapse {{ request()->routeIs('vendor.documents.*') ? 'show' : '' }}" id="documents-menu">
                    <li><a href="#" onclick="event.preventDefault();">Upload Documents</a></li>
                </ul>
            </li>

            <!-- <li class="menu-title"><span>Account</span></li>

            <li class="no-sub">
                <form action="{{ route('vendor.logout') }}" method="POST" id="logout-form">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                         <svg stroke="currentColor" stroke-width="1.5">
                            <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#sign-out"></use>
                        </svg>
                        Logout
                    </a>
                </form>
            </li> -->
        </ul>
    </div>

    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>

</nav>
