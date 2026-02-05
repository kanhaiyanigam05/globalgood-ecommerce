@extends('admin.layouts.app')

@push('styles:after')
<style>
    .discount-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #dcdfe3;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .btn-shopify {
        background: #008060;
        color: #fff;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
    }
    .btn-shopify:hover {
        background: #006e52;
        color: #fff;
    }
    .badge-status {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-active { background: #e3f9ef; color: #007f5f; }
    .badge-scheduled { background: #fff4e5; color: #995a00; }
    .badge-expired { background: #f1f2f3; color: #5c5f62; }
    .badge-disabled { background: #fff0f0; color: #d72c0d; }

    /* Modal Styling */
    .modal-content { border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
    .modal-header { border-bottom: 1px solid #f1f2f3; padding: 20px 24px; }
    .modal-title { font-weight: 700; color: #1a1c1d; }
    .discount-option { 
        display: flex; 
        align-items: center; 
        padding: 16px 24px; 
        text-decoration: none; 
        color: inherit; 
        border-bottom: 1px solid #f1f2f3;
        transition: background 0.2s;
    }
    .discount-option:last-child { border-bottom: none; }
    .discount-option:hover { background: #f9fafb; color: inherit; }
    .option-icon { 
        width: 40px; 
        height: 40px; 
        background: #f1f2f3; 
        border-radius: 8px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin-right: 16px; 
        font-size: 20px;
        color: #5c5f62;
    }
    .option-content { flex-grow: 1; }
    .option-title { font-weight: 600; color: #1a1c1d; margin-bottom: 2px; }
    .option-desc { font-size: 13px; color: #6d7175; }
    .option-arrow { color: #8c9196; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 f-w-700">Discounts</h4>
        <button type="button" class="btn btn-shopify" data-bs-toggle="modal" data-bs-target="#selectDiscountTypeModal">Create discount</button>
    </div>

    <div class="discount-card">
        <div class="p-3 border-bottom d-flex gap-2">
            <div class="flex-grow-1 position-relative">
                <input type="text" class="form-control ps-5" placeholder="Filter discounts">
                <i class="ph ph-magnifying-glass position-absolute" style="left: 15px; top: 12px; color: #6c757d;"></i>
            </div>
            <button class="btn btn-light border"><i class="ph ph-funnel me-2"></i>Status</button>
            <button class="btn btn-light border"><i class="ph ph-sort-ascending me-2"></i>Sort</button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="f-s-12 text-uppercase text-muted">
                        <th class="ps-4">Discount</th>
                        <th>Status</th>
                        <th>Used</th>
                        <th class="text-end pe-4">Start date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($discounts as $discount)
                    <tr onclick="window.location.href='{{ route('admin.discounts.edit', $discount->id) }}'" style="cursor: pointer;">
                        <td class="ps-4">
                            <div class="f-w-600 text-dark">{{ $discount->code ?? $discount->title }}</div>
                            <div class="text-muted f-s-13">
                                @if($discount->type == 'amount_off_products')
                                    {{ $discount->value_type == 'percentage' ? $discount->value . '%' : '$' . $discount->value }} off products
                                @elseif($discount->type == 'buy_x_get_y')
                                    Buy {{ $discount->buy_value }} Get {{ $discount->get_quantity }}
                                @elseif($discount->type == 'amount_off_order')
                                    {{ $discount->value_type == 'percentage' ? $discount->value . '%' : '$' . $discount->value }} off entire order
                                @elseif($discount->type == 'free_shipping')
                                    Free shipping
                                @endif
                            </div>
                        </td>
                        <td>
                            @php $status = strtolower($discount->status); @endphp
                            <span class="badge-status badge-{{ $status }}">{{ $discount->status }}</span>
                        </td>
                        <td class="text-muted">{{ $discount->usage_count ?? 0 }} used</td>
                        <td class="text-end pe-4 text-muted">
                            {{ $discount->starts_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="ph ph-tag f-s-48"></i></div>
                            <h6>No discounts found</h6>
                            <p class="f-s-14">Create a discount to help boost your sales.</p>
                            <a href="{{ route('admin.discounts.select-type') }}" class="btn btn-shopify btn-sm">Create discount</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
@if($discounts->hasPages())
        <div class="p-3 border-top">
            {{ $discounts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Select Discount Type Modal -->
<div class="modal fade" id="selectDiscountTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select discount type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <a href="{{ route('admin.discounts.create', ['type' => 'amount_off_products']) }}" class="discount-option">
                    <div class="option-icon"><i class="ph ph-tag"></i></div>
                    <div class="option-content">
                        <div class="option-title">Amount off products</div>
                        <div class="option-desc">Discount specific products or collections of products</div>
                    </div>
                    <div class="option-arrow"><i class="ph ph-caret-right"></i></div>
                </a>
                <a href="{{ route('admin.discounts.create', ['type' => 'buy_x_get_y']) }}" class="discount-option">
                    <div class="option-icon"><i class="ph ph-shuffle"></i></div>
                    <div class="option-content">
                        <div class="option-title">Buy X get Y</div>
                        <div class="option-desc">Discount products based on a customer's purchase</div>
                    </div>
                    <div class="option-arrow"><i class="ph ph-caret-right"></i></div>
                </a>
                <a href="{{ route('admin.discounts.create', ['type' => 'amount_off_order']) }}" class="discount-option">
                    <div class="option-icon"><i class="ph ph-receipt"></i></div>
                    <div class="option-content">
                        <div class="option-title">Amount off order</div>
                        <div class="option-desc">Discount the total order amount</div>
                    </div>
                    <div class="option-arrow"><i class="ph ph-caret-right"></i></div>
                </a>
                <a href="{{ route('admin.discounts.create', ['type' => 'free_shipping']) }}" class="discount-option">
                    <div class="option-icon"><i class="ph ph-truck"></i></div>
                    <div class="option-content">
                        <div class="option-title">Free shipping</div>
                        <div class="option-desc">Offer free shipping on an order</div>
                    </div>
                    <div class="option-arrow"><i class="ph ph-caret-right"></i></div>
                </a>
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection
