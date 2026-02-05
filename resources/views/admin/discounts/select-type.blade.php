@extends('admin.layouts.app')

@push('styles:after')
<style>
    .discount-type-card {
        border: 2px solid #e1e3e6;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.2s;
        cursor: pointer;
        background: white;
    }
    .discount-type-card:hover {
        border-color: #005bd3;
        box-shadow: 0 4px 12px rgba(0,91,211,0.1);
    }
    .discount-type-icon {
        width: 48px;
        height: 48px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }
    .discount-type-title {
        font-size: 16px;
        font-weight: 600;
        color: #202223;
        margin-bottom: 8px;
    }
    .discount-type-desc {
        font-size: 13px;
        color: #6d7175;
        line-height: 1.5;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 800px;">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.discounts.index') }}" class="text-dark me-2">
            <i class="ph ph-arrow-left"></i>
        </a>
        <h4 class="mb-0 f-w-700">Create discount</h4>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="mb-2 f-w-600">Select discount type</h5>
            <p class="text-muted mb-4">Choose the type of discount you want to create</p>

            <div class="row g-3">
                <!-- Amount off products -->
                <div class="col-12">
                    <a href="{{ route('admin.discounts.create', ['type' => 'amount_off_products']) }}" class="text-decoration-none">
                        <div class="discount-type-card">
                            <div class="discount-type-icon">
                                <i class="ph ph-tag"></i>
                            </div>
                            <div class="discount-type-title">Amount off products</div>
                            <div class="discount-type-desc">Discount specific products or collections of products</div>
                        </div>
                    </a>
                </div>

                <!-- Buy X Get Y -->
                <div class="col-12">
                    <a href="{{ route('admin.discounts.create', ['type' => 'buy_x_get_y']) }}" class="text-decoration-none">
                        <div class="discount-type-card">
                            <div class="discount-type-icon">
                                <i class="ph ph-shuffle"></i>
                            </div>
                            <div class="discount-type-title">Buy X get Y</div>
                            <div class="discount-type-desc">Discount products based on a customer's purchase</div>
                        </div>
                    </a>
                </div>

                <!-- Amount off order -->
                <div class="col-12">
                    <a href="{{ route('admin.discounts.create', ['type' => 'amount_off_order']) }}" class="text-decoration-none">
                        <div class="discount-type-card">
                            <div class="discount-type-icon">
                                <i class="ph ph-receipt"></i>
                            </div>
                            <div class="discount-type-title">Amount off order</div>
                            <div class="discount-type-desc">Discount the total order amount</div>
                        </div>
                    </a>
                </div>

                <!-- Free shipping -->
                <div class="col-12">
                    <a href="{{ route('admin.discounts.create', ['type' => 'free_shipping']) }}" class="text-decoration-none">
                        <div class="discount-type-card">
                            <div class="discount-type-icon">
                                <i class="ph ph-truck"></i>
                            </div>
                            <div class="discount-type-title">Free shipping</div>
                            <div class="discount-type-desc">Offer free shipping on an order</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
