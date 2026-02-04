<!-- Item Browse Modal -->
<div class="modal fade" id="itemBrowseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title f-w-600">Select Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <style>
                    .modal-table-header { display: grid; grid-template-columns: 40px 1fr 100px 120px; padding: 10px 20px; background: #f9fafb; border-bottom: 1px solid #f1f2f3; font-size: 12px; font-weight: 600; color: #6d7175; }
                    .modal-item-row { display: grid; grid-template-columns: 40px 1fr 100px 120px; padding: 12px 20px; align-items: center; border-bottom: 1px solid #f1f2f3; cursor: pointer; }
                    .modal-item-row:hover { background: #f9fafb; }
                    .modal-product-img { width: 36px; height: 36px; border-radius: 4px; object-fit: cover; border: 1px solid #ebebeb; }
                    .variant-row { grid-template-columns: 80px 1fr 100px 120px; background: #fafafa; }
                    .collection-item-row { display: grid; grid-template-columns: 40px 60px 1fr 100px; padding: 12px 20px; align-items: center; border-bottom: 1px solid #f1f2f3; cursor: pointer; }
                    .customer-item-row { display: grid; grid-template-columns: 40px 1fr 100px 120px; padding: 12px 20px; align-items: center; border-bottom: 1px solid #f1f2f3; cursor: pointer; }
                </style>
                <div class="p-3 border-bottom">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="ph ph-magnifying-glass"></i></span>
                        <input type="text" class="form-control border-start-0" id="modalSearch" placeholder="Search...">
                    </div>
                </div>
                <div id="modalHeaderRow" class="modal-table-header d-none">
                    <div></div>
                    <div id="modalHeaderText1">Product</div>
                    <div id="modalHeaderText2">Available</div>
                    <div id="modalHeaderText3">Price</div>
                </div>
                <div id="modalItemList" style="max-height: 500px; overflow-y: auto;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-shopify" id="modalAddBtn">Add</button>
            </div>
        </div>
    </div>
</div>

<script>
    // These need to be globally available or passed to initialize function
    const productsData = {!! isset($products) ? $products->toJson() : '[]' !!};
    const collectionsData = {!! isset($collections) ? $collections->toJson() : '[]' !!};
    const customersData = {!! isset($customers) ? $customers->toJson() : '[]' !!};
    const countriesData = {!! isset($countries) ? $countries->toJson() : '[]' !!};

    console.log('Products Data:', productsData); // Debugging

    function formatCurrency(amount) {
        return '$' + (amount / 100).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function isSelected(type, id) {
        // Use the current modal's target input name (e.g., 'buy_items[]', 'get_items[]', 'targets[]')
        // Default to 'targets[]' if not set (though it should be set when modal opens)
        const inputName = currentModalTargetInput || 'targets[]';
        const typeInputName = currentModalTypeInput || 'target_types[]';
        
        let found = false;
        $(`input[name="${inputName}"]`).each(function() {
            if ($(this).val() == id) {
                // If checking for a customer or country, we usually just match the ID in the specific array
                if (type === 'customer' || type === 'country') {
                    found = true;
                    return false;
                }

                // For others, check the sibling type input to ensure we don't mix up products/collections with same ID
                // The type input should be a sibling in the same container
                const typeVal = $(this).parent().find(`input[name="${typeInputName}"]`).val();
                
                // If found type input, check match. 
                // Note: 'variant' type check might need care if we only store variant IDs but type is 'variant'
                if (typeVal) {
                    if (typeVal == type) {
                        found = true;
                        return false;
                    }
                } else {
                    // Fallback: if no type input found (legacy or unsure), assume ID match is enough
                    found = true;
                    return false;
                }
            }
        });
        return found;
    }

    function updateParentCheckbox(productId) {
        const parentCb = $(`.product-cb[data-id="${productId}"]`);
        const variantCbs = $(`.variant-cb[data-pid="${productId}"]`);
        const total = variantCbs.length;
        if (total === 0) return;
        const checked = variantCbs.filter(':checked').length;

        if (checked === 0) {
            parentCb.prop('checked', false).prop('indeterminate', false);
        } else if (checked === total) {
            parentCb.prop('checked', true).prop('indeterminate', false);
        } else {
            parentCb.prop('checked', false).prop('indeterminate', true);
        }
    }

    function renderModalItems(type, query = '') {
        const modalBody = $('#modalItemList');
        const header = $('#modalHeaderRow');
        modalBody.empty();
        query = query.toLowerCase();

        // Support singular strings for market sales
        if (type === 'product') type = 'products';
        if (type === 'collection') type = 'collections';
        if (type === 'customer') type = 'customers';
        if (type === 'country') type = 'countries';

        if (type === 'customers') {
            header.removeClass('d-none');
            $('#modalHeaderText1').text('Customer');
            $('#modalHeaderText2').text('Orders');
            $('#modalHeaderText3').text('Spent');
            $('.modal-title').text('Add customers');

            const filtered = customersData.filter(c => 
                (c.first_name + ' ' + (c.last_name || '')).toLowerCase().includes(query) || 
                (c.email && c.email.toLowerCase().includes(query))
            );

            filtered.forEach(customer => {
                const name = customer.full_name || (customer.first_name + ' ' + (customer.last_name || ''));
                const checked = isSelected('customer', customer.id) ? 'checked' : '';
                modalBody.append(`
                    <div class="customer-item-row" data-id="${customer.id}" data-type="customer">
                        <div><input type="checkbox" class="form-check-input customer-cb" data-id="${customer.id}" data-name="${name}" ${checked}></div>
                        <div class="d-flex flex-column">
                            <span class="f-s-14 f-w-600 text-primary">${name}</span>
                            <span class="text-muted f-s-12">${customer.email || 'No email'}</span>
                        </div>
                        <div class="f-s-13">${customer.total_orders || 0} orders</div>
                        <div class="f-s-13">${formatCurrency((customer.total_spent || 0) * 100)}</div>
                    </div>
                `);
            });
        } else if (type === 'products') {
            header.removeClass('d-none');
            $('#modalHeaderText1').text('Product');
            $('#modalHeaderText2').text('Available');
            $('#modalHeaderText3').text('Price');
            $('.modal-title').text('Add products');

            productsData.forEach(product => {
                const matchesProduct = product.title.toLowerCase().includes(query);
                const variants = product.variants || [];
                const matchingVariants = variants.filter(v => {
                    const attrs = v.attributes || [];
                    return attrs.some(a => {
                        const val = a.pivot?.value || '';
                        return val.toLowerCase().includes(query);
                    });
                });

                if (matchesProduct || matchingVariants.length > 0) {
                    const images = product.images || [];
                    const img = images.length > 0 ? `/uploads/${images[0].file}` : '/admins/svg/_sprite.svg#shop';
                    const checked = isSelected('product', product.id) ? 'checked' : '';
                    
                    modalBody.append(`
                        <div class="modal-item-row" data-id="${product.id}" data-type="product">
                            <div><input type="checkbox" class="form-check-input product-cb" data-id="${product.id}" data-name="${product.title}" ${checked}></div>
                            <div class="d-flex align-items-center">
                                <img src="${img}" class="modal-product-img me-3" onerror="this.src='/admins/svg/_sprite.svg#shop'">
                                <span class="f-s-14 f-w-600">${product.title}</span>
                            </div>
                            <div class="text-muted f-s-13">${variants.length > 0 ? '' : product.quantity}</div>
                            <div class="text-muted f-s-13">${variants.length > 0 ? '' : formatCurrency(product.price)}</div>
                        </div>
                    `);

                    const variantsToRender = matchesProduct ? variants : matchingVariants;
                    variantsToRender.forEach(variant => {
                        const attrs = variant.attributes || [];
                        const vName = attrs.map(a => a.pivot?.value || '').filter(v => v).join(' / ') || 'Default';
                        const vChecked = isSelected('variant', variant.id) ? 'checked' : '';
                        modalBody.append(`
                            <div class="modal-item-row variant-row ps-5" data-id="${variant.id}" data-pid="${product.id}" data-type="variant">
                                <div><input type="checkbox" class="form-check-input variant-cb" data-id="${variant.id}" data-name="${product.title} - ${vName}" data-pid="${product.id}" ${vChecked}></div>
                                <div class="f-s-13 text-muted">${vName}</div>
                                <div class="f-s-13 text-muted">${variant.quantity}</div>
                                <div class="f-s-13 text-muted">${formatCurrency(variant.price)}</div>
                            </div>
                        `);
                    });
                }
            });

            productsData.forEach(product => {
                if (product.variants && product.variants.length > 0) {
                    updateParentCheckbox(product.id);
                }
            });
        } else if (type === 'collections') {
            header.addClass('d-none');
            $('.modal-title').text('Add collections');
            
            collectionsData.filter(c => c.title.toLowerCase().includes(query)).forEach(collection => {
                const img = collection.image ? `/uploads/${collection.image}` : '';
                const checked = isSelected('collection', collection.id) ? 'checked' : '';
                modalBody.append(`
                    <div class="collection-item-row" data-id="${collection.id}" data-type="collection">
                        <div><input type="checkbox" class="form-check-input collection-cb" data-id="${collection.id}" data-name="${collection.title}" ${checked}></div>
                        <div class="d-flex align-items-center bg-light rounded shadow-sm overflow-hidden" style="width: 48px; height: 48px; border: 1px solid #eee;">
                            ${img ? `<img src="${img}" style="width: 100%; height: 100%; object-fit: cover;">` : '<i class="ph ph-image text-muted" style="margin: auto;"></i>'}
                        </div>
                        <div class="ps-3">
                            <div class="f-s-14 f-w-600">${collection.title}</div>
                            <div class="text-muted f-s-12">${collection.products_count} products</div>
                        </div>
                    </div>
                `);
            });
        } else if (type === 'countries') {
            header.addClass('d-none');
            $('.modal-title').text('Add countries');
            
            countriesData.filter(c => c.name.toLowerCase().includes(query)).forEach(country => {
                const flagUrl = `/flags/${country.code.toLowerCase()}.svg`;
                const checked = isSelected('country', country.id) ? 'checked' : '';
                modalBody.append(`
                    <div class="modal-item-row" data-id="${country.id}" data-type="country">
                        <div><input type="checkbox" class="form-check-input country-cb" data-id="${country.id}" data-name="${country.name}" data-code="${country.code}" ${checked}></div>
                        <div class="d-flex align-items-center">
                            <img src="${flagUrl}" style="width: 24px; height: 16px; object-fit: cover; border: 1px solid #eee;" class="me-3" onerror="this.src='/admins/svg/_sprite.svg#shop'">
                            <span class="f-s-14 f-w-600">${country.name}</span>
                        </div>
                    </div>
                `);
            });
        }
    }

    let currentModalTargetArea = null;
    let currentModalTargetInput = null;
    let currentModalTypeInput = 'target_types[]';
    let currentModalType = 'products';

    function initializeDiscountModals(defaultType) {
        currentModalType = defaultType;

        $(document).on('click', '#browseBtn, #browseCustomersBtn, #browseCountriesBtn, .browse-items-btn', function() {
            const forcedType = $(this).data('type');
            
            // Priority: data-type > matching .items-type-select > defaultType
            let resolvedType = forcedType;
            if (!resolvedType) {
                const targetAreaId = $(this).data('target-area');
                // Try finding the select that specifically targets this selection area
                let specificSelect = $(`.items-type-select[data-target="${targetAreaId}"]`);
                
                // If not found by direct ID, check if the selector in data-target contains the current area
                if (!specificSelect.length && targetAreaId) {
                    $('.items-type-select').each(function() {
                        const selectTarget = $(this).data('target');
                        if (targetAreaId.includes(selectTarget.replace('#', '')) || selectTarget.includes(targetAreaId.replace('#', ''))) {
                            specificSelect = $(this);
                            return false;
                        }
                    });
                }

                if (specificSelect.length) {
                    resolvedType = specificSelect.val();
                } else {
                    // Fallback to the first select in the same card
                    resolvedType = $(this).closest('.discount-card').find('.items-type-select').val();
                }

                if (!resolvedType) {
                    const globalSelect = $('#appliesTo');
                    if (globalSelect.length) {
                        resolvedType = globalSelect.val();
                    }
                }
            }
            
            currentModalType = resolvedType || defaultType || 'products';
            
            if ($(this).attr('id') === 'browseCustomersBtn' || $(this).hasClass('browse-customers-btn')) {
                currentModalType = 'customers';
            } else if ($(this).attr('id') === 'browseCountriesBtn' || $(this).hasClass('browse-countries-btn')) {
                currentModalType = 'countries';
            }

            // Set targets for selection with intelligent defaults
            let defaultArea = '#selectedItems';
            let defaultInput = 'targets[]';
            
            if (currentModalType === 'customers') {
                defaultArea = '#selectedCustomers';
                defaultInput = 'customer_ids[]';
            } else if (currentModalType === 'countries') {
                defaultArea = '#selectedCountriesList';
                defaultInput = 'selected_countries[]';
            }

            currentModalTargetArea = $($(this).data('target-area') || defaultArea);
            currentModalTargetInput = $(this).data('target-input') || defaultInput;
            currentModalTypeInput = $(this).data('type-input') || 'target_types[]';
            
            $('#modalSearch').val('');
            renderModalItems(currentModalType);
            $('#itemBrowseModal').modal('show');
            
            // Re-bind add button to use current targets
            $('#modalAddBtn').off('click').on('click', function() {
                const checkedItems = $('#modalItemList input:checked');
                const selectedProducts = {};
                const selectedCollections = [];
                const selectedCustomers = [];
                const selectedCountries = [];

                checkedItems.each(function() {
                    const el = $(this);
                    const isVariant = el.hasClass('variant-cb');
                    const isProduct = el.hasClass('product-cb');
                    const isCollection = el.hasClass('collection-cb');
                    const isCustomer = el.hasClass('customer-cb');

                    if (isCollection) {
                        selectedCollections.push({
                            id: el.data('id'),
                            name: el.data('name')
                        });
                    } else if (isCustomer) {
                        selectedCustomers.push({
                            id: el.data('id'),
                            name: el.data('name')
                        });
                    } else if (el.hasClass('country-cb')) {
                        selectedCountries.push({
                            id: el.data('id'),
                            name: el.data('name'),
                            code: el.data('code')
                        });
                    } else {
                        // Product or Variant
                        let pid = isProduct ? el.data('id') : el.data('pid');
                        if (!selectedProducts[pid]) {
                            selectedProducts[pid] = {
                                hasFullProduct: false,
                                variants: []
                            };
                        }
                        
                        if (isProduct) {
                            selectedProducts[pid].hasFullProduct = true;
                        } else if (isVariant) {
                            selectedProducts[pid].variants.push(el.data('id'));
                        }
                    }
                });

                // Render Collections
                selectedCollections.forEach(c => {
                    const uniqueId = `selected-collection-${c.id}`;
                    if (!$(`#${uniqueId}`).length) {
                        currentModalTargetArea.append(renderSelectionRow('collection', c.id, c.name, 'folders'));
                    }
                });

                // Render Customers
                selectedCustomers.forEach(c => {
                    const uniqueId = `selected-customer-${c.id}`;
                    if (!$(`#${uniqueId}`).length) {
                        currentModalTargetArea.append(renderSelectionRow('customer', c.id, c.name, 'user'));
                    }
                });

                // Render Countries
                selectedCountries.forEach(c => {
                    const uniqueId = `selected-country-${c.id}`;
                    if (!$(`#${uniqueId}`).length) {
                        const flagUrl = `/flags/${c.code.toLowerCase()}.svg`;
                        const row = `
                            <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded" id="${uniqueId}">
                                <div class="d-flex align-items-center">
                                    <img src="${flagUrl}" style="width: 24px; height: 16px; object-fit: cover; border: 1px solid #eee;" class="me-2">
                                    <span class="f-s-13">${c.name}</span>
                                    <input type="hidden" name="${currentModalTargetInput}" value="${c.id}">
                                </div>
                                <button type="button" class="btn btn-link link-danger p-0 ms-2" onclick="$(this).closest('.bg-light').remove()">
                                    <i class="ph ph-x"></i>
                                </button>
                            </div>
                        `;
                        currentModalTargetArea.append(row);
                    }
                });

                // Render Products (Grouped)
                Object.keys(selectedProducts).forEach(pid => {
                    const selection = selectedProducts[pid];
                    const product = productsData.find(p => p.id == pid);
                    if (!product) return;

                    // If full product is selected, rendering the product row is enough
                    // If variants are selected, we check if ALL variants are selected
                    const totalVariants = product.variants ? product.variants.length : 0;
                    const selectedCount = selection.variants.length;
                    
                    // Logic: If product checkbox checked OR all variants checked -> Full Product
                    const isFull = selection.hasFullProduct || (totalVariants > 0 && selectedCount === totalVariants);
                    
                    // Remove existing row for this product if any (to update it)
                    $(`#selected-product-group-${pid}`).remove();

                    if (isFull) {
                        // Full Product
                        const html = renderProductRow(product, 'All variants selected', true, [], currentModalTargetInput, currentModalTypeInput); 
                        currentModalTargetArea.append(html);
                    } else if (selectedCount > 0) {
                        // Partial
                        const subtitle = `${selectedCount} of ${totalVariants} variants selected`;
                        const html = renderProductRow(product, subtitle, false, selection.variants, currentModalTargetInput, currentModalTypeInput);
                        currentModalTargetArea.append(html);
                    }
                });

                $('#itemBrowseModal').modal('hide');
            });

            function renderSelectionRow(type, id, name, icon) {
                return `
                    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light rounded" id="selected-${type}-${id}">
                        <div class="d-flex align-items-center">
                            <i class="ph ph-${icon} me-2"></i>
                            <span class="f-s-13">${name}</span>
                            <input type="hidden" name="${currentModalTargetInput}" value="${id}">
                            ${type !== 'customer' ? `<input type="hidden" name="${currentModalTypeInput}" value="${type}">` : ''}
                        </div>
                        <button type="button" class="btn btn-link link-danger p-0 ms-2" onclick="$(this).closest('.bg-light').remove()">
                            <i class="ph ph-x"></i>
                        </button>
                    </div>
                `;
            }

            function renderProductRow(product, subtitle, isFull, variantIds = [], inputName = 'targets[]', typeInputName = 'target_types[]') {
                const images = product.images || [];
                const img = images.length > 0 ? `/uploads/${images[0].file}` : '/admins/svg/_sprite.svg#shop';
                
                let hiddenInputs = '';
                if (isFull) {
                    hiddenInputs = `<input type="hidden" name="${inputName}" value="${product.id}">
                                    <input type="hidden" name="${typeInputName}" value="product">`;
                } else {
                    variantIds.forEach(vid => {
                        hiddenInputs += `<input type="hidden" name="${inputName}" value="${vid}">
                                         <input type="hidden" name="${typeInputName}" value="variant">`;
                    });
                }

                return `
                    <div class="d-flex align-items-center justify-content-between p-3 mb-2 border rounded bg-white shadow-sm" id="selected-product-group-${product.id}">
                        <div class="d-flex align-items-center">
                            <img src="${img}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #eee;">
                            <div>
                                <div class="f-s-14 f-w-600 text-dark">${product.title}</div>
                                <div class="f-s-13 text-muted">${subtitle}</div>
                            </div>
                            <div class="d-none">${hiddenInputs}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-link p-0 me-3 f-s-13 text-decoration-none" onclick="openEditModal(${product.id})">Edit</button>
                            <button type="button" class="btn btn-link link-secondary p-0" onclick="$(this).closest('.border').remove()">
                                <i class="ph ph-x"></i>
                            </button>
                        </div>
                    </div>
                `;
            }

            // Helper to re-open modal with selection (simple implementation)
            window.openEditModal = function(pid) {
                // Determine if we need to open products?
                // For now, just trigger browse button. Ideally we pre-select.
                // Pre-selection logic is handled by 'isSelected' which checks DOM.
                // Since we render hidden inputs in the DOM, our 'isSelected' function checking #selected-variant-ID might fail 
                // because we don't have IDs like #selected-variant-123 anymore.
                // We need to update isSelected logic or rely on the hidden inputs.
                $('#browseBtn').trigger('click');
            };
        });

        $(document).on('click', '#customerSearchTrigger', function() {
            $('#browseCustomersBtn').trigger('click');
        });

        $('#modalSearch').on('input', function() {
            renderModalItems(currentModalType, $(this).val());
        });

        $(document).on('click', '.modal-item-row, .collection-item-row, .customer-item-row', function(e) {
            if (e.target.type !== 'checkbox') {
                const cb = $(this).find('input[type="checkbox"]');
                cb.prop('checked', !cb.prop('checked')).trigger('change');
            }
        });

        $(document).on('change', '.variant-cb', function() {
            updateParentCheckbox($(this).data('pid'));
        });

        $(document).on('change', '.product-cb', function() {
            $(`.variant-cb[data-pid="${$(this).data('id')}"]`).prop('checked', $(this).prop('checked'));
        });
    }
</script>
