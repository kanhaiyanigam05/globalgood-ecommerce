<!-- Menu Navigation starts -->
<nav class="vertical-sidebar">
    <div class="app-logo">
        <a class="logo d-inline-block" href="ecommerce.html">
            <img src="{{ asset($data->site_logo) }}" alt="#">
        </a>

        <span class="bg-light-primary toggle-semi-nav d-flex-center">
            <i class="ti ti-chevron-right"></i>
        </span>
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">
            <li class="no-sub">
                <a href="{{ route('admin.dashboard') }}"
                    aria-expanded="{{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }}">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#home"></use>
                    </svg>
                    dashboard
                </a>
            </li>
            <li class="no-sub">
                <a href="{{ route('admin.vendors.index') }}"
                    aria-expanded="{{ request()->routeIs('admin.vendors.*') ? 'true' : 'false' }}">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#vendors"></use>
                    </svg>
                    Vendors
                </a>
            </li>
            <li class="no-sub">
                <a href="{{ route('admin.orders.index') }}"
                    aria-expanded="{{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }}">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#orders"></use>
                    </svg>
                    Orders
                </a>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#shop-menu">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#shop"></use>
                    </svg>
                    Shop
                </a>
                <ul class="collapse" id="shop-menu">
                    <li><a href="{{ route('admin.collections.index') }}">Collections</a></li>
                    <li><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                    <li><a href="{{ route('admin.variants.all') }}">All Variants</a></li>
                    <li><a href="{{ route('admin.products.index') }}">Products</a></li>
                    <li><a href="{{ route('admin.products.create') }}">Add Product</a></li>
                    <li><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                </ul>
            </li>
            <li class="no-sub">
                <a href="{{ route('admin.discounts.index') }}"
                    aria-expanded="{{ request()->routeIs('admin.discounts.*') ? 'true' : 'false' }}">
                    <i class="ph ph-tag f-s-20 me-2" style="transform: translateY(2px);"></i>
                    Discounts
                </a>
            </li>
            <li class="no-sub">
                <a href="{{ route('admin.market-sales.index') }}"
                    aria-expanded="{{ request()->routeIs('admin.market-sales.*') ? 'true' : 'false' }}">
                    <i class="ph ph-percent f-s-20 me-2" style="transform: translateY(2px);"></i>
                    Market Sales
                </a>
            </li>
            <li class="no-sub">
                <a href="{{ route('admin.customers.index') }}"
                    aria-expanded="{{ request()->routeIs('admin.customers.*') ? 'true' : 'false' }}">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#customers"></use>
                    </svg>
                    Customers
                </a>
            </li>
            <li class="no-sub">
                <a href="{{ route('admin.media.index') }}"
                    aria-expanded="{{ request()->routeIs('admin.media.*') ? 'true' : 'false' }}">
                    <i class="ph ph-images f-s-20 me-2" style="transform: translateY(2px);"></i>
                    Media Library
                </a>
            </li>
            {{-- 
            <li class="menu-title"><span>Component</span></li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#ui-kits">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#briefcase"></use>
                    </svg>
                    UI kits
                </a>
                <ul class="collapse" id="ui-kits">
                    <li><a href="cheatsheet.html">Cheatsheet</a></li>
                    <li><a href="alert.html">Alert</a></li>
                    <li><a href="badges.html">Badges</a></li>
                    <li><a href="buttons.html">Buttons</a></li>
                    <li><a href="cards.html">Cards</a></li>
                    <li><a href="dropdown.html">Dropdown</a></li>
                    <li><a href="grid.html">Grid</a></li>
                    <li><a href="avatar.html">Avatar</a></li>
                    <li><a href="tabs.html">Tabs</a></li>
                    <li><a href="accordions.html">Accordions</a></li>
                    <li><a href="progress.html">Progress</a></li>
                    <li><a href="notifications.html">Notifications</a></li>
                    <li><a href="list.html">Lists</a></li>
                    <li><a href="helper-classes.html">Helper Classes</a></li>
                    <li><a href="background.html">Background</a></li>
                    <li><a href="divider.html">Divider</a></li>
                    <li><a href="ribbons.html">Ribbons</a></li>
                    <li><a href="editor.html">Editor </a></li>
                    <li><a href="collapse.html">Collapse</a></li>
                    <li><a href="shadow.html">Shadow</a></li>
                    <li><a href="wrapper.html">Wrapper</a></li>
                    <li><a href="bullet.html">Bullet</a></li>
                    <li><a href="placeholder.html">Placeholder</a></li>
                    <li><a href="alignment.html">Alignment Thing</a></li>
                </ul>
            </li>


            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#advance-ui">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#briefcase-advance"></use>
                    </svg>
                    Advance UI
                    <span class=" badge rounded-pill bg-warning badge-notification ms-2">
                        12+
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </a>
                <ul class="collapse" id="advance-ui">
                    <li><a href="modals.html">Modals</a></li>
                    <li><a href="offcanvas.html">Offcanvas Toggle</a></li>
                    <li><a href="sweetalert.html">Sweat Alert</a></li>
                    <li><a href="scrollbar.html">Scrollbar</a></li>
                    <li><a href="spinners.html">Spinners</a></li>
                    <li><a href="animation.html">Animation</a></li>
                    <li><a href="video-embed.html">Video Embed</a></li>
                    <li><a href="tour.html">Tour</a></li>
                    <li><a href="slick-slider.html">Slider</a></li>
                    <li><a href="bootstrap-slider.html">Bootstrap Slider</a></li>
                    <li><a href="scrollpy.html">Scrollpy</a></li>
                    <li><a href="tooltips-popovers.html">Tooltip & Popovers</a></li>
                    <li><a href="ratings.html">Rating</a></li>
                    <li><a href="prismjs.html">Prismjs</a></li>
                    <li><a href="count-down.html">Count Down</a></li>
                    <li><a href="count-up.html"> Count up </a></li>
                    <li><a href="draggable.html">Draggable</a></li>
                    <li><a href="tree-view.html">Tree View</a></li>
                    <li><a href="block-ui.html">Block Ui </a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#icons">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#gift"></use>
                    </svg>
                    Icons
                </a>
                <ul class="collapse" id="icons">
                    <li><a href="fontawesome.html">Fontawesome</a></li>
                    <li><a href="flag-icons.html">Flag</a></li>
                    <li><a href="tabler-icons.html">Tabler</a></li>
                    <li><a href="weather-icon.html">Weather</a></li>
                    <li><a href="animated-icon.html">Animated</a></li>
                    <li><a href="iconoir-icon.html">Iconoir</a></li>
                    <li><a href="phosphor-icon.html">Phosphor</a></li>
                </ul>
            </li>
            <li class="no-sub">
                <a href="misc.html">

                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#rectangle"></use>
                    </svg>
                    Misc
                </a>
            </li>
            <li class="menu-title"><span>Map & Charts </span></li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#maps">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#location"></use>
                    </svg>
                    Map
                </a>
                <ul class="collapse" id="maps">
                    <li><a href="leaflet-map.html">Leaflet map</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#chart">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#chart"></use>
                    </svg>
                    Chart
                </a>
                <ul class="collapse" id="chart">
                    <li><a href="chart-js.html">Chart js</a></li>


                    <li class="another-level">
                        <a aria-expanded="false" data-bs-toggle="collapse" href="#apexcharts-page">
                            Apexcharts
                        </a>
                        <ul class="collapse" id="apexcharts-page">
                            <li><a href="line-chart.html">Line</a></li>
                            <li><a href="area-chart.html">Area</a></li>
                            <li><a href="column-chart.html">Column</a></li>
                            <li><a href="bar-chart.html">Bar</a></li>
                            <li><a href="mixed-chart.html">Mixed</a></li>
                            <li><a href="timeline-range-charts.html">Timeline & Range-Bars</a></li>
                            <li><a href="candlestick-chart.html">Candlestick</a></li>
                            <li><a href="boxplot-chart.html">Boxplot</a></li>
                            <li><a href="bubble-chart.html">Bubble</a></li>
                            <li><a href="scatter-chart.html">Scatter</a></li>
                            <li><a href="heatmap.html">Heatmap</a></li>
                            <li><a href="treemap-chart.html">Treemap</a></li>
                            <li><a href="pie-charts.html">Pie</a></li>
                            <li><a href="radial-bar-chart.html">Radial bar</a></li>
                            <li><a href="radar-chart.html">Radar</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="menu-title"><span>Table & forms </span></li>

            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#table">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#table"></use>
                    </svg>
                    Table
                </a>
                <ul class="collapse" id="table">
                    <li><a href="basic-table.html">BasicTable</a></li>
                    <li><a href="data-table.html">Data Table</a></li>
                    <li><a href="list-table.html">List Js</a></li>
                    <li><a href="advance-table.html">Advance Table</a></li>
                </ul>
            </li>


            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#forms">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#wallet"></use>
                    </svg>
                    Forms elements
                </a>
                <ul class="collapse" id="forms">
                    <li><a href="form-validation.html">Form Validation</a></li>
                    <li><a href="base-inputs.html">Base Input</a></li>
                    <li><a href="checkbox-radio.html">Checkbox & Radio</a></li>
                    <li><a href="input-groups.html">Input Groups</a></li>
                    <li><a href="input-masks.html">Input Masks</a></li>
                    <li><a href="floating-labels.html">Floating Labels</a></li>
                    <li><a href="date-picker.html">Datetimepicker</a></li>
                    <li><a href="touch-spin.html">Touch spin</a></li>
                    <li><a href="select.html">Select2</a></li>
                    <li><a href="switch.html">Switch</a></li>
                    <li><a href="range-slider.html">Range Slider</a></li>
                    <li><a href="typeahead.html">Typeahead</a></li>
                    <li><a href="textarea.html">Textarea</a></li>
                    <li><a href="clipboard.html">Clipboard</a></li>
                    <li><a href="file-upload.html">File Upload</a></li>
                    <li><a href="dual-list-boxes.html">Dual List Boxes</a></li>
                    <li><a href="default-forms.html">Default Forms</a></li>
                </ul>
            </li>

            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#ready_to_use">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#newspaper"></use>
                    </svg>
                    Ready to use
                    <span class="badge text-bg-success badge-notification ms-2">2</span>
                </a>
                <ul class="collapse" id="ready_to_use">
                    <li><a href="form-wizards.html">Form wizards</a></li>
                    <li><a href="form-wizard-1.html">Form wizards 1</a></li>
                    <li><a href="form-wizard-2.html">Form wizards 2</a></li>
                    <li><a href="ready-to-use-form.html">Ready To Use Form</a></li>
                    <li><a href="ready-to-use-table.html">Ready To Use Tables</a></li>
                </ul>
            </li>

            <li class="menu-title"><span>Pages</span></li>

            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#auth_pages">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#window"></use>
                    </svg>
                    Auth Pages
                </a>
                <ul class="collapse" id="auth_pages">
                    <li><a href="sign-in.html">Sign In</a></li>
                    <li><a href="sign-in-1.html">Sign In with Bg-image</a></li>
                    <li><a href="sign-up.html">Sign Up</a></li>
                    <li><a href="sign-up-1.html">Sign Up with Bg-image</a></li>
                    <li><a href="password-reset.html">Password Reset</a></li>
                    <li><a href="password-reset-1.html">Password Reset with Bg-image</a></li>
                    <li><a href="password-create.html">Password Create</a></li>
                    <li><a href="password-create-1.html">Password Create with Bg-image</a></li>
                    <li><a href="lock-screen.html">Lock Screen</a></li>
                    <li><a href="lock-screen-1.html">Lock Screen with Bg-image</a></li>
                    <li><a href="two-step-verification.html">Two-Step Verification</a></li>
                    <li><a href="two-step-verification-1.html">Two-Step Verification with Bg-image</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#error_pages">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#exclamation-circle"></use>
                    </svg>
                    Error Pages
                </a>
                <ul class="collapse" id="error_pages">
                    <li><a href="error-400.html">Bad Request </a></li>
                    <li><a href="error-403.html">Forbidden </a></li>
                    <li><a href="error-404.html">Not Found</a></li>
                    <li><a href="error-500.html">Internal Server</a></li>
                    <li><a href="error-503.html">Service Unavailable</a></li>
                </ul>
            </li>

            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#other_pages">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#document"></use>
                    </svg>
                    Other Pages
                </a>
                <ul class="collapse" id="other_pages">
                    <li><a href="blank.html">Blank</a></li>
                    <li><a href="maintenance.html">Maintenance</a></li>
                    <li><a href="landing.html">Landing Page</a></li>
                    <li><a href="coming-soon.html">Coming Soon</a></li>
                    <li><a href="sitemap.html">Sitemap</a></li>
                    <li><a href="privacy-policy.html">Privacy Policy</a></li>
                    <li><a href="terms-condition.html">Terms &amp; Condition</a></li>
                </ul>
            </li>

            <li class="menu-title"><span>Others</span></li>

            <li>
                <a class="" data-bs-toggle="collapse" href="#level" aria-expanded="false">
                    <i class="ti ti-box-multiple-2"></i> 2 level
                </a>
                <ul class="collapse" id="level">
                    <li><a href="#">Blank</a></li>
                    <li class="another-level">
                        <a class="" data-bs-toggle="collapse" href="#level2" aria-expanded="false">
                            Another level
                        </a>
                        <ul class="collapse" id="level2">
                            <li><a href="blank.html">Blank</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="no-sub">
                <a href="https://phpstack-1384472-5121645.cloudwaysapps.com/document/codeigniter/ki-admin/index.html">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#document-text"></use>
                    </svg>
                    Document
                </a>
            </li>

            <li class="no-sub">
                <a href="mailto:teqlathemes@gmail.com">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="assets/svg/_sprite.svg#chat-bubble"></use>
                    </svg>
                    Support
                </a>
            </li> --}}
            <li class="no-sub">
                <a href="{{ route('admin.settings.index') }}"
                    aria-expanded="{{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }}">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="{{ asset('admins/svg/_sprite.svg') }}#settings"></use>
                    </svg>
                    Settings
                </a>
            </li>
        </ul>
    </div>

    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>

</nav>
<!-- Menu Navigation ends -->
