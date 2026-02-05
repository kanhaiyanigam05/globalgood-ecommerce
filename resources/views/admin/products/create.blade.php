@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Breadcrumb start -->
                <div class="row m-1">
                    <div class="col-12">
                        <h4 class="main-title">Products</h4>
                        <ul class="app-line-breadcrumbs mb-3">
                            <li>
                                <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                                    <span><i class="ph-duotone ph-house f-s-16"></i> Home</span>
                                </a>
                            </li>
                            <li>
                                <a class="f-s-14 f-w-500" href="{{ route('admin.products.index') }}">
                                    <span><i class="ph-duotone ph-package f-s-16"></i> Products</span>
                                </a>
                            </li>
                            <li class="active">
                                <a class="f-s-14 f-w-500" href="#">Create New Product</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Breadcrumb end -->

                <!-- Main Product Form -->
                <x-forms.form :action="route('admin.products.store')" method="post" enctype="multipart/form-data" varient="reactive"
                    class="row">
                    <div class="col-lg-7 col-md-8">
                        <!-- Product Details Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ph-duotone ph-info"></i> Product Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="app-form">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <x-forms.input id="title" name="title" label="Product Title"
                                                placeholder="Enter Product Title" :value="old('title')" :error="$errors->first('title')"
                                                required />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-forms.input id="slug" name="slug" label="URL Slug"
                                                placeholder="auto-generated-from-title" :value="old('slug')"
                                                :error="$errors->first('slug')" />
                                            <small class="text-muted">Leave blank to auto-generate from title</small>
                                        </div>

                                        {{-- Hierarchical Category Select --}}
                                        <div class="col-md-6 mb-3">
                                            <x-forms.hierarchical-select id="category_id" name="category_id"
                                                label="Category" clearable placeholder="Select a category"
                                                searchPlaceholder="Search categories..." :options="$categories"
                                                apiUrl="{{ route('admin.categories.hierarchical_data') }}" :value="old('category_id')"
                                                :error="$errors->first('category_id')" required />
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <x-forms.input type="number" step="0.01" name="price" label="Price ($)"
                                                placeholder="0.00" :value="old('price')" :error="$errors->first('price')" required />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-forms.input type="number" step="0.01" name="compare_at_price"
                                                label="Compare At Price ($)" placeholder="0.00" :value="old('compare_at_price')"
                                                :error="$errors->first('compare_at_price')" />
                                            <small class="text-muted">Original price before discount</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-forms.input type="number" name="quantity" label="Stock Quantity"
                                                placeholder="0" :value="old('quantity')" :error="$errors->first('quantity')" required />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-forms.file name="images[]" label="Product Images" :multiple="true" :useMediaLibrary="true" directory="products" accept="image/*" />
                                            <small class="text-muted">Upload high-quality images for your product.</small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-forms.textarea id="short_description" rows="3" name="short_description"
                                                label="Short Description"
                                                placeholder="Brief product summary (shown in listings)" :value="old('short_description')"
                                                :error="$errors->first('short_description')" />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-forms.editor id="description" rows="8" name="description"
                                                label="Full Description" placeholder="Detailed product description"
                                                :value="old('description')" :error="$errors->first('description')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shopify-Style Variant Options Component -->
                        <x-variant-select />

                    </div>

                    <div class="col-lg-4 col-md-5">
                        <!-- Options Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ph-duotone ph-toggles"></i> Options</h5>
                            </div>
                            <div class="card-body">
                                <x-forms.switch id="status" name="status" label="Published" :checked="old('status', true)" />
                                <small class="text-muted d-block mb-3">Make product visible on storefront</small>

                                <x-forms.switch id="is_featured" name="is_featured" label="Featured Product"
                                    :checked="old('is_featured', false)" />
                                <small class="text-muted d-block">Show in featured sections</small>
                            </div>
                        </div>

                        <!-- Product Attributes Card -->
                        <div class="card mt-3" id="product-attributes-wrapper" style="display:none;">
                            <div class="card-header">
                                <h5><i class="ph-duotone ph-list-bullets"></i> Product Attributes</h5>
                            </div>
                            <div class="card-body">
                                <div class="app-form" id="product-attributes-container">
                                    <!-- Attributes will be loaded here dynamically -->
                                </div>
                                <small class="text-muted">Category-specific attributes</small>
                            </div>
                        </div>

                        <!-- Meta Details Card -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5><i class="ph-duotone ph-magnifying-glass"></i> SEO Meta</h5>
                            </div>
                            <div class="card-body">
                                <div class="app-form">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <x-forms.input name="meta_title" label="Meta Title"
                                                placeholder="SEO optimized title" :value="old('meta_title')" :error="$errors->first('meta_title')" />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-forms.input name="meta_keywords" label="Meta Keywords"
                                                placeholder="keyword1, keyword2, keyword3" :value="old('meta_keywords')"
                                                :error="$errors->first('meta_keywords')" />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-forms.textarea rows="3" name="meta_description"
                                                label="Meta Description"
                                                placeholder="SEO meta description (150-160 chars)" :value="old('meta_description')"
                                                :error="$errors->first('meta_description')" />
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="ph-duotone ph-check-circle"></i> Create Product
                                                </button>
                                                <button class="btn btn-secondary" type="reset">
                                                    <i class="ph-duotone ph-arrow-counter-clockwise"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-forms.form>
            </div>
        </div>
    </div>
@endsection

@push('styles:before')
    <style>
        .card-header h5 {
            margin-bottom: 0;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .badge {
            font-weight: 500;
        }

        #product-attributes-wrapper {
            transition: all 0.3s ease;
        }
    </style>
@endpush

@push('scripts:after')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Slug generation
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if (titleInput && slugInput) {
                let slugManuallyEdited = false;

                const slugify = (text) => {
                    return text
                        .toString()
                        .toLowerCase()
                        .trim()
                        .replace(/\s+/g, '-')
                        .replace(/[^\w\-]+/g, '')
                        .replace(/\-\-+/g, '-');
                };

                slugInput.addEventListener('input', () => {
                    slugManuallyEdited = slugInput.value.length > 0;
                });

                titleInput.addEventListener('input', () => {
                    if (!slugManuallyEdited) {
                        slugInput.value = slugify(titleInput.value);
                    }
                });

                slugInput.addEventListener('blur', () => {
                    if (slugInput.value.trim() === '') {
                        slugManuallyEdited = false;
                        slugInput.value = slugify(titleInput.value);
                    }
                });
            }

            // ========================================
            // REACTIVE CATEGORY-BASED ATTRIBUTE FILTERING
            // ========================================
            const categoryInput = document.querySelector('input[name="category_id"]');
            const productAttributesWrapper = document.getElementById('product-attributes-wrapper');
            const productAttributesContainer = document.getElementById('product-attributes-container');

            const fetchAttributesByCategory = async (categoryId) => {
                if (!categoryId) {
                    productAttributesWrapper.style.display = 'none';
                    productAttributesContainer.innerHTML = '';
                    return;
                }

                try {
                    const url =
                        `{{ route('admin.attributes.by-category') }}?category_id=${categoryId}&scope={{ \App\Enums\Scope::PRODUCT->value }}`;
                    const response = await fetch(url);
                    const data = await response.json();

                    if (data.success) {
                        renderProductAttributes(data.attributes);
                    }
                } catch (error) {
                    console.error('Error fetching attributes:', error);
                    window.toast?.error('Failed to load attributes');
                }
            };

            const renderProductAttributes = (attributes) => {
                productAttributesContainer.innerHTML = '';

                if (attributes.length === 0) {
                    productAttributesWrapper.style.display = 'none';
                    return;
                }

                productAttributesWrapper.style.display = 'block';
                $(productAttributesWrapper).hide().slideDown(300);

                attributes.forEach(attr => {
                    let optionsHtml = `<option value="">Select ${attr.name}</option>`;
                    attr.values.forEach(val => {
                        optionsHtml += `<option value="${val.value}">${val.value}</option>`;
                    });

                    const attrHtml = `
                        <div class="mb-3 attribute-item" data-id="${attr.id}">
                            <label class="form-label">${attr.name}</label>
                            <select class="form-select form-select-sm" name="product_attributes[${attr.id}]">
                                ${optionsHtml}
                            </select>
                        </div>
                    `;
                    productAttributesContainer.insertAdjacentHTML('beforeend', attrHtml);
                });
            };

            // Listen to category change from hierarchical select
            if (categoryInput) {
                categoryInput.addEventListener('change', function() {
                    const categoryId = this.value;
                    console.log('Category changed to:', categoryId); // Debug log
                    fetchAttributesByCategory(categoryId);
                });

                // Trigger on page load if category already selected (from old() input)
                const initialCategoryId = categoryInput.value;
                if (initialCategoryId) {
                    fetchAttributesByCategory(initialCategoryId);
                }
            }

            // Update base price when product price changes
            const priceInput = document.querySelector('input[name="price"]');
            if (priceInput) {
                priceInput.addEventListener('change', () => {
                    document.querySelectorAll('.variant-price').forEach(input => {
                        if (!input.dataset.edited) {
                            input.value = priceInput.value;
                        }
                    });
                });
            }

            // Track manual edits to variant prices
            document.addEventListener('input', (e) => {
                if (e.target.classList.contains('variant-price')) {
                    e.target.dataset.edited = 'true';
                }
            });
        });
    </script>
@endpush
