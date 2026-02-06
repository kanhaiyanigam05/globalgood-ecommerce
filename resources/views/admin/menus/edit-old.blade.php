@extends('admin.layouts.app')

@push('styles:after')
<style>
    .menu-item-row {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        background: #fff;
    }
    .menu-item-children {
        margin-left: 30px;
        border-left: 2px dashed #eee;
        padding-left: 15px;
    }
    .link-search-results {
        position: absolute;
        z-index: 1000;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        display: none;
    }
    .link-search-result {
        padding: 8px 12px;
        cursor: pointer;
    }
    .link-search-result:hover {
        background: #f8f9fa;
    }
    .link-selected {
        background: #e9ecef;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
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
        <div class="col-md-10">
            <x-forms.form :action="route('admin.menus.update', $menu->id)" method="PUT" id="menu-form">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Menu details</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <x-forms.input label="Name" name="name" id="name" placeholder="e.g., Main Menu" :value="old('name', $menu->name)" :error="$errors->first('name')" required />
                                    <div class="form-text text-muted">A descriptive name for internal use.</div>
                                </div>
                                <div class="mb-3">
                                    <x-forms.input label="Handle" name="handle" id="handle" placeholder="e.g., main-menu" :value="old('handle', $menu->handle)" :error="$errors->first('handle')" required />
                                    <div class="form-text text-muted">A unique identifier used in your theme code to fetch this menu. If left blank, it will be generated from the name.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Menu items</h6>
                            </div>
                            <div class="card-body pb-0" id="menu-items-container">
                                @if(isset($tree))
                                    @foreach($tree as $item)
                                        @include('admin.menus.partials.item-row', ['item' => $item, 'prefix' => "items[{$loop->index}]"])
                                    @endforeach
                                @endif
                            </div>
                            <div class="card-footer bg-white">
                                <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addMenuItem()">
                                    <i class="ph ph-plus me-1"></i> Add menu item
                                </button>
                            </div>
                        </div>

                        <div class="text-end mb-5">
                            <input type="hidden" name="menu_structure" id="menu_structure">
                            <button type="submit" class="btn btn-primary">Save Menu</button>
                        </div>
                    </div>
                </div>
            </x-forms.form>
        </div>
    </div>
</div>

{{-- Template for new menu item --}}
<template id="item-row-template">
    @include('admin.menus.partials.item-row', ['item' => null, 'prefix' => 'PREFIX[INDEX]'])
</template>

@endsection

@push('scripts:after')
<script src="{{ asset('admins/vendor/sortable/Sortable.min.js') }}"></script>
<script>
    let itemIndex = {{ $menu->items->count() + 1 }};

    function initSortable(el) {
        new Sortable(el, {
            group: 'menu-nested',  // ✅ Simple string - allows moving between all containers
            animation: 150,
            handle: '.ph-dots-six-vertical',
            ghostClass: 'bg-light',
            fallbackOnBody: true,
            swapThreshold: 0.65,
            emptyInsertThreshold: 5,
            sort: true
        });
    }

    // Initialize root items
    initSortable(document.getElementById('menu-items-container'));
    
    // Initialize existing children
    document.querySelectorAll('.menu-item-children').forEach(el => initSortable(el));

    function addMenuItem(containerId = 'menu-items-container', prefix = 'items') {
        const container = document.getElementById(containerId);
        const template = document.getElementById('item-row-template').innerHTML;
        const newIndex = itemIndex++;
        
        const html = template.replace(/INDEX/g, newIndex).replace(/PREFIX/g, prefix);
        const div = document.createElement('div');
        div.innerHTML = html;
        container.appendChild(div.firstElementChild);
        
        // Initialize sortable for new children containers
        if (div.firstElementChild.querySelector('.menu-item-children')) {
            initSortable(div.firstElementChild.querySelector('.menu-item-children'));
        }
    }

    function removeMenuItem(btn) {
        if(confirm('Are you sure you want to remove this menu item and all its sub-items?')) {
            btn.closest('.menu-item-wrapper').remove();
        }
    }

    function toggleMenuItemBody(btn) {
        const row = btn.closest('.menu-item-wrapper');
        const body = row.querySelector('.menu-item-body');
        
        if (body.style.display === 'none') {
            body.style.display = 'block';
        } else {
            body.style.display = 'none';
        }
    }

    function updateLabel(input) {
        const row = input.closest('.menu-item-wrapper');
        const displayLabel = row.querySelector('.item-display-label');
        displayLabel.textContent = input.value || 'New Item';
    }

    // Link Selection Logic
    $(document).on('focus', '.link-search-input', function() {
        loadLinkCategories($(this));
    });

    $(document).on('keyup', '.link-search-input', function() {
        const input = $(this);
        const query = input.val();
        const resultsBox = input.siblings('.link-search-results');
        const activeType = input.data('active-type');
        
        if (query.length < 2 && !activeType) {
            if (query.length === 0) loadLinkCategories(input);
            else resultsBox.hide();
            return;
        }

        $.get('{{ route("admin.menus.search-linkables") }}', { q: query, type: activeType }, function(data) {
            renderResults(input, data, activeType ? 'Search results' : null);
        });
    });

    function loadLinkCategories(input) {
        const resultsBox = input.siblings('.link-search-results');
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
                            <i class="ph ${item.icon}"></i>
                            <span>${item.label}</span>
                            ${item.has_children ? '<i class="ph ph-caret-right ms-auto"></i>' : ''}
                        </div>
                    `;
                });
            });
            resultsBox.html(html).show();
        });
    }

    function loadTypeResults(input, type, label) {
        const resultsBox = input.siblings('.link-search-results');
        input.data('active-type', type);
        resultsBox.html('<div class="p-2">Loading...</div>');
        $.get('{{ route("admin.menus.search-linkables") }}', { type: type }, function(data) {
            renderResults(input, data, label, true);
        });
    }

    function renderResults(input, data, label = null, showBack = false) {
        const resultsBox = input.siblings('.link-search-results');
        let html = '';
        if (label) {
            html += `<div class="link-group-header d-flex align-items-center">
                ${showBack ? '<i class="ph ph-arrow-left me-2 clickable back-to-categories"></i>' : ''}
                ${label}
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
                        ${item.type ? `<strong>${item.type}:</strong> ` : ''}${item.label}
                    </div>
                `;
            });
        } else {
            html += '<div class="p-2 text-muted">No results found</div>';
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
            // Focus input and allow manual entry
            input.val('').focus();
            wrapper.find('.item-url').val('');
            wrapper.find('.item-linkable-type').val('');
            wrapper.find('.item-linkable-id').val('');
            resultsBox.hide();
            return;
        }
        
        // Set hidden fields
        wrapper.find('.item-url').val(result.data('url'));
        wrapper.find('.item-linkable-type').val(result.data('type'));
        wrapper.find('.item-linkable-id').val(result.data('id'));
        
        // UI Update - show label in input
        input.val(result.data('label'));
        resultsBox.hide();
        
        // Auto-fill label if empty
        const labelInput = wrapper.closest('.menu-item-body').find('.item-label-input');
        if (!labelInput.val()) {
            labelInput.val(result.data('label'));
            updateLabel(labelInput[0]);
        }
    });

    // Handle manual URL entry for custom links
    $(document).on('input', '.link-search-input', function() {
        const input = $(this);
        const wrapper = input.closest('.link-selection-wrapper');
        const linkableTypeInput = wrapper.find('.item-linkable-type');
        
        // If it's a custom link (no linkable type), update the item-url hidden field
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

        const rows = Array.from(container.children)
            .filter(child => child.classList.contains('menu-item-wrapper'));

        rows.forEach(row => {
            const body = row.querySelector('.menu-item-body');
            if (!body) return;

            const label = body.querySelector('.item-label-input')?.value?.trim();
            const url = body.querySelector('.item-url')?.value?.trim();
            const linkableType = body.querySelector('.item-linkable-type')?.value || null;
            const linkableId = body.querySelector('.item-linkable-id')?.value || null;

            // ✅ SKIP EMPTY ITEMS (CRITICAL)
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