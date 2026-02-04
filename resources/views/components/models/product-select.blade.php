@props([
    'id' => 'product-select-modal', 
    'apiUrl' => route('admin.orders.search-products'),
    'products' => null, // Optional preloaded products
    'showVariants' => true // Whether to show/allow variant selection
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header px-4 py-3 border-bottom-0">
                <h5 class="modal-title fw-bold" id="{{ $id }}Label">Select products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Search & Actions -->
                <div class="px-4 pb-3">
                    <div class="d-flex gap-2 mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3"><i class="ph ph-magnifying-glass"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0 shadow-none" id="{{ $id }}-search" placeholder="Search products">
                        </div>
                        <select class="form-select flex-shrink-0" style="width: auto; max-width: 160px;" id="{{ $id }}-filter">
                            <option value="all">Search by All</option>
                        </select>
                    </div>
                </div>

                <!-- Product List -->
                <div class="product-list-container border-top position-relative" style="min-height: 300px; max-height: 60vh; background-color: #fff;">
                    
                    <!-- Loading State -->
                    <div id="{{ $id }}-loading" class="d-none position-absolute w-100 h-100 top-0 start-0 d-flex justify-content-center align-items-center bg-white bg-opacity-90" style="z-index: 10;">
                        <div class="d-flex flex-column align-items-center">
                            <div class="spinner-border text-primary mb-2" role="status" style="width: 2rem; height: 2rem;"></div>
                            <span class="text-muted small fw-medium">Loading products...</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="table-layout: fixed;">
                            <thead class="bg-light sticky-top" style="z-index: 5;">
                                <tr>
                                    <th style="width: 50px;" class="ps-4 py-3">
                                        <div class="form-check">
                                            <input class="form-check-input shadow-none" type="checkbox" id="{{ $id }}-select-all" disabled>
                                        </div>
                                    </th>
                                    <th class="text-muted small text-uppercase fw-bold py-3">Product</th>
                                    <th class="text-end text-muted small text-uppercase fw-bold py-3" style="width: 100px;">Available</th>
                                    <th class="text-end text-muted small text-uppercase fw-bold py-3 pe-4" style="width: 120px;">Price</th>
                                </tr>
                            </thead>
                            <tbody id="{{ $id }}-results">
                                <!-- Results injected via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between px-4 py-3 border-top">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
                        <span id="{{ $id }}-selected-count">0</span> items selected
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light px-4 fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4 fw-bold" id="{{ $id }}-add-btn" disabled>Add</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts:after')
<script>
    (function() {
        const modalId = '{{ $id }}';
        const apiUrl = '{{ $apiUrl }}';
        const initialProducts = @json($products) || [];
        const showVariants = @json($showVariants);
        
        // State
        let state = {
            allProducts: [], // Source of truth for client-side filtering
            displayedProducts: [], // Currently rendered
            selected: new Map(), // key: compound_id, value: itemData
            searchTimer: null,
            hasAttemptedFetch: false
        };

        // Elements
        const els = {
            modal: document.getElementById(modalId),
            searchInput: document.getElementById(`${modalId}-search`),
            resultsBody: document.getElementById(`${modalId}-results`),
            loading: document.getElementById(`${modalId}-loading`),
            selectedCount: document.getElementById(`${modalId}-selected-count`),
            addBtn: document.getElementById(`${modalId}-add-btn`),
            selectAll: document.getElementById(`${modalId}-select-all`)
        };

        function init() {
            if (initialProducts.length > 0) {
                state.allProducts = initialProducts;
                state.displayedProducts = initialProducts;
                render();
            } else {
                 renderPlaceholder('Type to search or wait for products...');
            }

            setupEventListeners();
        }

        // --- Data Fetching & Filtering ---

        function fetchProducts(query = '') {
            if (state.allProducts.length > 0) {
                filterLocalProducts(query);
                return;
            }

            els.loading.classList.remove('d-none');
            
            $.ajax({
                url: apiUrl,
                data: { q: query },
                success: function(data) {
                    const products = data || [];
                    if (!query) {
                        state.allProducts = products;
                    }
                    state.displayedProducts = products;
                    render();
                    
                    // After fetch, if we have pending sync, re-render
                    // Actually, if we just fetched, render is called.
                    // But we might need to apply selection state if it came before products.
                    // We handle this by checking selection state again or just rely on IDs matching.
                    // Since 'selected' Set only stores IDs and basic data, and render() uses it to check checkboxes,
                    // we just need to ensure 'selected' is populated.
                    // If selection came BEFORE fetch, render() will use it naturally.
                },
                error: function(err) {
                    console.error('Fetch error:', err);
                    renderPlaceholder('Error loading products', 'text-danger');
                },
                complete: function() {
                    els.loading.classList.add('d-none');
                }
            });
        }

        function filterLocalProducts(query) {
            if (!query) {
                state.displayedProducts = state.allProducts;
            } else {
                const lowerQ = query.toLowerCase();
                state.displayedProducts = state.allProducts.filter(p => {
                    if ((p.title || p.name || '').toLowerCase().includes(lowerQ)) return true;
                    if (p.variants && p.variants.length > 0) {
                         return p.variants.some(v => 
                             (v.sku || '').toLowerCase().includes(lowerQ) ||
                             (v.attributes && v.attributes.some(a => (a.pivot?.value || '').toLowerCase().includes(lowerQ)))
                         );
                    }
                    return false;
                });
            }
            render();
        }

        // --- Rendering ---

        function renderPlaceholder(msg, textClass = 'text-muted') {
            els.resultsBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-5 ${textClass}">
                        ${msg}
                    </td>
                </tr>
            `;
        }
        
        function getVariantName(v) {
            if (v.attributes && v.attributes.length > 0) {
                return v.attributes.map(a => a.pivot ? a.pivot.value : '').filter(Boolean).join(' / ');
            }
            return [v.option1, v.option2, v.option3].filter(Boolean).join(' / ') || 'Default Variant';
        }

        function render() {
            if (!state.displayedProducts || state.displayedProducts.length === 0) {
                renderPlaceholder('No products found');
                return;
            }

            let html = '';
            
            state.displayedProducts.forEach(p => {
                const hasVariants = showVariants && p.variants && p.variants.length > 0;
                const imgSrc = p.image_url || (p.images && p.images.length > 0 ? p.images[0].file_url : '') || 'https://placehold.co/50'; 
                const pId = p.id;
                const productName = p.title || p.name;

                if (hasVariants) {
                    const variantIds = p.variants.map(v => `v_${v.id}`);
                    const selectedCount = variantIds.filter(id => state.selected.has(id)).length;
                    
                    const isAll = selectedCount === variantIds.length;
                    const isSome = selectedCount > 0 && !isAll;

                    const parentChecked = isAll ? 'checked' : '';
                    const parentIndeterminate = isSome ? 'true' : 'false';

                    html += `
                        <tr class="bg-light border-bottom border-light">
                            <td class="ps-4">
                                <div class="form-check">
                                    <input class="form-check-input product-checkbox shadow-none" type="checkbox" 
                                        data-id="${pId}" 
                                        data-variants='${JSON.stringify(variantIds)}'
                                        id="cb_p_${pId}"
                                        ${parentChecked}
                                        data-indeterminate="${parentIndeterminate}"
                                    >
                                </div>
                            </td>
                            <td colspan="3">
                                <div class="d-flex align-items-center">
                                    <img src="${imgSrc}" class="rounded me-3 border" style="width: 36px; height: 36px; object-fit: cover;">
                                    <span class="fw-bold text-dark">${productName}</span>
                                </div>
                            </td>
                        </tr>
                    `;

                    p.variants.forEach((v, idx) => {
                        const compoundId = `v_${v.id}`;
                        const isSelected = state.selected.has(compoundId);
                        const isLast = idx === p.variants.length - 1;
                        const variantName = getVariantName(v);

                        html += `
                            <tr class="${isLast ? 'border-bottom' : ''}">
                                <td class="ps-5">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input variant-checkbox shadow-none" type="checkbox" 
                                            value="${v.id}" 
                                            data-parent-id="${pId}"
                                            data-type="variant"
                                            data-product-id="${pId}"
                                            data-name="${productName} - ${variantName}" 
                                            data-price="${v.price}"
                                            data-img="${imgSrc}"
                                            id="cb_${compoundId}"
                                            ${isSelected ? 'checked' : ''}
                                        >
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted small ps-2">
                                        ${variantName}
                                    </div>
                                </td>
                                <td class="text-end fw-medium text-dark">${v.quantity || 0}</td>
                                <td class="text-end pe-4 fw-medium text-dark">${formatMoney(v.price)}</td>
                            </tr>
                        `;
                    });
                } else {
                    const compoundId = `p_${p.id}`;
                    const isSelected = state.selected.has(compoundId);
                    
                    let qty = p.quantity || 0;
                    if (p.variants && p.variants.length > 0 && !showVariants) {
                         qty = p.variants.reduce((sum, v) => sum + (v.quantity || 0), 0);
                    }

                    html += `
                        <tr>
                            <td class="ps-4">
                                <div class="form-check">
                                    <input class="form-check-input variant-checkbox shadow-none" type="checkbox" 
                                        value="${p.id}" 
                                        data-type="product"
                                        data-product-id="${p.id}"
                                        data-name="${productName}" 
                                        data-price="${p.price}"
                                        data-img="${imgSrc}"
                                        id="cb_${compoundId}"
                                        ${isSelected ? 'checked' : ''}
                                    >
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${imgSrc}" class="rounded me-3 border" style="width: 36px; height: 36px; object-fit: cover;">
                                    <span class="fw-medium text-dark">${productName}</span>
                                </div>
                            </td>
                            <td class="text-end fw-medium text-dark">${qty}</td>
                            <td class="text-end pe-4 fw-medium text-dark">${formatMoney(p.price)}</td>
                        </tr>
                    `;
                }
            });

            els.resultsBody.innerHTML = html;
            updateIndeterminateStates();
        }

        function formatMoney(amount) {
            const val = parseFloat(amount) / 100; 
            return '₹' + val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function updateIndeterminateStates() {
            const productCBs = els.resultsBody.querySelectorAll('.product-checkbox');
            productCBs.forEach(cb => {
                cb.indeterminate = cb.dataset.indeterminate === 'true';
            });
        }

        // --- Interaction Logic ---

        function setupEventListeners() {
            els.searchInput.addEventListener('input', function(e) {
                clearTimeout(state.searchTimer);
                const val = e.target.value.trim();
                const debounce = state.allProducts.length > 0 ? 100 : 300;
                
                state.searchTimer = setTimeout(() => {
                    fetchProducts(val);
                }, debounce);
            });

            els.modal.addEventListener('shown.bs.modal', function () {
                els.searchInput.focus();
                
                // Request current selection from parent
                window.dispatchEvent(new CustomEvent('request-selection-state'));

                if (state.allProducts.length === 0 && !state.hasAttemptedFetch) {
                    fetchProducts('');
                    state.hasAttemptedFetch = true;
                }
            });

            window.addEventListener('provide-selection-state', function(e) {
                const selectedIds = e.detail.selectedIds || [];
                
                // We keep existing selected items ONLY if they are in the list provided.
                // Actually, we should replace the current selection with the provided one exactly.
                
                state.selected.clear();
                
                // We need to re-populate state.selected with full data objects if possible.
                // But initially we might only have IDs.
                // If we don't have the product data loaded yet, we can only store the ID.
                // BUT render() works based on IDs.
                // When we eventually submit (click ADD), we need the Full Data.
                // SO: When data is loaded (via fetch), we should probably "enrich" the selected items?
                // OR: Just store the IDs with placeholder data, and rely on the fact that if we click 'Add',
                // we only care about adding *new* items?
                // Wait, if we uncheck an item, we are "removing" it from our selection.
                // If we click Add, we emit the whole list.
                // The parent then *syncs* this list? A "smart" parent would Diff.
                // But my parent logic in create.blade.php just ADDS new ones.
                // If I uncheck a pre-existing item in the modal and click Add, nothing happens to the parent row (it's not removed).
                // Because Add implies "Add these to the order".
                // If I want "Manage" behavior, the parent logic needs to be "Replace all items with this list", which is risky.
                
                // For "Add" behavior validation:
                // 1. Modal opens, sees Item A is checked.
                // 2. User checks Item B.
                // 3. User clicks Add.
                // 4. Emitted: [A, B].
                // 5. Parent loop: A exists? Yes -> skip. B exists? No -> Add.
                // 6. Result: A, B. Correct.
                
                // What if User UNCHECKS Item A?
                // 1. Modal opens, Item A checked.
                // 2. User unchecks A. Checks B.
                // 3. Emitted: [B].
                // 4. Parent loop: B exists? No -> Add.
                // 5. Result: A, B. (A is NOT removed).
                // This is standard for "Add" modals. If user wants to remove A, they remove it from the table.
                // So my logic holds.
                
                // Re-populating state:
                // We need to store minimal data to keys to make checkboxes work.
                selectedIds.forEach(id => {
                    // Try to find data in loaded products
                    let itemData = { id: id.substring(2), type: id.startsWith('v_') ? 'variant' : 'product', productId: null, name: '', price: 0, img: '' };
                    
                    // We can try to look it up if products are loaded
                    const compoundId = id;
                    
                    // Populate if found in allProducts
                    // If not found, we still mark it as selected so if it eventually appears it will be checked.
                    // But if we click Add, we might emit empty data? 
                    // No, because we only click Add on things we can SEE (which means they are loaded).
                    // If an item is selected but not visible/loaded, we probably shouldn't emit it?
                    // Or we emit it as is.
                    // But 'Add' usually implies "Add selected items FROM THE LIST".
                    // If I have a hidden selected item (pre-selected but not matching current filter), should I emit it?
                    // If I emit it, parent checks existence. It exists. So valid.
                    // If I don't emit it, no harm.
                    
                    state.selected.set(compoundId, itemData);
                });
                
                // Now try to enrich data from allProducts if available
                enrichSelectionData();
                
                updateUIState();
                render();
            });

            function enrichSelectionData() {
                state.selected.forEach((val, key) => {
                     // logic to find product/variant in state.allProducts and update val
                     const isVariant = key.startsWith('v_');
                     const id = key.substring(2);
                     
                     // Search
                     // optimized search if needed, but linear fine for now
                     let found = null;
                     let foundVariant = null;
                     
                     if (isVariant) {
                         // Find product that has this variant
                         state.allProducts.some(p => {
                             if (p.variants) {
                                  const v = p.variants.find(v => v.id == id);
                                  if (v) {
                                      found = p;
                                      foundVariant = v;
                                      return true;
                                  }
                             }
                             return false;
                         });
                     } else {
                         found = state.allProducts.find(p => p.id == id);
                     }
                     
                     if (found) {
                         const imgSrc = found.image_url || (found.images && found.images.length > 0 ? found.images[0].file_url : '') || 'https://placehold.co/50';
                         if (isVariant && foundVariant) {
                            const vName = getVariantName(foundVariant);
                            state.selected.set(key, {
                                id: foundVariant.id,
                                type: 'variant',
                                productId: found.id,
                                name: `${found.title || found.name} - ${vName}`,
                                price: foundVariant.price / 100,
                                img: imgSrc
                            });
                         } else if (!isVariant) {
                            state.selected.set(key, {
                                id: found.id,
                                type: 'product',
                                productId: found.id,
                                name: found.title || found.name,
                                price: found.price / 100,
                                img: imgSrc
                            });
                         }
                     }
                });
            }

            els.resultsBody.addEventListener('change', function(e) {
                const target = e.target;
                if (target.classList.contains('variant-checkbox')) {
                    handleVariantCheck(target);
                } else if (target.classList.contains('product-checkbox')) {
                    handleProductCheck(target);
                }
            });

            els.addBtn.addEventListener('click', function() {
                // Return full enriched items
                 const items = Array.from(state.selected.values());
                // Filter out items that are incomplete? 
                // e.g. if we have an ID but data wasn't found (shouldn't happen if we only select visible)
                // But pre-selected items might be there.
                // "Add" should probably only add what I explicitly confirmed?
                // Actually, sending everything back is fine, parent filters duplicates.
                
                const event = new CustomEvent('products-selected', { detail: { items: items } });
                window.dispatchEvent(event);
                
                const modalInstance = bootstrap.Modal.getInstance(els.modal);
                if (modalInstance) modalInstance.hide();
                
                // state.selected.clear(); // Don't clear here? Or do? 
                // If I clear, and re-open, we fetch fresh state. So clearing is safe and cleaner.
                state.selected.clear(); 
                updateUIState();
                render(); 
            });
        }

        function handleVariantCheck(cb) {
            const compoundId = cb.dataset.type === 'variant' ? `v_${cb.value}` : `p_${cb.value}`;
            
            if (cb.checked) {
                state.selected.set(compoundId, {
                    id: cb.value,
                    type: cb.dataset.type,
                    productId: cb.dataset.productId,
                    name: cb.dataset.name,
                    price: parseFloat(cb.dataset.price) / 100,
                    img: cb.dataset.img
                });
            } else {
                state.selected.delete(compoundId);
            }

            if (cb.dataset.parentId) {
                updateParentStateOnUI(cb.dataset.parentId);
            }
            updateUIState();
        }

        function handleProductCheck(cb) {
            const isChecked = cb.checked;
            const parentId = cb.dataset.id;
            
            const productData = state.displayedProducts.find(p => p.id == parentId) || state.allProducts.find(p => p.id == parentId);
            
            if (productData && productData.variants) {
                productData.variants.forEach(v => {
                    const compoundId = `v_${v.id}`; 
                    const vName = getVariantName(v);
                    
                    if (isChecked) {
                        state.selected.set(compoundId, {
                            id: v.id,
                            type: 'variant',
                            productId: productData.id,
                            name: `${productData.title || productData.name} - ${vName}`,
                            price: v.price / 100,
                            img: productData.image_url || (productData.images && productData.images.length > 0 ? productData.images[0].file_url : '')
                        });
                    } else {
                        state.selected.delete(compoundId);
                    }
                });
                
                // Update UI variants 
                const variantCBs = els.resultsBody.querySelectorAll(`.variant-checkbox[data-parent-id="${parentId}"]`);
                variantCBs.forEach(vcb => vcb.checked = isChecked);
            }
            
            updateUIState();
        }

        function updateParentStateOnUI(parentId) {
            const parentCB = document.getElementById(`cb_p_${parentId}`);
            if (!parentCB) return;

             // Find variants from our data source
            const productData = state.displayedProducts.find(p => p.id == parentId) || state.allProducts.find(p => p.id == parentId);
            if (!productData) return;

            const totalVariants = productData.variants.length;
            let selectedCount = 0;
            productData.variants.forEach(v => {
                if (state.selected.has(`v_${v.id}`)) selectedCount++;
            });

            if (selectedCount === 0) {
                parentCB.checked = false;
                parentCB.indeterminate = false;
            } else if (selectedCount === totalVariants) {
                parentCB.checked = true;
                parentCB.indeterminate = false;
            } else {
                parentCB.checked = false;
                parentCB.indeterminate = true;
            }
        }

        function updateUIState() {
            const count = state.selected.size;
            els.selectedCount.textContent = count;
            els.addBtn.disabled = count === 0;
            els.addBtn.textContent = count > 0 ? `Add (${count})` : 'Add';
        }

        init();

    })();
</script>
@endpush
