@props([
    'id' => 'media_select_' . str_replace(['[', ']'], ['_', ''], $name),
    'name' => null,
    'label' => null,
    'value' => null, // Can be ID or array of IDs/Media objects
    'multiple' => false,
    'required' => false,
    'error' => null,
    'model_type' => null,
    'model_id' => null,
    'collection' => 'images',
])

@php
    $selectedMedia = [];
    if ($value) {
        if ($multiple) {
            $selectedMedia = \App\Models\Media::whereIn('id', (array) $value)->get();
        } else {
            $selectedMedia = \App\Models\Media::where('id', $value)->get();
        }
    }
@endphp

<div class="form-group media-select-wrapper" id="{{ $id }}_wrapper">
    @if ($label)
        <label>{{ $label }} @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="media-select-container border rounded p-2 bg-light">
        <div class="media-select-previews d-flex flex-wrap gap-2 mb-2" id="{{ $id }}_previews">
            @foreach ($selectedMedia as $media)
                <div class="media-preview-item position-relative mr-2 mb-2" data-id="{{ $media->id }}">
                    <img src="{{ $media->thumb }}" class="rounded shadow-sm"
                        style="width: 80px; height: 80px; object-fit: cover;">
                    <button type="button" class="btn btn-danger btn-sm position-absolute"
                        style="top: -5px; right: -5px; border-radius: 50%; padding: 0 5px;"
                        onclick="removeMediaItem('{{ $id }}', '{{ $media->id }}')">&times;</button>
                    <input type="hidden" name="{{ $multiple ? $name . '[]' : $name }}" value="{{ $media->id }}">
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-outline-primary btn-sm btn-block d-flex align-items-center justify-content-center py-3"
            onclick="openMediaModal('{{ $id }}', {{ $multiple ? 'true' : 'false' }}, '{{ str_replace('\\', '\\\\', $model_type) }}', '{{ $model_id }}', '{{ $collection }}')">
            <i class="fa fa-plus-circle mr-2"></i> {{ $multiple ? 'Add Media' : 'Select Media' }}
        </button>
    </div>

    @if ($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif
</div>

@pushOnce('scripts:after')
    <script>
        function openMediaModal(wrapperId, multiple, modelType = '', modelId = '', collection = '') {
            window.MediaLibrary.open({
                multiple: multiple,
                model_type: modelType,
                model_id: modelId,
                collection: collection,
                callback: (items) => {
                    const previews = document.getElementById(wrapperId + '_previews');
                    const isMultiple = multiple;
                    const name = "{{ $name }}";

                    if (!isMultiple) {
                        previews.innerHTML = '';
                    }

                    items.forEach(item => {
                        // Check if already exists in multiple mode
                        if (isMultiple && previews.querySelector(`[data-id="${item.id}"]`)) return;

                        const div = document.createElement('div');
                        div.className = 'media-preview-item position-relative mr-2 mb-2';
                        div.dataset.id = item.id;
                        div.innerHTML = `
                            <img src="${item.thumb}" class="rounded shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: -5px; right: -5px; border-radius: 50%; padding: 0 5px;" onclick="removeMediaItem('${wrapperId}', '${item.id}')">&times;</button>
                            <input type="hidden" name="${isMultiple ? name + '[]' : name}" value="${item.id}">
                        `;
                        previews.appendChild(div);
                    });
                }
            });
        }

        function removeMediaItem(wrapperId, id) {
            const previews = document.getElementById(wrapperId + '_previews');
            const item = previews.querySelector(`[data-id="${id}"]`);
            if (item) item.remove();
        }
    </script>
@endPushOnce
