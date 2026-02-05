@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <!-- Breadcrumb -->
                <div class="row m-1">
                    <div class="col-12">
                        <h4 class="main-title">Edit order</h4>
                        <ul class="app-line-breadcrumbs mb-3">
                            <li><a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}"><span><i class="ph-duotone ph-house f-s-16"></i> Home</span></a></li>
                            <li><a class="f-s-14 f-w-500" href="{{ route('admin.orders.index') }}"><span><i class="ph-duotone ph-shopping-cart f-s-16"></i> Orders</span></a></li>
                            <li class="active"><a class="f-s-14 f-w-500" href="#">{{ $order->order_number }}</a></li>
                        </ul>
                    </div>
                </div>

                <x-forms.form :action="route('admin.orders.update', $order->id)" method="post" id="order-form" class="row">
                    @method('PUT')
                    
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
                                    <div class="input-group" data-bs-toggle="modal" data-bs-target="#browseProductsModal" style="cursor: pointer;">
                                        <span class="input-group-text bg-white border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                                        <input type="text" class="form-control border-start-0" placeholder="Browse products..." readonly style="background-color: #fff; cursor: pointer;">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="order-items-table">
                                        <thead>
                                            <tr><th width="50"></th><th>Product</th><th width="100">Quantity</th><th>Total</th><th width="50"></th></tr>
                                        </thead>
                                        <tbody id="order-items-list">
                                            <tr id="empty-items-placeholder"><td colspan="5" class="text-center py-5 text-muted">Add a product to calculate total and view payment options</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white"><h6 class="mb-0">Payment</h6></div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span id="summary-subtotal">₹0.00</span></div>
                                <div class="d-flex justify-content-between mb-2 text-primary">
                                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#addDiscountModal" id="add-discount-link">Add discount</a>
                                    <span id="summary-discount">—</span>
                                    <input type="hidden" name="discount_amount" id="input-discount-amount" value="{{ $order->discount_amount / 100 }}">
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-primary">
                                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#addShippingModal" id="add-shipping-link">Add shipping</a>
                                    <span id="summary-shipping">—</span>
                                    <input type="hidden" name="shipping_amount" id="input-shipping-amount" value="{{ $order->shipping_amount / 100 }}">
                                </div>
                                <div class="d-flex justify-content-between mb-2"><span>Estimated tax</span><span id="summary-tax">₹{{ $order->formatted_tax_amount }}</span></div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold f-s-18"><span>Total</span><span id="summary-total">₹0.00</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Status Management -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Status</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Order status</label>
                                    <select name="order_status" class="form-select">
                                        <option value="open" {{ $order->order_status === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="archived" {{ $order->order_status === 'archived' ? 'selected' : '' }}>Archived</option>
                                        <option value="canceled" {{ $order->order_status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment status</label>
                                    <select name="payment_status" class="form-select">
                                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="partially_paid" {{ $order->payment_status === 'partially_paid' ? 'selected' : '' }}>Partially paid</option>
                                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        <option value="voided" {{ $order->payment_status === 'voided' ? 'selected' : '' }}>Voided</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Fulfillment status</label>
                                    <select name="fulfillment_status" class="form-select">
                                        <option value="unfulfilled" {{ ($order->fulfillment_status === 'unfulfilled' || !$order->fulfillment_status) ? 'selected' : '' }}>Unfulfilled</option>
                                        <option value="fulfilled" {{ $order->fulfillment_status === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                                        <option value="partially_fulfilled" {{ $order->fulfillment_status === 'partially_fulfilled' ? 'selected' : '' }}>Partially fulfilled</option>
                                        <option value="restocked" {{ $order->fulfillment_status === 'restocked' ? 'selected' : '' }}>Restocked</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Notes</h6>
                                <button type="button" class="btn btn-link btn-sm p-0"><i class="ph ph-pencil-simple"></i></button>
                            </div>
                            <div class="card-body"><textarea name="notes" class="form-control" rows="2" placeholder="No notes">{{ $order->notes }}</textarea></div>
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
                                    <div class="d-flex flex-column mb-3">
                                        <a href="#" class="fw-medium text-primary mb-1 text-decoration-none" id="display-customer-name">No name</a>
                                        <span class="text-muted small" id="display-customer-orders">No orders</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h7 class="mb-0 f-s-14 fw-bold text-dark">Contact information</h7>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-muted" id="edit-contact-btn"><i class="ph ph-pencil-simple"></i></button>
                                    </div>
                                    <div class="mb-3">
                                        <a href="#" class="d-block text-decoration-none" id="display-customer-email">No email</a>
                                        <span class="d-block text-muted small" id="display-customer-phone">No phone number</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h7 class="mb-0 f-s-14 fw-bold text-dark">Shipping address</h7>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-muted" id="edit-shipping-address-btn"><i class="ph ph-pencil-simple"></i></button>
                                    </div>
                                    <div class="mb-3">
                                        <span class="d-block text-muted small" id="display-customer-address">No shipping address provided</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h7 class="mb-0 f-s-14 fw-bold text-dark">Billing address</h7>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-muted" id="edit-billing-address-btn"><i class="ph ph-pencil-simple"></i></button>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check d-none" id="billing-same-check-wrapper">
                                            <input class="form-check-input" type="checkbox" name="billing_same_as_shipping" value="1" id="billing-same-as-shipping" checked>
                                            <label class="form-check-label text-muted small" for="billing-same-as-shipping">Same as shipping address</label>
                                        </div>
                                        <span class="d-block text-muted small mt-1" id="display-billing-address">Same as shipping address</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                         <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Tags</h6>
                                <button type="button" class="btn btn-link btn-sm p-0"><i class="ph ph-pencil-simple"></i></button>
                            </div>
                            <div class="card-body"><input type="text" name="tags" class="form-control" placeholder="Search or create tags" value="{{ implode(',', $order->tags ?? []) }}"></div>
                        </div>

                        <div class="card shadow-sm border-primary">
                            <div class="card-body d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Update order</button>
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
    
    <!-- Create Customer Modal (Same as Create) -->
    <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Create a new customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="create-customer-errors" class="alert alert-danger d-none"></div>
                    <form id="create-customer-form">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6"><label class="form-label">First name</label><input type="text" class="form-control" name="first_name" required></div>
                            <div class="col-md-6"><label class="form-label">Last name</label><input type="text" class="form-control" name="last_name" required></div>
                        </div>
                         <div class="mb-3">
                             <label class="form-label">Language</label>
                             <select class="form-select" name="language">
                                 <option value="en">English [Default]</option>
                             </select>
                             <div class="form-text">This customer will receive notifications in this language.</div>
                        </div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="email_marketing_consent" value="1" id="create-email-marketing">
                                <label class="form-check-label" for="create-email-marketing">Customer accepts email marketing</label>
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
                                @foreach($countries as $country) 
                                    <option value="{{ $country->name }}" data-id="{{ $country->id }}" data-postalcode="{{ $country->postalcode }}" data-flag="{{ $country->flag_url }}">{{ $country->name }}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label class="form-label">Company</label><input type="text" class="form-control" name="address[company]"></div>
                        <div class="mb-3"><label class="form-label">Address</label><input type="text" class="form-control" name="address[address1]"></div>
                        <div class="mb-3"><label class="form-label">Apartment, suite, etc</label><input type="text" class="form-control" name="address[address2]"></div>
                        <div class="row g-3 mb-3">
                             <div class="col-md-4"><label class="form-label">City</label><input type="text" class="form-control" name="address[city]"></div>
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
                                    <div class="d-flex align-items-center justify-content-center w-100 h-100 bg-white border rounded-start px-2">
                                        <img src="{{ asset('flags/untitle.svg') }}" id="create-phone-flag" width="24" alt="Flag" style="object-fit: contain;">
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
    <!-- Shipping and Delivery Modal -->
    <div class="modal fade" id="addShippingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Shipping and delivery options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="shipping-rates-container" class="mb-4">
                        <label class="form-label d-block fw-bold mb-3">Shipping rates</label>
                        <div id="shipping-rates-list" class="list-group list-group-flush border rounded">
                            <div class="list-group-item text-center py-4 text-muted small">
                                Select a customer to see eligible shipping rates
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="shipping_type" id="shipping_type_custom" value="custom">
                            <label class="form-check-label fw-bold" for="shipping_type_custom">Custom shipping</label>
                        </div>
                        <div id="custom-shipping-fields" class="ps-4" style="display: none;">
                            <div class="row g-2">
                                <div class="col-md-7">
                                    <label class="form-label small">Name</label>
                                    <input type="text" id="modal-custom-shipping-name" class="form-control form-control-sm" placeholder="Free shipping">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small">Price</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" id="modal-custom-shipping-price" class="form-control" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-dark" id="apply-shipping-btn" disabled>Done</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Discount Modal -->
    <div class="modal fade" id="addDiscountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add discount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold small">Discount codes</label>
                        <select id="modal-discount-select" class="form-select">
                            <option value="">Select a discount</option>
                            @foreach($discounts as $discount)
                                <option value="{{ $discount->code }}" data-value="{{ $discount->value }}" data-type="{{ $discount->value_type }}">
                                    {{ $discount->code }} ({{ $discount->value_type == 'percentage' ? (float)$discount->value.'%' : '₹'.number_format($discount->value, 2) }} off)
                                </option>
                            @endforeach
                        </select>

                        <div id="applied-discount-code-info" class="mt-2" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded border">
                                <div>
                                    <span class="badge bg-secondary me-2" id="display-applied-code">CODE</span>
                                    <span class="small text-muted" id="display-applied-desc">Desc</span>
                                </div>
                                <button type="button" class="btn-close small" id="remove-applied-code"></button>
                            </div>
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="apply-automatic-discounts">
                            <label class="form-check-label small" for="apply-automatic-discounts">Apply all eligible automatic discounts</label>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="add-custom-discount-toggle">
                            <label class="form-check-label fw-bold small" for="add-custom-discount-toggle">Add custom order discount</label>
                        </div>
                        <div id="custom-discount-fields" class="ps-4 mt-2" style="display: none;">
                            <div class="btn-group btn-group-sm mb-3 w-100" role="group">
                                <input type="radio" class="btn-check" name="custom_discount_type" id="discount_type_fixed" value="fixed" checked>
                                <label class="btn btn-outline-secondary" for="discount_type_fixed">Fixed amount</label>
                                <input type="radio" class="btn-check" name="custom_discount_type" id="discount_type_percentage" value="percentage">
                                <label class="btn btn-outline-secondary" for="discount_type_percentage">Percentage</label>
                            </div>
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label small">Discount value</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text" id="custom-discount-symbol">₹</span>
                                        <input type="number" id="modal-custom-discount-value" class="form-control" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-dark" id="apply-discount-btn">Done</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Item Modal -->
    <div class="modal fade" id="addCustomItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Add custom item</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Item name</label><input type="text" id="modal-custom-name" class="form-control" placeholder="Short description"></div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" id="modal-custom-price" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-dark" id="confirm-custom-item-btn">Add item</button></div>
            </div>
        </div>
    </div>

    {{-- Template --}}
    <template id="order-item-row-template">
        <tr class="order-item-row">
            <td><div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #eee;"><i class="ph ph-image text-muted"></i></div></td>
            <td>
                <div class="fw-medium item-name">PRODUCT_NAME</div>
                <div class="text-muted small item-variant">VARIANT_TITLE</div>
                <input type="hidden" name="items[INDEX][product_id]" class="input-product-id">
                <input type="hidden" name="items[INDEX][variant_id]" class="input-variant-id">
                <input type="hidden" name="items[INDEX][price]" class="input-price">
                <input type="hidden" name="items[INDEX][name]" class="input-item-name">
                <input type="hidden" name="items[INDEX][is_custom]" class="input-is-custom" value="0">
            </td>
            <td><input type="number" name="items[INDEX][quantity]" class="form-control form-control-sm input-quantity" value="1" min="1"></td>
            <td><span class="item-total">₹0.00</span></td>
            <td class="text-end"><button type="button" class="btn btn-link text-danger p-0 remove-item-btn"><i class="ph ph-x"></i></button></td>
        </tr>
    </template>
@endsection

@push('scripts:after')
<script>
    $(document).ready(function() {
        let itemsCount = 0;
        let selectedCustomer = null;
        let editingAddressType = 'shipping'; 
        let billingAddress = null; 
        let currentAddresses = []; 

        // --- Data Injection for Edit ---
        const existingItems = @json($order->items);
        const existingCustomer = @json($order->customer);
        const savedShipping = @json($order->shipping_address);
        const savedBilling = @json($order->billing_address);

        // --- Pre-fill Logic ---
        
        // 1. Items
        if (existingItems && existingItems.length > 0) {
            $('#empty-items-placeholder').hide();
            existingItems.forEach(item => {
                let imgUrl = null;
                if (item.product && item.product.images) {
                    const images = Array.isArray(item.product.images) ? item.product.images : Object.values(item.product.images);
                    if (images.length > 0) imgUrl = images[0].thumb;
                }
                
                addItem({
                    productId: item.product_id,
                    variantId: item.variant_id,
                    name: item.name ?? (item.product ? item.product.title : 'Unknown'),
                    variantName: item.variant ? item.variant.title : '',
                    price: item.price / 100,
                    img: imgUrl,
                    quantity: item.quantity
                });
            });
        }

        // 2. Customer
        if (existingCustomer) {
            selectCustomer(existingCustomer, false); // false = don't auto-set default addresses yet
            
            // 3. Addresses - Force Override from Saved Order Data
            
            // Try to find matching address IDs for the snapshots
            if (savedShipping) {
                updateAddressDisplay(savedShipping, 'shipping');
                // Attempt to match snapshot with current customer addresses to set ID
                if (existingCustomer.addresses) {
                    const match = existingCustomer.addresses.find(a => 
                        (a.address1 || '').trim().toLowerCase() === (savedShipping.address1 || '').trim().toLowerCase() && 
                        (a.city || '').trim().toLowerCase() === (savedShipping.city || '').trim().toLowerCase() && 
                        (a.zip || '').trim().toLowerCase() === (savedShipping.zip || '').trim().toLowerCase()
                    );
                    if (match) $('#input-shipping-address-id').val(match.id);
                }
            } 
            
            if (savedBilling) {
                updateAddressDisplay(savedBilling, 'billing');
                if (existingCustomer.addresses) {
                    const match = existingCustomer.addresses.find(a => 
                        (a.address1 || '').trim().toLowerCase() === (savedBilling.address1 || '').trim().toLowerCase() && 
                        (a.city || '').trim().toLowerCase() === (savedBilling.city || '').trim().toLowerCase() && 
                        (a.zip || '').trim().toLowerCase() === (savedBilling.zip || '').trim().toLowerCase()
                    );
                    if (match) {
                        $('#input-billing-address-id').val(match.id);
                        billingAddress = match;
                    } else {
                        billingAddress = savedBilling;
                        $('#input-billing-address-id').val('');
                    }
                }
            } else {
                // ... (check equality logic below)
            }
            
            // Check equality to set "Same as shipping" toggle
            if (savedShipping && savedBilling && JSON.stringify(savedShipping) === JSON.stringify(savedBilling)) {
                 $('#billing-same-as-shipping').prop('checked', true);
                 updateBillingDisplay();
            } else if (savedShipping && !savedBilling) {
                 $('#billing-same-as-shipping').prop('checked', true);
                 updateBillingDisplay();
            } else {
                 $('#billing-same-as-shipping').prop('checked', false);
                 updateBillingDisplay();
            }
        }

        // --- Standard Logic (Copied from Create) ---

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
            window.dispatchEvent(new CustomEvent('provide-selection-state', { detail: { selectedIds: currentItems } }));
        });

        function addItem(data) {
             $('#empty-items-placeholder').hide();
            const template = $('#order-item-row-template').html();
            const index = itemsCount++;
            const html = $(template.replace(/INDEX/g, index).replace(/PRODUCT_NAME/g, data.name).replace(/VARIANT_TITLE/g, data.variantName || ''));
            if (data.img) html.find('.ph-image').replaceWith(`<img src="${data.img}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">`);
            html.find('.input-product-id').val(data.productId || '');
            html.find('.input-variant-id').val(data.variantId || '');
            html.find('.input-price').val(data.price);
            html.find('.input-quantity').val(data.quantity || 1);
            html.find('.input-item-name').val(data.name);
            if (data.isCustom) html.find('.input-is-custom').val('1');
            
            $('#order-items-list').append(html);
            updateTotals();
        }

        const customerSearchInput = $('#customer-search');
        let customerSearchTimeout;

        function fetchCustomers(query = '') {
            $.get("{{ route('admin.orders.search-customers') }}", { q: query }, function(customers) {
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
        
        customerSearchInput.on('focus', function() { if (!$(this).val()) fetchCustomers(''); else $('#customer-search-results').addClass('show'); });
        customerSearchInput.on('input', function() { clearTimeout(customerSearchTimeout); customerSearchTimeout = setTimeout(() => fetchCustomers($(this).val()), 300); });
        
         $(document).on('click', '#btn-open-create-customer', function() {
             const modal = new bootstrap.Modal(document.getElementById('createCustomerModal'));
             modal.show();
             $('#customer-search-results').removeClass('show');
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

        // Modified SelectCustomer to take optional 'setDefaultAddress' flag
        function selectCustomer(customer, setDefaultAddress = true) {
            selectedCustomer = customer;
            $('#customer-search-wrapper').addClass('d-none');
            $('#selected-customer-info').removeClass('d-none');
            $('#remove-customer-btn').removeClass('d-none');
            $('#input-customer-id').val(customer.id);
            
            const fullName = (customer.first_name || '') + ' ' + (customer.last_name || '');
            $('#display-customer-name').text(fullName.trim() || 'No name');
            $('#display-customer-email').text(customer.email || 'No email');
            $('#display-customer-phone').text(customer.phone || 'No phone number');
            
            if (setDefaultAddress) {
                // Set Default Addresses Logic (For Creation or Changing Customer)
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
            } else {
                 // For Edit Mode Initial Load: Do nothing to addresses, let the caller handle it override
            }
            $('#customer-search-results').removeClass('show');
        }

        function updateAddressDisplay(address, type = 'shipping') {
            const displayEl = type === 'shipping' ? $('#display-customer-address') : $('#display-billing-address');
            const btnEl = type === 'shipping' ? $('#edit-shipping-address-btn') : $('#edit-billing-address-btn');
            
            if (address) {
                const formattedAddr = [address.address1, address.city, address.country].filter(Boolean).join(', ');
                displayEl.text(formattedAddr);
                btnEl.html('<i class="ph ph-pencil-simple"></i>');
                btnEl.removeClass('d-none');
            } else {
                 const linkId = type === 'shipping' ? 'link-create-address' : 'link-create-billing-address';
                 const text = type === 'shipping' ? 'Create address' : 'Add billing address';
                displayEl.html(`<a href="#" id="${linkId}" class="text-primary text-decoration-none">${text}</a>`);
                btnEl.html('');
            }
        }

        function updateBillingDisplay() {
            if ($('#billing-same-as-shipping').is(':checked')) {
                $('#display-billing-address').text('Same as shipping address');
                $('#edit-billing-address-btn').addClass('d-none');
                $('#input-billing-address-id').val(''); 
            } else {
                 $('#edit-billing-address-btn').removeClass('d-none');
                 updateAddressDisplay(billingAddress, 'billing');
                 if(billingAddress) $('#input-billing-address-id').val(billingAddress.id);
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
        
        $('#billing-same-as-shipping').change(function() { updateBillingDisplay(); });

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
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(response) {
                    selectCustomer(response);
                    bootstrap.Modal.getInstance(document.getElementById('createCustomerModal')).hide();
                    document.getElementById('create-customer-form').reset();
                },
                error: function(xhr) { 
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorHtml = '<ul class="mb-0">';
                        for (const key in errors) errorHtml += `<li>${errors[key][0]}</li>`;
                        errorHtml += '</ul>';
                        errorContainer.html(errorHtml).removeClass('d-none');
                    } else { alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error')); }
                },
                complete: function() { btn.prop('disabled', false).text('Save'); }
            });
        });

        // Zone Fetching for Create Customer
        function fetchZonesForCreate(countryName) {
            const option = $(`#create-country-select option[value="${countryName}"]`);
            const countryId = option.data('id');
            const postalCodeName = option.data('postalcode');
            const flag = option.data('flag');
            
            if(flag) $('#create-phone-flag').attr('src', flag);
            
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
                data: { country_id: countryId },
                success: function(data) {
                    let options = '<option value="" disabled selected>Select a state</option>';
                    if(data.length > 0) {
                        data.forEach(function(zone) { options += `<option value="${zone.name}">${zone.name}</option>`; });
                        $('#create-state-container').show();
                    } else { $('#create-state-container').hide(); }
                    $('#create-province-select').html(options);
                },
                error: function() { $('#create-state-container').hide(); }
            });
        }
        $('#create-country-select').change(function() { fetchZonesForCreate($(this).val()); });

        // Address Modal
        function fetchZones(countryId, selectedProvince = null) {
            const countryOption = $(`#modal_country option[value="${countryId}"]`);
            const flag = countryOption.data('flag');
            const postalCodeName = countryOption.data('postalcode');
             if(flag) $('#modal_phone_flag').attr('src', flag);
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
                data: { country_id: countryId },
                success: function(data) {
                    let options = '<option value="" disabled selected>Select a state</option>';
                    if(data.length > 0) {
                        data.forEach(function(zone) {
                            const isSelected = selectedProvince && zone.name === selectedProvince ? 'selected' : '';
                            options += `<option value="${zone.name}" ${isSelected}>${zone.name}</option>`;
                        });
                        $('#state_container').show();
                    } else { $('#state_container').hide(); }
                    $('#modal_province').html(options);
                },
                error: function() { $('#state_container').hide(); }
            });
        }
        $('#modal_country').change(function() { fetchZones($(this).val()); });

        function openAddressModal(type) {
            editingAddressType = type;
            $('#addAddressModalLabel').text(type === 'shipping' ? 'Edit shipping address' : 'Edit billing address');
            $('#modal_first_name').val(''); $('#modal_last_name').val(''); $('#modal_company').val('');
            $('#modal_address1').val(''); $('#modal_address2').val(''); $('#modal_city').val('');
            $('#modal_zip').val(''); $('#modal_phone').val(''); $('#modal_country').val(''); $('#state_container').hide();
            
             $.get("{{ route('admin.orders.get-addresses') }}", { customer_id: selectedCustomer.id }, function(addresses) {
                 currentAddresses = addresses;
                 const select = $('#modal_existing_address');
                 select.empty();
                 select.append('<option value="" selected>New Address</option>');
                 addresses.forEach(addr => {
                     const label = `${addr.first_name} ${addr.last_name}, ${addr.address1}, ${addr.city}` + (addr.is_default ? ' (Default)' : '');
                     select.append(`<option value="${addr.id}">${label}</option>`);
                 });
                 let currentId = (type === 'shipping') ? $('#input-shipping-address-id').val() : $('#input-billing-address-id').val();
                 
                 if (currentId) { 
                     select.val(currentId.toString()); 
                     if (select.val() == currentId) {
                         fillModalForm(currentId); 
                     } else {
                         const snapshot = (type === 'shipping') ? savedShipping : (billingAddress || savedBilling);
                         if (snapshot) fillModalFromSnapshot(snapshot);
                     }
                 } else {
                     const snapshot = (type === 'shipping') ? savedShipping : (billingAddress || savedBilling);
                     if (snapshot) {
                         fillModalFromSnapshot(snapshot);
                     } else {
                         $('#modal_first_name').val(selectedCustomer.first_name); 
                         $('#modal_last_name').val(selectedCustomer.last_name);
                     }
                 }
                 
                 const modal = new bootstrap.Modal(document.getElementById('addAddressModal'));
                 modal.show();
             });
        }
        $('#modal_existing_address').change(function() {
            const val = $(this).val();
            if (val) fillModalForm(val);
            else {
                $('#modal_first_name').val(selectedCustomer.first_name); $('#modal_last_name').val(selectedCustomer.last_name);
                $('#modal_company').val(''); $('#modal_address1').val(''); $('#modal_address2').val(''); $('#modal_city').val('');
                $('#modal_zip').val(''); $('#modal_phone').val(''); $('#modal_country').val(''); $('#state_container').hide();
            }
        });

        function fillModalFromSnapshot(addr) {
             const option = $(`#modal_country option[data-name="${addr.country}"]`);
             if (option.length) { $('#modal_country').val(option.val()); fetchZones(option.val(), addr.province); }
             $('#modal_first_name').val(addr.first_name); $('#modal_last_name').val(addr.last_name);
             $('#modal_company').val(addr.company); $('#modal_address1').val(addr.address1);
             $('#modal_address2').val(addr.address2); $('#modal_city').val(addr.city);
             $('#modal_zip').val(addr.zip); $('#modal_phone').val(addr.phone);
        }

        function fillModalForm(addressId) {
             const addr = currentAddresses.find(a => a.id == addressId);
             if (!addr) return;
             fillModalFromSnapshot(addr);
        }

        $(document).on('click', '#edit-shipping-address-btn, #link-create-address', function(e) {
            e.preventDefault(); if(selectedCustomer) openAddressModal('shipping');
        });
        $(document).on('click', '#edit-billing-address-btn, #link-create-billing-address', function(e) {
            e.preventDefault(); if(selectedCustomer) openAddressModal('billing');
        });

        $('#saveAddressBtn').click(function() {
            const btn = $(this);
            btn.prop('disabled', true).text('Saving...');
            const selectedId = $('#modal_existing_address').val();
            const rawData = {
                country: $('#modal_country option:selected').data('name'),
                first_name: $('#modal_first_name').val(), last_name: $('#modal_last_name').val(),
                company: $('#modal_company').val(), address1: $('#modal_address1').val(),
                address2: $('#modal_address2').val(), city: $('#modal_city').val(),
                province: $('#state_container').is(':visible') ? $('#modal_province').val() : '',
                zip: $('#modal_zip').val(), phone: $('#modal_phone').val(),
            };

            let hasChanges = false;
            if (selectedId) {
                const addr = currentAddresses.find(a => a.id == selectedId);
                if (addr) {
                    const fields = ['first_name', 'last_name', 'company', 'address1', 'address2', 'city', 'province', 'zip', 'phone', 'country'];
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
                const addr = currentAddresses.find(a => a.id == selectedId);
                handleAddressUpdateSuccess(addr);
                btn.prop('disabled', false).text('Save');
                return;
            }

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
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(response) { handleAddressUpdateSuccess(response); },
                error: function(xhr) { alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error')); },
                complete: function() { btn.prop('disabled', false).text('Save'); }
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

        // --- New Order Options Logic ---
        let appliedDiscountCode = null;
        let currentTaxRate = 0;

        function updateTotals() {
            let subtotal = 0;
            $('.order-item-row').each(function() {
                const price = parseFloat($(this).find('.input-price').val()) || 0;
                const qty = parseInt($(this).find('.input-quantity').val()) || 0;
                const rowTotal = price * qty;
                $(this).find('.item-total').text('₹' + rowTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
                subtotal += rowTotal;
            });

            const shipping = parseFloat($('#input-shipping-amount').val()) || 0;
            const discount = parseFloat($('#input-discount-amount').val()) || 0;
            
            // Fetch Tax via AJAX
            const customerId = selectedCustomer ? selectedCustomer.id : null;
            const addressId = $('#input-shipping-address-id').val();

            if (subtotal > 0) {
                $.ajax({
                    url: "{{ route('admin.orders.calculate-tax') }}",
                    method: "POST",
                    data: {
                        customer_id: customerId,
                        address_id: addressId,
                        subtotal: subtotal,
                        shipping_amount: shipping
                    },
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(response) {
                        const taxAmount = response.tax_amount;
                        currentTaxRate = response.tax_rate;
                        $('#input-tax-amount').val(taxAmount);
                        $('#summary-tax').text('₹' + taxAmount.toLocaleString(undefined, {minimumFractionDigits: 2}));
                        
                        const total = subtotal + shipping + taxAmount - discount;
                        renderSummary(subtotal, shipping, discount, taxAmount, total);
                    }
                });
            } else {
                renderSummary(subtotal, shipping, discount, 0, subtotal + shipping - discount);
            }
        }

        function renderSummary(subtotal, shipping, discount, tax, total) {
            $('#summary-subtotal').text('₹' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
            $('#summary-shipping').text(shipping > 0 ? '₹' + shipping.toLocaleString(undefined, {minimumFractionDigits: 2}) : '—');
            $('#summary-discount').text(discount > 0 ? '₹' + discount.toLocaleString(undefined, {minimumFractionDigits: 2}) : '—');
            $('#summary-tax').text(tax > 0 ? '₹' + tax.toLocaleString(undefined, {minimumFractionDigits: 2}) : '—');
            $('#summary-total').text('₹' + total.toLocaleString(undefined, {minimumFractionDigits: 2}));
        }

        // Shipping Logic
        $(document).on('click', '[data-bs-target="#addShippingModal"]', function() {
            if (!selectedCustomer) {
                alert('Please select a customer first');
                return;
            }
            const addressId = $('#input-shipping-address-id').val();
            
            $('#shipping-rates-list').html('<div class="list-group-item text-center py-4"><div class="spinner-border spinner-border-sm"></div></div>');
            
            $.get("{{ route('admin.orders.get-shipping-rates') }}", {
                customer_id: selectedCustomer.id,
                address_id: addressId
            }, function(rates) {
                if (rates.length === 0) {
                    $('#shipping-rates-list').html('<div class="list-group-item text-center py-4 text-muted small">No shipping rates found for this address</div>');
                } else {
                    let html = '';
                    rates.forEach(rate => {
                        html += `
                            <label class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div class="form-check w-100">
                                    <input class="form-check-input" type="radio" name="shipping_rate_select" value="${rate.price}" data-name="${rate.name}">
                                    <span class="form-check-label ms-2">
                                        <span class="d-block fw-medium">${rate.name}</span>
                                        <span class="text-muted small">${rate.type === 'price_based' ? 'Standard' : 'Weight based'}</span>
                                    </span>
                                </div>
                                <span class="fw-bold">₹${parseFloat(rate.price).toLocaleString()}</span>
                            </label>
                        `;
                    });
                    $('#shipping-rates-list').html(html);
                }
            });
        });

        $(document).on('change', 'input[name="shipping_type"], input[name="shipping_rate_select"]', function() {
            const type = $('input[name="shipping_type"]:checked').val();
            $('#custom-shipping-fields').toggle(type === 'custom');
            $('#apply-shipping-btn').prop('disabled', false);
        });

        $('#apply-shipping-btn').click(function() {
            const type = $('input[name="shipping_type"]:checked').val();
            let amount = 0;
            let name = '';

            if (type === 'custom') {
                amount = parseFloat($('#modal-custom-shipping-price').val()) || 0;
                name = $('#modal-custom-shipping-name').val() || 'Custom shipping';
            } else {
                const selected = $('input[name="shipping_rate_select"]:checked');
                amount = parseFloat(selected.val()) || 0;
                name = selected.data('name');
            }

            $('#input-shipping-amount').val(amount);
            $('#shipping-link').text(`${name} (₹${amount.toLocaleString()})`);
            updateTotals();
            bootstrap.Modal.getInstance(document.getElementById('addShippingModal')).hide();
        });

        // Discount Logic
        $('#modal-discount-select').change(function() {
            const selected = $(this).find('option:selected');
            const code = selected.val();
            if (!code) return;

            const value = parseFloat(selected.data('value')) || 0;
            const type = selected.data('type');
            const subtotal = parseFloat($('#summary-subtotal').text().replace('₹', '').replace(',', '')) || 0;
            
            let amount = (type === 'percentage') ? (subtotal * value / 100) : value;

            appliedDiscountCode = { code: code, amount: amount };
            $('#display-applied-code').text(code);
            $('#display-applied-desc').text(`₹${amount.toLocaleString()} off`);
            $('#applied-discount-code-info').show();
            $(this).prop('disabled', true);
        });

        $('#remove-applied-code').click(function() {
            appliedDiscountCode = null;
            $('#applied-discount-code-info').hide();
            $('#modal-discount-select').val('').prop('disabled', false);
        });

        $('#add-custom-discount-toggle').change(function() {
            $('#custom-discount-fields').toggle(this.checked);
        });

        $('input[name="custom_discount_type"]').change(function() {
            $('#custom-discount-symbol').text(this.value === 'percentage' ? '%' : '₹');
        });

        $('#apply-discount-btn').click(function() {
            let totalDiscount = 0;
            let desc = '';

            if (appliedDiscountCode) {
                totalDiscount += appliedDiscountCode.amount;
                desc += appliedDiscountCode.code;
            }

            if ($('#add-custom-discount-toggle').is(':checked')) {
                const val = parseFloat($('#modal-custom-discount-value').val()) || 0;
                const type = $('input[name="custom_discount_type"]:checked').val();
                let customAmt = (type === 'percentage') ? (parseFloat($('#summary-subtotal').text().replace('₹', '').replace(',', '')) * val / 100) : val;
                totalDiscount += customAmt;
                desc += (desc ? ' + ' : '') + 'Custom';
            }

            $('#input-discount-amount').val(totalDiscount);
            $('#discount-link').text(totalDiscount > 0 ? `${desc} (₹${totalDiscount.toLocaleString()})` : 'Add discount');
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
            if (!name) { alert('Please enter a name'); return; }
            addItem({ productId: null, variantId: null, name: name, price: price, isCustom: true });
            bootstrap.Modal.getInstance(document.getElementById('addCustomItemModal')).hide();
        });

        $(document).on('input', '.input-quantity', updateTotals);
        $(document).on('click', '.remove-item-btn', function() {
            $(this).closest('tr').remove();
            if ($('.order-item-row').length === 0) $('#empty-items-placeholder').show();
            updateTotals();
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#customer-search, #customer-search-results').length) $('#customer-search-results').removeClass('show');
        });
    });
</script>
@endpush
