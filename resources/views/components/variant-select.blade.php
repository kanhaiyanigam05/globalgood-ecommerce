@props([
    'initialOptions' => [],
    'initialVariants' => [],
    'attributesApiUrl' => route('admin.attributes.by-category')
])

<div class="card  shadow-sm mb-4" id="variant-manager-card">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-sm -primary text-primary rounded">
                <i class="ph-duotone ph-stack h4 mb-0"></i>
            </div>
            <div>
                <h5 class="mb-0">Product Variants</h5>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Manage
                    sizes, colors, and other variations</small>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="options-list-container">
            {{-- Variant option items will be injected here --}}
        </div>

        <div class="mt-3" id="add-option-wrapper">
            <div class="dropdown">
                <button type="button"
                    class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2 px-3"
                    id="add-option-btn"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="ph-duotone ph-plus-circle h6 mb-0"></i> Add option
                </button>

                <div class="dropdown-menu shadow-lg  p-3 mt-2" id="attribute-selector-dropdown"
                    style="width: 320px; z-index: 1050; border-radius: 12px;">
                    <div class="mb-3 position-relative">
                        <i
                            class="ph ph-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" class="form-control form-control-sm ps-5  "
                            placeholder="Search attributes..." id="attr-search-input"
                            style="height: 40px; border-radius: 8px;">
                    </div>

                    <div id="attribute-list-results" style="max-height: 280px; overflow-y: auto;">
                        <span class="text-muted fw-bold text-uppercase mb-2 d-block"
                            style="font-size: 0.65rem; letter-spacing: 1px;">Recommended</span>
                        <div id="recommended-attributes" class="d-grid gap-1"></div>

                        <hr class="my-3 opacity-10">
                        <button type="button"
                            class="btn btn-light btn-sm w-100 text-primary fw-bold text-start py-2 px-3 d-flex align-items-center gap-2"
                            id="create-custom-option-btn" style="border-radius: 8px;">
                            <i class="ph-duotone ph-plus-square h5 mb-0"></i>
                            <span>Create custom option</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="variants-table-container" class="mt-5 pt-4 border-top d-none">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h6 class="mb-1 fw-bold text-dark">Variants Preview</h6>
                    <p class="text-muted small mb-0">Generated combinations for your product</p>
                </div>
                <div class="badge -primary text-primary px-3 py-2 rounded-pill f-w-600">
                    <span id="total-variants-count">0</span> Variants found
                </div>
            </div>

            <div class="table-responsive rounded-3 border">
                <table class="table table-hover align-middle mb-0">
                    <thead class=" border-bottom">
                        <tr>
                            <th width="50" class="ps-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input shadow-none">
                                </div>
                            </th>
                            <th class="text-uppercase fw-bold text-muted small py-3" style="letter-spacing: 1px;">
                                Variant Info</th>
                            <th width="180" class="text-uppercase fw-bold text-muted small py-3"
                                style="letter-spacing: 1px;">Price</th>
                            <th width="140" class="text-uppercase fw-bold text-muted small py-3"
                                style="letter-spacing: 1px;">Stock</th>
                        </tr>
                    </thead>
                    <tbody id="variants-preview-body">
                        {{-- Rows generated via JS --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Template for Variant Option Item --}}
<template id="option-item-template">
    <div class="variant-option-card  bg-white rounded-3 mb-4 shadow-sm"
        style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(226, 232, 240, 0.8) !important;">
        {{-- Edit Mode --}}
        <div class="edit-mode p-3">
            <div class="row align-items-center mb-3">
                <div class="col">
                    <label class="form-label text-uppercase fw-bold text-muted mb-2 d-block"
                        style="font-size: 0.65rem; letter-spacing: 1px;">Option Name</label>
                    <div class="position-relative">
                        <input type="text"
                            class="form-control form-control-sm option-name-field   f-w-500"
                            placeholder="e.g. Color, Size, Material">
                        <div class="db-icon text-primary position-absolute top-50 end-0 translate-middle-y me-3 d-none">
                            <i class="ph-duotone ph-database h5 mb-0"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-uppercase fw-bold text-muted mb-2 d-block"
                    style="font-size: 0.65rem; letter-spacing: 1px;">Values</label>
                <div class="tag-input-wrapper">
                    <x-tag-input name="variant_option_values" placeholder="Press enter to add values..." />
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center border-top border-light">
                <button type="button"
                    class="btn btn-link text-danger text-decoration-none fw-bold small d-flex align-items-center gap-2 remove-option-btn">
                    <i class="ph ph-trash"></i> Delete
                </button>
                <button type="button" class="btn btn-dark btn-sm px-4 fw-bold done-option-btn"
                    style="border-radius: 8px; height: 36px;">
                    Done
                </button>
            </div>
        </div>

        {{-- Summary Mode --}}
        <div class="summary-mode d-none p-3  bg-opacity-50">
            <div class="d-flex align-items-center gap-3">
                <div
                    class="drag-handle text-muted bg-white shadow-sm p-2 rounded cursor-move d-flex align-items-center">
                    <i class="ph ph-dots-six-vertical h5 mb-0"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="mb-0 fw-bold text-dark summary-name"></h6>
                            <span class="badge -primary text-primary db-badge d-none px-2 py-1 rounded-pill"
                                style="font-size: 0.7rem;">
                                <i class="ph ph-database me-1"></i> Database
                            </span>
                        </div>
                        <button type="button"
                            class="btn btn-outline-secondary btn-xs rounded-pill px-3 py-1 edit-option-btn fw-bold"
                            style="font-size: 0.75rem;">
                            Edit
                        </button>
                    </div>
                    <div class="summary-values d-flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>

        {{-- Hidden Store --}}
        <div class="hidden-inputs"></div>
    </div>
</template>

@once
    @push('scripts:after')
        <script>
            class VariantManager {
                constructor(initialOptions = [], initialVariants = []) {
                    this.container = document.getElementById('variant-manager-card');
                    if (!this.container) return;

                    this.options = initialOptions;
                    this.initialVariants = initialVariants;
                    this.availableAttributes = [];
                    this.categoryId = null;
                    this.basePrice = 0;
                    this.attributesApiUrl = "{{ $attributesApiUrl }}";

                    this.elements = {
                        optionsList: document.getElementById('options-list-container'),
                        dropdown: document.getElementById('attribute-selector-dropdown'),
                        attrSearch: document.getElementById('attr-search-input'),
                        recommendedList: document.getElementById('recommended-attributes'),
                        customBtn: document.getElementById('create-custom-option-btn'),
                        tableContainer: document.getElementById('variants-table-container'),
                        tableBody: document.getElementById('variants-preview-body'),
                        totalCount: document.getElementById('total-variants-count'),
                        addWrapper: document.getElementById('add-option-wrapper')
                    };

                    this.init();
                    this.loadInitialData();
                }

                loadInitialData() {
                    if (this.options && this.options.length > 0) {
                        this.options.forEach(option => {
                            this.renderOption(option);
                        });
                        this.generateVariants();
                        this.updateVisibility();
                    }
                }

                init() {
                    const catInput = document.querySelector('input[name="category_id"]');
                    if (catInput) {
                        this.categoryId = catInput.value;
                        this.fetchAttributes();

                        const observer = new MutationObserver(() => {
                            if (this.categoryId !== catInput.value) {
                                this.categoryId = catInput.value;
                                this.fetchAttributes();
                            }
                        });
                        observer.observe(catInput, {
                            attributes: true,
                            attributeFilter: ['value']
                        });
                        catInput.addEventListener('change', () => {
                            this.categoryId = catInput.value;
                            this.fetchAttributes();
                        });
                    }

                    const priceInput = document.querySelector('input[name="price"]');
                    if (priceInput) {
                        this.basePrice = priceInput.value;
                        priceInput.addEventListener('input', () => {
                            this.basePrice = priceInput.value;
                            this.updateAllPrices();
                        });
                    }

                    this.elements.attrSearch.addEventListener('input', () => this.renderAttributeList());
                    const addOptionBtn = document.getElementById('add-option-btn');
                    if (addOptionBtn) {
                        addOptionBtn.addEventListener('click', () => {
                            setTimeout(() => this.renderAttributeList(), 50);
                        });
                    }
                    this.elements.customBtn.addEventListener('click', () => this.addOption());
                    
                    // Track manual edits on variant prices
                    this.elements.tableBody.addEventListener('input', (e) => {
                        if (e.target.classList.contains('variant-price-field')) {
                            e.target.dataset.edited = 'true';
                        }
                    });

                    this.updateVisibility();
                }

                async fetchAttributes() {
                    if (!this.categoryId) {
                        this.availableAttributes = [];
                        this.renderAttributeList();
                        return;
                    }
                    try {
                        const response = await fetch(
                            `${this.attributesApiUrl}?category_id=${this.categoryId}&scope=variant`);
                        const data = await response.json();
                        if (data.success) {
                            this.availableAttributes = data.attributes || [];
                            this.renderAttributeList();
                        }
                    } catch (error) {
                        console.error('Error fetching attributes:', error);
                    }
                }

                renderAttributeList() {
                    const query = this.elements.attrSearch.value.toLowerCase();
                    const usedAttrNames = this.options.map(o => o.name.toLowerCase());

                    const filtered = this.availableAttributes.filter(attr =>
                        attr.name.toLowerCase().includes(query) && !usedAttrNames.includes(attr.name.toLowerCase())
                    );

                    this.elements.recommendedList.innerHTML = filtered.map(attr => {
                        let iconHtml = '<i class="ph ph-list-bullets text-muted"></i>';
                        const displayName = attr.value || attr.name;
                        
                        if (attr.type === 'color') {
                            const colorCode = attr.code || (attr.values && attr.values.length > 0 ? (attr.values[0].code || attr.values[0].value) : null);
                            if (colorCode) {
                                iconHtml = `<div class="rounded-circle border border-light shadow-sm" style="width: 18px; height: 18px; background-color: ${colorCode};"></div>`;
                            }
                        }

                        return `
                            <button type="button" class="dropdown-item d-flex align-items-center gap-2 py-2" data-id="${attr.id}">
                                ${iconHtml}
                                <span class="fw-medium">${displayName}</span>
                            </button>
                        `;
                    }).join('');

                    this.elements.recommendedList.querySelectorAll('.dropdown-item').forEach(item => {
                        item.addEventListener('click', () => {
                            const attr = this.availableAttributes.find(a => a.id == item.dataset.id);
                            this.addOption(attr);
                            this.elements.dropdown.classList.remove('show');
                        });
                    });
                }

                addOption(attribute = null) {
                    const id = Date.now();
                    const option = {
                        id: id,
                        attribute_id: attribute ? attribute.id : null,
                        name: attribute ? attribute.name : '',
                        values: [],
                        isDone: false,
                        isDbAttribute: !!attribute,
                        attribute_type: attribute ? attribute.type : 'text'
                    };

                    this.options.push(option);
                    this.renderOption(option, attribute);
                    this.updateVisibility();
                }

                renderOption(option, attribute = null) {
                    const template = document.getElementById('option-item-template');
                    const clone = template.content.cloneNode(true);
                    const card = clone.querySelector('.variant-option-card');
                    card.dataset.id = option.id;

                    const nameInput = card.querySelector('.option-name-field');
                    nameInput.value = option.name;
                    if (option.isDbAttribute) {
                        card.querySelector('.db-icon').classList.remove('d-none');
                    }

                    const tagInputEl = card.querySelector('.tag-input-container');
                    if (typeof TagInput === 'undefined') {
                        console.error('TagInput is not defined. Ensure x-tag-input is properly loaded.');
                        return;
                    }

                    // Use attribute type from DB if available, else from option (for edited variants)
                    const attrType = attribute ? attribute.type : (option.type || option.attribute_type || 'text');
                    const availableOpts = attribute ? (attribute.values || []).map(v => ({
                        name: v.value || v.name,
                        value: v.value,
                        code: v.code
                    })) : (option.availableValues || []);

                    const tagInputInstance = new TagInput(tagInputEl, {
                        attributeType: attrType,
                        availableOptions: availableOpts,
                        onChanged: () => {
                            option.values = tagInputInstance.getValues();
                            this.updateHiddenInputs(card, option);
                            this.generateVariants();
                        }
                    });
                    
                    // IF WE HAVE INITIAL VALUES, ADD THEM
                    if (option.values && option.values.length > 0) {
                        option.values.forEach(v => {
                            // Find the matching object from availableOpts to get the code if it's a color
                            const match = availableOpts.find(o => o.value === v || o.name === v);
                            if (match) {
                                tagInputInstance.addTag(match.value, match.name, match.code);
                            } else {
                                tagInputInstance.addTag(v);
                            }
                        });
                    }

                    option.tagInput = tagInputInstance;
                    option.attribute_type = attrType; // Ensure it's stored on the option

                    if (option.isDone) {
                        this.toggleMode(card, option);
                    }

                    card.querySelector('.done-option-btn').addEventListener('click', () => {
                        option.name = nameInput.value;
                        if (!option.name || option.values.length === 0) return;
                        option.isDone = true;
                        this.toggleMode(card, option);
                        this.generateVariants();
                    });

                    card.querySelector('.edit-option-btn').addEventListener('click', () => {
                        option.isDone = false;
                        this.toggleMode(card, option);
                    });

                    card.querySelector('.remove-option-btn').addEventListener('click', () => {
                        this.options = this.options.filter(o => o.id !== option.id);
                        card.remove();
                        this.updateVisibility();
                        this.generateVariants();
                    });

                    this.elements.optionsList.appendChild(clone);
                }

                toggleMode(card, option) {
                    const editMode = card.querySelector('.edit-mode');
                    const summaryMode = card.querySelector('.summary-mode');

                    if (option.isDone) {
                        editMode.classList.add('d-none');
                        summaryMode.classList.remove('d-none');
                        card.querySelector('.summary-name').textContent = option.name;
                        const valuesCont = card.querySelector('.summary-values');
                        valuesCont.innerHTML = (option.values || []).map(v => {
                            let colorBox = '';
                            if (option.attribute_type === 'color') {
                                // We need to find the code if possible, or assume v is the code
                                colorBox = `<div class="color-preview rounded-1 border border-white border-opacity-25" style="width: 12px; height: 12px; background-color: ${v};"></div>`;
                            }
                            return `
                                <span class="tag-item badge shadow-sm py-2 px-3 fw-bold d-inline-flex align-items-center gap-2" style="border-radius:8px; font-size: 0.8rem;">
                                    ${colorBox}
                                    ${v}
                                </span>
                            `;
                        }).join('');
                        if (option.isDbAttribute) {
                            card.querySelector('.db-badge').classList.remove('d-none');
                        }
                    } else {
                        editMode.classList.remove('d-none');
                        summaryMode.classList.add('d-none');
                        card.classList.remove('');
                    }
                    this.updateHiddenInputs(card, option);
                }

                updateHiddenInputs(card, option) {
                    const container = card.querySelector('.hidden-inputs');
                    const index = this.options.indexOf(option);
                    let html = `<input type="hidden" name="variant_options[${index}][name]" value="${option.name}">`;
                    if (option.attribute_id) {
                        html += `<input type="hidden" name="variant_options[${index}][attribute_id]" value="${option.attribute_id}">`;
                    }
                    option.values.forEach(val => {
                        html += `<input type="hidden" name="variant_options[${index}][values][]" value="${val}">`;
                    });
                    container.innerHTML = html;
                }

                generateVariants() {
                    const validOptions = this.options.filter(o => o.name && o.values.length > 0);
                    if (validOptions.length === 0) {
                        this.elements.tableContainer.classList.add('d-none');
                        this.elements.tableBody.innerHTML = '';
                        return;
                    }

                    const combinations = this.cartesianProduct(validOptions.map(o => o.values));
                    this.elements.tableBody.innerHTML = combinations.map((combo, vIndex) => {
                        const comboArray = Array.isArray(combo) ? combo : [combo];
                        const label = comboArray.join(' / ');
                        const attrObj = comboArray.map((val, i) => ({
                            name: validOptions[i].name,
                            value: val
                        }));

                        // Match with initial variants if available
                        let price = this.basePrice;
                        let qty = 0;
                        let sku = label.replace(/ \/ /g, '-').substring(0, 8);

                        if (this.initialVariants && this.initialVariants.length > 0) {
                            const match = this.initialVariants.find(v => {
                                if (v.attributes.length !== comboArray.length) return false;
                                return v.attributes.every(va => {
                                    const optIndex = validOptions.findIndex(o => o.name === va.name);
                                    return optIndex !== -1 && comboArray[optIndex] === va.value;
                                });
                            });

                            if (match) {
                                price = match.price;
                                qty = match.quantity;
                                sku = match.sku;
                            }
                        }

                        return `
                <tr class="variant-row">
                    <td class="ps-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input shadow-none">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3 py-1">
                            <div class=" rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; border: 1px solid rgba(0,0,0,0.05);">
                                <i class="ph-duotone ph-image h4 text-muted mb-0"></i>
                            </div>
                            <div>
                                <div class="f-w-600 text-dark">${label}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">SKU: <span class="text-uppercase">${sku}</span></div>
                                <input type="hidden" name="variants[${vIndex}][label]" value="${label}">
                                <input type="hidden" name="variants[${vIndex}][attributes]" value='${JSON.stringify(attrObj)}'>
                                <input type="hidden" name="variants[${vIndex}][sku]" value="${sku}">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text   text-muted f-w-600">$</span>
                            <input type="number" step="0.01" class="form-control   f-w-500 variant-price-field" name="variants[${vIndex}][price]" value="${price}" style="border-radius: 0 8px 8px 0;">
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control   f-w-500 text-center" name="variants[${vIndex}][quantity]" value="${qty}" style="height: 38px; border-radius: 8px;">
                    </td>
                </tr>`;
                    }).join('');

                    this.elements.totalCount.textContent = combinations.length;
                    this.elements.tableContainer.classList.remove('d-none');
                }

                cartesianProduct(arrays) {
                    return arrays.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())));
                }

                updateAllPrices() {
                    this.elements.tableBody.querySelectorAll('.variant-price-field').forEach(input => {
                        if (!input.dataset.edited) {
                            input.value = this.basePrice;
                        }
                    });
                }

                updateVisibility() {
                    this.elements.addWrapper.classList.toggle('d-none', this.options.length >= 3);
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                new VariantManager(@json($initialOptions), @json($initialVariants));
            });
        </script>
    @endpush
@endonce
