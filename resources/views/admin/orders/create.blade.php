@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <!-- Breadcrumb -->
                <div class="row m-1">
                    <div class="col-12">
                        <h4 class="main-title">Create order</h4>
                        <ul class="app-line-breadcrumbs mb-3">
                            <li><a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}"><span><i
                                            class="ph-duotone ph-house f-s-16"></i> Home</span></a></li>
                            <li><a class="f-s-14 f-w-500" href="{{ route('admin.orders.index') }}"><span><i
                                            class="ph-duotone ph-shopping-cart f-s-16"></i> Orders</span></a></li>
                            <li class="active"><a class="f-s-14 f-w-500" href="#">Create order</a></li>
                        </ul>
                    </div>
                </div>

                <x-forms.form :action="route('admin.orders.store')" method="post" id="order-form" class="row">
                    <div class="col-lg-8">
                        <!-- Products Selection -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Products</h6>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#browseProductsModal">Browse</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        id="add-custom-item-btn">Add custom item</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="input-group" data-bs-toggle="modal" data-bs-target="#browseProductsModal"
                                        style="cursor: pointer;">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="ph ph-magnifying-glass"></i></span>
                                        <input type="text" class="form-control border-start-0"
                                            placeholder="Browse products..." readonly
                                            style="background-color: #fff; cursor: pointer;">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="order-items-table">
                                        <thead>
                                            <tr>
                                                <th width="50"></th>
                                                <th>Product</th>
                                                <th width="100">Quantity</th>
                                                <th>Total</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="order-items-list">
                                            <tr id="empty-items-placeholder">
                                                <td colspan="5" class="text-center py-5 text-muted">Add a product to
                                                    calculate total and view payment options</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Payment</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span
                                        id="summary-subtotal">₹0.00</span></div>
                                <div class="d-flex justify-content-between mb-2 text-primary">
                                    <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                        data-bs-target="#addDiscountModal" id="add-discount-link">Add discount</a>
                                    <span id="summary-discount">—</span>
                                    <input type="hidden" name="discount_amount" id="input-discount-amount" value="0">
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-primary">
                                    <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                        data-bs-target="#addShippingModal" id="add-shipping-link">Add shipping</a>
                                    <span id="summary-shipping">—</span>
                                    <input type="hidden" name="shipping_amount" id="input-shipping-amount" value="0">
                                </div>
                                <div class="d-flex justify-content-between mb-2"><span>Estimated tax</span><span
                                        id="summary-tax">Not calculated</span></div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold f-s-18"><span>Total</span><span
                                        id="summary-total">₹0.00</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Notes -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Notes</h6>
                                <button type="button" class="btn btn-link btn-sm p-0"><i
                                        class="ph ph-pencil-simple"></i></button>
                            </div>
                            <div class="card-body">
                                <textarea name="notes" class="form-control" rows="2" placeholder="No notes"></textarea>
                            </div>
                        </div>

                        <!-- Customer Selection -->
                        <div class="card shadow-sm mb-4" id="customer-card">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Customer</h6>
                                <button type="button" class="btn btn-link btn-sm p-0 text-danger d-none"
                                    id="remove-customer-btn"><i class="ph ph-x"></i></button>
                            </div>
                            <div class="card-body">
                                <div id="customer-search-wrapper">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="ph ph-magnifying-glass"></i></span>
                                        <input type="text" id="customer-search" class="form-control border-start-0"
                                            placeholder="Search or create a customer">
                                    </div>
                                    <div id="customer-search-results" class="dropdown-menu w-100 shadow-sm"
                                        style="max-height: 250px; overflow-y: auto;"></div>
                                </div>
                                <div id="selected-customer-info" class="d-none">
                                    <div class="d-flex flex-column mb-3">
                                        <a href="#" class="fw-medium text-primary mb-1 text-decoration-none"
                                            id="display-customer-name">No name</a>
                                        <span class="text-muted small" id="display-customer-orders">No orders</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h7 class="mb-0 f-s-14 fw-bold text-dark">Contact information</h7>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-muted"
                                            id="edit-contact-btn"><i class="ph ph-pencil-simple"></i></button>
                                    </div>
                                    <div class="mb-3">
                                        <a href="#" class="d-block text-decoration-none"
                                            id="display-customer-email">No email</a>
                                        <span class="d-block text-muted small" id="display-customer-phone">No phone
                                            number</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h7 class="mb-0 f-s-14 fw-bold text-dark">Shipping address</h7>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-muted"
                                            id="edit-shipping-address-btn"><i class="ph ph-pencil-simple"></i></button>
                                    </div>
                                    <div class="mb-3">
                                        <span class="d-block text-muted small" id="display-customer-address">No shipping
                                            address provided</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h7 class="mb-0 f-s-14 fw-bold text-dark">Billing address</h7>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-muted"
                                            id="edit-billing-address-btn"><i class="ph ph-pencil-simple"></i></button>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check d-none" id="billing-same-check-wrapper">
                                            <input class="form-check-input" type="checkbox"
                                                name="billing_same_as_shipping" value="1"
                                                id="billing-same-as-shipping" checked>
                                            <label class="form-check-label text-muted small"
                                                for="billing-same-as-shipping">Same as shipping address</label>
                                        </div>
                                        <span class="d-block text-muted small mt-1" id="display-billing-address">Same as
                                            shipping address</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Tags</h6>
                                <button type="button" class="btn btn-link btn-sm p-0"><i
                                        class="ph ph-pencil-simple"></i></button>
                            </div>
                            <div class="card-body"><input type="text" name="tags" class="form-control"
                                    placeholder="Search or create tags"></div>
                        </div>

                        <div class="card shadow-sm border-primary">
                            <div class="card-body d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Collect payment</button>
                                <button type="submit" name="action" value="send_invoice"
                                    class="btn btn-outline-secondary">Send invoice</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="customer_id" id="input-customer-id">
                    <input type="hidden" name="shipping_address_id" id="input-shipping-address-id">
                    <input type="hidden" name="billing_address_id" id="input-billing-address-id">
                </x-forms.form>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <x-models.product-select id="browseProductsModal" :apiUrl="route('admin.orders.search-products')" />

    <!-- Create Customer Modal -->
    <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Create a new customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Validation Errors Container -->
                    <div id="create-customer-errors" class="alert alert-danger d-none"></div>

                    <form id="create-customer-form">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6"><label class="form-label">First name</label><input type="text"
                                    class="form-control" name="first_name" required></div>
                            <div class="col-md-6"><label class="form-label">Last name</label><input type="text"
                                    class="form-control" name="last_name" required></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Language</label>
                            <select class="form-select" name="language">
                                <option value="en">English [Default]</option>
                            </select>
                            <div class="form-text">This customer will receive notifications in this language.</div>
                        </div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email"
                                class="form-control" name="email" required></div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="email_marketing_consent"
                                    value="1" id="create-email-marketing">
                                <label class="form-check-label" for="create-email-marketing">Customer accepts email
                                    marketing</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create-tax-exempt">
                                <label class="form-check-label" for="create-tax-exempt">Customer is tax exempt</label>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Shipping address</h6>
                        <div class="mb-3"><label class="form-label">Country/region</label>
                            <select class="form-select" name="address[country]" id="create-country-select">
                                <option value="">Select country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->name }}" data-id="{{ $country->id }}"
                                        data-postalcode="{{ $country->postalcode }}" data-flag="{{ $country->flag_url }}">
                                        {{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label class="form-label">Company</label><input type="text"
                                class="form-control" name="address[company]"></div>
                        <div class="mb-3"><label class="form-label">Address</label><input type="text"
                                class="form-control" name="address[address1]"></div>
                        <div class="mb-3"><label class="form-label">Apartment, suite, etc</label><input type="text"
                                class="form-control" name="address[address2]"></div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4"><label class="form-label">City</label><input type="text"
                                    class="form-control" name="address[city]"></div>

                            <div class="col-md-4" id="create-state-container" style="display: none;">
                                <label class="form-label" id="create-state-label">State</label>
                                <select class="form-select" name="address[province]" id="create-province-select">
                                    <option value="" disabled selected>Select a state</option>
                                </select>
                            </div>

                            <div class="col-md-4" id="create-postal-container" style="display: none;">
                                <label class="form-label" id="create-postal-label">PIN code</label>
                                <input type="text" class="form-control" name="address[zip]" id="create-zip">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <div class="input-group">
                                <div class="position-relative d-flex align-items-center" style="width: 50px;">
                                    <div
                                        class="d-flex align-items-center justify-content-center w-100 h-100 bg-white border rounded-start px-2">
                                        <img src="{{ asset('flags/untitle.svg') }}" id="create-phone-flag" width="24"
                                            alt="Flag" style="object-fit: contain;">
                                    </div>
                                    <input type="hidden" id="create-phone-code" name="address[tel]">
                                </div>
                                <input type="text" class="form-control" name="phone">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-dark" id="save-new-customer-btn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reused Address Modal -->
    @include('admin.customers.partials.address-modal', ['countries' => $countries])

    <!-- Add Shipping Modal -->
    <div class="modal fade" id="addShippingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add shipping</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Shipping rate</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" id="modal-shipping-rate" class="form-control" placeholder="0.00"
                                step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-dark"
                        id="apply-shipping-btn">Apply</button></div>
            </div>
        </div>
    </div>

    <!-- Add Discount Modal -->
    <div class="modal fade" id="addDiscountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add discount</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Discount value</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" id="modal-discount-value" class="form-control" placeholder="0.00"
                                step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-dark"
                        id="apply-discount-btn">Apply</button></div>
            </div>
        </div>
    </div>

    <!-- Custom Item Modal -->
    <div class="modal fade" id="addCustomItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add custom item</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Item name</label><input type="text"
                            id="modal-custom-name" class="form-control" placeholder="Short description"></div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" id="modal-custom-price" class="form-control" placeholder="0.00"
                                step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-dark"
                        id="confirm-custom-item-btn">Add item</button></div>
            </div>
        </div>
    </div>

    {{-- Template --}}
    <template id="order-item-row-template">
        <tr class="order-item-row">
            <td>
                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px; border: 1px solid #eee;"><i class="ph ph-image text-muted"></i>
                </div>
            </td>
            <td>
                <div class="fw-medium item-name">PRODUCT_NAME</div>
                <div class="text-muted small item-variant">VARIANT_TITLE</div>
                <input type="hidden" name="items[INDEX][product_id]" class="input-product-id">
                <input type="hidden" name="items[INDEX][variant_id]" class="input-variant-id">
                <input type="hidden" name="items[INDEX][price]" class="input-price">
                <input type="hidden" name="items[INDEX][name]" class="input-item-name">
                <input type="hidden" name="items[INDEX][is_custom]" class="input-is-custom" value="0">
            </td>
            <td><input type="number" name="items[INDEX][quantity]" class="form-control form-control-sm input-quantity"
                    value="1" min="1"></td>
            <td><span class="item-total">₹0.00</span></td>
            <td class="text-end"><button type="button" class="btn btn-link text-danger p-0 remove-item-btn"><i
                        class="ph ph-x"></i></button></td>
        </tr>
    </template>
@endsection

@push('scripts:after')
    <script>
        $(document).ready(function() {
            let itemsCount = 0;
            let selectedCustomer = null;
            let editingAddressType = 'shipping'; // 'shipping' or 'billing'
            let billingAddress = null; // Stored object for UI display
            let currentAddresses = []; // Fetch addresses for modal

            // -- Product Selection Logic --
            window.addEventListener('products-selected', function(e) {
                const items = e.detail.items;
                if (items && items.length > 0) {
                    items.forEach(item => {
                        let exists = false;
                        $('.order-item-row').each(function() {
                            const pid = $(this).find('.input-product-id').val();
                            const vid = $(this).find('.input-variant-id').val();
                            if (item.type === 'variant') {
                                if (pid == item.productId && vid == item.id) exists = true;
                            } else {
                                if (pid == item.id && !vid) exists = true;
                            }
                        });
                        if (exists) return;

                        let variantName = '';
                        let productName = item.name;
                        if (item.type === 'variant' && item.name.includes(' - ')) {
                            const parts = item.name.split(' - ');
                            productName = parts[0];
                            variantName = parts.slice(1).join(' - ');
                        }

                        addItem({
                            productId: item.productId,
                            variantId: item.type === 'variant' ? item.id : null,
                            name: productName,
                            variantName: variantName,
                            price: item.price,
                            img: item.img
                        });
                    });
                }
            });
            window.addEventListener('request-selection-state', function(e) {
                const currentItems = [];
                $('.order-item-row').each(function() {
                    const pid = $(this).find('.input-product-id').val();
                    const vid = $(this).find('.input-variant-id').val();
                    if (vid) currentItems.push('v_' + vid);
                    else if (pid) currentItems.push('p_' + pid);
                });
                window.dispatchEvent(new CustomEvent('provide-selection-state', {
                    detail: {
                        selectedIds: currentItems
                    }
                }));
            });

            function addItem(data) {
                $('#empty-items-placeholder').hide();
                const template = $('#order-item-row-template').html();
                const index = itemsCount++;
                const html = $(template.replace(/INDEX/g, index).replace(/PRODUCT_NAME/g, data.name).replace(
                    /VARIANT_TITLE/g, data.variantName || ''));
                if (data.img) html.find('.ph-image').replaceWith(
                    `<img src="${data.img}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">`
                    );
                html.find('.input-product-id').val(data.productId || '');
                html.find('.input-variant-id').val(data.variantId || '');
                html.find('.input-price').val(data.price);
                html.find('.input-item-name').val(data.name);
                if (data.isCustom) html.find('.input-is-custom').val('1');

                $('#order-items-list').append(html);
                updateTotals();
            }

            // --- Customer Logic ---
            const customerSearchInput = $('#customer-search');
            let customerSearchTimeout;

            function fetchCustomers(query = '') {
                $.get("{{ route('admin.orders.search-customers') }}", {
                    q: query
                }, function(customers) {
                    const dropdown = $('#customer-search-results');
                    dropdown.empty();
                    dropdown.append(`
                    <button type="button" class="dropdown-item py-2 text-primary fw-medium bg-light mb-1" id="btn-open-create-customer">
                        <i class="ph ph-plus-circle me-1"></i> Create a new customer
                    </button>
                `);

                    if (customers.length > 0) {
                        customers.forEach(customer => {
                            const customerData = JSON.stringify(customer).replace(/"/g, '&quot;');
                            dropdown.append(`
                            <button type="button" class="dropdown-item py-2 select-customer-btn border-bottom" data-customer="${customerData}">
                                <div class="fw-medium">${customer.first_name} ${customer.last_name || ''}</div>
                                <div class="text-muted small">${customer.email}</div>
                            </button>
                        `);
                        });
                    }
                    dropdown.addClass('show');
                });
            }

            customerSearchInput.on('focus', function() {
                if (!$(this).val()) fetchCustomers('');
                else $('#customer-search-results').addClass('show');
            });
            customerSearchInput.on('input', function() {
                clearTimeout(customerSearchTimeout);
                customerSearchTimeout = setTimeout(() => fetchCustomers($(this).val()), 300);
            });

            $(document).on('click', '#btn-open-create-customer', function() {
                const modal = new bootstrap.Modal(document.getElementById('createCustomerModal'));
                modal.show();
                $('#customer-search-results').removeClass('show');
                // Reset Form and Errors
                $('#create-customer-form')[0].reset();
                $('#create-customer-errors').addClass('d-none').html('');
                $('#create-state-container').hide();
                $('#create-postal-container').hide();
                $('#create-phone-flag').attr('src', "{{ asset('flags/untitle.svg') }}");
            });

            $(document).on('click', '.select-customer-btn', function() {
                const customer = $(this).data('customer');
                selectCustomer(typeof customer === 'string' ? JSON.parse(customer) : customer);
            });

            function selectCustomer(customer) {
                selectedCustomer = customer;
                $('#customer-search-wrapper').addClass('d-none');
                $('#selected-customer-info').removeClass('d-none');
                $('#remove-customer-btn').removeClass('d-none');
                $('#input-customer-id').val(customer.id);

                const fullName = (customer.first_name || '') + ' ' + (customer.last_name || '');
                $('#display-customer-name').text(fullName.trim() || 'No name');
                $('#display-customer-email').text(customer.email || 'No email');
                $('#display-customer-phone').text(customer.phone || 'No phone number');

                // Set Shipping Address (Default)
                if (customer.default_address) {
                    updateAddressDisplay(customer.default_address, 'shipping');
                    $('#input-shipping-address-id').val(customer.default_address.id);
                } else {
                    updateAddressDisplay(null, 'shipping');
                    $('#input-shipping-address-id').val('');
                }

                // Reset Billing
                $('#billing-same-check-wrapper').removeClass('d-none');
                $('#billing-same-as-shipping').prop('checked', true);
                billingAddress = null;
                $('#input-billing-address-id').val('');

                updateBillingDisplay();
                $('#customer-search-results').removeClass('show');
            }

            function updateAddressDisplay(address, type = 'shipping') {
                const displayEl = type === 'shipping' ? $('#display-customer-address') : $(
                    '#display-billing-address');
                const btnEl = type === 'shipping' ? $('#edit-shipping-address-btn') : $(
                '#edit-billing-address-btn');

                if (address) {
                    const formattedAddr = [address.address1, address.city, address.country].filter(Boolean).join(
                        ', ');
                    displayEl.text(formattedAddr);
                    btnEl.html('<i class="ph ph-pencil-simple"></i>');
                    btnEl.removeClass('d-none');
                } else {
                    const linkId = type === 'shipping' ? 'link-create-address' : 'link-create-billing-address';
                    const text = type === 'shipping' ? 'Create address' : 'Add billing address';
                    displayEl.html(
                        `<a href="#" id="${linkId}" class="text-primary text-decoration-none">${text}</a>`);
                    btnEl.html('');
                }
            }

            function updateBillingDisplay() {
                if ($('#billing-same-as-shipping').is(':checked')) {
                    $('#display-billing-address').text('Same as shipping address');
                    $('#edit-billing-address-btn').addClass('d-none');
                    $('#input-billing-address-id').val(''); // Clear ID ensures backend uses shipping
                } else {
                    $('#edit-billing-address-btn').removeClass('d-none');
                    updateAddressDisplay(billingAddress, 'billing');
                    if (billingAddress) $('#input-billing-address-id').val(billingAddress.id);
                }
            }

            $('#remove-customer-btn').click(function() {
                selectedCustomer = null;
                $('#customer-search-wrapper').removeClass('d-none');
                $('#selected-customer-info').addClass('d-none');
                $('#remove-customer-btn').addClass('d-none');
                $('#input-customer-id').val('');
                $('#input-shipping-address-id').val('');
                $('#input-billing-address-id').val('');
                $('#customer-search').val('');
                $('#billing-same-check-wrapper').addClass('d-none');
            });

            $('#billing-same-as-shipping').change(function() {
                updateBillingDisplay();
            });


            // --- Create Customer Save with Validation ---
            $('#save-new-customer-btn').click(function() {
                const btn = $(this);
                const errorContainer = $('#create-customer-errors');
                btn.prop('disabled', true).text('Saving...');
                errorContainer.addClass('d-none').html('');

                const formData = new FormData(document.getElementById('create-customer-form'));
                const isTaxExempt = $('#create-tax-exempt').is(':checked');
                formData.append('tax_setting', isTaxExempt ? 'exempt' : 'collect');

                $.ajax({
                    url: "{{ route('admin.orders.store-customer') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        selectCustomer(response);
                        bootstrap.Modal.getInstance(document.getElementById(
                            'createCustomerModal')).hide();
                        document.getElementById('create-customer-form').reset();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorHtml = '<ul class="mb-0">';
                            for (const key in errors) {
                                errorHtml += `<li>${errors[key][0]}</li>`;
                            }
                            errorHtml += '</ul>';
                            errorContainer.html(errorHtml).removeClass('d-none');
                        } else {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Save');
                    }
                });
            });

            // --- Create Customer Modal Zone Fetching ---
            function fetchZonesForCreate(countryName) {
                // Find option to get data attributes
                const option = $(`#create-country-select option[value="${countryName}"]`);
                const countryId = option.data('id');
                const postalCodeName = option.data('postalcode');
                const flag = option.data('flag');

                if (flag) $('#create-phone-flag').attr('src', flag);

                if (postalCodeName) {
                    $('#create-postal-label').text(postalCodeName);
                    $('#create-postal-container').show();
                } else {
                    $('#create-postal-container').hide();
                    $('#create-zip').val('');
                }

                $('#create-province-select').html('<option value="" disabled selected>Loading...</option>');
                $('#create-state-container').show();

                $.ajax({
                    url: "{{ route('admin.customers.get-zones') }}",
                    type: "GET",
                    data: {
                        country_id: countryId
                    },
                    success: function(data) {
                        let options = '<option value="" disabled selected>Select a state</option>';
                        if (data.length > 0) {
                            data.forEach(function(zone) {
                                options += `<option value="${zone.name}">${zone.name}</option>`;
                            });
                            $('#create-state-container').show();
                        } else {
                            $('#create-state-container').hide();
                        }
                        $('#create-province-select').html(options);
                    },
                    error: function() {
                        $('#create-state-container').hide();
                    }
                });
            }
            $('#create-country-select').change(function() {
                fetchZonesForCreate($(this).val());
            });


            // --- Address Modal Logic ---
            function fetchZones(countryId, selectedProvince = null) {
                const countryOption = $(`#modal_country option[value="${countryId}"]`);
                const flag = countryOption.data('flag');
                const postalCodeName = countryOption.data('postalcode');

                if (flag) $('#modal_phone_flag').attr('src', flag);
                if (postalCodeName) {
                    $('#postal_code_label').text(postalCodeName);
                    $('#postal_code_container').show();
                } else {
                    $('#postal_code_container').hide();
                    $('#modal_zip').val('');
                }

                $('#modal_province').html('<option value="" disabled selected>Loading...</option>');
                $('#state_container').show();

                $.ajax({
                    url: "{{ route('admin.customers.get-zones') }}",
                    type: "GET",
                    data: {
                        country_id: countryId
                    },
                    success: function(data) {
                        let options = '<option value="" disabled selected>Select a state</option>';
                        if (data.length > 0) {
                            data.forEach(function(zone) {
                                const isSelected = selectedProvince && zone.name ===
                                    selectedProvince ? 'selected' : '';
                                options +=
                                    `<option value="${zone.name}" ${isSelected}>${zone.name}</option>`;
                            });
                            $('#state_container').show();
                        } else {
                            $('#state_container').hide();
                        }
                        $('#modal_province').html(options);
                    },
                    error: function() {
                        $('#state_container').hide();
                    }
                });
            }
            $('#modal_country').change(function() {
                fetchZones($(this).val());
            });

            function openAddressModal(type) {
                editingAddressType = type;
                $('#addAddressModalLabel').text(type === 'shipping' ? 'Edit shipping address' :
                    'Edit billing address');

                // clear form
                $('#modal_first_name').val('');
                $('#modal_last_name').val('');
                $('#modal_company').val('');
                $('#modal_address1').val('');
                $('#modal_address2').val('');
                $('#modal_city').val('');
                $('#modal_zip').val('');
                $('#modal_phone').val('');
                $('#modal_country').val('');
                $('#state_container').hide();

                // Fetch Addresses for Customer
                $.get("{{ route('admin.orders.get-addresses') }}", {
                    customer_id: selectedCustomer.id
                }, function(addresses) {
                    currentAddresses = addresses;
                    const select = $('#modal_existing_address');
                    select.empty();
                    select.append('<option value="" selected>New Address</option>');
                    addresses.forEach(addr => {
                        const label =
                            `${addr.first_name} ${addr.last_name}, ${addr.address1}, ${addr.city}` +
                            (addr.is_default ? ' (Default)' : '');
                        select.append(`<option value="${addr.id}">${label}</option>`);
                    });

                    // Pre-select logic
                    let currentId = null;
                    if (type === 'shipping') currentId = $('#input-shipping-address-id').val();
                    else currentId = $('#input-billing-address-id').val();

                    // Fallback to customer names if new
                    if (currentId) {
                        select.val(currentId);
                        fillModalForm(currentId);
                    } else {
                        $('#modal_first_name').val(selectedCustomer.first_name);
                        $('#modal_last_name').val(selectedCustomer.last_name);
                    }

                    const modal = new bootstrap.Modal(document.getElementById('addAddressModal'));
                    modal.show();
                });
            }

            $('#modal_existing_address').change(function() {
                const val = $(this).val();
                if (val) {
                    fillModalForm(val);
                } else {
                    // Reset form
                    $('#modal_first_name').val(selectedCustomer.first_name);
                    $('#modal_last_name').val(selectedCustomer.last_name);
                    $('#modal_company').val('');
                    $('#modal_address1').val('');
                    $('#modal_address2').val('');
                    $('#modal_city').val('');
                    $('#modal_zip').val('');
                    $('#modal_phone').val('');
                    $('#modal_country').val('');
                    $('#state_container').hide();
                }
            });

            function fillModalForm(addressId) {
                const addr = currentAddresses.find(a => a.id == addressId);
                if (!addr) return;

                // Country Select
                const option = $(`#modal_country option[data-name="${addr.country}"]`);
                if (option.length) {
                    $('#modal_country').val(option.val());
                    fetchZones(option.val(), addr.province);
                }
                $('#modal_first_name').val(addr.first_name);
                $('#modal_last_name').val(addr.last_name);
                $('#modal_company').val(addr.company);
                $('#modal_address1').val(addr.address1);
                $('#modal_address2').val(addr.address2);
                $('#modal_city').val(addr.city);
                $('#modal_zip').val(addr.zip);
                $('#modal_phone').val(addr.phone);
            }

            $(document).on('click', '#edit-shipping-address-btn, #link-create-address', function(e) {
                e.preventDefault();
                if (selectedCustomer) openAddressModal('shipping');
            });
            $(document).on('click', '#edit-billing-address-btn, #link-create-billing-address', function(e) {
                e.preventDefault();
                if (selectedCustomer) openAddressModal('billing');
            });

            $('#saveAddressBtn').click(function() {
                const btn = $(this);
                btn.prop('disabled', true).text('Saving...');

                const selectedId = $('#modal_existing_address').val();
                const rawData = {
                    country: $('#modal_country option:selected').data('name'),
                    first_name: $('#modal_first_name').val(),
                    last_name: $('#modal_last_name').val(),
                    company: $('#modal_company').val(),
                    address1: $('#modal_address1').val(),
                    address2: $('#modal_address2').val(),
                    city: $('#modal_city').val(),
                    province: $('#state_container').is(':visible') ? $('#modal_province').val() : '',
                    zip: $('#modal_zip').val(),
                    phone: $('#modal_phone').val(),
                };

                let hasChanges = false;
                if (selectedId) {
                    const addr = currentAddresses.find(a => a.id == selectedId);
                    if (addr) {
                        const fields = ['first_name', 'last_name', 'company', 'address1', 'address2',
                            'city', 'province', 'zip', 'phone', 'country'
                        ];
                        for (const field of fields) {
                            const original = (addr[field] || '').toString().trim().toLowerCase();
                            const current = (rawData[field] || '').toString().trim().toLowerCase();
                            if (original !== current) {
                                hasChanges = true;
                                break;
                            }
                        }
                    } else hasChanges = true;
                } else hasChanges = true;

                if (!hasChanges && selectedId) {
                    // Use Existing without creating new
                    const addr = currentAddresses.find(a => a.id == selectedId);
                    handleAddressUpdateSuccess(addr);
                    btn.prop('disabled', false).text('Save');
                    return;
                }

                // Create New Address
                const formData = new FormData();
                formData.append('customer_id', selectedCustomer.id);
                for (const key in rawData) formData.append(key, rawData[key]);

                const isDefault = editingAddressType === 'shipping';
                formData.append('is_default', isDefault ? '1' : '0');

                $.ajax({
                    url: "{{ route('admin.orders.store-address') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        handleAddressUpdateSuccess(response);
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Save');
                    }
                });
            });

            function handleAddressUpdateSuccess(address) {
                if (editingAddressType === 'shipping') {
                    updateAddressDisplay(address, 'shipping');
                    $('#input-shipping-address-id').val(address.id);
                } else {
                    billingAddress = address;
                    updateAddressDisplay(address, 'billing');
                    $('#input-billing-address-id').val(address.id);
                }
                bootstrap.Modal.getInstance(document.getElementById('addAddressModal')).hide();
            }

            // Utils
            function updateTotals() {
                let subtotal = 0;
                $('.order-item-row').each(function() {
                    const price = parseFloat($(this).find('.input-price').val()) || 0;
                    const qty = parseInt($(this).find('.input-quantity').val()) || 0;
                    const rowTotal = price * qty;
                    $(this).find('.item-total').text('₹' + rowTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2
                    }));
                    subtotal += rowTotal;
                });

                const shipping = parseFloat($('#input-shipping-amount').val()) || 0;
                const discount = parseFloat($('#input-discount-amount').val()) || 0;
                const total = subtotal + shipping - discount;

                $('#summary-subtotal').text('₹' + subtotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
                $('#summary-shipping').text(shipping > 0 ? '₹' + shipping.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }) : '—');
                $('#summary-discount').text(discount > 0 ? '₹' + discount.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }) : '—');
                $('#summary-total').text('₹' + total.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
            }

            $('#apply-shipping-btn').click(function() {
                const val = parseFloat($('#modal-shipping-rate').val()) || 0;
                $('#input-shipping-amount').val(val);
                updateTotals();
                bootstrap.Modal.getInstance(document.getElementById('addShippingModal')).hide();
            });

            $('#apply-discount-btn').click(function() {
                const val = parseFloat($('#modal-discount-value').val()) || 0;
                $('#input-discount-amount').val(val);
                updateTotals();
                bootstrap.Modal.getInstance(document.getElementById('addDiscountModal')).hide();
            });

            $('#add-custom-item-btn').click(function() {
                const modal = new bootstrap.Modal(document.getElementById('addCustomItemModal'));
                $('#modal-custom-name').val('');
                $('#modal-custom-price').val('');
                modal.show();
            });

            $('#confirm-custom-item-btn').click(function() {
                const name = $('#modal-custom-name').val();
                const price = parseFloat($('#modal-custom-price').val()) || 0;
                if (!name) {
                    alert('Please enter a name');
                    return;
                }

                addItem({
                    productId: null,
                    variantId: null,
                    name: name,
                    price: price,
                    isCustom: true
                });
                bootstrap.Modal.getInstance(document.getElementById('addCustomItemModal')).hide();
            });
            $(document).on('input', '.input-quantity', updateTotals);
            $(document).on('click', '.remove-item-btn', function() {
                $(this).closest('tr').remove();
                if ($('.order-item-row').length === 0) $('#empty-items-placeholder').show();
                updateTotals();
            });
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#customer-search, #customer-search-results').length) {
                    $('#customer-search-results').removeClass('show');
                }
            });
        });
    </script>
@endpush
