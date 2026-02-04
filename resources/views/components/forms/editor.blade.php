@props([
    'id',
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'error' => null,
    'variant' => 'default',
])

@php
    $groupClass = $variant === 'default' ? 'form-group' : 'form-floating';

    $inputClass = 'form-control';
    $inputClass .= $error ? ' is-invalid' : '';
@endphp

<div class="{{ $groupClass }}">
    @if ($variant === 'default' && $label)
        <label for="{{ $id }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <textarea id="{{ $id }}" name="{{ $name }}" @required($required) {!! $attributes->merge(['class' => $inputClass . ' ck-editor']) !!}>{{ $value }}</textarea>

    @if ($variant === 'floating' && $label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    @if ($error)
        <div class="invalid-feedback">{{ $error }}</div>
    @endif
</div>

@pushOnce('scripts:before')
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
    <script>
        const CKInit = (element) => {
            if (!element || element.classList.contains('ck-loaded')) return;

            ClassicEditor.create(element, {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'numberedList', 'bulletedList', '|',
                        'link', 'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                }
            }).then(editor => {
                element.classList.add('ck-loaded');

                editor.plugins.get('FileRepository').createUploadAdapter = loader => ({
                    upload: () =>
                        loader.file.then(file =>
                            new Promise((resolve, reject) => {
                                const reader = new FileReader();
                                reader.onload = () => resolve({
                                    default: reader.result
                                });
                                reader.onerror = reject;
                                reader.readAsDataURL(file);
                            })
                        )
                });
            }).catch(console.error);
        }
    </script>
@endPushOnce
@push('scripts:after')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const el = document.getElementById(@json($id));
            CKInit(el);

        });
    </script>
@endpush
