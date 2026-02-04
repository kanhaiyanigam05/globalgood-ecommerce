@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <!-- Breadcrumb start -->
                <div class="row m-1">
                    <div class="col-12">
                        <h4 class="main-title">Create order</h4>
                        <ul class="app-line-breadcrumbs mb-3">
                            <li>
                                <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                                    <span><i class="ph-duotone ph-house f-s-16"></i> Home</span>
                                </a>
                            </li>
                            <li>
                                <a class="f-s-14 f-w-500" href="{{ route('admin.orders.index') }}">
                                    <span><i class="ph-duotone ph-shopping-cart f-s-16"></i> Orders</span>
                                </a>
                            </li>
                            <li class="active">
                                <a class="f-s-14 f-w-500" href="#">Create order</a>
                            </li>
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
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#browseProductsModal">Browse</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="add-custom-item-btn">Add custom item</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                                        <input type="text" id="product-search" class="form-control border-start-0" placeholder="Search products...">
                                    </div>
                                    <div id="product-search-results" class="dropdown-menu w-100 shadow-sm" style="max-height: 300px; overflow-y: auto;"></div>
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
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    Add a product to calculate total and view payment options
                                                </td>
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
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal</span>
                                    <span id="summary-subtotal">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-primary">
                                    <a href="#" class="text-decoration-none" id="add-discount-link">Add discount</a>
                                    <span id="summary-discount">—</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-primary">
                                    <a href="#" class="text-decoration-none" id="add-shipping-link">Add shipping</a>
                                    <span id="summary-shipping">—</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Estimated tax</span>
                                    <span id="summary-tax">Not calculated</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold f-s-18">
                                    <span>Total</span>
                                    <span id="summary-total">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Notes -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Notes</h6>
                                <button type="button" class="btn btn-link btn-sm p-0"><i class="ph ph-pencil-simple"></i></button>
                            </div>
                            <div class="card-body">
                                <textarea name="notes" class="form-control" rows="2" placeholder="No notes"></textarea>
                            </div>
                        </div>

                        <!-- Customer Selection -->
                        <div class="card shadow-sm mb-4" id="customer-card">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Customer</h6>
                                <button type="button" class="btn btn-link btn-sm p-0 text-danger d-none" id="remove-customer-btn"><i class="ph ph-x"></i></button>
                            </div>
                            <div class="card-body">
                                <div id="customer-search-wrapper">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text bg-white border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                                        <input type="text" id="customer-search" class="form-control border-start-0" placeholder="Search or create a customer">
                                    </div>
                                    <div id="customer-search-results" class="dropdown-menu w-100 shadow-sm" style="max-height: 250px; overflow-y: auto;"></div>
                                </div>
                                <div id="selected-customer-info" class="d-none">
                                    <div class="d-flex flex-column">
                                        <a href="#" class="fw-medium text-primary mb-1" id="display-customer-name">No name</a>
                                        <span class="text-muted small" id="display-customer-email">No email</span>
                                        <span class="text-muted small" id="display-customer-orders">No orders</span>
                                    </div>
                                    <hr class="my-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h7 class="mb-0 f-s-14 fw-semibold text-muted">CONTACT INFORMATION</h7>
                                        <button type="button" class="btn btn-link btn-sm p-0"><i class="ph ph-pencil-simple"></i></button>
                                    </div>
                                    <span class="d-block small text-muted mb-3" id="display-customer-phone">No phone number</span>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h7 class="mb-0 f-s-14 fw-semibold text-muted">SHIPPING ADDRESS</h7>
                                        <button type="button" class="btn btn-link btn-sm p-0"><i class="ph ph-pencil-simple"></i></button>
                                    </div>
                                    <span class="d-block small text-muted" id="display-customer-address">No shipping address provided</span>
                                </div>
                            </div>
                        </div>

                        <!-- Market / Tags -->
                         <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Tags</h6>
                                <button type="button" class="btn btn-link btn-sm p-0"><i class="ph ph-pencil-simple"></i></button>
                            </div>
                            <div class="card-body">
                                <input type="text" name="tags" class="form-control" placeholder="Search or create tags">
                            </div>
                        </div>

                        <div class="card shadow-sm border-primary">
                            <div class="card-body d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Collect payment</button>
                                <button type="submit" name="action" value="send_invoice" class="btn btn-outline-secondary">Send invoice</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="customer_id" id="input-customer-id">
                </x-forms.form>
            </div>
        </div>
    </div>

    <!-- Modals & Templates will be added here -->
    
    {{-- Templates --}}
    <template id="order-item-row-template">
        <tr class="order-item-row">
            <td>
                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #eee;">
                    <i class="ph ph-image text-muted"></i>
                </div>
            </td>
            <td>
                <div class="fw-medium item-name">PRODUCT_NAME</div>
                <div class="text-muted small item-variant">VARIANT_TITLE</div>
                <input type="hidden" name="items[INDEX][product_id]" class="input-product-id">
                <input type="hidden" name="items[INDEX][variant_id]" class="input-variant-id">
                <input type="hidden" name="items[INDEX][price]" class="input-price">
            </td>
            <td>
                <input type="number" name="items[INDEX][quantity]" class="form-control form-control-sm input-quantity" value="1" min="1">
            </td>
            <td>
                <span class="item-total">$0.00</span>
            </td>
            <td class="text-end">
                <button type="button" class="btn btn-link text-danger p-0 remove-item-btn">
                    <i class="ph ph-x"></i>
                </button>
            </td>
        </tr>
    </template>
@endsection

@push('scripts:after')
<script>
    $(document).ready(function() {
        let itemsCount = 0;
        let selectedCustomer = null;

        // --- Product Search ---
        let productSearchTimeout;
        $('#product-search').on('input', function() {
            const query = $(this).val();
            clearTimeout(productSearchTimeout);
            
            if (query.length < 2) {
                $('#product-search-results').removeClass('show').empty();
                return;
            }

            productSearchTimeout = setTimeout(() => {
                $.get("{{ route('admin.orders.search-products') }}", { q: query }, function(products) {
                    const dropdown = $('#product-search-results');
                    dropdown.empty();
                    
                    if (products.length > 0) {
                        products.forEach(product => {
                            if (product.variants && product.variants.length > 0) {
                                product.variants.forEach(variant => {
                                    dropdown.append(`
                                        <button type="button" class="dropdown-item py-2 add-item-btn" 
                                            data-product-id="${product.id}" 
                                            data-variant-id="${variant.id}"
                                            data-name="${product.name}" 
                                            data-variant-name="${variant.name || ''}"
                                            data-price="${variant.price}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-medium">${product.name}</span>
                                                    <div class="text-muted small">${variant.name || 'Standard'}</div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-medium">$${variant.price}</div>
                                                    <div class="text-muted small">${variant.stock_quantity || 0} available</div>
                                                </div>
                                            </div>
                                        </button>
                                    `);
                                });
                            } else {
                                dropdown.append(`
                                    <button type="button" class="dropdown-item py-2 add-item-btn" 
                                        data-product-id="${product.id}" 
                                        data-name="${product.name}" 
                                        data-price="${product.price}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>${product.name}</span>
                                            <span>$${product.price}</span>
                                        </div>
                                    </button>
                                `);
                            }
                        });
                        dropdown.addClass('show');
                    } else {
                        dropdown.removeClass('show');
                    }
                });
            }, 300);
        });

        // Add item from search
        $(document).on('click', '.add-item-btn', function() {
            const data = $(this).data();
            addItem(data);
            $('#product-search').val('');
            $('#product-search-results').removeClass('show');
        });

        function addItem(data) {
            $('#empty-items-placeholder').hide();
            
            const template = $('#order-item-row-template').html();
            const index = itemsCount++;
            const html = $(template.replace(/INDEX/g, index)
                                 .replace(/PRODUCT_NAME/g, data.name)
                                 .replace(/VARIANT_TITLE/g, data.variantName || ''));
            
            html.find('.input-product-id').val(data.productId);
            html.find('.input-variant-id').val(data.variantId);
            html.find('.input-price').val(data.price);
            
            $('#order-items-list').append(html);
            updateTotals();
        }

        // --- Customer Search ---
        let customerSearchTimeout;
        $('#customer-search').on('input', function() {
            const query = $(this).val();
            clearTimeout(customerSearchTimeout);
            
            if (query.length < 2) {
                $('#customer-search-results').removeClass('show').empty();
                return;
            }

            customerSearchTimeout = setTimeout(() => {
                $.get("{{ route('admin.orders.search-customers') }}", { q: query }, function(customers) {
                    const dropdown = $('#customer-search-results');
                    dropdown.empty();
                    
                    dropdown.append(`
                        <button type="button" class="dropdown-item py-2 text-primary fw-medium" id="btn-create-customer">
                            <i class="ph ph-plus-circle me-1"></i> Create a new customer
                        </button>
                    `);

                    if (customers.length > 0) {
                        customers.forEach(customer => {
                            dropdown.append(`
                                <button type="button" class="dropdown-item py-2 select-customer-btn" 
                                    data-id="${customer.id}" 
                                    data-firstname="${customer.first_name}" 
                                    data-lastname="${customer.last_name}"
                                    data-email="${customer.email}"
                                    data-phone="${customer.phone || 'No phone'}">
                                    <div class="fw-medium">${customer.first_name} ${customer.last_name}</div>
                                    <div class="text-muted small">${customer.email}</div>
                                </button>
                            `);
                        });
                        dropdown.addClass('show');
                    } else {
                        dropdown.addClass('show'); // show only "create new"
                    }
                });
            }, 300);
        });

        // Select customer
        $(document).on('click', '.select-customer-btn', function() {
            const data = $(this).data();
            selectedCustomer = data;
            
            $('#customer-search-wrapper').addClass('d-none');
            $('#selected-customer-info').removeClass('d-none');
            $('#remove-customer-btn').removeClass('d-none');
            $('#input-customer-id').val(data.id);
            
            $('#display-customer-name').text(data.firstname + ' ' + data.lastname);
            $('#display-customer-email').text(data.email);
            $('#display-customer-phone').text(data.phone);
            
            $('#customer-search-results').removeClass('show');
        });

        $('#remove-customer-btn').click(function() {
            selectedCustomer = null;
            $('#customer-search-wrapper').removeClass('d-none');
            $('#selected-customer-info').addClass('d-none');
            $('#remove-customer-btn').addClass('d-none');
            $('#input-customer-id').val('');
            $('#customer-search').val('');
        });

        // --- Totals Calculation ---
        function updateTotals() {
            let subtotal = 0;
            $('.order-item-row').each(function() {
                const price = parseFloat($(this).find('.input-price').val()) || 0;
                const qty = parseInt($(this).find('.input-quantity').val()) || 0;
                const rowTotal = price * qty;
                $(this).find('.item-total').text('$' + rowTotal.toLocaleString());
                subtotal += rowTotal;
            });

            $('#summary-subtotal').text('$' + subtotal.toLocaleString());
            // TODO: Add shipping/tax calculation here
            $('#summary-total').text('$' + subtotal.toLocaleString());
        }

        $(document).on('input', '.input-quantity', updateTotals);

        $(document).on('click', '.remove-item-btn', function() {
            $(this).closest('tr').remove();
            if ($('.order-item-row').length === 0) {
                $('#empty-items-placeholder').show();
            }
            updateTotals();
        });

        // Close dropdowns on outside click
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#product-search, #product-search-results').length) {
                $('#product-search-results').removeClass('show');
            }
            if (!$(e.target).closest('#customer-search, #customer-search-results').length) {
                $('#customer-search-results').removeClass('show');
            }
        });
    });
</script>
@endpush
