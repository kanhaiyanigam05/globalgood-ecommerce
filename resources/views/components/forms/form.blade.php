@props([
    'method' => 'POST',
    'action' => null,
    'id' => null,
    'processingText' => 'Processing...',
    'varient' => 'default',
    'class' => '',
    'confirm' => false,
    'confirmTitle' => 'Confirm Action',
    'confirmMessage' => 'Are you sure you want to continue?',
    'confirmButtonText' => 'Yes, Continue',
    'confirmVariant' => 'danger',
])

@php
    $httpMethod = strtoupper($method);
    $formMethod = in_array($httpMethod, ['GET', 'POST']) ? $httpMethod : 'POST';

    $formClass = $class;
    $formClass .= $varient === 'reactive' ? ' reactive' : '';

    $variants = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];

    $confirmVariant = in_array($confirmVariant, $variants) ? $confirmVariant : 'danger';
@endphp

<form method="{{ strtolower($formMethod) }}" action="{{ $action }}" id="{{ $id }}"
    data-processing-text="{{ $processingText }}" data-confirm="{{ $confirm ? 'true' : 'false' }}"
    {{ $attributes->merge(['class' => $formClass]) }}>
    @csrf

    @if (!in_array($httpMethod, ['GET', 'POST']))
        @method($httpMethod)
    @endif

    {{ $slot }}
</form>

@if ($confirm)
    <div class="modal fade" tabindex="-1" id="confirmModal-{{ $id }}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-{{ $confirmVariant }}">
                    <h5 class="modal-title text-white">
                        {{ $confirmTitle }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="text-dark mb-0">{{ $confirmMessage }}</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-{{ $confirmVariant }} confirm-submit">
                        {{ $confirmButtonText }}
                    </button>
                </div>

            </div>
        </div>
    </div>
@endif

@pushOnce('scripts:after')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('form.reactive').forEach(function(form) {

                let confirmed = false;

                form.addEventListener('submit', function(e) {

                    if (form.dataset.confirm === 'true' && !confirmed) {
                        e.preventDefault();

                        const modalEl = document.getElementById('confirmModal-' + form.id);
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();

                        modalEl.querySelector('.confirm-submit').onclick = function() {
                            confirmed = true;
                            modal.hide();
                            form.requestSubmit();
                        };

                        return;
                    }

                    const buttons = form.querySelectorAll(
                        'button[type="submit"], input[type="submit"]'
                    );

                    buttons.forEach(function(btn) {
                        btn.disabled = true;

                        if (btn.tagName === 'BUTTON') {
                            btn.innerHTML =
                                `<span class="spinner-border spinner-border-sm me-1"></span>${form.dataset.processingText}`;
                        }
                    });
                });
            });
        });
    </script>
@endPushOnce
