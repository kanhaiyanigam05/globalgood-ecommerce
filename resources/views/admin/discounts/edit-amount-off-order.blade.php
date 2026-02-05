@extends('admin.layouts.app')

@push('styles:after')
<style>
    .discount-card { background: #fff; border-radius: 12px; border: 1px solid #ebebeb; margin-bottom: 20px; }
    .discount-card-header { padding: 16px 20px; border-bottom: 1px solid #f1f2f3; }
    .discount-card-body { padding: 20px; }
    .btn-shopify { background: #303030; color: #fff; font-weight: 600; padding: 8px 16px; border-radius: 8px; border: none; }
    .btn-shopify:hover { background: #1a1a1a; color: #fff; }
    .form-label { font-size: 13px; font-weight: 600; color: #1a1c1d; margin-bottom: 8px; }
    .summary-card { background: #fff; border-radius: 12px; border: 1px solid #ebebeb; padding: 20px; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 1100px;">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.discounts.index') }}" class="text-dark me-2"><i class="ph ph-arrow-left"></i></a>
        <h4 class="mb-0 f-w-700">{{ $discount->code ?? $discount->title }}</h4>
    </div>

    <form action="{{ route('admin.discounts.update', $discount->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Amount off order</h6></div>
                    <div class="discount-card-body">
                        <label class="form-label">Method</label>
                        <div class="btn-group w-100 mb-4">
                            <input type="radio" class="btn-check" name="method" id="method_code" value="code" {{ $discount->method == 'code' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="method_code">Discount code</label>
                            <input type="radio" class="btn-check" name="method" id="method_automatic" value="automatic" {{ $discount->method == 'automatic' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="method_automatic">Automatic discount</label>
                        </div>
                        <div id="codeSection" class="{{ $discount->method == 'automatic' ? 'd-none' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Discount code</label>
                                <a href="javascript:void(0)" class="generate-code text-primary f-s-13 text-decoration-none">Generate random code</a>
                            </div>
                            <input type="text" name="code" class="form-control discount-code-input" value="{{ $discount->code }}" required>
                        </div>
                        <div id="titleSection" class="{{ $discount->method == 'code' ? 'd-none' : '' }}">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $discount->title }}" required>
                        </div>
                    </div>
                </div>

                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Discount value</h6></div>
                    <div class="discount-card-body">
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <select name="value_type" class="form-select" id="valueType">
                                    <option value="percentage" {{ $discount->value_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed_amount" {{ $discount->value_type == 'fixed_amount' ? 'selected' : '' }}>Fixed amount</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="number" name="value" class="form-control" value="{{ $discount->value }}" step="0.01" required>
                                    <span class="input-group-text" id="valueSuffix">{{ $discount->value_type == 'percentage' ? '%' : '$' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Eligibility -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Eligibility</h6></div>
                    <div class="discount-card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="customer_selection" id="elig_all" value="all" {{ $discount->customer_selection == 'all' ? 'checked' : '' }}>
                            <label class="form-check-label" for="elig_all">All customers</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="customer_selection" id="elig_specific" value="specific" {{ $discount->customer_selection == 'specific' ? 'checked' : '' }}>
                            <label class="form-check-label" for="elig_specific">Specific customers</label>
                        </div>
                        <div id="customerSelectionArea" class="ms-4 mt-3 {{ $discount->customer_selection != 'specific' ? 'd-none' : '' }}">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="ph ph-magnifying-glass"></i></span>
                                <input type="text" class="form-control border-start-0" placeholder="Search customers" id="customerSearchTrigger">
                                <button class="btn btn-outline-secondary" type="button" id="browseCustomersBtn" data-target-input="customer_ids[]">Browse</button>
                            </div>
                            <div id="selectedCustomers" class="mt-3">
                                @foreach($discount->customers as $customer)
                                    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded" id="selected-customer-{{ $customer->id }}">
                                        <div class="d-flex align-items-center">
                                            <i class="ph ph-user me-2"></i>
                                            <span class="f-s-13">{{ $customer->full_name }}</span>
                                            <input type="hidden" name="customer_ids[]" value="{{ $customer->id }}">
                                        </div>
                                        <button type="button" class="btn btn-link link-danger p-0 ms-2" onclick="$(this).closest('.bg-light').remove()">
                                            <i class="ph ph-x"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Dates -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Active dates</h6></div>
                    <div class="discount-card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Start date</label><input type="date" name="starts_at_date" class="form-control" value="{{ $discount->starts_at->format('Y-m-d') }}" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Start time (IST)</label><input type="time" name="starts_at_time" class="form-control" value="{{ $discount->starts_at->format('H:i') }}" required></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-card">
                    <h6 class="f-w-600">Summary</h6>
                    <hr>
                    <button type="submit" class="btn btn-shopify w-100 mb-3">Save changes</button>
                    <a href="{{ route('admin.discounts.index') }}" class="btn btn-light border w-100">Cancel</a>
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
        $('#valueType').on('change', function() {
            $('#valueSuffix').text($(this).val() === 'percentage' ? '%' : '$');
        });
        $('input[name="customer_selection"]').on('change', function() {
            $('#customerSelectionArea').toggleClass('d-none', $(this).val() !== 'specific');
        });
        initializeDiscountModals('products');
    });
</script>
@endpush
