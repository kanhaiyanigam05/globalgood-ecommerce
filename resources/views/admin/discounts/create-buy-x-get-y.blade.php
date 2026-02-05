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
    .summary-card { background: #fff; border-radius: 12px; border: 1px solid #ebebeb; }
    .summary-card-body { padding: 20px; }
    .summary-title { font-size: 14px; font-weight: 600; color: #1a1c1d; margin-bottom: 5px; }
    .summary-subtitle { font-size: 13px; color: #6d7175; margin-bottom: 15px; }
    .summary-heading { font-size: 12px; font-weight: 700; color: #1a1c1d; text-transform: uppercase; margin-top: 15px; margin-bottom: 10px; }
    .summary-list { list-style: none; padding: 0; margin: 0; }
    .summary-list li { font-size: 13px; color: #1a1c1d; margin-bottom: 8px; display: flex; align-items: center; }
    .summary-list li::before { content: "•"; margin-right: 8px; color: #6d7175; }
    .input-sm-fixed { width: 120px; }
    .modal-table-header { display: grid; grid-template-columns: 40px 1fr 100px 120px; padding: 10px 20px; background: #f9fafb; border-bottom: 1px solid #f1f2f3; font-size: 12px; font-weight: 600; color: #6d7175; }
    .modal-item-row { display: grid; grid-template-columns: 40px 1fr 100px 120px; padding: 12px 20px; align-items: center; border-bottom: 1px solid #f1f2f3; cursor: pointer; }
    .modal-item-row:hover { background: #f9fafb; }
    .modal-product-img { width: 36px; height: 36px; border-radius: 4px; object-fit: cover; border: 1px solid #ebebeb; }
    .variant-row { grid-template-columns: 80px 1fr 100px 120px; background: #fafafa; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 1100px;">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.discounts.select-type') }}" class="text-dark me-2">
            <i class="ph ph-arrow-left"></i>
        </a>
        <h4 class="mb-0 f-w-700">Create buy X get Y discount</h4>
    </div>

    <form action="{{ route('admin.discounts.store') }}" method="POST" id="discountForm">
        @csrf
        <input type="hidden" name="type" value="buy_x_get_y">
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Method & Code Card -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0">
                        <h6 class="mb-0 f-w-600">Buy X get Y</h6>
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
                            <input type="text" name="code" id="discountCode" class="form-control" placeholder="e.g. BOGO2024" required>
                        </div>
                        <div id="titleSection" class="d-none">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="discountTitle" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Customer Buys Section -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0">
                        <h6 class="mb-0 f-w-600">Customer buys</h6>
                    </div>
                    <div class="discount-card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="buy_type" id="buy_qty" value="quantity" checked>
                            <label class="form-check-label" for="buy_qty">Minimum quantity of items</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="buy_type" id="buy_amount" value="amount">
                            <label class="form-check-label" for="buy_amount">Minimum purchase amount ($)</label>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label" id="buyValueLabel">Quantity</label>
                                <input type="number" name="buy_value" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Any items from</label>
                                <select name="buy_items_type" class="form-select items-type-select" data-target="#buySelectedItems">
                                    <option value="products">Specific products</option>
                                    <option value="collections">Specific collections</option>
                                </select>
                            </div>
                        </div>

                        <div id="buySelectionArea">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="ph ph-magnifying-glass"></i></span>
                                <input type="text" class="form-control border-start-0" placeholder="Search items...">
                                <button class="btn btn-outline-secondary browse-items-btn" type="button" 
                                    data-target-area="#buySelectedItems" 
                                    data-target-input="buy_items[]"
                                    data-type-input="buy_target_types[]">Browse</button>
                            </div>
                            <div id="buySelectedItems" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Customer Gets Section -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0">
                        <h6 class="mb-0 f-w-600">Customer gets</h6>
                        <p class="text-muted f-s-12 mb-0">Customers must add the quantity of items specified below to their cart.</p>
                    </div>
                    <div class="discount-card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="get_quantity" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Any items from</label>
                                <select name="get_items_type" class="form-select items-type-select" data-target="#getSelectedItems">
                                    <option value="products">Specific products</option>
                                    <option value="collections">Specific collections</option>
                                </select>
                            </div>
                        </div>

                        <div id="getSelectionArea" class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="ph ph-magnifying-glass"></i></span>
                                <input type="text" class="form-control border-start-0" placeholder="Search items...">
                                <button class="btn btn-outline-secondary browse-items-btn" type="button" 
                                    data-target-area="#getSelectedItems" 
                                    data-target-input="get_items[]"
                                    data-type-input="get_target_types[]">Browse</button>
                            </div>
                            <div id="getSelectedItems" class="mt-3"></div>
                        </div>

                        <label class="form-label">At a discounted value</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="get_type" id="get_perc" value="percentage" checked>
                            <label class="form-check-label" for="get_perc">Percentage</label>
                        </div>
                        <div id="getPercInput" class="ms-4 mb-3">
                            <div class="input-group input-sm-fixed">
                                <input type="number" name="get_value" class="form-control" placeholder="0" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="get_type" id="get_amount" value="fixed_amount">
                            <label class="form-check-label" for="get_amount">Amount off each ($)</label>
                        </div>
                        <div id="getAmountInput" class="ms-4 mb-3 d-none">
                            <input type="number" name="get_value_fixed" class="form-control input-sm-fixed" placeholder="0">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="get_type" id="get_free" value="free">
                            <label class="form-check-label" for="get_free">Free</label>
                        </div>

                        <div class="form-check pt-3 border-top">
                            <input class="form-check-input" type="checkbox" name="limit_uses_per_order" id="limit_uses_order">
                            <label class="form-check-label" for="limit_uses_order">Set a maximum number of uses per order</label>
                        </div>
                        <div id="maxUsesOrderInput" class="ms-4 mt-2 d-none">
                            <input type="number" name="max_uses_per_order" class="form-control input-sm-fixed" placeholder="1">
                        </div>
                    </div>
                </div>

                <!-- Standard Sections (Eligibility, etc.) -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Eligibility</h6></div>
                    <div class="discount-card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="customer_selection" id="elig_all" value="all" checked>
                            <label class="form-check-label" for="elig_all">All customers</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="customer_selection" id="elig_specific" value="specific">
                            <label class="form-check-label" for="elig_specific">Specific customers</label>
                        </div>
                        <div id="customerSelectionArea" class="ms-4 mt-3 d-none">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="ph ph-magnifying-glass"></i></span>
                                <input type="text" class="form-control border-start-0" placeholder="Search customers" id="customerSearchTrigger">
                                <button class="btn btn-outline-secondary" type="button" id="browseCustomersBtn" data-target-input="customer_ids[]">Browse</button>
                            </div>
                            <div id="selectedCustomers" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Active Dates -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Active dates</h6></div>
                    <div class="discount-card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Start date</label><input type="date" name="starts_at_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Start time (IST)</label><input type="time" name="starts_at_time" class="form-control" value="{{ date('H:i') }}" required></div>
                        </div>
                        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="setEndDate"><label class="form-check-label" for="setEndDate">Set end date</label></div>
                        <div id="endDateSection" class="row d-none">
                            <div class="col-md-6 mb-3"><label class="form-label">End date</label><input type="date" name="ends_at_date" class="form-control"></div>
                            <div class="col-md-6 mb-3"><label class="form-label">End time</label><input type="time" name="ends_at_time" class="form-control" value="23:59"></div>
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
                            <div class="f-s-13 mb-1">Buy X get Y</div>
                            <div class="text-muted f-s-12"><i class="ph ph-tag me-1"></i> Product discount</div>
                            <p class="summary-heading">Details</p>
                            <ul class="summary-list" id="summaryDetails">
                                <li>All customers</li>
                                <li>Active from today</li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end"><button type="submit" class="btn btn-shopify">Save discount</button></div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('admin.discounts._modals')
@endsection

@push('scripts:after')
<script>
    function generateRandomCode(length = 8) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    $(document).ready(function() {
        $('#generateCode').on('click', function() {
            $('#discountCode').val(generateRandomCode());
        });

        $('.items-type-select').on('change', function() {
            const targetArea = $(this).data('target');
            $(targetArea).find('#buySelectedItems, #getSelectedItems').empty();
        });

        // Shared logic toggles
        $('input[name="method"]').on('change', function() {
            const isCode = $(this).val() === 'code';
            $('#codeSection').toggleClass('d-none', !isCode);
            $('#titleSection').toggleClass('d-none', isCode);
            $('#summaryMethodDisplay').text(isCode ? 'Code' : 'Automatic');
        });

        $('input[name="buy_type"]').on('change', function() {
            $('#buyValueLabel').text($(this).val() === 'quantity' ? 'Quantity' : 'Minimum purchase amount ($)');
        });

        $('input[name="get_type"]').on('change', function() {
            const val = $(this).val();
            $('#getPercInput').toggleClass('d-none', val !== 'percentage');
            $('#getAmountInput').toggleClass('d-none', val !== 'fixed_amount');
            
            // Tweak required attributes
            $('input[name="get_value"]').prop('required', val === 'percentage');
            $('input[name="get_value_fixed"]').prop('required', val === 'fixed_amount');
        });

        $('#limit_uses_order').on('change', function() {
            $('#maxUsesOrderInput').toggleClass('d-none', !$(this).is(':checked'));
        });

        $('#setEndDate').on('change', function() {
            $('#endDateSection').toggleClass('d-none', !$(this).is(':checked'));
        });

        $('input[name="customer_selection"]').on('change', function() {
            $('#customerSelectionArea').toggleClass('d-none', $(this).val() !== 'specific');
        });

        // Initialize modals
        initializeDiscountModals('products');
    });
</script>
@endpush
