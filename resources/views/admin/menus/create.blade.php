@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb start -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Create Menu</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="{{ route('admin.menus.index') }}">
                        <span>
                            <i class="ph-duotone ph-list f-s-16"></i> Menus
                        </span>
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Create Menu</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Breadcrumb end -->

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <x-forms.form :action="route('admin.menus.store')" method="POST" id="create-menu-form">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-1 fw-semibold">Menu Details</h5>
                                <p class="text-muted f-s-13 mb-0">Create a new navigation menu for your site</p>
                            </div>
                            <i class="ph ph-navigation text-primary" style="font-size: 2rem; opacity: 0.3;"></i>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Name Field -->
                        <div class="mb-4">
                            <x-forms.input
                                label="Menu Name"
                                name="name"
                                id="name"
                                placeholder="e.g., Main Menu, Footer Menu, Mobile Menu"
                                :value="old('name')"
                                :error="$errors->first('name')"
                                required
                            />
                            <div class="form-text text-muted d-flex align-items-start gap-2 mt-2">
                                <i class="ph ph-info text-primary mt-1"></i>
                                <span>A descriptive name for internal use. This helps you identify the menu in your admin panel.</span>
                            </div>
                        </div>

                        <!-- Handle Field -->
                        <div class="mb-4">
                            <x-forms.input
                                label="Handle"
                                name="handle"
                                id="handle"
                                placeholder="e.g., main-menu, footer-menu"
                                :value="old('handle')"
                                :error="$errors->first('handle')"
                            />
                            <div class="form-text text-muted d-flex align-items-start gap-2 mt-2">
                                <i class="ph ph-info text-primary mt-1"></i>
                                <span>A unique identifier used in your theme code to fetch this menu. Leave blank to auto-generate from the menu name.</span>
                            </div>
                            <div class="form-text text-muted mt-2">
                                <small class="font-monospace bg-light px-2 py-1 rounded">
                                    Example: <code>menu('main-menu')</code>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-light">
                            <i class="ph ph-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-plus-circle me-2"></i>Create Menu
                        </button>
                    </div>
                </div>
            </x-forms.form>
        </div>
    </div>
</div>
@endsection

@push('scripts:after')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const handleInput = document.getElementById('handle');
        let manuallyEdited = false;

        // Auto-generate handle from name
        if (nameInput && handleInput) {
            handleInput.addEventListener('input', function() {
                if (this.value) manuallyEdited = true;
            });

            nameInput.addEventListener('input', function() {
                if (!manuallyEdited) {
                    const handle = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    handleInput.value = handle;
                }
            });
        }
    });
</script>
@endpush
