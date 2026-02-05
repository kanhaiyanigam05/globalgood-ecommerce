@props([
    'type' => 'text',
    'id' => null,
    'class' => null,
    'label' => null,
    'varient' => 'default',
    'error' => null,
    'required' => false,
])

@php
    $groupClass = $varient === 'default' ? 'form-group' : 'form-floating';
    if ($type === 'password') {
        $groupClass .= ' position-relative';
    }

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

    <input type="{{ $type }}" id="{{ $id }}" @required($required) {!! $attributes->merge(['class' => $inputClass]) !!} />

    @if ($varient === 'floating')
        <label for="{{ $id }}" style="left: 0; top: 0; height: 100%; pointer-events: none;">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @if ($type === 'password')
        <span class="password-toggle" data-target="{{ $id }}"
            style="
                position: absolute;
                top: 50%;
                right: 12px;
                transform: translateY(-50%);
                cursor: pointer;
                z-index: 10;
            ">
            <i class="fa fa-eye"></i>
        </span>
    @endif

    @if ($error)
        <div class="invalid-feedback mb-1">{{ $error }}</div>
    @endif
</div>

@pushOnce('scripts:after')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordToggle = document.querySelectorAll('.password-toggle');
            passwordToggle.forEach((toggle) => {
                toggle.addEventListener('click', function() {
                    const target = document.getElementById(this.dataset.target);
                    if (target.type === 'password') {
                        target.type = 'text';
                        this.querySelector('i').classList.remove('fa-eye');
                        this.querySelector('i').classList.add('fa-eye-slash');
                    } else {
                        target.type = 'password';
                        this.querySelector('i').classList.remove('fa-eye-slash');
                        this.querySelector('i').classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
@endPushOnce
