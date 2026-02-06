@props([
    'item' => null,
    'prefix' => 'items[0]',
    'loop' => null
])

@php
    $uid = uniqid();
    // Logic for expanded state: default to false for existing items to reduce clutter,
    // but can be toggled. New items can default to expanded if desired,
    // though the JS usually handles the 'new item' state.
    $isExpanded = false;

    // Determine labels and values safely
    $calculatedLabel = $item ? $item->calculated_label : 'New Item';
    $calculatedUrl = $item ? $item->calculated_url : '';
    $linkableType = $item ? $item->linkable_type : '';
    $classBasename = $linkableType ? class_basename($linkableType) : 'Custom Link';

    // Check if we have children to decide if we should render the children container
    // For new items (template), $item is null, but we still need the structure.
    $hasChildren = $item && $item->children->count() > 0;
@endphp

<div class="menu-item-wrapper" data-uid="{{ $uid }}">
    {{-- Header / Bar --}}
    <div class="menu-item-bar d-flex flex-column align-items-center gap-3 px-2 py-1 rounded-3 border bg-white shadow-sm transition-all hover-shadow-md position-relative">
        {{-- Left Accent Border --}}
        <div class="position-absolute top-0 start-0 bottom-0 bg-primary item-active-border" style="width: 4px; opacity: 0; border-top-left-radius: inherit; border-bottom-left-radius: inherit;"></div>

        <div class="w-100 d-flex align-items-center gap-3">
            {{-- Drag Handle --}}
            <div class="cursor-grab text-muted p-1 d-flex align-items-center justify-content-center bg-light rounded hover-bg-primary-subtle hover-text-primary transition-colors handle">
                <i class="ph ph-dots-six-vertical f-s-20"></i>
            </div>

            {{-- Icon / Indicator --}}
            <div class="text-primary d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                <i class="ph ph-list f-s-18"></i>
            </div>

            {{-- Label & URL --}}
            <div class="d-flex flex-column flex-grow-1 overflow-hidden cursor-pointer" style="min-width: 0;" onclick="toggleMenuItemBody(this)">
                <span class="f-w-600 text-truncate item-display-label text-dark">
                    {{ $calculatedLabel }}
                </span>
                <span class="text-muted f-s-12 text-truncate font-monospace" style="opacity: 0.7;">
                    {{ $calculatedUrl }}
                </span>
            </div>

            {{-- Type Badge --}}
            <div class="d-none d-sm-block">
                @if($linkableType)
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 fw-medium">
                        {{ $classBasename }}
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 fw-medium">
                        Custom Link
                    </span>
                @endif
            </div>

            {{-- Actions --}}
            <div class="d-flex align-items-center gap-2 border-start ps-3 ms-2">
                <button type="button" class="btn btn-sm btn-icon btn-ghost-secondary rounded-circle" onclick="toggleMenuItemBody(this)" title="Edit">
                    <i class="ph ph-pencil-simple"></i>
                </button>
                <button type="button" class="btn btn-sm btn-icon btn-ghost-danger rounded-circle" onclick="removeMenuItem(this)" title="Remove">
                    <i class="ph ph-trash"></i>
                </button>
            </div>
        </div>

        {{-- Body / Form --}}
        <div class="menu-item-body w-100 mt-3 border-top" style="display: {{ $isExpanded ? 'block' : 'none' }}">
            <div class="row g-3 p-3">
                {{-- Label Field --}}
                <div class="col-md-6">
                    <label class="form-label f-s-13 mb-2 fw-semibold text-dark">
                        Label <span class="text-danger">*</span>
                    </label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="ph ph-text-aa text-muted"></i>
                        </span>
                        <input
                            type="text"
                            name="{{ $prefix }}[label]"
                            class="form-control form-control-sm item-label-input border-start-0 ps-0"
                            value="{{ $item ? $item->label : '' }}"
                            placeholder="e.g., Home, About Us"
                            onkeyup="updateLabel(this)"
                        >
                    </div>
                </div>

                {{-- Destination Field --}}
                <div class="col-md-6 link-selection-wrapper">
                    <label class="form-label f-s-13 mb-2 fw-semibold text-dark">
                        Destination <span class="text-danger">*</span>
                    </label>
                    <div class="position-relative">
                        @php
                            $displayLink = $item ? $item->url : '';
                            if ($item && $item->linkable) {
                                $displayLink = $item->linkable->title ?? $item->linkable->name ?? $item->calculated_url;
                            }
                        @endphp
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ph ph-link text-muted"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control form-control-sm link-search-input border-start-0 ps-0"
                                value="{{ $displayLink }}"
                                placeholder="Search or paste URL"
                            >
                        </div>
                        <div class="link-search-results shadow-lg rounded-3 border-0 mt-1"></div>

                        <input type="hidden" name="{{ $prefix }}[url]" class="item-url" value="{{ $item ? $item->url : '' }}">
                        <input type="hidden" name="{{ $prefix }}[linkable_type]" class="item-linkable-type" value="{{ $item ? $item->linkable_type : '' }}">
                        <input type="hidden" name="{{ $prefix }}[linkable_id]" class="item-linkable-id" value="{{ $item ? $item->linkable_id : '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Children Container --}}
    <div
        class="menu-item-children ps-2 position-relative"
        id="children-of-{{ $item ? $item->id : 'new-' . $uid }}"
    >
        {{-- Vertical Guide Line --}}
        <div class="position-absolute top-0 bottom-0 start-0 border-dashed border-secondary-subtle" style="left: 20px;"></div>

        @if($item && $item->children->count() > 0)
            @foreach($item->children as $child)
                <x-menu-item :item="$child" :prefix="$prefix . '[children][' . $loop->index . ']'" />
            @endforeach
        @endif
    </div>
</div>
