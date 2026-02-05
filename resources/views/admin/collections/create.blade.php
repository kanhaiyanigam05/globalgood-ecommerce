@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <!-- Breadcrumb start -->
                <div class="row m-1">
                    <div class="col-12">
                        <h4 class="main-title">Collections</h4>
                        <ul class="app-line-breadcrumbs mb-3">
                            <li>
                                <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                                    <span><i class="ph-duotone ph-house f-s-16"></i> Home</span>
                                </a>
                            </li>
                            <li>
                                <a class="f-s-14 f-w-500" href="{{ route('admin.collections.index') }}">
                                    <span><i class="ph-duotone ph-folder f-s-16"></i> Collections</span>
                                </a>
                            </li>
                            <li class="active">
                                <a class="f-s-14 f-w-500" href="#">Create Collection</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <x-forms.form :action="route('admin.collections.store')" method="post" enctype="multipart/form-data" varient="reactive"
                    class="row">
                    <div class="col-lg-8">
                        <!-- Main Details -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <div class="mb-4">
                                    <x-forms.input id="title" name="title" label="Title"
                                        placeholder="e.g. Summer collection, Under $100, Staff picks" :value="old('title')"
                                        :error="$errors->first('title')" required />
                                </div>
                                <div class="mb-2">
                                    <x-forms.editor id="description" name="description" label="Description"
                                        :value="old('description')" :error="$errors->first('description')" placeholder="Enter collection description..." />
                                </div>
                            </div>
                        </div>

                        <!-- Collection Type Selection -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Collection type</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="type" id="type_manual"
                                        value="manual" {{ old('type', 'manual') === 'manual' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_manual">
                                        <strong>Manual</strong>
                                        <p class="text-muted small mb-0">Add products to this collection one by one.</p>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_smart"
                                        value="smart" {{ old('type') === 'smart' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_smart">
                                        <strong>Smart</strong>
                                        <p class="text-muted small mb-0">Existing and future products that match the
                                            conditions you set will automatically be added to this collection.</p>
                                    </label>
                                </div>
                                @error('type')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Smart Collection - Conditions (Initially Hidden) -->
                        <div id="smart-conditions-wrapper"
                            class="card shadow-sm mb-4 {{ old('type') === 'smart' ? '' : 'd-none' }}">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Conditions</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <p class="mb-2 fw-medium">Products must match:</p>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="condition_type"
                                                id="cond_all" value="all"
                                                {{ old('condition_type', 'all') === 'all' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cond_all">all conditions</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="condition_type"
                                                id="cond_any" value="any"
                                                {{ old('condition_type') === 'any' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cond_any">any condition</label>
                                        </div>
                                    </div>
                                    @error('condition_type')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="conditions-list">
                                    @if (old('conditions'))
                                        @foreach (old('conditions') as $index => $condition)
                                            <div class="condition-row row gx-2 mb-2">
                                                <div class="col-4">
                                                    <select name="conditions[{{ $index }}][field]"
                                                        class="form-select condition-field">
                                                        @foreach (['title' => 'Product title', 'type' => 'Product type', 'category' => 'Category', 'price' => 'Product price', 'compare_at_price' => 'Compare at price', 'inventory_stock' => 'Inventory stock', 'tag' => 'Product tag'] as $val => $label)
                                                            <option value="{{ $val }}"
                                                                {{ $condition['field'] === $val ? 'selected' : '' }}>
                                                                {{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-3">
                                                    <select name="conditions[{{ $index }}][operator]"
                                                        class="form-select condition-operator"
                                                        data-initial="{{ $condition['operator'] }}">
                                                        {{-- Populated by JS --}}
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" name="conditions[{{ $index }}][value]"
                                                        class="form-control condition-value"
                                                        value="{{ $condition['value'] }}" placeholder="Value">
                                                    @error("conditions.{$index}.value")
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-1">
                                                    <button type="button"
                                                        class="btn btn-link text-danger remove-condition-btn p-0">
                                                        <i class="ph ph-trash f-s-18"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                @error('conditions')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror

                                <button type="button" class="btn btn-outline-secondary btn-sm mt-3"
                                    id="add-condition-btn">
                                    <i class="ph ph-plus"></i> Add another condition
                                </button>
                            </div>
                        </div>

                        <!-- Manual Collection - Products (Initially Visible) -->
                        <div id="manual-products-wrapper"
                            class="card shadow-sm mb-4 {{ old('type', 'manual') === 'manual' ? '' : 'd-none' }}">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Products</h6>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#collection-product-select-modal">Browse</button>
                            </div>
                            <div class="card-body">
                                @error('product_ids')
                                    <div class="alert alert-danger py-2 px-3 small mb-3">
                                        <i class="ph ph-warning-circle"></i> {{ $message }}
                                    </div>
                                @enderror

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="selected-products-table">
                                        <thead>
                                            <tr>
                                                <th width="50"></th>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="no-products-placeholder">
                                                <td colspan="4" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="ph ph-tag f-s-40 mb-3 d-block"></i>
                                                        There are no products in this collection.
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Publishing</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Active Status</span>
                                    <x-forms.switch name="status" id="status" :checked="true" />
                                </div>
                                <hr class="my-3 opacity-10">
                                <p class="small text-muted mb-0">Collections status determines visibility on your
                                    storefront.</p>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Collection image</h6>
                            </div>
                            <div class="card-body">
                                <x-forms.file name="image" label="Collection Image" :error="$errors->first('image')"
                                    accept="image/*" :useMediaLibrary="true" directory="collections" />
                                <p class="small text-muted mt-2">Upload or select an image to represent this collection.
                                </p>
                            </div>
                        </div>

                        <div class="card shadow-sm border-primary">
                            <div class="card-body d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Save Collection</button>
                            </div>
                        </div>
                    </div>
                </x-forms.form>
            </div>
        </div>
    </div>

    <!-- Product Select Modal Component -->
    <x-models.product-select 
        id="collection-product-select-modal" 
        :apiUrl="route('admin.collections.search-products')"
        :showVariants="true"
    />

    <!-- Templates -->
    <template id="condition-row-template">
        <div class="condition-row row gx-2 mb-2">
            <div class="col-4">
                <select name="conditions[INDEX][field]" class="form-select condition-field">
                    <option value="title">Product title</option>
                    <option value="type">Product type</option>
                    <option value="category">Category</option>
                    <option value="price">Product price</option>
                    <option value="compare_at_price">Compare at price</option>
                    <option value="inventory_stock">Inventory stock</option>
                    <option value="tag">Product tag</option>
                </select>
            </div>
            <div class="col-3">
                <select name="conditions[INDEX][operator]" class="form-select condition-operator">
                    <option value="equals">is equal to</option>
                    <option value="not_equals">is not equal to</option>
                    <option value="contains">contains</option>
                    <option value="not_contains">does not contain</option>
                    <option value="greater_than">is greater than</option>
                    <option value="less_than">is less than</option>
                    <option value="starts_with">starts with</option>
                    <option value="ends_with">ends with</option>
                </select>
            </div>
            <div class="col-4">
                <input type="text" name="conditions[INDEX][value]" class="form-control condition-value"
                    placeholder="Value">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-link text-danger remove-condition-btn p-0">
                    <i class="ph ph-trash f-s-18"></i>
                </button>
            </div>
        </div>
    </template>

    <template id="selected-product-row">
        <tr>
            <td>
                <div class="rounded bg-light" style="width: 40px; height: 40px; border: 1px solid #eee;"></div>
            </td>
            <td>
                <div class="fw-medium">PRODUCT_TITLE</div>
                <input type="hidden" name="product_ids[]" value="PRODUCT_ID">
            </td>
            <td class="text-muted">$PRODUCT_PRICE</td>
            <td class="text-end">
                <button type="button" class="btn btn-link text-danger remove-product-btn p-0">
                    <i class="ph ph-x f-s-16"></i>
                </button>
            </td>
        </tr>
    </template>

@endsection

@push('scripts:after')
    <script>
        $(document).ready(function() {
            // Toggle Manual/Smart sections
            $('input[name="type"]').change(function() {
                if (this.value === 'smart') {
                    $('#smart-conditions-wrapper').removeClass('d-none');
                    $('#manual-products-wrapper').addClass('d-none');
                } else {
                    $('#smart-conditions-wrapper').addClass('d-none');
                    $('#manual-products-wrapper').removeClass('d-none');
                }
            });

            // Condition Builder Logic
            let conditionIndex = {{ old('conditions') ? count(old('conditions')) : 0 }};
            const fieldOperators = {
                'title': ['equals', 'not_equals', 'contains', 'not_contains', 'starts_with', 'ends_with',
                    'is_empty', 'is_not_empty'
                ],
                'type': ['equals', 'not_equals'],
                'category': ['equals', 'not_equals'],
                'price': ['equals', 'not_equals', 'greater_than', 'less_than'],
                'compare_at_price': ['equals', 'not_equals', 'greater_than', 'less_than'],
                'inventory_stock': ['equals', 'not_equals', 'greater_than', 'less_than'],
                'tag': ['equals', 'not_equals', 'contains', 'not_contains']
            };

            const operatorLabels = {
                'equals': 'is equal to',
                'not_equals': 'is not equal to',
                'contains': 'contains',
                'not_contains': 'does not contain',
                'greater_than': 'is greater than',
                'less_than': 'is less than',
                'starts_with': 'starts with',
                'ends_with': 'ends with',
                'is_empty': 'is empty',
                'is_not_empty': 'is not empty'
            };

            function updateOperators(row) {
                const field = row.find('.condition-field').val();
                const operatorSelect = row.find('.condition-operator');
                const currentValue = operatorSelect.val() || operatorSelect.data('initial');

                operatorSelect.empty();
                const available = fieldOperators[field] || fieldOperators['title'];

                available.forEach(op => {
                    operatorSelect.append(
                        `<option value="${op}" ${currentValue == op ? 'selected' : ''}>${operatorLabels[op]}</option>`
                        );
                });

                // Toggle value input for empty/not empty
                const valueInput = row.find('.condition-value');
                if (['is_empty', 'is_not_empty'].includes(operatorSelect.val())) {
                    valueInput.addClass('invisible');
                } else {
                    valueInput.removeClass('invisible');
                }
            }

            function addCondition() {
                const template = $('#condition-row-template').html();
                const html = $(template.replace(/INDEX/g, conditionIndex++));
                $('#conditions-list').append(html);
                updateOperators(html);
            }

            // Initialize existing rows (from old input)
            $('.condition-row').each(function() {
                updateOperators($(this));
            });

            $('#add-condition-btn').click(addCondition);

            $(document).on('change', '.condition-field', function() {
                updateOperators($(this).closest('.condition-row'));
            });

            $(document).on('change', '.condition-operator', function() {
                const row = $(this).closest('.condition-row');
                const valueInput = row.find('.condition-value');
                if (['is_empty', 'is_not_empty'].includes($(this).val())) {
                    valueInput.addClass('invisible');
                } else {
                    valueInput.removeClass('invisible');
                }
            });

            $(document).on('click', '.remove-condition-btn', function() {
                $(this).closest('.condition-row').remove();
                if ($('.condition-row').length === 0) addCondition();
            });

            // Add initial condition if none exist
            if ($('.condition-row').length === 0) {
                addCondition();
            }

            // Product Select Component Integration
            window.addEventListener('products-selected', function(e) {
                const items = e.detail.items || [];
                
                // Group items by product ID
                const productMap = new Map();
                
                items.forEach(item => {
                    const productId = item.productId;
                    
                    if (!productMap.has(productId)) {
                        productMap.set(productId, {
                            productId: productId,
                            name: item.name,
                            price: item.price,
                            img: item.img,
                            variants: [],
                            isVariant: item.type === 'variant'
                        });
                    }
                    
                    if (item.type === 'variant') {
                        productMap.get(productId).variants.push({
                            id: item.id,
                            name: item.name
                        });
                    }
                });
                
                productMap.forEach((product, productId) => {
                    // Check if product already added
                    if ($(`input[name="product_ids[]"][value="${productId}"]`).length > 0) {
                        return;
                    }

                    $('#no-products-placeholder').hide();
                    
                    // Build variant display text
                    let displayName = product.name;
                    if (product.variants.length > 0) {
                        const variantNames = product.variants.map(v => v.name.split(' - ')[1] || v.name).join(', ');
                        displayName = product.name.split(' - ')[0] + ' (' + variantNames + ')';
                    }
                    
                    // Build variant hidden inputs
                    let variantInputs = '';
                    if (product.variants.length > 0) {
                        product.variants.forEach(variant => {
                            variantInputs += `<input type="hidden" name="variant_ids[${productId}][]" value="${variant.id}">`;
                        });
                    }

                    const row = `
                        <tr>
                            <td>
                                <img src="${product.img || 'https://placehold.co/40x40'}" 
                                     class="rounded border" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-medium">${displayName}</div>
                                <input type="hidden" name="product_ids[]" value="${productId}">
                                ${variantInputs}
                            </td>
                            <td class="text-muted">₹${product.price.toFixed(2)}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-link text-danger remove-product-btn p-0">
                                    <i class="ph ph-x f-s-16"></i>
                                </button>
                            </td>
                        </tr>
                    `;

                    $('#selected-products-table tbody').append(row);
                });
            });

            // Provide current selection state to modal when it requests it
            window.addEventListener('request-selection-state', function() {
                const selectedIds = [];
                $('input[name="product_ids[]"]').each(function() {
                    selectedIds.push('p_' + $(this).val());
                });

                window.dispatchEvent(new CustomEvent('provide-selection-state', {
                    detail: { selectedIds: selectedIds }
                }));
            });

            // Remove product
            $(document).on('click', '.remove-product-btn', function() {
                $(this).closest('tr').remove();
                if ($('#selected-products-table tbody tr').length === 0 || 
                    ($('#selected-products-table tbody tr').length === 1 && $('#no-products-placeholder').length)) {
                    $('#no-products-placeholder').show();
                }
            });
        });
    </script>
@endpush
