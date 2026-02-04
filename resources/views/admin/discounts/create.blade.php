@extends('admin.layouts.app')

@push('styles:after')
<style>
    .discount-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #ebebeb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        margin-bottom: 20px;
    }
    .discount-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f2f3;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .discount-card-body {
        padding: 20px;
    }
    .btn-shopify {
        background: #303030;
        color: #fff;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
    }
    .btn-shopify:hover {
        background: #1a1a1a;
        color: #fff;
    }
    .btn-shopify-secondary {
        background: #fff;
        border: 1px solid #dcdfe3;
        color: #1a1c1d;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 8px;
    }
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #1a1c1d;
        margin-bottom: 8px;
    }
    .method-btn-group .btn {
        border-color: #dcdfe3;
        color: #5c5f62;
        font-weight: 500;
        padding: 8px 16px;
    }
    .method-btn-group .btn-check:checked + .btn {
        background-color: #f1f2f3;
        border-color: #dcdfe3;
        color: #1a1c1d;
        cursor: default;
    }
    .summary-sidebar {
        position: sticky;
        top: 20px;
    }
    .summary-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #ebebeb;
    }
    .summary-card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f1f2f3;
    }
    .summary-card-body {
        padding: 20px;
    }
    .summary-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a1c1d;
        margin-bottom: 5px;
    }
    .summary-subtitle {
        font-size: 13px;
        color: #6d7175;
        margin-bottom: 15px;
    }
    .summary-heading {
        font-size: 12px;
        font-weight: 700;
        color: #1a1c1d;
        text-transform: uppercase;
        margin-top: 15px;
        margin-bottom: 10px;
    }
    .summary-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .summary-list li {
        font-size: 13px;
        color: #1a1c1d;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    .summary-list li::before {
        content: "•";
        margin-right: 8px;
        color: #6d7175;
    }
    .form-check-label {
        font-size: 14px;
        color: #1a1c1d;
    }
    .input-sm-fixed {
        width: 120px;
    }
    .modal-table-header {
        display: grid;
        grid-template-columns: 40px 1fr 100px 120px;
        padding: 10px 20px;
        background: #f9fafb;
        border-bottom: 1px solid #f1f2f3;
        font-size: 12px;
        font-weight: 600;
        color: #6d7175;
    }
    .modal-item-row {
        display: grid;
        grid-template-columns: 40px 1fr 100px 120px;
        padding: 12px 20px;
        align-items: center;
        border-bottom: 1px solid #f1f2f3;
        cursor: pointer;
    }
    .modal-item-row:hover {
        background: #f9fafb;
    }
    .modal-product-img {
        width: 36px;
        height: 36px;
        border-radius: 4px;
        object-fit: cover;
        border: 1px solid #ebebeb;
    }
    .variant-row {
        grid-template-columns: 80px 1fr 100px 120px;
        background: #fafafa;
    }
    .collection-item-row {
        display: grid;
        grid-template-columns: 40px 60px 1fr 100px;
        padding: 12px 20px;
        align-items: center;
        border-bottom: 1px solid #f1f2f3;
        cursor: pointer;
    }
    .collection-item-row {
        display: grid;
        grid-template-columns: 40px 60px 1fr 100px;
        padding: 12px 20px;
        align-items: center;
        border-bottom: 1px solid #f1f2f3;
        cursor: pointer;
    }
    .customer-item-row {
        display: grid;
        grid-template-columns: 40px 1fr 100px 120px;
        padding: 12px 20px;
        align-items: center;
        border-bottom: 1px solid #f1f2f3;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 1100px;">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.discounts.index') }}" class="text-dark me-2">
            <i class="ph ph-arrow-left"></i>
        </a>
        <h4 class="mb-0 f-w-700">Create discount</h4>
    </div>

    <form action="{{ route('admin.discounts.store') }}" method="POST" id="discountForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Header Card -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0">
                        <h6 class="mb-0 f-w-600">Amount off products</h6>
                    </div>
                    <div class="discount-card-body">
                        <label class="form-label">Method</label>
                        <div class="btn-group method-btn-group w-100 mb-4" role="group">
                            <input type="radio" class="btn-check" name="method" id="method_code" value="code" checked>
                            <label class="btn btn-outline-secondary" for="method_code">Discount code</label>

                            <input type="radio" class="btn-check" name="method" id="method_automatic" value="automatic">
                            <label class="btn btn-outline-secondary" for="method_automatic">Automatic discount</label>
                        </div>

                        <div id="codeSection">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Discount code</label>
                                <a href="javascript:void(0)" id="generateCode" class="text-primary f-s-13 text-decoration-none">Generate random code</a>
                            </div>
                            <input type="text" name="code" id="discountCode" class="form-control" placeholder="e.g. SUMMER2024">
                            <p class="text-muted f-s-12 mt-2 mb-0">Customers must enter this code at checkout.</p>
                        </div>

                        <div id="titleSection" class="d-none">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="discountTitle" class="form-control">
                            <p class="text-muted f-s-12 mt-2 mb-0">Customers will see this in their cart and at checkout.</p>
                        </div>
                    </div>
                </div>

                <!-- Value Card -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0">
                        <h6 class="mb-0 f-w-600">Discount value</h6>
                    </div>
                    <div class="discount-card-body">
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <select name="value_type" class="form-select" id="valueType">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed_amount">Fixed amount</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="number" name="value" class="form-control" placeholder="0">
                                    <span class="input-group-text" id="valueSuffix">%</span>
                                </div>
                            </div>
                        </div>

                        <label class="form-label">Applies to</label>
                        <select name="applies_to" class="form-select mb-3" id="appliesTo">
                            <option value="order">Entire order</option>
                            <option value="collections">Specific collections</option>
                            <option value="products">Specific products</option>
                        </select>

                        <div id="selectionArea" class="d-none">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="ph ph-magnifying-glass"></i></span>
                                <input type="text" class="form-control border-start-0" placeholder="Search items..." id="productSearchTrigger">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" id="browseBtn">Browse</button>
                            </div>
                            <div id="selectedItems" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Eligibility Card -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0">
                        <h6 class="mb-0 f-w-600">Eligibility</h6>
                    </div>
                    <div class="discount-card-body">
                        <p class="f-s-13 text-muted mb-3">Available on all sales channels</p>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="customer_selection" id="elig_all" value="all" checked>
                            <label class="form-check-label" for="elig_all">All customers</label>
                            <div class="ms-4 mt-2 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="apply_on_pos" id="pos_pro">
                                    <label class="form-check-label f-s-13" for="pos_pro">Apply on POS Pro locations</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="customer_selection" id="elig_specific" value="specific">
                            <label class="form-check-label" for="elig_specific">Specific customers</label>
                        </div>
                        <div id="customerSelectionArea" class="ms-4 mt-3 d-none">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="ph ph-magnifying-glass"></i></span>
                                <input type="text" class="form-control border-start-0" placeholder="Search customers" id="customerSearchTrigger">
                                <button class="btn btn-outline-secondary" type="button" id="browseCustomersBtn">Browse</button>
                            </div>
                            <div id="selectedCustomers" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Minimum Requirements Card -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0">
                        <h6 class="mb-0 f-w-600">Minimum purchase requirements</h6>
                    </div>
                    <div class="discount-card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="min_requirement_type" id="min_none" value="none" checked>
                            <label class="form-check-label" for="min_none">No minimum requirements</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="min_requirement_type" id="min_amount" value="amount">
                            <label class="form-check-label" for="min_amount">Minimum purchase amount ($)</label>
                        </div>
                        <div id="minAmountInput" class="ms-4 mb-3 d-none">
                            <input type="number" name="min_requirement_value" class="form-control input-sm-fixed" placeholder="0">
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="min_requirement_type" id="min_quantity" value="quantity">
                            <label class="form-check-label" for="min_quantity">Minimum quantity of items</label>
                        </div>
                        <div id="minQuantityInput" class="ms-4 d-none">
                            <input type="number" name="min_qty_value" class="form-control input-sm-fixed" placeholder="0">
                            <p class="text-muted f-s-12 mt-2 mb-0">Applies only to selected collections.</p>
                        </div>
                    </div>
                </div>

                <!-- Maximum Uses Card -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0">
                        <h6 class="mb-0 f-w-600">Maximum discount uses</h6>
                    </div>
                    <div class="discount-card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="limit_usage_total" id="limit_total">
                            <label class="form-check-label" for="limit_total">Limit number of times this discount can be used in total</label>
                        </div>
                        <div id="totalLimitInput" class="ms-4 mb-3 d-none">
                            <input type="number" name="usage_limit_total" class="form-control input-sm-fixed" placeholder="0">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="usage_limit_per_customer" id="limit_customer" value="1">
                            <label class="form-check-label" for="limit_customer">Limit to one use per customer</label>
                        </div>
                    </div>
                </div>

                <!-- Combinations Card -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0">
                        <h6 class="mb-0 f-w-600">Combinations</h6>
                    </div>
                    <div class="discount-card-body">
                        <p class="f-s-13 text-muted mb-3">This discount can be combined with:</p>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="combinations[]" id="comb_product" value="product_discounts">
                            <label class="form-check-label f-s-14" for="comb_product">Product discounts</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="combinations[]" id="comb_order" value="order_discounts">
                            <label class="form-check-label f-s-14" for="comb_order">Order discounts</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="combinations[]" id="comb_shipping" value="shipping_discounts">
                            <label class="form-check-label f-s-14" for="comb_shipping">Shipping discounts</label>
                        </div>
                    </div>
                </div>

                <!-- Active Dates Card -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0">
                        <h6 class="mb-0 f-w-600">Active dates</h6>
                    </div>
                    <div class="discount-card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start date</label>
                                <input type="date" name="starts_at_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start time (IST)</label>
                                <input type="time" name="starts_at_time" class="form-control" value="{{ date('H:i') }}">
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="setEndDate">
                            <label class="form-check-label" for="setEndDate">Set end date</label>
                        </div>
                        <div id="endDateSection" class="row d-none">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End date</label>
                                <input type="date" name="ends_at_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End time</label>
                                <input type="time" name="ends_at_time" class="form-control" value="23:59">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Summary -->
            <div class="col-lg-4">
                <div class="summary-sidebar">
                    <div class="summary-card mb-3">
                        <div class="summary-card-body">
                            <div class="summary-title" id="summaryTitleDisplay">No discount code yet</div>
                            <div class="summary-subtitle" id="summaryMethodDisplay">Code</div>
                            
                            <p class="summary-heading">Type</p>
                            <div class="f-s-13 mb-1">Amount off products</div>
                            <div class="text-muted f-s-12"><i class="ph ph-tag me-1"></i> Product discount</div>

                            <p class="summary-heading">Details</p>
                            <ul class="summary-list" id="summaryDetails">
                                <li id="sum_cust">All customers</li>
                                <li id="sum_pos">POS excluded</li>
                                <li id="sum_comb">Can't combine with other discounts</li>
                                <li id="sum_date">Active from today</li>
                            </ul>
                        </div>
                    </div>

                    <div class="summary-card mb-3">
                        <div class="summary-card-body">
                            <p class="summary-heading mt-0">Sales channel access</p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="feat_channels">
                                <label class="form-check-label f-s-13" for="feat_channels">Allow discount to be featured on selected channels</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-shopify">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Item Browse Modal -->
<div class="modal fade" id="itemBrowseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title f-w-600">Select Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 border-bottom">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="ph ph-magnifying-glass"></i></span>
                        <input type="text" class="form-control border-start-0" id="modalSearch" placeholder="Search...">
                    </div>
                </div>
                <div id="modalHeaderRow" class="modal-table-header d-none">
                    <div></div>
                    <div id="modalHeaderText1">Product</div>
                    <div id="modalHeaderText2">Available</div>
                    <div id="modalHeaderText3">Price</div>
                </div>
                <div id="modalItemList" style="max-height: 500px; overflow-y: auto;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-shopify" id="modalAddBtn">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts:after')
<script>
    $(document).ready(function() {
        // Method selection
        $('input[name="method"]').on('change', function() {
            const method = $(this).val();
            if (method === 'code') {
                $('#codeSection').removeClass('d-none');
                $('#titleSection').addClass('d-none');
                $('#summaryMethodDisplay').text('Code');
            } else {
                $('#codeSection').addClass('d-none');
                $('#titleSection').removeClass('d-none');
                $('#summaryMethodDisplay').text('Automatic');
            }
            updateSummary();
        });

        // Generate Code
        $('#generateCode').on('click', function() {
            const code = Math.random().toString(36).substring(2, 10).toUpperCase();
            $('#discountCode').val(code).trigger('input');
        });

        // Value type suffix
        $('#valueType').on('change', function() {
            $('#valueSuffix').text($(this).val() === 'percentage' ? '%' : '$');
        });

        // Applies to toggle
        $('#appliesTo').on('change', function() {
            const val = $(this).val();
            if (val === 'order') {
                $('#selectionArea').addClass('d-none');
            } else {
                $('#selectionArea').removeClass('d-none');
                $('#itemSearchTrigger').attr('placeholder', 'Search ' + val + '...');
            }
        });

        // Min requirements toggle
        $('input[name="min_requirement_type"]').on('change', function() {
            const val = $(this).val();
            $('#minAmountInput').toggleClass('d-none', val !== 'amount');
            $('#minQuantityInput').toggleClass('d-none', val !== 'quantity');
        });

        // Max usage toggle
        $('#limit_total').on('change', function() {
            $('#totalLimitInput').toggleClass('d-none', !$(this).is(':checked'));
        });

        // End date toggle
        $('#setEndDate').on('change', function() {
            $('#endDateSection').toggleClass('d-none', !$(this).is(':checked'));
        });

        // Eligibility toggle
        $('input[name="customer_selection"]').on('change', function() {
            $('#customerSelectionArea').toggleClass('d-none', $(this).val() !== 'specific');
        });

        // Summary live updates
        $('#discountCode, #discountTitle').on('input', function() {
            updateSummary();
        });

        function updateSummary() {
            const method = $('input[name="method"]:checked').val();
            const val = method === 'code' ? $('#discountCode').val() : $('#discountTitle').val();
            $('#summaryTitleDisplay').text(val || (method === 'code' ? 'No discount code yet' : 'No title yet'));
        }

        // Data from backend
        const products = {!! $products->toJson() !!};
        const collections = {!! $collections->toJson() !!};
        const customers = {!! $customers->toJson() !!};

        // Helper to format currency
        function formatCurrency(amount) {
            return '$' + (amount / 100).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Helper to check if item is already selected on main form
        function isSelected(type, id) {
            return $(`#selected-${type}-${id}`).length > 0;
        }

        // Helper to update parent product checkbox based on variant selection
        function updateParentCheckbox(productId) {
            const parentCb = $(`.product-cb[data-id="${productId}"]`);
            const variantCbs = $(`.variant-cb[data-pid="${productId}"]`);
            const total = variantCbs.length;
            const checked = variantCbs.filter(':checked').length;

            if (checked === 0) {
                parentCb.prop('checked', false).prop('indeterminate', false);
            } else if (checked === total) {
                parentCb.prop('checked', true).prop('indeterminate', false);
            } else {
                parentCb.prop('checked', false).prop('indeterminate', true);
            }
        }

        // Consolidated Modal Rendering with Filtering
        function renderModalItems(type, query = '') {
            const modalBody = $('#modalItemList');
            const header = $('#modalHeaderRow');
            modalBody.empty();
            query = query.toLowerCase();

            if (type === 'customers') {
                header.removeClass('d-none');
                $('#modalHeaderText1').text('Customer');
                $('#modalHeaderText2').text('Orders');
                $('#modalHeaderText3').text('Spent');
                $('.modal-title').text('Add customers');

                const filtered = customers.filter(c => 
                    (c.first_name + ' ' + c.last_name).toLowerCase().includes(query) || 
                    c.email.toLowerCase().includes(query)
                );

                filtered.forEach(customer => {
                    const name = customer.full_name || (customer.first_name + ' ' + customer.last_name);
                    const checked = isSelected('customer', customer.id) ? 'checked' : '';
                    modalBody.append(`
                        <div class="customer-item-row" data-id="${customer.id}" data-type="customer">
                            <div><input type="checkbox" class="form-check-input customer-cb" data-id="${customer.id}" data-name="${name}" ${checked}></div>
                            <div class="d-flex flex-column">
                                <span class="f-s-14 f-w-600 text-primary">${name}</span>
                                <span class="text-muted f-s-12">${customer.email}</span>
                            </div>
                            <div class="f-s-13">${customer.total_orders || 0} orders</div>
                            <div class="f-s-13">${formatCurrency(customer.total_spent * 100)}</div>
                        </div>
                    `);
                });
            } else if (type === 'products') {
                header.removeClass('d-none');
                $('#modalHeaderText1').text('Product');
                $('#modalHeaderText2').text('Available');
                $('#modalHeaderText3').text('Price');
                $('.modal-title').text('Add products');

                products.forEach(product => {
                    const matchesProduct = product.title.toLowerCase().includes(query);
                    const variants = product.variants || [];
                    const matchingVariants = variants.filter(v => {
                        const attrs = v.attributes || [];
                        return attrs.some(a => {
                            const val = a.pivot?.value || '';
                            return val.toLowerCase().includes(query);
                        });
                    });

                    if (matchesProduct || matchingVariants.length > 0) {
                        const images = product.images || [];
                        const img = images.length > 0 ? `/storage/${images[0].path}` : 'https://via.placeholder.com/60x60?text=No+Image';
                        const checked = isSelected('product', product.id) ? 'checked' : '';
                        
                        modalBody.append(`
                            <div class="modal-item-row" data-id="${product.id}" data-type="product">
                                <div><input type="checkbox" class="form-check-input product-cb" data-id="${product.id}" data-name="${product.title}" ${checked}></div>
                                <div class="d-flex align-items-center">
                                    <img src="${img}" class="modal-product-img me-3" onerror="this.src='/admins/svg/_sprite.svg#shop'">
                                    <span class="f-s-14 f-w-600">${product.title}</span>
                                </div>
                                <div class="text-muted f-s-13">${variants.length > 0 ? '' : product.quantity}</div>
                                <div class="text-muted f-s-13">${variants.length > 0 ? '' : formatCurrency(product.price)}</div>
                            </div>
                        `);

                        const variantsToRender = matchesProduct ? variants : matchingVariants;
                        variantsToRender.forEach(variant => {
                            const attrs = variant.attributes || [];
                            const vName = attrs.map(a => a.pivot?.value || '').filter(v => v).join(' / ') || 'Default';
                            const vChecked = isSelected('variant', variant.id) ? 'checked' : '';
                            modalBody.append(`
                                <div class="modal-item-row variant-row ps-5" data-id="${variant.id}" data-pid="${product.id}" data-type="variant">
                                    <div><input type="checkbox" class="form-check-input variant-cb" data-id="${variant.id}" data-name="${product.title} - ${vName}" data-pid="${product.id}" ${vChecked}></div>
                                    <div class="f-s-13 text-muted">${vName}</div>
                                    <div class="f-s-13 text-muted">${variant.quantity}</div>
                                    <div class="f-s-13 text-muted">${formatCurrency(variant.price)}</div>
                                </div>
                            `);
                        });
                    }
                });

                // Update parent checkboxes for all products with variants
                products.forEach(product => {
                    if (product.variants && product.variants.length > 0) {
                        updateParentCheckbox(product.id);
                    }
                });
            } else if (type === 'collections') {
                header.addClass('d-none');
                $('.modal-title').text('Add collections');
                
                collections.filter(c => c.title.toLowerCase().includes(query)).forEach(collection => {
                    const img = collection.image ? `/storage/${collection.image}` : '';
                    const checked = isSelected('collection', collection.id) ? 'checked' : '';
                    modalBody.append(`
                        <div class="collection-item-row" data-id="${collection.id}" data-type="collection">
                            <div><input type="checkbox" class="form-check-input collection-cb" data-id="${collection.id}" data-name="${collection.title}" ${checked}></div>
                            <div class="d-flex-center bg-light rounded shadow-sm overflow-hidden" style="width: 48px; height: 48px; border: 1px solid #eee;">
                                ${img ? `<img src="${img}" style="width: 100%; height: 100%; object-fit: cover;">` : '<i class="ph ph-image text-muted"></i>'}
                            </div>
                            <div class="ps-3">
                                <div class="f-s-14 f-w-600">${collection.title}</div>
                                <div class="text-muted f-s-12">${collection.products_count} products</div>
                            </div>
                        </div>
                    `);
                });
            }
        }

        let currentModalType = 'products';

        $('#browseBtn, #browseCustomersBtn').on('click', function() {
            currentModalType = $(this).attr('id') === 'browseCustomersBtn' ? 'customers' : $('#appliesTo').val();
            $('#modalSearch').val('');
            renderModalItems(currentModalType);
            $('#itemBrowseModal').modal('show');
        });

        // Live Modal Search
        $('#modalSearch').on('input', function() {
            renderModalItems(currentModalType, $(this).val());
        });

        // Auto-Modal on Search from Main Form
        $('#customerSearchTrigger, #productSearchTrigger').on('input', function() {
            const val = $(this).val();
            if (val.length > 0) {
                currentModalType = $(this).attr('id') === 'customerSearchTrigger' ? 'customers' : $('#appliesTo').val();
                $('#modalSearch').val(val);
                renderModalItems(currentModalType, val);
                $('#itemBrowseModal').modal('show');
                $(this).val(''); // Clear trigger
            }
        });

        $(document).on('click', '.modal-item-row, .collection-item-row, .customer-item-row', function(e) {
            if (e.target.type !== 'checkbox') {
                const cb = $(this).find('input[type="checkbox"]');
                cb.prop('checked', !cb.prop('checked')).trigger('change');
            }
        });

        // Update parent product checkbox when variant selection changes
        $(document).on('change', '.variant-cb', function() {
            const productId = $(this).data('pid');
            updateParentCheckbox(productId);
        });

        // When parent product checkbox is clicked, toggle all variants
        $(document).on('change', '.product-cb', function() {
            const productId = $(this).data('id');
            const isChecked = $(this).prop('checked');
            $(`.variant-cb[data-pid="${productId}"]`).prop('checked', isChecked);
        });

        $('#modalAddBtn').on('click', function() {
            const appliesToType = $('#appliesTo').val();
            
            $('#modalItemList input:checked').each(function() {
                const isVariant = $(this).hasClass('variant-cb');
                const isCustomer = $(this).hasClass('customer-cb');
                
                if (isVariant && $(`.product-cb[data-id="${$(this).data('pid')}"]`).is(':checked')) return; 

                const id = $(this).data('id');
                const name = $(this).data('name');
                const targetType = isCustomer ? 'customer' : (isVariant ? 'variant' : appliesToType.slice(0, -1)); 
                
                const uniqueId = `selected-${targetType}-${id}`;
                const selectedArea = isCustomer ? $('#selectedCustomers') : $('#selectedItems');
                const icon = isCustomer ? 'user' : (appliesToType === 'products' ? 'tag' : 'folders');

                if (!$(`#${uniqueId}`).length) {
                    selectedArea.append(`
                        <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded" id="${uniqueId}">
                            <div class="d-flex align-items-center">
                                <i class="ph ph-${icon} me-2"></i>
                                <span class="f-s-13">${name}</span>
                                <input type="hidden" name="${isCustomer ? 'customer_ids[]' : 'targets[]'}" value="${id}">
                                ${!isCustomer ? `<input type="hidden" name="target_types[]" value="${targetType}">` : ''}
                            </div>
                            <button type="button" class="btn btn-link link-danger p-0 ms-2" onclick="$(this).closest('.bg-light').remove()">
                                <i class="ph ph-x"></i>
                            </button>
                        </div>
                    `);
                }
            });
            $('#itemBrowseModal').modal('hide');
        });
    });
</script>
@endpush
