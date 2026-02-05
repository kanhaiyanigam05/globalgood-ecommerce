@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'error' => null,
    'required' => false,
    'multiple' => false,
    'preview' => true,
    'shape' => 'full',
    'accept' => 'all',
    'value' => [],
    'useMediaLibrary' => true,
    'directory' => 'media',
])

@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;

    $id = $id ?? 'file_' . Str::random(8);
    $pickerId = 'picker_' . $id;

    $wrapperClass = 'x-file-upload';
    $wrapperClass .= $shape === 'square' ? ' square' : '';
    $wrapperClass .= $shape === 'circle' ? ' circle' : '';

    // Normalize value
    if (empty($value)) {
        $files = [];
    } elseif ($value instanceof \Illuminate\Support\Collection) {
        $files = $value->toArray();
    } elseif (is_array($value)) {
        $files = $value;
    } else {
        $files = [$value];
    }
@endphp

<div class="form-group x-file-wrapper" id="wrapper_{{ $id }}">
    @if ($label)
        <label>{{ $label }} @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $wrapperClass }}"
        data-multiple="{{ $multiple ? 'true' : 'false' }}"
        data-directory="{{ $directory }}"
        data-id="{{ $id }}">

        <div class="x-file-dropzone">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <p>Choose from Media Library</p>
        </div>

        <div class="x-file-preview">
            @foreach ($files as $file)
                @php
                    $isMedia = is_object($file) || (is_array($file) && isset($file['id']));
                    $filePath = $isMedia ? ($file->url ?? $file['url'] ?? '') : (is_string($file) ? $file : $file['path'] ?? '');
                    $fileName = $isMedia ? ($file->file_name ?? $file['file_name'] ?? '') : basename($filePath);
                    $thumb = $isMedia ? ($file->thumb ?? $file['thumb'] ?? $filePath) : $filePath;
                    $isImage = Str::contains($fileName, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'JPG', 'JPEG', 'PNG', 'WEBP']) || (isset($file['mime_type']) && Str::startsWith($file['mime_type'], 'image/'));
                @endphp
                <div class="x-preview-item" data-existing="true">
                    <button type="button" class="x-preview-close">&times;</button>

                    @if ($isImage)
                        <img src="{{ $thumb }}" />
                    @else
                        <div class="file-icon"><i class="fa-solid fa-file"></i></div>
                        <p class="mt-1 text-truncate small px-2">{{ $fileName }}</p>
                    @endif

                    @if($isMedia)
                        <input type="hidden" class="media-id-input" name="{{ $multiple ? 'media_ids[]' : 'media_id' }}" value="{{ $file->id ?? $file['id'] }}">
                        @php
                            $imageId = is_object($file) && isset($file->pivot) ? $file->pivot->id : (isset($file['image_id']) ? $file['image_id'] : null);
                        @endphp
                        @if($imageId)
                            <input type="hidden" name="existing_image_ids[]" value="{{ $imageId }}">
                        @endif
                    @else
                        <input type="hidden" name="existing_files[]" value="{{ $filePath }}">
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    @if($useMediaLibrary)
        <x-media-picker :id="$pickerId" :multiple="$multiple" :accept="$accept" />
    @endif

    @if ($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif
</div>

@pushOnce('styles:after')
    <style>
        .x-file-upload {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 24px;
            background: #fafafa;
            transition: all 0.3s;
        }
        .x-file-upload:hover {
            border-color: #667eea;
            background: #f9fafb;
        }

        .x-file-dropzone {
            text-align: center;
            cursor: pointer;
            color: #6b7280;
        }

        .x-file-dropzone span {
            text-decoration: underline;
            font-weight: 600;
            color: #667eea;
        }

        .x-file-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .x-preview-item {
            position: relative;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px;
            background: #fff;
            text-align: center;
        }

        .x-preview-item:hover .x-preview-close {
            opacity: 1;
        }

        .sortable-ghost {
            opacity: 0.4;
            background: #f3f4f6;
        }

        .x-preview-item {
            cursor: move;
        }

        .x-preview-item img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 4px;
        }

        .file-icon {
            aspect-ratio: 1/1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            background: #f3f4f6;
            border-radius: 4px;
        }

        .x-preview-close {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 20px;
            height: 20px;
            border: none;
            border-radius: 50%;
            background: #ef4444;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            z-index: 5;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
@endPushOnce

@pushOnce('scripts:before')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endPushOnce

@pushOnce('scripts:after')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrappers = document.querySelectorAll('.x-file-upload');
            
            wrappers.forEach(wrapper => {
                const id = wrapper.dataset.id;
                const dropzone = wrapper.querySelector('.x-file-dropzone');
                const preview = wrapper.querySelector('.x-file-preview');
                const isMultiple = wrapper.dataset.multiple === 'true';
                const directory = wrapper.dataset.directory || 'media';
                const pickerId = 'picker_' + id;

                // Initialize picker if used
                let picker = null;
                if (window.initMediaPicker) {
                    picker = window.initMediaPicker(pickerId, {
                        multiple: isMultiple,
                        directory: directory,
                        onSelect: (selected) => {
                            if (!isMultiple) preview.innerHTML = '';
                            
                            selected.forEach(media => {
                                // Check if already added for multiple
                                if (isMultiple) {
                                    const existing = preview.querySelector(`input[value="${media.id}"]`);
                                    if (existing) return;
                                }

                                const item = createPreviewItem(media);
                                preview.appendChild(item);
                            });
                            updateDropzoneVisibility();
                        }
                    });

                    dropzone.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        picker.show();
                    });
                }

                // Initialize Sortable for drag-and-drop reordering (only for multiple)
                if (isMultiple && window.Sortable) {
                    Sortable.create(preview, {
                        animation: 150,
                        handle: '.x-preview-item',
                        ghostClass: 'sortable-ghost',
                        onEnd: function() {
                            updatePositions();
                        }
                    });
                }

                preview.addEventListener('click', (e) => {
                    const close = e.target.closest('.x-preview-close');
                    if (close) {
                        close.closest('.x-preview-item').remove();
                        updateDropzoneVisibility();
                        updatePositions();
                    }
                });

                function createPreviewItem(data) {
                    const item = document.createElement('div');
                    item.className = 'x-preview-item';
                    
                    const close = document.createElement('button');
                    close.type = 'button';
                    close.className = 'x-preview-close';
                    close.innerHTML = '&times;';
                    
                    let content;
                    if (data.is_image) {
                        content = document.createElement('img');
                        content.src = data.thumb || data.url;
                    } else {
                        content = document.createElement('div');
                        content.className = 'file-icon';
                        content.innerHTML = '<i class="fa-solid fa-file"></i>';
                        const name = document.createElement('p');
                        name.className = 'mt-1 text-truncate small px-2';
                        name.textContent = data.file_name;
                        item.appendChild(name);
                    }
                    
                    item.appendChild(close);
                    item.appendChild(content);

                    if (data.id) {
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = isMultiple ? 'media_ids[]' : 'media_id';
                        hidden.value = data.id;
                        hidden.className = 'media-id-input';
                        item.appendChild(hidden);

                        // Add existing_image_ids for tracking
                        if (data.image_id) {
                            const existingInput = document.createElement('input');
                            existingInput.type = 'hidden';
                            existingInput.name = 'existing_image_ids[]';
                            existingInput.value = data.image_id;
                            item.appendChild(existingInput);
                        }
                    }

                    return item;
                }

                function updatePositions() {
                    // Remove old position inputs
                    preview.querySelectorAll('.position-input').forEach(el => el.remove());
                    
                    // Add new position inputs based on current order
                    const items = preview.querySelectorAll('.x-preview-item');
                    items.forEach((item, index) => {
                        const existingIdInput = item.querySelector('input[name="existing_image_ids[]"]');
                        if (existingIdInput) {
                            const posInput = document.createElement('input');
                            posInput.type = 'hidden';
                            posInput.name = `image_positions[${existingIdInput.value}]`;
                            posInput.value = index;
                            posInput.className = 'position-input';
                            item.appendChild(posInput);
                        }
                    });
                }

                function updateDropzoneVisibility() {
                    if (!isMultiple && preview.children.length > 0) {
                        dropzone.style.display = 'none';
                    } else {
                        dropzone.style.display = 'block';
                    }
                }

                updateDropzoneVisibility();
                updatePositions();
            });
        });
    </script>
@endPushOnce
