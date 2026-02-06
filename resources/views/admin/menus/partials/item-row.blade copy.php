@php
    $uid = uniqid();
    $isExpanded = !$item; // New items expanded, existing minimized
@endphp

<div class="menu-item-wrapper mb-2">
    {{-- Header / Bar --}}
    <div class="menu-item-bar d-flex flex-column align-items-center gap-3 p-3 rounded border bg-white shadow-sm">
        {{-- Header / Bar --}}
        <div class="w-100 d-flex align-items-center gap-3">
            {{-- Drag Handle --}}
            <div class="cursor-grab text-muted p-2 d-flex align-items-center justify-content-center hover-bg-light rounded">
                <i class="ph ph-dots-six-vertical f-s-20" style="opacity: 0.5;"></i>
            </div>

            {{-- Icon / Indicator --}}
            <div class="text-primary">
                <i class="ph ph-arrow-elbow-down-right f-s-16"></i>
            </div>

            {{-- Label & URL --}}
            <div class="d-flex flex-column flex-grow-1 overflow-hidden" style="min-width: 0;">
                <span class="f-w-600 text-truncate item-display-label">
                    {{ $item ? $item->calculated_label : 'New Item' }}
                </span>
                <span class="text-muted f-s-12 text-truncate font-monospace" style="opacity: 0.7;">
                    {{ $item ? $item->calculated_url : '' }}
                </span>
            </div>

            {{-- Type Badge --}}
            <div>
                @if($item && $item->linkable_type)
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 fw-normal">
                        {{ class_basename($item->linkable_type) }}
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 fw-normal">
                        Custom Link
                    </span>
                @endif
            </div>

            {{-- Actions --}}
            <div class="d-flex align-items-center gap-1 border-start ps-3">
                <button type="button" class="btn btn-sm btn-icon" onclick="toggleMenuItemBody(this)" title="Edit">
                    <i class="ph {{ $isExpanded ? 'ph-caret-up' : 'ph-pencil-simple' }} text-primary"></i>
                </button>
                <button type="button" class="btn btn-sm btn-icon" onclick="removeMenuItem(this)" title="Remove">
                    <i class="ph ph-trash text-danger"></i>
                </button>
            </div>
        </div>
        
        {{-- Body / Form --}}
        <div class="menu-item-body w-100" style="display: {{ $isExpanded ? 'block' : 'none' }}">
            <div class="card border-0 shadow-sm bg-light m-0">
                <div class="card-body">
                    <div class="row g-3">
                        {{-- Label Field --}}
                        <div class="col-md-6">
                            <label class="form-label f-s-13 mb-2 fw-semibold">
                                <i class="ph ph-text-aa me-1 text-primary"></i>
                                Label <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="{{ $prefix }}[label]" 
                                class="form-control form-control-sm item-label-input" 
                                value="{{ $item ? $item->label : '' }}" 
                                placeholder="e.g., Home, About Us, Contact" 
                                onkeyup="updateLabel(this)"
                            >
                            <small class="form-text text-muted d-block mt-1">
                                The text that appears in the menu
                            </small>
                        </div>

                        {{-- Destination Field --}}
                        <div class="col-md-6 link-selection-wrapper">
                            <label class="form-label f-s-13 mb-2 fw-semibold">
                                <i class="ph ph-link me-1 text-primary"></i>
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
                                    <span class="input-group-text bg-white">
                                        <i class="ph ph-magnifying-glass text-muted"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-sm link-search-input" 
                                        value="{{ $displayLink }}" 
                                        placeholder="Search pages, posts, or paste URL"
                                    >
                                </div>
                                <div class="link-search-results shadow-lg"></div>
                                
                                <input type="hidden" name="{{ $prefix }}[url]" class="item-url" value="{{ $item ? $item->url : '' }}">
                                <input type="hidden" name="{{ $prefix }}[linkable_type]" class="item-linkable-type" value="{{ $item ? $item->linkable_type : '' }}">
                                <input type="hidden" name="{{ $prefix }}[linkable_id]" class="item-linkable-id" value="{{ $item ? $item->linkable_id : '' }}">
                                
                                <small class="form-text text-muted d-block mt-1">
                                    Search for content or enter a custom URL
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Info --}}
                    @if($item && $item->linkable_type)
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex align-items-center gap-2 text-muted f-s-12">
                            <i class="ph ph-link-simple"></i>
                            <span>Linked to: <strong>{{ class_basename($item->linkable_type) }}</strong></span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- Children Container --}}
    <div 
        class="menu-item-children ps-4 mt-2" 
        id="children-of-{{ $item ? $item->id : 'new-' . $uid }}"
    >
        @if($item && $item->children->count() > 0)
            @foreach($item->children as $child)
                @include('admin.menus.partials.item-row', ['item' => $child, 'prefix' => $prefix . "[children][{$loop->index}]"])
            @endforeach
        @endif
    </div>
</div>