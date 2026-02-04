@props([
    'id' => null,
    'label' => null,
    'options' => [],
    'varient' => 'default',
    'error' => null,
    'required' => false,
    'placeholder' => null,
])

@php
    $groupClass = $varient === 'default' ? 'form-group' : 'form-floating';

    $selectClass = $varient === 'default' ? 'form-select' : 'form-select floating';

    $selectClass .= $error ? ' is-invalid' : '';
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

    <select id="{{ $id }}" @required($required) {!! $attributes->merge(['class' => $selectClass]) !!}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected($value == $attributes->get('value'))>
                {{ $text }}
            </option>
        @endforeach
    </select>

    @if ($varient === 'floating')
        <label for="{{ $id }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @if ($error)
        <div class="invalid-feedback">
            {{ $error }}
        </div>
    @endif
</div>
