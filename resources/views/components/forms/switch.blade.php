@props([
    'id' => null,
    'label' => null,
    'error' => null,
    'required' => false,
    'checked' => false,
    'class' => null,
])

@php
    $wrapperClass = 'swich-size my-3';
    $inputClass = 'toggle';
    $inputClass .= $class ? " {$class}" : '';
@endphp

<div class="main-switch main-switch-color">
    <div class="{{ $wrapperClass }}">
        <input type="checkbox" id="{{ $id }}" value="1" {!! $attributes->merge(['class' => $inputClass]) !!}
            @checked($checked) @required($required) />

        <label for="{{ $id }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>

        @if ($error)
            <div class="invalid-feedback d-block mt-1">
                {{ $error }}
            </div>
        @endif
    </div>
</div>
