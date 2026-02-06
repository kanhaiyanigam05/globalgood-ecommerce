
@extends('admin.layouts.app')

@push('styles:after')
    <style>
        /* Premium UX Enhancements */
        .menu-item-wrapper {
            position: relative;
        }

        .cursor-grab {
            cursor: grab;
        }
        .cursor-grab:active {
            cursor: grabbing;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        /* Avoid blink on hover */
        .menu-item-wrapper:has(.menu-item-body[style*="block"]) .menu-item-bar,
        .menu-item-wrapper:not(:has(.menu-item-body[style*="block"])) .menu-item-bar:hover {
            /* Apply transform only when NOT expanded to prevent layout shift/blink during editing */
        }

        .hover-shadow-md:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }

        /* Apply transform only if NOT expanded/focused to avoid jumping/blinking */
        .menu-item-wrapper:not(:has(.menu-item-body[style*="block"])) .hover-shadow-md:hover {
            /*transform: translateY(-1px);*/
        }

        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        .transition-colors {
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        /* Active/Expanded State Styling */
        .menu-item-bar:hover .item-active-border {
            opacity: 0.5 !important;
        }

        .menu-item-wrapper:has(.menu-item-body[style*="block"]) > .menu-item-bar .item-active-border {
            opacity: 1 !important;
        }

        /* Search Results Dropdown */
        .link-search-results {
            position: absolute;
            z-index: 9999;
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
            background: #fff;
            display: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Stronger shadow */
        }

        /* Z-Index Management */
        /* Ensure the active/expanded item sits ON TOP of subsequent items */
        /*.menu-item-wrapper:has(.menu-item-body[style*="block"]),
        .menu-item-wrapper:focus-within {
            z-index: 50;
        }*/

        .link-search-result {
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.15s ease;
        }

        .link-search-result:last-child {
            border-bottom: none;
        }

        .link-search-result:hover {
            background: #f0f7ff; /* Light primary background */
        }

        .link-group-header {
            padding: 8px 12px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            font-weight: 600;
            background: #f9fafb;
        }

        /* Nested Items Guide Line & Container */
        .menu-item-children {
            margin-left: 10px;
            padding-bottom: 5px; /* Add breathing room */
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Edit Menu</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.menus.index') }}">
                        <span>
                            <i class="ph-duotone ph-list f-s-16"></i> Menus
                        </span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Edit Menu</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12">
                <x-forms.form :action="route('admin.menus.update', $menu->id)" method="PUT" id="menu-form">
                    <div class="row g-4">
                        {{-- Left Sidebar: Menu Settings --}}
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm sticky-top" style="top: 20px; z-index: 100;">
                                <div class="card-header bg-white py-3 border-bottom-0">
                                    <h6 class="mb-0 fw-bold">Menu Details</h6>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="mb-4">
                                        <x-forms.input label="Name" name="name" id="name" placeholder="e.g., Main Menu" :value="old('name', $menu->name)" :error="$errors->first('name')" required />
                                        <div class="form-text text-muted f-s-12">Internal name for this menu.</div>
                                    </div>
                                    <div class="mb-3">
                                        <x-forms.input label="Handle" name="handle" id="handle" placeholder="e.g., main-menu" :value="old('handle', $menu->handle)" :error="$errors->first('handle')" required />
                                        <div class="form-text text-muted f-s-12">Unique ID for theme implementation. Auto-generated if empty.</div>
                                    </div>

                                    <div class="mt-4 pt-3 border-top">
                                        <input type="hidden" name="menu_structure" id="menu_structure">
                                        <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">
                                            <i class="ph ph-floppy-disk me-2"></i> Save Menu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Content: Menu Items --}}
                        <div class="col-md-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold">Menu Structure</h6>
                                    <span class="badge bg-light text-dark border fw-normal">Drag to reorder</span>
                                </div>
                                <div class="card-body pb-0 mb-2" id="menu-items-container">
                                    @if(isset($tree))
                                        @foreach($tree as $item)
                                            <x-menu-item :item="$item" :prefix="'items[' . $loop->index . ']'" />
                                        @endforeach
                                    @endif

                                    {{-- Empty State Placeholder --}}
                                    @if(!isset($tree) || count($tree) === 0)
                                        <div id="empty-state" class="text-center py-5 text-muted border border-dashed rounded bg-light mb-3">
                                            <i class="ph ph-list-dashes f-s-32 mb-2 opacity-50"></i>
                                            <p class="mb-0">This menu is empty</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-white border-top-0 pb-4 pt-2">
                                    <button type="button" class="btn btn-outline-primary w-100 border-dashed py-2" onclick="addMenuItem()">
                                        <i class="ph ph-plus me-1"></i> Add new menu item
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-forms.form>
            </div>
        </div>
    </div>

    {{-- Template for new menu item --}}
    <template id="item-row-template">
        <x-menu-item :item="null" prefix="PREFIX[INDEX]" />
    </template>

@endsection

@push('scripts:after')
    <script src="{{ asset('admins/vendor/sortable/Sortable.min.js') }}"></script>
    <script>
        let itemIndex = {{ $menu->items->count() + 1 }};

        function initSortable(el) {
            new Sortable(el, {
                group: {
                    name: 'menu-nested',
                    pull: true,
                    put: true
                },
                animation: 150,
                handle: '.handle', // UPDATED to match new handle class in component
                ghostClass: 'bg-primary-subtle',
                fallbackOnBody: true,
                swapThreshold: 0.65,
                emptyInsertThreshold: 10, // Increased threshold
                sort: true,
                forceFallback: true,
                onStart: function(evt) {
                    document.body.classList.add('grabbing');
                },
                onEnd: function(evt) {
                    document.body.classList.remove('grabbing');
                    // Check if empty state needs to be removed
                    const emptyState = document.getElementById('empty-state');
                    if (emptyState && document.getElementById('menu-items-container').children.length > 1) { // > 1 because dragged item is there
                        emptyState.style.display = 'none';
                    }
                },
                onAdd: function (evt) {
                    if (evt.pullMode === 'clone') {
                        evt.from.removeChild(evt.clone);
                    }
                }
            });
        }

        // Initialize root items
        const rootContainer = document.getElementById('menu-items-container');
        initSortable(rootContainer);

        // Initialize existing children
        document.querySelectorAll('.menu-item-children').forEach(el => initSortable(el));

        // Handle Empty State
        if (rootContainer.children.length > 0 && !rootContainer.querySelector('#empty-state')) {
            // logic if needed
        }

        function addMenuItem(containerId = 'menu-items-container', prefix = 'items') {
            const container = document.getElementById(containerId);
            const template = document.getElementById('item-row-template').innerHTML;
            const newIndex = itemIndex++;

            // Simple regex replace for the placeholders
            const html = template.replace(/INDEX/g, newIndex).replace(/PREFIX/g, prefix);

            // Create a temp div to parse string to DOM
            const div = document.createElement('div');
            div.innerHTML = html.trim(); // Trim to avoid text nodes

            const newItem = div.firstElementChild;
            container.appendChild(newItem);

            // Hide empty state if exists
            const emptyState = document.getElementById('empty-state');
            if (emptyState) emptyState.style.display = 'none';

            // Initialize sortable for the new item's children container
            const childrenContainer = newItem.querySelector('.menu-item-children');
            if (childrenContainer) {
                initSortable(childrenContainer);
            }

            // Auto-expand the new item so user can edit immediately
            const body = newItem.querySelector('.menu-item-body');
            if(body) body.style.display = 'block';

            // Scroll to new item
            newItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function removeMenuItem(btn) {
            if(confirm('Delete this menu item? Nested items will also be removed.')) {
                const wrapper = btn.closest('.menu-item-wrapper');
                const container = wrapper.parentElement;

                // Animate removal
                wrapper.style.opacity = '0';
                wrapper.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    wrapper.remove();

                    // Show empty state if needed
                    if (document.getElementById('menu-items-container').children.length === 0 ||
                        (document.getElementById('menu-items-container').children.length === 1 && document.getElementById('empty-state'))) {
                        const emptyState = document.getElementById('empty-state');
                        if (emptyState) emptyState.style.display = 'block';
                    }
                }, 200);
            }
        }

        function toggleMenuItemBody(element) {
            // Handle click from either button or header
            const row = element.closest('.menu-item-wrapper');
            const body = row.querySelector('.menu-item-body');

            if (body.style.display === 'none') {
                $(body).slideDown(200); // Standard jQuery slide
            } else {
                $(body).slideUp(200);
            }
        }

        function updateLabel(input) {
            const row = input.closest('.menu-item-wrapper');
            const displayLabel = row.querySelector('.item-display-label');
            displayLabel.textContent = input.value || 'New Item';
        }

        // Link Search Logic (Cleaned up)
        $(document).on('click', '.link-search-input', function() {
            const input = $(this);
            const resultsBox = input.closest('.link-selection-wrapper').find('.link-search-results');

            // Open if hidden (allows user to re-open menu even if populated)
            // This solves "not open" while avoiding "permanently display" on auto-focus
            // if (resultsBox.is(':hidden')) {
            loadLinkCategories(input);
            // }
        });

        $(document).on('keyup', '.link-search-input', debounce(function() {
            const input = $(this);
            const query = input.val();
            const resultsBox = input.closest('.link-selection-wrapper').find('.link-search-results');
            const activeType = input.data('active-type');

            if (query.length < 2 && !activeType) {
                if (query.length === 0) loadLinkCategories(input);
                else resultsBox.hide();
                return;
            }

            $.get('{{ route("admin.menus.search-linkables") }}', { q: query, type: activeType }, function(data) {
                renderResults(input, data, activeType ? 'Search results' : null);
            });
        }, 300));

        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this, args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        function loadLinkCategories(input) {
            const resultsBox = input.closest('.link-selection-wrapper').find('.link-search-results');
            input.data('active-type', null);
            $.get('{{ route("admin.menus.search-linkables") }}', function(data) {
                let html = '';
                data.forEach(group => {
                    html += `<div class="link-group-header">${group.group}</div>`;
                    group.items.forEach(item => {
                        html += `
                        <div class="link-search-result d-flex align-items-center gap-2"
                             data-label="${item.label}"
                             data-url="${item.url || ''}"
                             data-type="${item.type}"
                             data-has-children="${item.has_children ?? false}">
                            <i class="ph ${item.icon} text-muted"></i>
                            <span class="text-dark">${item.label}</span>
                            ${item.has_children ? '<i class="ph ph-caret-right ms-auto text-muted"></i>' : ''}
                        </div>
                    `;
                    });
                });
                resultsBox.html(html).show();
            });
        }

        function loadTypeResults(input, type, label) {
            const resultsBox = input.closest('.link-selection-wrapper').find('.link-search-results');
            input.data('active-type', type);
            resultsBox.html('<div class="p-3 text-center text-muted"><i class="ph ph-spinner ph-spin f-s-20"></i></div>');
            $.get('{{ route("admin.menus.search-linkables") }}', { type: type }, function(data) {
                renderResults(input, data, label, true);
            });
        }

        function renderResults(input, data, label = null, showBack = false) {
            const resultsBox = input.closest('.link-selection-wrapper').find('.link-search-results');
            let html = '';
            if (label) {
                html += `<div class="link-group-header d-flex align-items-center bg-light border-bottom">
                ${showBack ? '<i class="ph ph-arrow-left me-2 cursor-pointer back-to-categories text-primary"></i>' : ''}
                <span>${label}</span>
            </div>`;
            }

            if (data.length > 0) {
                data.forEach(item => {
                    html += `
                    <div class="link-search-result"
                         data-label="${item.label}"
                         data-url="${item.url}"
                         data-type="${item.full_type || item.type}"
                         data-id="${item.id}">
                        <div class="d-flex flex-column">
                            <span class="text-dark fw-medium">${item.label}</span>
                            ${item.type ? `<small class="text-muted" style="font-size: 0.75rem;">${item.type}</small>` : ''}
                        </div>
                    </div>
                `;
                });
            } else {
                html += '<div class="p-3 text-center text-muted">No results found</div>';
            }
            resultsBox.html(html).show();
        }

        $(document).on('click', '.back-to-categories', function(e) {
            e.stopPropagation();
            loadLinkCategories($(this).closest('.link-selection-wrapper').find('.link-search-input'));
        });

        $(document).on('click', '.link-search-result', function() {
            const result = $(this);
            const wrapper = result.closest('.link-selection-wrapper');
            const input = wrapper.find('.link-search-input');
            const resultsBox = wrapper.find('.link-search-results');

            if (result.data('has-children')) {
                loadTypeResults(input, result.data('type'), result.data('label'));
                return;
            }

            const type = result.data('type');

            if (type === 'Custom') {
                input.val('').focus();
                wrapper.find('.item-url').val('');
                wrapper.find('.item-linkable-type').val('');
                wrapper.find('.item-linkable-id').val('');
            } else {
                wrapper.find('.item-url').val(result.data('url'));
                wrapper.find('.item-linkable-type').val(result.data('type'));
                wrapper.find('.item-linkable-id').val(result.data('id'));
                input.val(result.data('label'));

                // Auto-fill label if empty
                const labelInput = wrapper.closest('.menu-item-body').find('.item-label-input');
                if (!labelInput.val()) {
                    labelInput.val(result.data('label'));
                    updateLabel(labelInput[0]);
                }
            }

            resultsBox.hide();
        });

        // Handle manual URL entry for custom links
        $(document).on('input', '.link-search-input', function() {
            const input = $(this);
            const wrapper = input.closest('.link-selection-wrapper');
            const linkableTypeInput = wrapper.find('.item-linkable-type');

            if (!linkableTypeInput.val()) {
                wrapper.find('.item-url').val(input.val());
            }
        });

        // Close results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.link-selection-wrapper').length) {
                $('.link-search-results').hide();
            }
        });

        // Serialize Menu Items
        $('#menu-form').on('submit', function () {
            const items = serializeMenuItems(document.getElementById('menu-items-container'));
            document.getElementById('menu_structure').value = JSON.stringify(items);
        });

        function serializeMenuItems(container) {
            let items = [];

            // Updated selector to match new component class
            const rows = Array.from(container.children)
                .filter(child => child.classList.contains('menu-item-wrapper'));

            rows.forEach(row => {
                const body = row.querySelector('.menu-item-body');
                if (!body) return;

                const label = body.querySelector('.item-label-input')?.value?.trim();
                const url = body.querySelector('.item-url')?.value?.trim();
                const linkableType = body.querySelector('.item-linkable-type')?.value || null;
                const linkableId = body.querySelector('.item-linkable-id')?.value || null;

                if (!label && !url && !linkableId) {
                    return;
                }

                const item = {
                    label: label || null,
                    url: url || null,
                    linkable_type: linkableType,
                    linkable_id: linkableId,
                    children: []
                };

                const childrenContainer = row.querySelector('.menu-item-children');
                if (childrenContainer) {
                    item.children = serializeMenuItems(childrenContainer);
                }

                items.push(item);
            });

            return items;
        }

    </script>
@endpush
