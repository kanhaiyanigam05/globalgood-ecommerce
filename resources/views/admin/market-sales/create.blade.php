@extends('admin.layouts.app')

@push('styles:after')
<style>
    .discount-card { background: #fff; border-radius: 12px; border: 1px solid #ebebeb; margin-bottom: 20px; }
    .discount-card-header { padding: 16px 20px; border-bottom: 1px solid #f1f2f3; }
    .discount-card-body { padding: 20px; }
    .btn-shopify { background: #303030; color: #fff; font-weight: 600; padding: 8px 16px; border-radius: 8px; border: none; }
    .btn-shopify:hover { background: #1a1a1a; color: #fff; }
    .form-label { font-size: 13px; font-weight: 600; color: #1a1c1d; margin-bottom: 8px; }
    .summary-card { background: #fff; border-radius: 12px; border: 1px solid #ebebeb; padding: 20px; position: sticky; top: 20px; }
    .item-select-card { border: 1px solid #ebebeb; border-radius: 8px; padding: 15px; margin-bottom: 15px; cursor: pointer; transition: all 0.2s; }
    .item-select-card:hover { border-color: #303030; background: #f9f9f9; }
    .item-select-card.selected { border-color: #303030; background: #f0f7ff; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 1100px;">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.market-sales.index') }}" class="text-dark me-2"><i class="ph ph-arrow-left"></i></a>
        <h4 class="mb-0 f-w-700">Create market sale</h4>
    </div>

    <form action="{{ route('admin.market-sales.store') }}" method="POST" id="marketSaleForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- General Info -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">General Information</h6></div>
                    <div class="discount-card-body">
                        <div class="mb-4">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Flash Sale, Summer Promo, etc." required>
                        </div>
                    </div>
                </div>

                <!-- Discount value -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Discount value</h6></div>
                    <div class="discount-card-body">
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <select name="sale_type" class="form-select" id="saleType">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed amount</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="number" name="sale_value" class="form-control" placeholder="0" step="0.01" required>
                                    <span class="input-group-text" id="valueSuffix">%</span>
                                </div>
                            </div>
                        </div>

                        <label class="form-label">Applies to</label>
                        <select name="applied_on" class="form-select mb-3 items-type-select" id="appliesTo" data-target="#selectedItemsList">
                            <option value="collection">Specific collections</option>
                            <option value="product" selected>Specific products</option>
                        </select>

                        <div id="selectionArea">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="ph ph-magnifying-glass"></i></span>
                                <input type="text" class="form-control border-start-0" placeholder="Search items..." id="productSearchTrigger">
                                <button class="btn btn-outline-secondary browse-items-btn" type="button" id="browseBtn" 
                                    data-target-area="#selectedItemsList" 
                                    data-target-input="items[]">Browse</button>
                            </div>
                            <div id="selectedItemsList" class="mt-3">
                                <!-- Selected items will appear here -->
                                <div class="text-center py-4 text-muted bg-light rounded" id="noItemsMessage">
                                    No items selected.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Active dates</h6></div>
                    <div class="discount-card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start date</label>
                                <input type="date" name="starts_at_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start time (IST)</label>
                                <input type="time" name="starts_at_time" class="form-control" value="{{ date('H:i') }}" required>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="setEndDate">
                            <label class="form-check-label" for="setEndDate">Set end date</label>
                        </div>
                        <div class="row d-none" id="endDateSection">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End date</label>
                                <input type="date" name="ends_at_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End time (IST)</label>
                                <input type="time" name="ends_at_time" class="form-control" value="23:59">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-card">
                    <h6 class="f-w-600">Summary</h6>
                    <hr>
                    <div class="mb-4">
                        <label class="form-label d-block">Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-shopify w-100 mb-3">Save market sale</button>
                    <a href="{{ route('admin.market-sales.index') }}" class="btn btn-light border w-100">Cancel</a>
                    
                    <div class="mt-4 p-3 bg-light rounded f-s-13">
                        <i class="ph ph-info me-1"></i> This sale will be applied to the selected items based on the start date.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@php
    $customers = collect(); // Not needed for market sales but required by _modals
    $countries = collect(); // Not needed for market sales but required by _modals
@endphp
@include('admin.discounts._modals')
@endsection

@push('scripts:after')
<script>
    $(document).ready(function() {
        $('#saleType').on('change', function() {
            $('#valueSuffix').text($(this).val() === 'percentage' ? '%' : '$');
        });

        $('#setEndDate').on('change', function() {
            $('#endDateSection').toggleClass('d-none', !$(this).is(':checked'));
        });

        $('#appliesTo').on('change', function() {
            $('#selectedItemsList').html('<div class="text-center py-4 text-muted bg-light rounded" id="noItemsMessage">No items selected.</div>');
        });

        // Use the centralized item selection logic
        initializeDiscountModals('products');
        
        // Sync the value type UI on load
        $('#saleType').trigger('change');
    });
</script>
@endpush
