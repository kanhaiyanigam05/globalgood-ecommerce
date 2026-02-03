@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'error' => null,
    'required' => false,
    'multiple' => false,
    'preview' => true,
    'shape' => 'full',
    'accept' => null,
    'value' => [],
])

@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;

    $wrapperClass = 'x-file-upload';
    $wrapperClass .= $shape === 'square' ? ' square' : '';
    $wrapperClass .= $shape === 'circle' ? ' circle' : '';

    // Normalize value
    if (empty($value)) {
        $files = [];
    } elseif (is_string($value)) {
        $files = [
            [
                'path' => $value,
                'name' => basename($value),
                'type' => Str::endsWith($value, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? 'image' : 'file',
            ],
        ];
    } else {
        $files = $value;
    }

    if (!$multiple) {
        $files = Arr::take($files, 1);
    }
@endphp

<div class="form-group x-file-wrapper">
    @if ($label)
        <label>{{ $label }} @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $wrapperClass }}" data-multiple="{{ $multiple ? 'true' : 'false' }}"
        data-preview="{{ $preview ? 'true' : 'false' }}">

        <input type="file" class="d-none x-file-input" name="{{ $multiple ? $name . '[]' : $name }}"
            @if ($multiple) multiple @endif
            @if ($accept) accept="{{ $accept }}" @endif @required($required)>

        <div class="x-file-dropzone">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <p>Drag & drop or <span>browse</span></p>
        </div>

        {{-- SINGLE SHARED CONTAINER --}}
        <div class="x-file-preview">
            @foreach ($files as $file)
                <div class="x-preview-item" data-existing="true">
                    <button type="button" class="x-preview-close">&times;</button>

                    @if (Str::startsWith($file['type'], 'image'))
                        <img src="{{ asset('uploads/' . $file['path']) }}">
                    @else
                        <div class="file-icon"><i class="fa-solid fa-file"></i></div>
                        <p class="mt-1 text-truncate">{{ $file['name'] }}</p>
                    @endif

                    <input type="hidden" name="existing_files[]" value="{{ $file['path'] }}">
                </div>
            @endforeach
        </div>
    </div>

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
            background: #fafafa
        }

        .x-file-dropzone {
            text-align: center;
            cursor: pointer;
            color: #6b7280
        }

        .x-file-dropzone span {
            text-decoration: underline;
            font-weight: 600
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
            background: #fff
        }

        .x-preview-item img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 4px
        }

        .file-icon {
            aspect-ratio: 1/1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            background: #f3f4f6
        }

        .x-preview-close {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 24px;
            height: 24px;
            border: none;
            border-radius: 50%;
            background: #dc3545;
            color: #fff;
            cursor: pointer;
        }
    </style>
@endPushOnce
@pushOnce('scripts:after')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            document.querySelectorAll('.x-file-upload').forEach(wrapper => {

                const input = wrapper.querySelector('.x-file-input');
                const dropzone = wrapper.querySelector('.x-file-dropzone');
                const preview = wrapper.querySelector('.x-file-preview');
                const isMultiple = wrapper.dataset.multiple === 'true';

                dropzone.onclick = () => input.click();

                input.addEventListener('change', () => {
                    addNewFiles();
                    updateDropzone();
                });

                wrapper.addEventListener('click', e => {
                    const btn = e.target.closest('.x-preview-close');
                    if (!btn) return;

                    const item = btn.closest('.x-preview-item');

                    if (item.dataset.existing === 'true') {
                        item.remove(); // existing file
                    } else {
                        removeNewFile([...preview.children].indexOf(item));
                    }

                    updateDropzone();
                });

                function addNewFiles() {
                    Array.from(input.files).forEach(file => {
                        const item = document.createElement('div');
                        item.className = 'x-preview-item';

                        const close = document.createElement('button');
                        close.className = 'x-preview-close';
                        close.innerHTML = '&times;';

                        let content;
                        if (file.type.startsWith('image/')) {
                            content = document.createElement('img');
                            content.src = URL.createObjectURL(file);
                        } else {
                            content = document.createElement('div');
                            content.className = 'file-icon';
                            content.innerHTML = '<i class="fa-solid fa-file"></i>';
                        }

                        item.append(close, content);
                        preview.appendChild(item);
                    });
                }

                function removeNewFile(index) {
                    const dt = new DataTransfer();
                    Array.from(input.files).forEach((f, i) => {
                        if (i !== index) dt.items.add(f);
                    });
                    input.files = dt.files;
                    preview.children[index]?.remove();
                }

                function updateDropzone() {
                    const hasFiles = preview.children.length > 0;
                    if (!isMultiple && hasFiles) {
                        dropzone.classList.add('d-none');
                    } else {
                        dropzone.classList.remove('d-none');
                    }
                }

                updateDropzone();
            });
        });
    </script>
@endPushOnce
