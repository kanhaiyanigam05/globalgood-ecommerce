@props([
    'id' => null,
    'label' => null,
    'error' => null,
    'required' => false,
])

@php
    $groupClass = 'form-check';
    $inputClass = 'form-check-input';
    $inputClass .= $error ? ' is-invalid' : '';
@endphp

<div class="{{ $groupClass }}">
    <input type="checkbox" id="{{ $id }}" @required($required) {!! $attributes->merge(['class' => $inputClass]) !!}>

    <label class="form-check-label" for="{{ $id }}">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    @if ($error)
        <div class="invalid-feedback d-block">
            {{ $error }}
        </div>
    @endif
</div>
