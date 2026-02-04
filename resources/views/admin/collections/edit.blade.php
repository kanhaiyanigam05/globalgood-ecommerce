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
                                <a class="f-s-14 f-w-500" href="#">Edit Collection</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <x-forms.form :action="route('admin.collections.update', Crypt::encryptString($collection->id))" method="put" enctype="multipart/form-data" varient="reactive" class="row">
                    <div class="col-lg-8">
                        <!-- Main Details -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <div class="mb-4">
                                    <x-forms.input id="title" name="title" label="Title" placeholder="e.g. Summer collection" :value="old('title', $collection->title)" :error="$errors->first('title')" required />
                                </div>
                                <div class="mb-2">
                                    <x-forms.editor id="description" name="description" label="Description" :value="old('description', $collection->description)" :error="$errors->first('description')" placeholder="Enter collection description..." />
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
                                    <input class="form-check-input" type="radio" name="type" id="type_manual" value="manual" {{ old('type', $collection->type) === 'manual' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_manual">
                                        <strong>Manual</strong>
                                        <p class="text-muted small mb-0">Add products to this collection one by one.</p>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_smart" value="smart" {{ old('type', $collection->type) === 'smart' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_smart">
                                        <strong>Smart</strong>
                                        <p class="text-muted small mb-0">Existing and future products that match the conditions you set will automatically be added to this collection.</p>
                                    </label>
                                </div>
                                @error('type')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Smart Collection - Conditions -->
                        <div id="smart-conditions-wrapper" class="card shadow-sm mb-4 {{ old('type', $collection->type) === 'smart' ? '' : 'd-none' }}">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Conditions</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <p class="mb-2 fw-medium">Products must match:</p>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="condition_type" id="cond_all" value="all" {{ old('condition_type', $collection->condition_type) === 'all' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cond_all">all conditions</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="condition_type" id="cond_any" value="any" {{ old('condition_type', $collection->condition_type) === 'any' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cond_any">any condition</label>
                                        </div>
                                    </div>
                                    @error('condition_type')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="conditions-list">
                                    @php
                                        $displayConditions = old('conditions', $collection->conditions->toArray());
                                    @endphp
                                    @foreach($displayConditions as $index => $condition)
                                        <div class="condition-row row gx-2 mb-2 align-items-center">
                                            <div class="col-4">
                                                <select name="conditions[{{ $index }}][field]" class="form-select condition-field">
                                                    @foreach(['title'=>'Product title','type'=>'Product type','category'=>'Category','price'=>'Product price','compare_at_price'=>'Compare at price','inventory_stock'=>'Inventory stock','tag'=>'Product tag'] as $val => $label)
                                                        <option value="{{ $val }}" {{ ($condition['field'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <select name="conditions[{{ $index }}][operator]" class="form-select condition-operator" data-initial="{{ $condition['operator'] ?? '' }}">
                                                    {{-- Populated by JS --}}
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <input type="text" name="conditions[{{ $index }}][value]" class="form-control condition-value" value="{{ $condition['value'] ?? '' }}" placeholder="Value">
                                                @error("conditions.{$index}.value")
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-1">
                                                <button type="button" class="btn btn-link text-danger remove-condition-btn p-0">
                                                    <i class="ph ph-trash f-s-18"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('conditions')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror

                                <button type="button" class="btn btn-outline-secondary btn-sm mt-3" id="add-condition-btn">
                                    <i class="ph ph-plus"></i> Add another condition
                                </button>
                            </div>
                        </div>

                        <!-- Manual Collection - Products -->
                        <div id="manual-products-wrapper" class="card shadow-sm mb-4 {{ old('type', $collection->type) === 'manual' ? '' : 'd-none' }}">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Products</h6>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="browse-products-btn">Browse</button>
                            </div>
                            <div class="card-body">
                                @error('product_ids')
                                    <div class="alert alert-danger py-2 px-3 small mb-3">
                                        <i class="ph ph-warning-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                                        <input type="text" id="product-search" class="form-control border-start-0" placeholder="Search products...">
                                    </div>
                                    <div id="search-results-dropdown" class="dropdown-menu w-100 shadow-sm" style="max-height: 250px; overflow-y: auto;"></div>
                                </div>

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
                                            @forelse($collection->products as $product)
                                                <tr>
                                                    <td><img src="{{ $product->firstImage() ? route('file.path', ['path' => $product->firstImage()->file, 'w' => 40, 'h' => 40]) : 'https://placehold.co/40x40' }}" width="40" height="40" class="rounded border"></td>
                                                    <td>
                                                        <div class="fw-medium">{{ $product->title }}</div>
                                                        <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                                    </td>
                                                    <td class="text-muted">${{ $product->price_formatted }}</td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-link text-danger remove-product-btn p-0">
                                                            <i class="ph ph-x f-s-16"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr id="no-products-placeholder">
                                                    <td colspan="4" class="text-center py-5">
                                                        <div class="text-muted">
                                                            <i class="ph ph-tag f-s-40 mb-3 d-block"></i>
                                                            There are no products in this collection.
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
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
                                    <x-forms.switch name="status" id="status" :checked="$collection->status" />
                                </div>
                                <hr class="my-3 opacity-10">
                                <p class="small text-muted mb-0">Collections status determines visibility on your storefront.</p>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Collection image</h6>
                            </div>
                            <div class="card-body">
                                <x-forms.file name="image" label="Collection Image"
                                    :value="$collection->image"
                                    :error="$errors->first('image')" 
                                    accept="image/*" />
                                <p class="small text-muted mt-2">Upload or select an image to change the current one.</p>
                            </div>
                        </div>

                        <div class="card shadow-sm border-primary">
                            <div class="card-body d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Update Collection</button>
                            </div>
                        </div>
                    </div>
                </x-forms.form>
            </div>
        </div>
    </div>

    <!-- Templates -->
    <template id="condition-row-template">
        <div class="condition-row row gx-2 mb-2 align-items-center">
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
                <input type="text" name="conditions[INDEX][value]" class="form-control condition-value" placeholder="Value">
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
            <td><div class="rounded bg-light" style="width: 40px; height: 40px; border: 1px solid #eee;"></div></td>
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
        let conditionIndex = {{ old('conditions') ? count(old('conditions')) : $collection->conditions->count() }};
        const fieldOperators = {
            'title': ['equals', 'not_equals', 'contains', 'not_contains', 'starts_with', 'ends_with', 'is_empty', 'is_not_empty'],
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
                operatorSelect.append(`<option value="${op}" ${currentValue == op ? 'selected' : ''}>${operatorLabels[op]}</option>`);
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

        // Initialize existing rows
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

        // Product Search Logic for Manual Collection
        let searchTimeout;
        $('#product-search').on('input', function() {
            const query = $(this).val();
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                $('#search-results-dropdown').removeClass('show').empty();
                return;
            }

            searchTimeout = setTimeout(() => {
                $.get("{{ route('admin.collections.search-products') }}", { q: query }, function(data) {
                    const dropdown = $('#search-results-dropdown');
                    dropdown.empty();
                    
                    if (data.length > 0) {
                        data.forEach(product => {
                            dropdown.append(`
                                <button type="button" class="dropdown-item d-flex justify-content-between align-items-center py-2 add-product-item" 
                                    data-id="${product.id}" data-title="${product.title}" data-price="${(product.price / 100).toFixed(2)}">
                                    <span>${product.title}</span>
                                    <span class="text-muted small">$${(product.price / 100).toFixed(2)}</span>
                                </button>
                            `);
                        });
                        dropdown.addClass('show');
                    } else {
                        dropdown.removeClass('show');
                    }
                });
            }, 300);
        });

        $(document).on('click', '.add-product-item', function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            const price = $(this).data('price');

            if ($(`input[name="product_ids[]"][value="${id}"]`).length > 0) {
                window.toast.warning('Product already added');
                return;
            }

            $('#no-products-placeholder').hide();
            
            const template = $('#selected-product-row').html();
            const html = template.replace(/PRODUCT_TITLE/g, title)
                                .replace(/PRODUCT_ID/g, id)
                                .replace(/PRODUCT_PRICE/g, price);
            
            $('#selected-products-table tbody').append(html);
            $('#product-search').val('');
            $('#search-results-dropdown').removeClass('show');
        });

        $(document).on('click', '.remove-product-btn', function() {
            $(this).closest('tr').remove();
            if ($('#selected-products-table tbody tr').length === 0 || ($('#selected-products-table tbody tr').length === 1 && $('#no-products-placeholder').length)) {
                 $('#no-products-placeholder').show();
            }
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#product-search, #search-results-dropdown').length) {
                $('#search-results-dropdown').removeClass('show');
            }
        });
    });
</script>
@endpush
