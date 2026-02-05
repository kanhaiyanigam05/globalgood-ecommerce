@extends('admin.layouts.app')
@push('styles:after')
    <style>
        :root {
            --header-height: 65px;
        }

        /* Essential Sidebar Layout */
        .variant-sidebar {
            background: #ffffff;
            border-right: 1px solid #e5e8eb;
            height: calc(100vh - var(--header-height));
            position: sticky;
            top: var(--header-height);
            overflow-y: auto;
            z-index: 10;
        }

        .variant-item {
            padding: 12px 16px;
            margin: 4px 8px;
            border-radius: 8px;
            transition: all 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
            border: 1px solid transparent;
        }

        .variant-item:hover {
            background-color: #f8fafc;
            border-color: #e9ecef;
        }

        .variant-item.active {
            background-color: #e7f3ff;
            border-color: var(--primary);
            border-left-width: 4px;
        }

        .variant-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .variant-info-top {
            padding: 16px;
            border-bottom: 1px solid #e5e8eb;
            background: #fafbfc;
        }

        .search-box {
            padding: 12px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        @media (max-width: 768px) {
            .variant-sidebar {
                position: relative;
                height: auto;
                border-right: none;
                border-bottom: 1px solid #e5e8eb;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Edit Variant</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li>
                        <a class="f-s-14 f-w-500" href="{{ route('admin.products.index') }}">
                            <span><i class="ph-duotone ph-package f-s-16"></i> Products</span>
                        </a>
                    </li>
                    <li>
                        <a class="f-s-14 f-w-500"
                            href="{{ route('admin.products.edit', Crypt::encryptString($product->id)) }}">
                            <span><i class="ph-duotone ph-folder f-s-16"></i> {{ $product->title }}</span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500"
                            href="javascript:void(0)">{{ $variant->attributes->map(fn($a) => $a->pivot->value)->implode(' / ') ?: 'Default Variant' }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 variant-sidebar">
                <div class="variant-info-top">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ $product->firstImage() ? route('file.path', ['path' => $product->firstImage()->file, 'w' => 100]) : 'https://placehold.co/40x40' }}"
                            class="rounded" width="100">
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="mb-0 text-truncate f-s-13 text-wrap">{{ $product->title }}</h6>
                            <span class="badge {{ $product->status ? 'text-bg-success' : 'text-bg-danger' }} f-s-10">
                                {{ $product->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="search-box">
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-white border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                        <input type="text" class="form-control border-start-0" id="variantSearch"
                            placeholder="Search variants...">
                    </div>

                    <div class="dropdown dropdown-sm w-100">
                        <button class="btn btn-light btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                            <i class="ph ph-funnel me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">All Variants</a></li>
                        </ul>
                    </div>
                </div>

                <div class="variant-list" id="variantList">
                    @php $encryptedProductId = \Crypt::encryptString($product->id); @endphp
                    @foreach ($product->variants as $v)
                        @php
                            $vTitle =
                                $v->attributes->map(fn($a) => $a->pivot->value)->implode(' / ') ?: 'Default Variant';
                            $vEncryptedId = \Crypt::encryptString($v->id);
                        @endphp
                        <a href="{{ route('admin.products.variants.edit', [$encryptedProductId, $vEncryptedId]) }}"
                            class="variant-item {{ $v->id == $variant->id ? 'active' : '' }}"
                            data-title="{{ strtolower($vTitle) }}">
                            <img
                                src="{{ $product->firstImage() ? route('file.path', ['path' => $product->firstImage()->file, 'w' => 40, 'h' => 40]) : 'https://placehold.co/40x40' }}">
                            <div class="text-truncate flex-grow-1">
                                <p class="mb-0 f-w-500 f-s-12 text-truncate text-dark">{{ $vTitle }}</p>
                                <p class="mb-0 text-muted f-s-11">{{ $v->quantity }} available</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Main Area -->
            <div class="col-md-9 col-lg-10 p-4 bg-light">
                <div class="mx-auto" style="max-width: 900px;">
                    <x-forms.form varient="reactive" :action="route('admin.products.variants.update', [
                        $encryptedProductId,
                        \Crypt::encryptString($variant->id),
                    ])" method="PUT">
                        @php $currentVTitle = $variant->attributes->map(fn($a) => $a->pivot->value)->implode(' / ') ?: 'Default Variant'; @endphp

                        <!-- Variant Info -->
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    <i class="ph ph-tag text-primary me-2"></i>
                                    {{ $currentVTitle }}
                                </h5>

                                @if ($errors->any())
                                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4">
                                        <i class="ph ph-warning-circle f-s-20 me-2"></i>
                                        <div>
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                                    <i class="ph ph-info f-s-20 me-2"></i>
                                    <span>These attributes define what makes this variant unique from other variants of this
                                        product.</span>
                                </div>

                                @php $variantAttributesMap = $variant->attributes->pluck('pivot.value', 'id')->toArray(); @endphp
                                @foreach ($attributes as $index => $attribute)
                                    <div class="mb-3">
                                        <input type="hidden" name="attributes[{{ $index }}][attribute_id]"
                                            value="{{ $attribute->id }}">
                                        <x-forms.select name="attributes[{{ $index }}][value]" :label="$attribute->name"
                                            :options="$attribute->values->pluck('value', 'value')->toArray()" :value="$variantAttributesMap[$attribute->id] ?? ''" placeholder="Select {{ $attribute->name }}"
                                            :error="$errors->first('attributes.' . $index . '.value')" disabled />
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div
                            class="card mb-4 shadow-sm @if ($errors->has('price') || $errors->has('compare_at_price')) border border-danger @else border-0 @endif">
                            <div class="card-header bg-white py-3 border-0">
                                <h6 class="mb-0 f-w-600"><i class="ph-duotone ph-currency-circle-dollar me-2"></i>Pricing
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input type="number" name="price" label="Price" step="0.01"
                                            :value="old('price', $variant->price_formatted)" :error="$errors->first('price')" required />
                                        <small class="text-muted"><i class="ph ph-info me-1"></i>Customer-facing
                                            price</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input type="number" name="compare_at_price" label="Compare-At Price"
                                            step="0.01" :value="old('compare_at_price', $variant->compare_at_price_formatted)" :error="$errors->first('compare_at_price')" />
                                        <small class="text-muted"><i class="ph ph-tag me-1"></i>Show savings to
                                            customers</small>
                                    </div>
                                </div>

                                @if (old('compare_at_price', $variant->compare_at_price_formatted))
                                    @php
                                        $price = old('price', $variant->price_formatted);
                                        $comparePrice = old('compare_at_price', $variant->compare_at_price_formatted);
                                        $savings =
                                            $comparePrice > $price
                                                ? (($comparePrice - $price) / $comparePrice) * 100
                                                : 0;
                                    @endphp
                                    @if ($savings > 0)
                                        <div class="alert alert-success mt-3 mb-0 border-0 shadow-sm">
                                            <i class="ph ph-check-circle me-2"></i>
                                            <strong>Customers save {{ number_format($savings, 0) }}%</strong>
                                            (${{ number_format($comparePrice - $price, 2) }})
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Inventory Section -->
                        <div
                            class="card mb-4 shadow-sm @if ($errors->has('quantity') || $errors->has('sku')) border border-danger @else border-0 @endif">
                            <div
                                class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                                <h6 class="mb-0 f-w-600"><i class="ph-duotone ph-warehouse me-2"></i>Inventory</h6>
                                {{-- <x-forms.switch id="trackQuantity" name="track_quantity" label="Track quantity" checked /> --}}
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0 align-middle">
                                        <thead class="bg-light-subtle">
                                            <tr>
                                                <th class="ps-4 border-0">Location</th>
                                                <th class="border-0">Available</th>
                                                <th class="border-0 text-end pe-4">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="ps-4">
                                                    <strong>Global Good Store</strong><br>
                                                    <small class="text-muted">Primary Location</small>
                                                </td>
                                                <td>
                                                    <x-forms.input label="Quantity" type="number" name="quantity"
                                                        :value="old('quantity', $variant->quantity)" :error="$errors->first('quantity')" required style="width: 120px;"
                                                        min="0" />
                                                </td>
                                                <td class="text-end pe-4">
                                                    @php $qty = old('quantity', $variant->quantity); @endphp
                                                    @if ($qty <= 0)
                                                        <span class="badge text-bg-danger">Out of Stock</span>
                                                    @elseif($qty < 10)
                                                        <span class="badge text-bg-warning text-dark">Low Stock</span>
                                                    @else
                                                        <span class="badge text-bg-success">In Stock</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-4 border-top bg-light-subtle">
                                    <div class="row">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <x-forms.input name="sku" label="SKU (Stock Keeping Unit)"
                                                :value="old('sku', $variant->sku)" :error="$errors->first('sku')" placeholder="e.g. WH-01-L" />
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <x-forms.input name="barcode" label="Barcode" placeholder="Coming soon" disabled class="bg-light" />
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Section -->
                        {{-- <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                                <h6 class="mb-0 f-w-600"><i class="ph-duotone ph-truck me-2"></i>Shipping</h6>
                                <x-forms.switch id="isPhysical" name="is_physical" label="Physical product" checked />
                            </div>
                            <div class="card-body">
                                <div class="alert alert-light border shadow-none mb-4 f-s-13">
                                    <i class="ph ph-info me-2 text-info"></i>
                                    Shipping dimensions and weight help calculate accurate shipping costs.
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-forms.input type="number" name="weight" label="Weight (kg)" step="0.1" placeholder="0.0" disabled class="bg-light" />
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <a href="{{ route('admin.products.edit', $encryptedProductId) }}"
                                class="btn btn-outline-secondary">
                                <i class="ph ph-arrow-left me-1"></i> Back to Product
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-2">
                                <i class="ph ph-check-circle me-1"></i> Save Variant
                            </button>
                        </div>
                    </x-forms.form>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3 text-muted">Saving changes...</p>
        </div>
    </div>
@endsection

@push('scripts:after')
    <script>
        $(document).ready(function() {
            // Variant Search Filtering
            $('#variantSearch').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#variantList .variant-item').each(function() {
                    $(this).toggle($(this).data('title').indexOf(value) > -1);
                });
            });
        });
    </script>
@endpush
