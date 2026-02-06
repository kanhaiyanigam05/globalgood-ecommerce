@php
    $uid = uniqid();
    $isActive = $item ? true : false; // For existing items, we render them collapsed
    // Actually, user wants "all those menu-items already created, should show minimized, and new menu-item should editable"
    // So $item (existing) -> Body hidden.
    // New item -> Body visible.
    $isExpanded = !$item;
@endphp
<div class="menu-item-wrapper mb-2">
    {{-- Header / Bar --}}
    <div class="menu-item-bar d-flex align-items-center gap-3 p-2 rounded border bg-white shadow-sm">
        {{-- Drag Handle --}}
        <div class="cursor-grab text-muted px-1">
            <i class="ph ph-dots-six-vertical f-s-18"></i>
        </div>

        {{-- Icon / Indicator --}}
        <div class="text-primary f-w-bold">
            <i class="ph ph-arrow-elbow-down-right"></i>
        </div>

        {{-- Label & URL --}}
        <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden">
            <span class="f-w-600 text-truncate item-display-label">{{ $item ? $item->calculated_label : 'New Item' }}</span>
            <span class="text-muted f-s-12 text-truncate font-monospace">{{ $item ? $item->calculated_url : '' }}</span>
        </div>

        {{-- Type Badge --}}
        <div class="badge bg-dark-subtle text-dark border border-dark-subtle rounded-pill px-3 fw-normal">
            {{ $item && $item->linkable_type ? class_basename($item->linkable_type) : 'Custom Link' }}
        </div>

        {{-- Actions --}}
        <div class="d-flex align-items-center gap-1 border-start ps-2 ms-2">
            <button type="button" class="btn btn-icon btn-sm btn-ghost-primary" onclick="toggleMenuItemBody(this)" title="Edit">
                <i class="ph ph-pencil-simple"></i>
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-ghost-danger" onclick="removeMenuItem(this)" title="Remove">
                <i class="ph ph-trash"></i>
            </button>
        </div>
    </div>

    {{-- Body / Form --}}
    <div class="menu-item-body mt-2 ps-4" style="display: {{ $isExpanded ? 'block' : 'none' }}">
        <div class="card card-body bg-light border-0 mb-2">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label f-s-13 mb-1 fw-bold">Label <span class="text-danger">*</span></label>
                    <input type="text" name="{{ $prefix }}[label]" class="form-control form-control-sm item-label-input" value="{{ $item ? $item->label : '' }}" placeholder="Menu label" onkeyup="updateLabel(this)">
                </div>
                <div class="col-md-6 link-selection-wrapper">
                    <label class="form-label f-s-13 mb-1 fw-bold">Destination <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        @php
                            $displayLink = $item ? $item->url : '';
                            if ($item && $item->linkable) {
                                $displayLink = $item->linkable->title ?? $item->linkable->name ?? $item->calculated_url;
                            }
                        @endphp
                        <input type="text" class="form-control form-control-sm link-search-input" value="{{ $displayLink }}" placeholder="Search or paste link">
                        <div class="link-search-results shadow-lg"></div>
                        
                        <input type="hidden" name="{{ $prefix }}[url]" class="item-url" value="{{ $item ? $item->url : '' }}">
                        <input type="hidden" name="{{ $prefix }}[linkable_type]" class="item-linkable-type" value="{{ $item ? $item->linkable_type : '' }}">
                        <input type="hidden" name="{{ $prefix }}[linkable_id]" class="item-linkable-id" value="{{ $item ? $item->linkable_id : '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Children Container --}}
    <div class="menu-item-children ps-4 mt-2 border-start border-2 border-light ms-2" id="children-of-{{ $item ? $item->id : 'new-' . $uid }}" style="min-height: 40px; padding-bottom: 10px;">
        @if($item && $item->children->count() > 0)
            @foreach($item->children as $child)
                @include('admin.menus.partials.item-row', ['item' => $child, 'prefix' => $prefix . "[children][{$loop->index}]"])
            @endforeach
        @endif
    </div>
</div>