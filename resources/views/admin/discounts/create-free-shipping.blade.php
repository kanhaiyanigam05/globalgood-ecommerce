@extends('admin.layouts.app')

@push('styles:after')
<style>
    .discount-card { background: #fff; border-radius: 12px; border: 1px solid #ebebeb; margin-bottom: 20px; }
    .discount-card-header { padding: 16px 20px; border-bottom: 1px solid #f1f2f3; }
    .discount-card-body { padding: 20px; }
    .btn-shopify { background: #303030; color: #fff; font-weight: 600; padding: 8px 16px; border-radius: 8px; border: none; }
    .btn-shopify:hover { background: #1a1a1a; color: #fff; }
    .form-label { font-size: 13px; font-weight: 600; color: #1a1c1d; margin-bottom: 8px; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 1100px;">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.discounts.select-type') }}" class="text-dark me-2"><i class="ph ph-arrow-left"></i></a>
        <h4 class="mb-0 f-w-700">Create free shipping discount</h4>
    </div>

    <form action="{{ route('admin.discounts.store') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="free_shipping">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Free shipping</h6></div>
                    <div class="discount-card-body">
                        <label class="form-label">Method</label>
                        <div class="btn-group w-100 mb-4">
                            <input type="radio" class="btn-check" name="method" id="method_code" value="code" checked>
                            <label class="btn btn-outline-secondary" for="method_code">Discount code</label>
                            <input type="radio" class="btn-check" name="method" id="method_automatic" value="automatic">
                            <label class="btn btn-outline-secondary" for="method_automatic">Automatic discount</label>
                        </div>
                        <div id="codeSection">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Discount code</label>
                                <a href="javascript:void(0)" class="generate-code text-primary f-s-13 text-decoration-none">Generate random code</a>
                            </div>
                            <input type="text" name="code" class="form-control discount-code-input" placeholder="e.g. FREESHIP" required>
                        </div>
                        <div id="titleSection" class="d-none">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Countries</h6></div>
                    <div class="discount-card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="countries" id="countries_all" value="all" checked>
                            <label class="form-check-label" for="countries_all">All countries</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="countries" id="countries_selected" value="selected">
                            <label class="form-check-label" for="countries_selected">Selected countries</label>
                        </div>
                        <div id="countriesSection" class="ms-4 mb-4 d-none">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="ph ph-magnifying-glass"></i></span>
                                <input type="text" class="form-control border-start-0" placeholder="Search countries">
                                <button class="btn btn-outline-secondary browse-items-btn" type="button" id="browseCountriesBtn" 
                                    data-target-area="#selectedCountriesList" 
                                    data-target-input="selected_countries[]"
                                    data-type="countries">Browse</button>
                            </div>
                            <div id="selectedCountriesList" class="mt-3"></div>
                        </div>

                        <div class="form-check pt-3 border-top">
                            <input class="form-check-input" type="checkbox" name="exclude_shipping_rates" id="excludeShipping" value="1">
                            <label class="form-check-label" for="excludeShipping">Exclude shipping rates over a certain amount</label>
                        </div>
                        <div id="excludeAmountInput" class="ms-4 mt-2 d-none">
                            <div class="input-group" style="width: 200px;">
                                <span class="input-group-text">$</span>
                                <input type="number" name="exclude_shipping_over" class="form-control" placeholder="0" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Eligibility -->
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

                <!-- Min Requirements -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Minimum purchase requirements</h6></div>
                    <div class="discount-card-body">
                        <div class="form-check mb-3"><input class="form-check-input" type="radio" name="min_requirement_type" id="min_none" value="none" checked><label class="form-check-label" for="min_none">No minimum requirements</label></div>
                        <div class="form-check mb-3"><input class="form-check-input" type="radio" name="min_requirement_type" id="min_amount" value="amount"><label class="form-check-label" for="min_amount">Minimum purchase amount ($)</label></div>
                        <div id="minAmountInput" class="ms-4 mb-3 d-none"><input type="number" name="min_requirement_value" class="form-control" style="width: 150px;" placeholder="0" step="0.01"></div>
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
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-card" style="background: #fff; border-radius: 12px; border: 1px solid #ebebeb; padding: 20px;">
                    <h6 class="f-w-600">Summary</h6>
                    <p class="text-muted f-s-13">Free shipping</p>
                    <hr>
                    <button type="submit" class="btn btn-shopify w-100">Save discount</button>
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
        $('.generate-code').on('click', function() {
            $('.discount-code-input').val(generateRandomCode());
        });

        $('input[name="method"]').on('change', function() {
            const isCode = $(this).val() === 'code';
            $('#codeSection').toggleClass('d-none', !isCode);
            $('#titleSection').toggleClass('d-none', isCode);
        });
        $('input[name="countries"]').on('change', function() {
            $('#countriesSection').toggleClass('d-none', $(this).val() !== 'selected');
        });
        $('#excludeShipping').on('change', function() {
            $('#excludeAmountInput').toggleClass('d-none', !$(this).is(':checked'));
        });
        $('input[name="min_requirement_type"]').on('change', function() {
            $('#minAmountInput').toggleClass('d-none', $(this).val() !== 'amount');
        });
        $('input[name="customer_selection"]').on('change', function() {
            $('#customerSelectionArea').toggleClass('d-none', $(this).val() !== 'specific');
        });
        initializeDiscountModals('products');
    });
</script>
@endpush
