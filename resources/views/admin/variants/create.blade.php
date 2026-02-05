@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Product Variants</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                            <span>
                                <i class="ph-duotone  ph-form f-s-16"></i> Home
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.products.index') }}">
                            <span>
                                <i class="ph-duotone  ph-form f-s-16"></i> Products
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a class="f-s-14 f-w-500"
                            href="{{ route('admin.products.edit', \Crypt::encryptString($product->id)) }}">
                            <span>
                                <i class="ph-duotone  ph-form f-s-16"></i> {{ $product->title }}
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Create Variant</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- ready to use form start -->
        <x-forms.form :action="route('admin.products.variants.store', \Crypt::encryptString($product->id))" method="post" varient="reactive" class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h5>Variant Details for {{ $product->title }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="app-form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-forms.input type="text" varient="floating" name="sku" label="SKU"
                                        placeholder="Enter SKU" :value="old('sku')" :error="$errors->first('sku')" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.input type="number" varient="floating" name="quantity" label="Quantity"
                                        placeholder="Enter Quantity" :value="old('quantity', 0)" :error="$errors->first('quantity')" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.input type="number" step="0.01" varient="floating" name="price"
                                        label="Price" placeholder="Enter Price" :value="old('price')" :error="$errors->first('price')"
                                        required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.input type="number" step="0.01" varient="floating" name="compare_at_price"
                                        label="Compare At Price" placeholder="Enter Compare At Price" :value="old('compare_at_price')"
                                        :error="$errors->first('compare_at_price')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attributes Section -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Variant Attributes</h5>
                    </div>
                    <div class="card-body">
                        <div class="app-form" id="attributes-container">
                            @if ($attributes->count() > 0)
                                @foreach ($attributes as $index => $attribute)
                                    <div class="row mb-3 attribute-row">
                                        <div class="col-md-5">
                                            <label class="form-label">{{ $attribute->name }}</label>
                                            <input type="hidden" name="attributes[{{ $index }}][attribute_id]"
                                                value="{{ $attribute->id }}">
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-select" name="attributes[{{ $index }}][value]">
                                                <option value="">Select {{ $attribute->name }}</option>
                                                @foreach ($attribute->values as $value)
                                                    <option value="{{ $value->value }}">{{ $value->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No variant attributes defined. <a
                                        href="{{ route('admin.attributes.create') }}">Create attributes</a> first.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Options</h5>
                    </div>
                    <div class="card-body">
                        <x-forms.switch id="status" name="status" label="Status" :checked="old('status', true)" />
                        <div class="mt-3">
                            <button class="btn btn-primary w-100 mb-2" type="submit">Create Variant</button>
                            <a href="{{ route('admin.products.edit', \Crypt::encryptString($product->id)) }}"
                                class="btn btn-secondary w-100">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </x-forms.form>
        <!-- ready to use form end -->
    </div>
@endsection
