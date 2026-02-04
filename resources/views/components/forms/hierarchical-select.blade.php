@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'options' => [],
    'varient' => 'default',
    'error' => null,
    'required' => false,
    'placeholder' => 'Select an option',
    'value' => null,
    'searchPlaceholder' => 'Search...',
    'noResultsText' => 'No results found',
    'childrenKey' => 'children',
    'labelKey' => 'name',
    'valueKey' => 'id',
    'clearable' => true,
    'apiUrl' => null, // Unified API URL for children, descendants, and search
    'multiple' => false,
])


@php
    $groupClass = $varient === 'default' ? 'form-group' : 'form-floating';
    $selectClass = $varient === 'default' ? 'form-select' : 'form-select floating';
    $selectClass .= $error ? ' is-invalid' : '';

    $componentId = $id ?? 'hierarchical-select-' . uniqid();
    $selectedValue = $value ?? (old($name) ?? $attributes->get('value'));
    // Ensure selectedValue is array if multiple
    if ($multiple) {
        if (is_string($selectedValue)) {
            $selectedValue = json_decode($selectedValue, true) ?? explode(',', $selectedValue);
        }
        $selectedValue = is_array($selectedValue) ? $selectedValue : [];
    }

    // Find selected item label
    $selectedLabel = $placeholder;
    if ($multiple) {
        $count = count($selectedValue);
        if ($count > 0) {
            $selectedLabel = $count . ' item' . ($count !== 1 ? 's' : '') . ' selected';
        }
    } else {
        if ($selectedValue && !empty($options)) {
            $findLabel = function ($items, $searchValue) use (&$findLabel, $valueKey, $labelKey, $childrenKey) {
                foreach ($items as $item) {
                    if (isset($item[$valueKey]) && $item[$valueKey] == $searchValue) {
                        return $item[$labelKey] ?? '';
                    }
                    if (isset($item[$childrenKey]) && !empty($item[$childrenKey])) {
                        $found = $findLabel($item[$childrenKey], $searchValue);
                        if ($found) {
                            return $found;
                        }
                    }
                }
                return null;
            };
            $foundLabel = $findLabel($options, $selectedValue);
            if ($foundLabel) {
                $selectedLabel = $foundLabel;
            }
        }
    }
@endphp


<div class="{{ $groupClass }} hierarchical-select-wrapper" data-component-id="{{ $componentId }}">
    @if ($varient === 'default' && $label)
        <label for="{{ $componentId }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="hierarchical-select-container" id="{{ $componentId }}" data-component-id="{{ $componentId }}"
        data-children-key="{{ $childrenKey }}" data-label-key="{{ $labelKey }}" data-value-key="{{ $valueKey }}"
        data-api-url="{{ $apiUrl }}" data-multiple="{{ $multiple ? 'true' : 'false' }}"
        data-clearable="{{ $clearable ? 'true' : 'false' }}" data-name="{{ $name }}">

        <!-- Hidden inputs container -->
        <div class="hidden-inputs">
            @if ($multiple)
                @foreach ($selectedValue as $val)
                    <input type="hidden" name="{{ $name }}[]" value="{{ $val }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $name }}" id="{{ $componentId }}-input"
                    value="{{ $selectedValue }}" @required($required) />
            @endif
        </div>

        <!-- Display button -->
        <div type="button" class="hierarchical-select-button {{ $selectClass }}" data-toggle="dropdown"
            aria-expanded="false">
            <span
                class="selected-text {{ ($multiple && count($selectedValue) > 0) || (!$multiple && $selectedValue) ? '' : 'placeholder' }}">{{ $selectedLabel }}</span>
            <div class="button-icons">
                @if ($clearable)
                    <button type="button" class="btn-clear"
                        style="{{ $selectedValue ? 'display: flex;' : 'display: none;' }}" title="Clear selection">
                        <i class="ph-duotone ph-x"></i>
                    </button>
                @endif
                <i class="ph-duotone ph-caret-down"></i>
            </div>
        </div>

        <!-- Dropdown menu -->
        <div class="hierarchical-dropdown-menu">
            <!-- Search bar -->
            <div class="hierarchical-search-wrapper">
                <input type="text" class="hierarchical-search-input form-control form-control-sm"
                    placeholder="{{ $searchPlaceholder }}" autocomplete="off" />
                <i class="ph-duotone ph-magnifying-glass search-icon"></i>
            </div>

            <!-- Breadcrumb navigation -->
            <div class="hierarchical-breadcrumb" style="display: none;">
                <button type="button" class="btn-back">
                    <i class="ph-duotone ph-arrow-left"></i>
                </button>
                <span class="breadcrumb-text"></span>
            </div>

            <!-- Options list -->
            <div class="hierarchical-options-container">
                <div class="hierarchical-options-list" data-level="0">
                    @forelse($options as $option)
                        @php
                            $hasChildren = isset($option[$childrenKey]) && !empty($option[$childrenKey]);
                            $itemValue = $option[$valueKey] ?? '';
                            $itemLabel = $option[$labelKey] ?? '';
                            $isSelected = $multiple
                                ? in_array($itemValue, $selectedValue)
                                : $itemValue == $selectedValue;
                        @endphp
                        <div class="hierarchical-option {{ $hasChildren ? 'has-children' : '' }} {{ $isSelected ? 'selected' : '' }}"
                            data-value="{{ $itemValue }}" data-label="{{ $itemLabel }}"
                            data-has-children="{{ $hasChildren ? 'true' : 'false' }}" data-level="0">
                            @if ($hasChildren)
                                <div class="option-label" data-action="select">
                                    @if ($multiple)
                                        <i
                                            class="ph-duotone {{ $isSelected ? 'ph-check-square' : 'ph-square' }} option-icon check-icon"></i>
                                    @else
                                        <i class="ph-duotone ph-folder option-icon"></i>
                                    @endif
                                    <span>{{ $itemLabel }}</span>
                                </div>
                                <button type="button" class="btn-navigate" data-action="navigate"
                                    title="View subcategories">
                                    <i class="ph-duotone ph-caret-right has-children-icon"></i>
                                </button>
                            @else
                                <div class="option-label" data-action="select">
                                    @if ($multiple)
                                        <i
                                            class="ph-duotone {{ $isSelected ? 'ph-check-square' : 'ph-square' }} option-icon check-icon"></i>
                                    @else
                                        <i class="ph-duotone ph-file option-icon"></i>
                                    @endif
                                    <span>{{ $itemLabel }}</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="no-results">
                            <i class="ph-duotone ph-magnifying-glass"></i> {{ $noResultsText }}
                        </div>
                    @endforelse
                </div>
                <!-- Dynamic children container -->
                <div class="hierarchical-children-list" style="display: none;"></div>
                <!-- Search results container -->
                <div class="hierarchical-search-results" style="display: none;"></div>
                <!-- Loading state -->
                <div class="loading-state" style="display: none;">
                    <i class="ph-duotone ph-spinner"></i> Loading...
                </div>
            </div>
        </div>
    </div>

    @if ($varient === 'floating' && $label)
        <label for="{{ $componentId }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @if ($error)
        <div class="invalid-feedback d-block">
            {{ $error }}
        </div>
    @endif
</div>

@pushOnce('styles:after')
    <style>
        .hierarchical-select-wrapper {
            position: relative;
        }

        .hierarchical-select-container {
            position: relative;
            width: 100%;
        }

        .hierarchical-select-button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            text-align: left;
            background: white;
            border: 1px solid #dee2e6;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .hierarchical-select-button:hover {
            border-color: #adb5bd;
        }

        .hierarchical-select-button:focus {
            border-color: #667eea;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .hierarchical-select-button.is-invalid {
            border-color: #dc3545;
        }

        .hierarchical-select-button .selected-text {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #495057;
            background-color: transparent;
        }

        .hierarchical-select-button .selected-text.placeholder {
            color: #6c757d;
        }

        .hierarchical-select-button .button-icons {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-left: 0.5rem;
        }

        .hierarchical-select-button .btn-clear {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 1.5rem;
            height: 1.5rem;
            padding: 0;
            border: none;
            background: transparent;
            border-radius: 0.25rem;
            cursor: pointer;
            color: #6c757d;
            transition: all 0.15s;
        }

        .hierarchical-select-button .btn-clear:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .hierarchical-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            display: none;
            margin-top: 0.25rem;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            max-height: 400px;
            overflow: hidden;
        }

        .hierarchical-dropdown-menu.show {
            display: flex;
            flex-direction: column;
        }

        .hierarchical-search-wrapper {
            position: relative;
            padding: 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .hierarchical-search-input {
            padding-left: 2rem !important;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
        }

        .hierarchical-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .hierarchical-breadcrumb .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border: none;
            background: white;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background-color 0.15s;
        }

        .hierarchical-breadcrumb .btn-back:hover {
            background: #e9ecef;
        }

        .hierarchical-breadcrumb .breadcrumb-text {
            font-size: 0.875rem;
            color: #495057;
            font-weight: 500;
        }

        .hierarchical-options-container {
            flex: 1;
            overflow-y: auto;
            max-height: 320px;
        }

        .hierarchical-options-list {
            padding: 0.25rem;
        }

        .hierarchical-search-results {
            padding: 0.25rem;
        }

        .hierarchical-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 0.25rem;
            transition: background-color 0.15s;
            user-select: none;
            overflow: hidden;
        }

        .hierarchical-option:hover {
            background: #f8f9fa;
        }

        .hierarchical-option.selected {
            background: #e7f3ff;
            color: #0066cc;
        }

        .hierarchical-option .option-label {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            min-width: 0;
        }

        .hierarchical-option .option-label span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .hierarchical-option .option-icon {
            width: 1.25rem;
            height: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            flex-shrink: 0;
        }

        .hierarchical-option .btn-navigate {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.75rem;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: background-color 0.15s;
            flex-shrink: 0;
            border-left: 1px solid transparent;
        }

        .hierarchical-option:hover .btn-navigate {
            background: rgba(0, 0, 0, 0.05);
            border-left-color: #dee2e6;
        }

        .hierarchical-option .btn-navigate:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        .hierarchical-option .has-children-icon {
            color: #6c757d;
            transition: transform 0.2s;
        }

        .hierarchical-option.has-children {
            font-weight: 500;
        }

        .hierarchical-option.has-children .option-label:hover {
            background: rgba(0, 0, 0, 0.02);
        }

        .hierarchical-option .children-container {
            display: none !important;
        }

        .no-results {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 2rem;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .loading-state {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 2rem;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .loading-state i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Floating variant adjustments */
        .form-floating .hierarchical-select-button {
            height: calc(3.5rem + 2px);
            padding: 1rem 0.75rem;
        }

        .form-floating .hierarchical-select-button .selected-text {
            padding-top: 0.625rem;
        }

        .form-floating.is-floating>label {
            opacity: .65;
            transform: scale(.85) translateY(-.5rem) translateX(.15rem);
        }

        .form-floating:not(.is-floating) .selected-text {
            opacity: 0;
        }

        .form-floating .selected-text {
            transition: opacity 0.1s ease-in-out;
        }
    </style>
@endPushOnce

@pushOnce('scripts:after')
    <script>
        (function() {
            class HierarchicalSelect {
                constructor(container) {
                    this.container = container;
                    this.componentId = container.dataset.componentId;
                    this.childrenKey = container.dataset.childrenKey || 'children';
                    this.labelKey = container.dataset.labelKey || 'name';
                    this.valueKey = container.dataset.valueKey || 'id';
                    this.placeholder = container.dataset.placeholder || 'Select an option';
                    this.clearable = container.dataset.clearable === 'true';
                    this.apiUrl = container.dataset.apiUrl || null;
                    this.multiple = container.dataset.multiple === 'true';
                    this.inputName = container.dataset.name;

                    this.selectedValue = ''; // For single select
                    this.selectedValues = new Set(); // For multi select

                    this.breadcrumbStack = [];
                    this.searchTimeout = null;
                    this.childrenCache = {}; // Cache fetched children
                    this.descendantsCache = {}; // Cache all descendant IDs
                    this.parentMap = {}; // Map child ID to parent ID

                    this.elements = {
                        button: container.querySelector('.hierarchical-select-button'),
                        dropdown: container.querySelector('.hierarchical-dropdown-menu'),
                        hiddenInput: container.querySelector(`#${this.componentId}-input`),
                        hiddenInputsContainer: container.querySelector('.hidden-inputs'),
                        searchInput: container.querySelector('.hierarchical-search-input'),
                        breadcrumb: container.querySelector('.hierarchical-breadcrumb'),
                        breadcrumbText: container.querySelector('.breadcrumb-text'),
                        backButton: container.querySelector('.btn-back'),
                        optionsContainer: container.querySelector('.hierarchical-options-container'),
                        optionsList: container.querySelector('.hierarchical-options-list'),
                        childrenList: container.querySelector('.hierarchical-children-list'),
                        searchResults: container.querySelector('.hierarchical-search-results'),
                        loadingState: container.querySelector('.loading-state'),
                        selectedText: container.querySelector('.selected-text'),
                        clearButton: container.querySelector('.btn-clear'),
                        wrapper: container.closest('.hierarchical-select-wrapper')
                    };

                    this.init();
                }

                init() {
                    if (this.multiple) {
                        const inputs = this.elements.hiddenInputsContainer.querySelectorAll('input');
                        inputs.forEach(input => this.selectedValues.add(input.value));
                        this.registerRootProviders();
                    } else {
                        if (this.elements.hiddenInput) {
                            this.selectedValue = this.elements.hiddenInput.value;
                        }
                    }

                    this.updateFloatingLabel();
                    this.bindEvents();

                    if (this.multiple) {
                        this.updateVisualState();
                    }
                }

                registerRootProviders() {
                    const options = this.elements.optionsList.querySelectorAll('.hierarchical-option');
                    options.forEach(opt => {
                        const val = opt.dataset.value;
                        this.parentMap[val] = null;
                    });
                }

                bindEvents() {
                    // Toggle dropdown
                    this.elements.button.addEventListener('click', (e) => {
                        if (e.target.closest('.btn-clear')) {
                            return;
                        }
                        e.stopPropagation();
                        this.toggleDropdown();
                    });

                    // Clear button
                    if (this.elements.clearButton) {
                        this.elements.clearButton.addEventListener('click', (e) => {
                            e.stopPropagation();
                            this.clearSelection();
                        });
                    }

                    // Close dropdown when clicking outside
                    document.addEventListener('click', (e) => {
                        if (!this.container.contains(e.target)) {
                            this.closeDropdown();
                        }
                    });

                    // Search input
                    this.elements.searchInput.addEventListener('input', (e) => {
                        clearTimeout(this.searchTimeout);
                        this.searchTimeout = setTimeout(() => {
                            this.handleSearch(e.target.value);
                        }, 300);
                    });

                    // Back button
                    this.elements.backButton.addEventListener('click', () => {
                        this.goBack();
                    });

                    // Prevent dropdown close on internal clicks
                    this.elements.dropdown.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });

                    // Bind option click events for root level
                    this.bindOptionEvents(this.elements.optionsList);
                }

                bindOptionEvents(container) {
                    const options = container.querySelectorAll('.hierarchical-option');

                    options.forEach(option => {
                        const selectArea = option.querySelector('[data-action="select"]');
                        const navigateBtn = option.querySelector('[data-action="navigate"]');

                        if (selectArea) {
                            selectArea.addEventListener('click', (e) => {
                                e.stopPropagation();
                                const value = option.dataset.value;
                                const label = option.dataset.label;

                                if (this.multiple) {
                                    this.toggleOption(value, label);
                                } else {
                                    this.selectOption(value, label);
                                }
                            });
                        }

                        if (navigateBtn) {
                            navigateBtn.addEventListener('click', async (e) => {
                                e.stopPropagation();
                                await this.navigateToChildren(option);
                            });
                        }
                    });
                }

                toggleDropdown() {
                    if (this.elements.dropdown.classList.contains('show')) {
                        this.closeDropdown();
                    } else {
                        this.openDropdown();
                    }
                }

                openDropdown() {
                    this.elements.dropdown.classList.add('show');
                    this.elements.searchInput.focus();
                    this.updateFloatingLabel();
                }

                closeDropdown() {
                    this.elements.dropdown.classList.remove('show');
                    this.elements.searchInput.value = '';
                    this.handleSearch('');
                    this.resetToRoot();
                    this.updateFloatingLabel();
                }

                async navigateToChildren(parentOption) {
                    const parentValue = parentOption.dataset.value;
                    const parentLabel = parentOption.dataset.label;

                    // Show loading state
                    this.showLoading();

                    try {
                        // Check cache first
                        let children;
                        if (this.childrenCache[parentValue]) {
                            children = this.childrenCache[parentValue];
                        } else if (this.apiUrl) {
                            // Fetch from server
                            children = await this.fetchData(parentValue, 'children');
                            // Cache the result
                            this.childrenCache[parentValue] = children;
                        } else {
                            children = [];
                        }

                        // Register relationships for cache
                        if (children.length > 0) {
                            children.forEach(child => {
                                const childId = child[this.valueKey];
                                this.parentMap[childId] = parentValue;
                            });
                        }

                        // Hide root list and show children
                        this.elements.optionsList.style.display = 'none';
                        this.elements.childrenList.style.display = 'block';

                        // Render children
                        this.renderChildren(children, parentValue);

                        // Push to breadcrumb stack
                        this.breadcrumbStack.push({
                            container: 'root',
                            label: parentLabel,
                            parentValue: parentValue
                        });

                        // Update breadcrumb
                        this.elements.breadcrumb.style.display = 'flex';
                        this.elements.breadcrumbText.textContent = parentLabel;

                    } catch (error) {
                        console.error('Failed to fetch children:', error);
                        if (window.toast) {
                            window.toast.error('Failed to load subcategories');
                        }
                    } finally {
                        this.hideLoading();
                    }
                }

                async fetchData(parentId, action = 'children', extras = {}) {
                    if (!this.apiUrl) return [];

                    let url = `${this.apiUrl}?action=${action}`;
                    if (parentId) url += `&parent_id=${parentId}`;

                    for (const key in extras) {
                        url += `&${key}=${encodeURIComponent(extras[key])}`;
                    }

                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    return data.data || [];
                }

                renderChildren(children, parentValue) {
                    this.elements.childrenList.innerHTML = '';

                    if (!children || children.length === 0) {
                        this.elements.childrenList.innerHTML = `
                            <div class="no-results">
                                <i class="ph-duotone ph-magnifying-glass"></i> No subcategories found
                            </div>
                        `;
                        return;
                    }

                    children.forEach(child => {
                        const hasChildren = child[this.childrenKey] && child[this.childrenKey].length > 0;
                        const itemValue = child[this.valueKey] || '';
                        const itemLabel = child[this.labelKey] || '';

                        let isSelected = false;
                        let iconClass = 'ph-file';

                        if (this.multiple) {
                            isSelected = this.selectedValues.has(String(itemValue));
                            const indeterminate = !isSelected && this.hasSelectedDescendants(itemValue);

                            if (isSelected) iconClass = 'ph-check-square';
                            else if (indeterminate) iconClass = 'ph-minus-square';
                            else iconClass = 'ph-square';
                        } else {
                            isSelected = itemValue == this.selectedValue;
                            if (hasChildren) iconClass = 'ph-folder';
                        }

                        const optionHtml = `
                            <div class="hierarchical-option ${hasChildren ? 'has-children' : ''} ${isSelected ? 'selected' : ''}" 
                                 data-value="${itemValue}" 
                                 data-label="${this.escapeHtml(itemLabel)}"
                                 data-has-children="${hasChildren ? 'true' : 'false'}"
                                 data-parent-value="${parentValue}">
                                ${hasChildren ? `
                                                                                                                                                                                                                                                                    <div class="option-label" data-action="select">
                                                                                                                                                                                                                                                                        <i class="ph-duotone ${iconClass} option-icon ${this.multiple ? 'check-icon' : ''}"></i>
                                                                                                                                                                                                                                                                        <span>${this.escapeHtml(itemLabel)}</span>
                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                    <button type="button" class="btn-navigate" data-action="navigate" title="View subcategories">
                                                                                                                                                                                                                                                                        <i class="ph-duotone ph-caret-right has-children-icon"></i>
                                                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                                                ` : `
                                                                                                                                                                                                                                                                    <div class="option-label" data-action="select">
                                                                                                                                                                                                                                                                        <i class="ph-duotone ${iconClass} option-icon ${this.multiple ? 'check-icon' : ''}"></i>
                                                                                                                                                                                                                                                                        <span>${this.escapeHtml(itemLabel)}</span>
                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                `}
                            </div>
                        `;

                        this.elements.childrenList.insertAdjacentHTML('beforeend', optionHtml);
                    });

                    // Bind events to newly created options
                    this.bindOptionEvents(this.elements.childrenList);
                }

                hasSelectedDescendants(parentId) {
                    if (!this.childrenCache[parentId]) return false;

                    const children = this.childrenCache[parentId];
                    for (const child of children) {
                        const id = String(child[this.valueKey]);
                        if (this.selectedValues.has(id)) return true;
                        if (this.hasSelectedDescendants(id)) return true;
                    }
                    return false;
                }

                goBack() {
                    if (this.breadcrumbStack.length === 0) return;

                    const previous = this.breadcrumbStack.pop();

                    if (this.breadcrumbStack.length === 0) {
                        // Back to root
                        this.resetToRoot();
                    } else {
                        // Back to previous level
                        const prevItem = this.breadcrumbStack[this.breadcrumbStack.length - 1];
                        this.elements.breadcrumbText.textContent = prevItem.label;

                        // Re-render previous level from cache
                        if (this.childrenCache[prevItem.parentValue]) {
                            this.renderChildren(this.childrenCache[prevItem.parentValue], prevItem.parentValue);
                        }
                    }
                }

                resetToRoot() {
                    this.breadcrumbStack = [];
                    this.elements.breadcrumb.style.display = 'none';
                    this.elements.optionsList.style.display = 'block';
                    this.elements.childrenList.style.display = 'none';
                    this.elements.childrenList.innerHTML = '';
                }

                selectOption(value, label) {
                    this.selectedValue = value;
                    this.elements.hiddenInput.value = value;
                    this.elements.selectedText.textContent = label;
                    this.elements.selectedText.classList.remove('placeholder');

                    this.container.querySelectorAll('.hierarchical-option').forEach(opt => {
                        if (opt.dataset.value === value) {
                            opt.classList.add('selected');
                        } else {
                            opt.classList.remove('selected');
                        }
                    });

                    this.updateClearButton();
                    const event = new Event('change', {
                        bubbles: true
                    });
                    this.elements.hiddenInput.dispatchEvent(event);
                    this.closeDropdown();
                }

                async toggleOption(value, label) {
                    const strValue = String(value);
                    const isSelected = this.selectedValues.has(strValue);

                    if (isSelected) {
                        this.selectedValues.delete(strValue);
                        await this.cascadeSelection(strValue, false);
                    } else {
                        this.selectedValues.add(strValue);
                        await this.cascadeSelection(strValue, true);
                    }

                    this.updateMultiSelectUI();
                }

                async cascadeSelection(parentId, select) {
                    // 1. First cascade locally known items (fast UI response)
                    this.cascadeLocalSelection(parentId, select);

                    // 2. If it has subcategories, fetch all descendants once to ensure full coverage
                    // We check data-has-children="true" on the option element if visible
                    const option = this.container.querySelector(`.hierarchical-option[data-value="${parentId}"]`);
                    const hasChildrenAttr = option ? option.dataset.hasChildren === 'true' : false;

                    if (this.descendantsUrl && (hasChildrenAttr || this.childrenCache[parentId])) {
                        try {
                            let descendantIds;
                            if (this.descendantsCache[parentId]) {
                                descendantIds = this.descendantsCache[parentId];
                            } else {
                                this.showLoading();
                                descendantIds = await this.fetchData(parentId, 'descendants');
                                this.descendantsCache[parentId] = descendantIds;
                                this.hideLoading();
                            }

                            descendantIds.forEach(id => {
                                const strId = String(id);
                                if (select) this.selectedValues.add(strId);
                                else this.selectedValues.delete(strId);
                            });

                        } catch (error) {
                            console.error('Failed to fetch descendants:', error);
                        } finally {
                            this.hideLoading();
                            this.toggleDropdown();
                        }
                    }
                }

                cascadeLocalSelection(parentId, select) {
                    const children = this.childrenCache[parentId];
                    if (children) {
                        children.forEach(child => {
                            const childId = String(child[this.valueKey]);
                            if (select) this.selectedValues.add(childId);
                            else this.selectedValues.delete(childId);
                            this.cascadeLocalSelection(childId, select);
                        });
                    }
                }

                updateMultiSelectUI() {
                    this.elements.hiddenInputsContainer.innerHTML = '';
                    this.selectedValues.forEach(val => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `${this.inputName}[]`;
                        input.value = val;
                        this.elements.hiddenInputsContainer.appendChild(input);
                    });

                    const count = this.selectedValues.size;
                    if (count > 0) {
                        this.elements.selectedText.textContent = `${count} item${count !== 1 ? 's' : ''} selected`;
                        this.elements.selectedText.classList.remove('placeholder');
                    } else {
                        this.elements.selectedText.textContent = this.placeholder;
                        this.elements.selectedText.classList.add('placeholder');
                    }

                    this.updateClearButton();
                    this.updateVisualState();
                    this.updateFloatingLabel();
                }

                updateVisualState() {
                    const options = [
                        ...this.elements.optionsList.querySelectorAll('.hierarchical-option'),
                        ...this.elements.childrenList.querySelectorAll('.hierarchical-option'),
                        ...this.elements.searchResults.querySelectorAll('.hierarchical-option')
                    ];

                    options.forEach(opt => {
                        const val = String(opt.dataset.value);
                        const icon = opt.querySelector('.option-icon');
                        const isSelected = this.selectedValues.has(val);

                        const indeterminate = !isSelected && this.hasSelectedDescendants(val);

                        opt.classList.remove('selected');
                        if (icon) {
                            icon.classList.remove('ph-check-square', 'ph-square', 'ph-minus-square',
                                'ph-folder', 'ph-file');
                            icon.classList.add('ph-duotone');
                        }

                        if (isSelected) {
                            opt.classList.add('selected');
                            if (icon) icon.classList.add('ph-check-square');
                        } else if (indeterminate) {
                            if (icon) icon.classList.add('ph-minus-square');
                        } else {
                            if (icon) icon.classList.add('ph-square');
                        }
                    });
                }

                clearSelection() {
                    if (this.multiple) {
                        this.selectedValues.clear();
                        this.updateMultiSelectUI();
                    } else {
                        this.selectedValue = '';
                        this.elements.hiddenInput.value = '';
                        this.elements.selectedText.textContent = this.placeholder;
                        this.elements.selectedText.classList.add('placeholder');
                        this.container.querySelectorAll('.hierarchical-option').forEach(opt => {
                            opt.classList.remove('selected');
                        });
                        this.updateClearButton();
                        const event = new Event('change', {
                            bubbles: true
                        });
                        this.elements.hiddenInput.dispatchEvent(event);
                        this.updateFloatingLabel();
                    }
                }

                updateClearButton() {
                    if (this.elements.clearButton && this.clearable) {
                        const hasSelection = this.multiple ? this.selectedValues.size > 0 : !!this.selectedValue;
                        this.elements.clearButton.style.display = hasSelection ? 'flex' : 'none';
                    }
                }

                showLoading() {
                    console.log('showLoading');
                    this.elements.loadingState.style.display = 'flex';
                    this.elements.optionsList.style.display = 'none';
                    this.elements.childrenList.style.display = 'none';
                }

                updateFloatingLabel() {
                    if (!this.elements.wrapper) return;

                    // Only apply for floating variant
                    if (!this.elements.wrapper.classList.contains('form-floating')) return;

                    let hasValue = false;
                    if (this.multiple) {
                        hasValue = this.selectedValues.size > 0;
                    } else {
                        hasValue = this.selectedValue !== '' && this.selectedValue !== null;
                    }

                    const isOpen = this.elements.dropdown.classList.contains('show');
                    const isFloating = hasValue || isOpen;

                    if (isFloating) {
                        this.elements.wrapper.classList.add('is-floating');
                    } else {
                        this.elements.wrapper.classList.remove('is-floating');
                    }
                }

                hideLoading() {
                    this.elements.loadingState.style.display = 'none';
                }

                async handleSearch(query) {
                    if (!query.trim()) {
                        // Show regular list
                        this.elements.searchResults.style.display = 'none';
                        this.elements.breadcrumb.style.display = this.breadcrumbStack.length > 0 ? 'flex' : 'none';

                        if (this.breadcrumbStack.length > 0) {
                            this.elements.childrenList.style.display = 'block';
                        } else {
                            this.elements.optionsList.style.display = 'block';
                        }
                        return;
                    }

                    // Hide regular lists and breadcrumb
                    this.elements.optionsList.style.display = 'none';
                    this.elements.childrenList.style.display = 'none';
                    this.elements.breadcrumb.style.display = 'none';
                    this.elements.searchResults.style.display = 'block';

                    // Show loading state for search
                    this.elements.searchResults.innerHTML = `
                        <div class="loading-state">
                            <i class="ph-duotone ph-spinner"></i> Searching...
                        </div>
                    `;

                    try {
                        const results = await this.fetchData(null, 'search', {
                            query: query
                        });
                        this.renderSearchResults(results);
                    } catch (error) {
                        console.error('Search failed:', error);
                        this.elements.searchResults.innerHTML = `
                            <div class="no-results">
                                <i class="ph-duotone ph-warning"></i> Search failed
                            </div>
                        `;
                    }
                }

                renderSearchResults(results) {
                    this.elements.searchResults.innerHTML = '';

                    if (!results || results.length === 0) {
                        this.elements.searchResults.innerHTML = `
                            <div class="no-results">
                                <i class="ph-duotone ph-magnifying-glass"></i> No results found
                            </div>
                        `;
                        return;
                    }

                    results.forEach(result => {
                        const itemValue = result[this.valueKey] || result.id || '';
                        const itemLabel = result[this.labelKey] || result.name || '';
                        const hasChildren = result.has_children || false;

                        let isSelected = false;
                        let iconClass = 'ph-file';

                        if (this.multiple) {
                            isSelected = this.selectedValues.has(String(itemValue));
                            const indeterminate = !isSelected && this.hasSelectedDescendants(itemValue);

                            if (isSelected) iconClass = 'ph-check-square';
                            else if (indeterminate) iconClass = 'ph-minus-square';
                            else iconClass = 'ph-square';
                        } else {
                            isSelected = itemValue == this.selectedValue;
                            if (hasChildren) iconClass = 'ph-folder';
                        }

                        const optionHtml = `
                            <div class="hierarchical-option ${isSelected ? 'selected' : ''}" 
                                 data-value="${itemValue}" 
                                 data-label="${this.escapeHtml(itemLabel)}">
                                <div class="option-label" data-action="select">
                                    <i class="ph-duotone ${iconClass} option-icon ${this.multiple ? 'check-icon' : ''}"></i>
                                    <span>${this.escapeHtml(itemLabel)}</span>
                                </div>
                            </div>
                        `;

                        const div = document.createElement('div');
                        div.innerHTML = optionHtml.trim();
                        const option = div.firstChild;

                        // Bind click event
                        const selectArea = option.querySelector('[data-action="select"]');
                        if (selectArea) {
                            selectArea.addEventListener('click', (e) => {
                                e.stopPropagation();
                                if (this.multiple) {
                                    this.toggleOption(itemValue, itemLabel);
                                } else {
                                    this.selectOption(itemValue, itemLabel);
                                }
                            });
                        }

                        this.elements.searchResults.appendChild(option);
                    });
                }

                escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }
            }

            // Initialize all hierarchical selects on the page
            document.addEventListener('DOMContentLoaded', function() {
                initializeHierarchicalSelects();
            });

            // Global initialization function
            window.initializeHierarchicalSelects = function() {
                document.querySelectorAll('.hierarchical-select-container').forEach(container => {
                    if (container.dataset.initialized) return;

                    new HierarchicalSelect(container);
                    container.dataset.initialized = 'true';
                });
            };
        })
        ();
    </script>
@endPushOnce
