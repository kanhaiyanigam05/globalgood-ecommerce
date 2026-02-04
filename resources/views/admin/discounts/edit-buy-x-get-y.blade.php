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
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Buy X get Y</h6></div>
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
                                <a href="javascript:void(0)" id="generateCode" class="text-primary f-s-13 text-decoration-none">Generate random code</a>
                            </div>
                            <input type="text" name="code" id="discountCode" class="form-control" value="{{ $discount->code }}" required>
                        </div>
                        <div id="titleSection" class="{{ $discount->method == 'code' ? 'd-none' : '' }}">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="discountTitle" class="form-control" value="{{ $discount->title }}" required>
                        </div>
                    </div>
                </div>

                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Customer buys</h6></div>
                    <div class="discount-card-body">
                        <div class="form-check mb-2"><input class="form-check-input" type="radio" name="buy_type" id="buy_qty" value="quantity" {{ $discount->buy_type == 'quantity' ? 'checked' : '' }}><label class="form-check-label" for="buy_qty">Minimum quantity of items</label></div>
                        <div class="form-check mb-3"><input class="form-check-input" type="radio" name="buy_type" id="buy_amount" value="amount" {{ $discount->buy_type == 'amount' ? 'checked' : '' }}><label class="form-check-label" for="buy_amount">Minimum purchase amount ($)</label></div>
                        <div class="row mb-3">
                            <div class="col-md-4"><label class="form-label">Quantity/Amount</label><input type="number" name="buy_value" class="form-control" value="{{ $discount->buy_value }}" required></div>
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
                            <div id="buySelectedItems" class="mt-3">
                                @foreach($discount->items as $item)
                                    @if($item->collection_id)
                                        @php $collection = $collections->find($item->collection_id); @endphp
                                        @if($collection)
                                            <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded" id="selected-buy-collection-{{ $collection->id }}">
                                                <div class="d-flex align-items-center">
                                                    <i class="ph ph-folders me-2"></i>
                                                    <span class="f-s-13">{{ $collection->title }}</span>
                                                    <input type="hidden" name="buy_items[]" value="{{ $collection->id }}">
                                                    <input type="hidden" name="buy_target_types[]" value="collection">
                                                </div>
                                                <button type="button" class="btn btn-link link-danger p-0 ms-2" onclick="$(this).closest('.rounded').remove()"><i class="ph ph-x"></i></button>
                                            </div>
                                        @endif
                                    @elseif($item->product_id)
                                        @php
                                            $product = $products->find($item->product_id);
                                            $selectedVariants = $item->variant_ids ?? [];
                                            $isFull = empty($selectedVariants);
                                            $totalVariants = $product->variants->count();
                                            $subtitle = $isFull ? 'All variants selected' : (count($selectedVariants) . ' of ' . $totalVariants . ' variants selected');
                                            $image = $product->images->first()->path ?? null;
                                            $imgSrc = $image ? asset('storage/'.$image) : asset('admins/svg/_sprite.svg#shop');
                                        @endphp
                                        @if($product)
                                            <div class="d-flex align-items-center justify-content-between p-3 mb-2 border rounded bg-white shadow-sm">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $imgSrc }}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #eee;">
                                                    <div>
                                                        <div class="f-s-14 f-w-600 text-dark">{{ $product->title }}</div>
                                                        <div class="f-s-13 text-muted">{{ $subtitle }}</div>
                                                    </div>
                                                    <div class="d-none">
                                                        @if($isFull)
                                                            <input type="hidden" name="buy_items[]" value="{{ $product->id }}">
                                                            <input type="hidden" name="buy_target_types[]" value="product">
                                                        @else
                                                            @foreach($selectedVariants as $vid)
                                                                <input type="hidden" name="buy_items[]" value="{{ $vid }}">
                                                                <input type="hidden" name="buy_target_types[]" value="variant">
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-link link-secondary p-0" onclick="$(this).closest('.border').remove()"><i class="ph ph-x"></i></button>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Gets -->
                <div class="discount-card">
                    <div class="discount-card-header border-0 pb-0"><h6 class="mb-0 f-w-600">Customer gets</h6></div>
                    <div class="discount-card-body">
                        <div class="row mb-3">
                             <div class="col-md-4"><label class="form-label">Quantity</label><input type="number" name="get_quantity" class="form-control" value="{{ $discount->get_quantity }}" required></div>
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
                            <div id="getSelectedItems" class="mt-3">
                                @foreach($discount->rewardItems as $item)
                                    @if($item->collection_id)
                                        @php $collection = $collections->find($item->collection_id); @endphp
                                        @if($collection)
                                            <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded" id="selected-get-collection-{{ $collection->id }}">
                                                <div class="d-flex align-items-center">
                                                    <i class="ph ph-folders me-2"></i>
                                                    <span class="f-s-13">{{ $collection->title }}</span>
                                                    <input type="hidden" name="get_items[]" value="{{ $collection->id }}">
                                                    <input type="hidden" name="get_target_types[]" value="collection">
                                                </div>
                                                <button type="button" class="btn btn-link link-danger p-0 ms-2" onclick="$(this).closest('.rounded').remove()"><i class="ph ph-x"></i></button>
                                            </div>
                                        @endif
                                    @elseif($item->product_id)
                                        @php
                                            $product = $products->find($item->product_id);
                                            $selectedVariants = $item->variant_ids ?? [];
                                            $isFull = empty($selectedVariants);
                                            $totalVariants = $product->variants->count();
                                            $subtitle = $isFull ? 'All variants selected' : (count($selectedVariants) . ' of ' . $totalVariants . ' variants selected');
                                            $image = $product->images->first()->path ?? null;
                                            $imgSrc = $image ? asset('storage/'.$image) : asset('admins/svg/_sprite.svg#shop');
                                        @endphp
                                        @if($product)
                                            <div class="d-flex align-items-center justify-content-between p-3 mb-2 border rounded bg-white shadow-sm">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $imgSrc }}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #eee;">
                                                    <div>
                                                        <div class="f-s-14 f-w-600 text-dark">{{ $product->title }}</div>
                                                        <div class="f-s-13 text-muted">{{ $subtitle }}</div>
                                                    </div>
                                                    <div class="d-none">
                                                        @if($isFull)
                                                            <input type="hidden" name="get_items[]" value="{{ $product->id }}">
                                                            <input type="hidden" name="get_target_types[]" value="product">
                                                        @else
                                                            @foreach($selectedVariants as $vid)
                                                                <input type="hidden" name="get_items[]" value="{{ $vid }}">
                                                                <input type="hidden" name="get_target_types[]" value="variant">
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-link link-secondary p-0" onclick="$(this).closest('.border').remove()"><i class="ph ph-x"></i></button>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <label class="form-label">At a discounted value</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="get_type" id="get_perc" value="percentage" {{ $discount->get_type == 'percentage' ? 'checked' : '' }}>
                            <label class="form-check-label" for="get_perc">Percentage</label>
                        </div>
                        <div id="getPercInput" class="ms-4 mb-3 {{ $discount->get_type != 'percentage' ? 'd-none' : '' }}">
                            <div class="input-group input-sm-fixed">
                                <input type="number" name="get_value" class="form-control" value="{{ $discount->get_type == 'percentage' ? $discount->get_value : '' }}" placeholder="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="get_type" id="get_amount" value="fixed_amount" {{ $discount->get_type == 'fixed_amount' ? 'checked' : '' }}>
                            <label class="form-check-label" for="get_amount">Amount off each ($)</label>
                        </div>
                        <div id="getAmountInput" class="ms-4 mb-3 {{ $discount->get_type != 'fixed_amount' ? 'd-none' : '' }}">
                            <input type="number" name="get_value_fixed" class="form-control input-sm-fixed" value="{{ $discount->get_type == 'fixed_amount' ? $discount->get_value : '' }}" placeholder="0">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="get_type" id="get_free" value="free" {{ $discount->get_type == 'free' ? 'checked' : '' }}>
                            <label class="form-check-label" for="get_free">Free</label>
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
                
                <!-- Eligibility Fix -->
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
        $('#generateCode').on('click', function() {
            $('#discountCode').val(generateRandomCode());
        });

        $('.items-type-select').on('change', function() {
            const targetArea = $(this).data('target');
            $(targetArea).empty();
        });

        $('input[name="method"]').on('change', function() {
            const isCode = $(this).val() === 'code';
            $('#codeSection').toggleClass('d-none', !isCode);
            $('#titleSection').toggleClass('d-none', isCode);
        });

        $('input[name="get_type"]').on('change', function() {
            const val = $(this).val();
            $('#getPercInput').toggleClass('d-none', val !== 'percentage');
            $('#getAmountInput').toggleClass('d-none', val !== 'fixed_amount');
            
            // Tweak required attributes
            $('input[name="get_value"]').prop('required', val === 'percentage');
            $('input[name="get_value_fixed"]').prop('required', val === 'fixed_amount');
        });

        $('input[name="customer_selection"]').on('change', function() {
            $('#customerSelectionArea').toggleClass('d-none', $(this).val() !== 'specific');
        });

        initializeDiscountModals('products');
    });
</script>
@endpush
