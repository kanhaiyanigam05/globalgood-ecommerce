@extends('admin.settings.layout')

@section('settings-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="f-w-700 mb-0">General</h4>
</div>

<form action="{{ route('admin.settings.general.update') }}" method="POST">
    @csrf
    <div class="settings-card">
        <div class="settings-card-header">
            <h6 class="mb-0 f-w-600">Store details</h6>
        </div>
        <div class="settings-card-body">
            <div class="row g-4">
                <div class="col-md-12">
                    <label class="form-label f-w-600 f-s-14">Store name</label>
                    <input type="text" name="store_name" class="form-control" value="{{ $settings['store_name'] ?? '' }}" placeholder="My Awesome Store">
                </div>
                <div class="col-md-6">
                    <label class="form-label f-w-600 f-s-14">Store contact email</label>
                    <input type="email" name="store_email" class="form-control" value="{{ $settings['store_email'] ?? '' }}" placeholder="contact@store.com">
                    <small class="text-muted">We'll use this address if we need to contact you about your store.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label f-w-600 f-s-14">Sender email</label>
                    <input type="email" name="sender_email" class="form-control" value="{{ $settings['sender_email'] ?? '' }}" placeholder="no-reply@store.com">
                    <small class="text-muted">Your customers will see this address when you send them emails.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="settings-card">
        <div class="settings-card-header">
            <h6 class="mb-0 f-w-600">Standards and formats</h6>
        </div>
        <div class="settings-card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label f-w-600 f-s-14">Store currency</label>
                    <select name="store_currency" class="form-select">
                        <option value="USD" {{ ($settings['store_currency'] ?? '') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                        <option value="USD" {{ ($settings['store_currency'] ?? '') == 'USD' ? 'selected' : '' }}>United States Dollar (USD)</option>
                        <option value="GBP" {{ ($settings['store_currency'] ?? '') == 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                        <option value="EUR" {{ ($settings['store_currency'] ?? '') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label f-w-600 f-s-14">Timezone</label>
                    <select name="store_timezone" class="form-select">
                        <option value="Asia/Kolkata" {{ ($settings['store_timezone'] ?? '') == 'Asia/Kolkata' ? 'selected' : '' }}>(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                        <option value="UTC" {{ ($settings['store_timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>Universal Coordinated Time (UTC)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label f-w-600 f-s-14">Unit system</label>
                    <select name="unit_system" class="form-select">
                        <option value="metric" {{ ($settings['unit_system'] ?? '') == 'metric' ? 'selected' : '' }}>Metric system (kg, g, m, cm)</option>
                        <option value="imperial" {{ ($settings['unit_system'] ?? '') == 'imperial' ? 'selected' : '' }}>Imperial system (lb, oz, ft, in)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label f-w-600 f-s-14">Default weight unit</label>
                    <select name="weight_unit" class="form-select">
                        <option value="kg" {{ ($settings['weight_unit'] ?? '') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="g" {{ ($settings['weight_unit'] ?? '') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="lb" {{ ($settings['weight_unit'] ?? '') == 'lb' ? 'selected' : '' }}>Pound (lb)</option>
                        <option value="oz" {{ ($settings['weight_unit'] ?? '') == 'oz' ? 'selected' : '' }}>Ounce (oz)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="submit" class="btn btn-shopify-primary">Save changes</button>
    </div>
</form>
@endsection
