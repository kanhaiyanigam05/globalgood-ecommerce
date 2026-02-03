@props([
    'id' => null,
    'class' => null,
    'label' => null,
    'varient' => 'default',
    'value' => null,
    'error' => null,
    'required' => false,
])

@php
    $groupClass = $varient === 'default' ? 'form-group' : 'form-floating';

    $inputClass = $varient === 'default' ? 'form-control' : 'form-control floating';
    $inputClass .= $error ? ' is-invalid' : '';
@endphp

<div class="{{ $groupClass }}">
    @if ($varient === 'default')
        <label for="{{ $id }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <textarea id="{{ $id }}" @required($required) {!! $attributes->merge(['class' => $inputClass]) !!}>{{ $value }}</textarea>

    @if ($varient === 'floating')
        <label for="{{ $id }}" style="left: 0; top: 0; height: 100%; pointer-events: none;">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @if ($error)
        <div class="invalid-feedback mb-1">{{ $error }}</div>
    @endif
</div>
