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
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 1100px;">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.market-sales.index') }}" class="text-dark me-2"><i class="ph ph-arrow-left"></i></a>
        <h4 class="mb-0 f-w-700">Edit: {{ $marketSale->title }}</h4>
    </div>

    <form action="{{ route('admin.market-sales.update', $marketSale->id) }}" method="POST" id="marketSaleForm">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <!-- General Info -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">General Information</h6></div>
                    <div class="discount-card-body">
                        <div class="mb-4">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $marketSale->title }}" required>
                        </div>
                    </div>
                </div>

                <!-- Discount value and Applies to -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Discount value</h6></div>
                    <div class="discount-card-body">
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <select name="sale_type" class="form-select" id="saleType">
                                    <option value="percentage" {{ $marketSale->sale_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ $marketSale->sale_type == 'fixed' ? 'selected' : '' }}>Fixed amount</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="number" name="sale_value" class="form-control" value="{{ $marketSale->sale_value }}" step="0.01" required>
                                    <span class="input-group-text" id="valueSuffix">{{ $marketSale->sale_type == 'percentage' ? '%' : '$' }}</span>
                                </div>
                            </div>
                        </div>

                        <label class="form-label">Applies to</label>
                        <select name="applied_on" class="form-select mb-3 items-type-select" id="appliesTo" data-target="#selectedItemsList">
                            <option value="collection" {{ $marketSale->applied_on == 'collection' ? 'selected' : '' }}>Specific collections</option>
                            <option value="product" {{ $marketSale->applied_on == 'product' ? 'selected' : '' }}>Specific products</option>
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
                                @forelse($marketSale->items as $item)
                                    @php
                                        $obj = $marketSale->applied_on == 'product' ? $item->product : $item->collection;
                                        if (!$obj) continue;
                                        $id = $obj->id;
                                        $title = $obj->title;
                                        
                                        $imgSrc = null;
                                        if ($marketSale->applied_on == 'product') {
                                            $firstImage = $obj->images->first();
                                            $imgSrc = $firstImage ? asset('uploads/' . $firstImage->file) : null;
                                        } else {
                                            $imgSrc = $obj->image ? asset('uploads/' . $obj->image) : null;
                                        }
                                        
                                        $type = $marketSale->applied_on;
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between p-3 mb-2 border rounded bg-white shadow-sm" id="selected-{{ $type }}-group-{{ $id }}">
                                        <div class="d-flex align-items-center">
                                            @if($imgSrc)
                                                <img src="{{ $imgSrc }}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #eee;">
                                            @else
                                                <div class="rounded me-3 d-flex-center bg-light" style="width: 40px; height: 40px; border: 1px solid #eee;">
                                                    <i class="ph ph-folders text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="f-s-14 f-w-600 text-dark">{{ $title }}</div>
                                            </div>
                                            <input type="hidden" name="items[]" value="{{ $id }}">
                                            <input type="hidden" name="target_types[]" value="{{ $type }}">
                                        </div>
                                        <button type="button" class="btn btn-link link-danger p-0" onclick="$(this).closest('.border').remove()">
                                            <i class="ph ph-x"></i>
                                        </button>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted bg-light rounded" id="noItemsMessage">No items selected.</div>
                                @endforelse
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
                                <input type="date" name="starts_at_date" class="form-control" value="{{ $marketSale->starts_at->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start time (IST)</label>
                                <input type="time" name="starts_at_time" class="form-control" value="{{ $marketSale->starts_at->format('H:i') }}" required>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="setEndDate" {{ $marketSale->ends_at ? 'checked' : '' }}>
                            <label class="form-check-label" for="setEndDate">Set end date</label>
                        </div>
                        <div class="row {{ $marketSale->ends_at ? '' : 'd-none' }}" id="endDateSection">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End date</label>
                                <input type="date" name="ends_at_date" class="form-control" value="{{ $marketSale->ends_at ? $marketSale->ends_at->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End time (IST)</label>
                                <input type="time" name="ends_at_time" class="form-control" value="{{ $marketSale->ends_at ? $marketSale->ends_at->format('H:i') : '23:59' }}">
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
                            <option value="active" {{ $marketSale->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $marketSale->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-shopify w-100 mb-3">Update market sale</button>
                    <a href="{{ route('admin.market-sales.index') }}" class="btn btn-light border w-100">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

@php
    $customers = collect(); // Required by _modals
    $countries = collect(); // Required by _modals
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

        initializeDiscountModals('products');
    });
</script>
@endpush
