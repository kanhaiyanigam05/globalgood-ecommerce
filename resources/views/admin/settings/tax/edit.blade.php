@extends('admin.settings.layout')

@section('settings-content')
<div class="mb-4">
    <div class="d-flex align-items-center mb-3 f-s-13">
        <a href="{{ route('admin.settings.tax') }}" class="text-muted d-flex align-items-center">
            <i class="ph ph-arrow-left me-2"></i> Taxes and duties
        </a>
        <span class="mx-2 text-muted">/</span>
        <span class="text-dark f-w-600">{{ $country->name }}</span>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <div class="bg-white p-2 rounded shadow-sm border me-3">
                <i class="ph ph-percentage f-s-24 text-dark"></i>
            </div>
            <h4 class="f-w-700 mb-0">{{ $country->name }}</h4>
        </div>
        <div class="d-flex gap-2">
             <button type="submit" form="taxForm" class="btn btn-shopify-primary btn-sm">Save</button>
        </div>
    </div>
</div>

<form id="taxForm" action="{{ route('admin.settings.tax.country.save', $country->id) }}" method="POST">
    @csrf
    
    <!-- Tax Service -->
    <div class="settings-card mb-4">
        <div class="settings-card-header">
            <h6 class="mb-0 f-w-600">Tax service</h6>
        </div>
        <div class="settings-card-body">
            <div class="d-flex align-items-center p-3 border rounded shadow-sm">
                <div class="bg-light-success p-2 rounded-circle me-3">
                    <i class="shopify-logo">
                        <svg viewBox="0 0 448 512" width="24" height="24" fill="#95bf47"><path d="M211.5 8C204.4 33.1 190.1 57.3 162.3 80.2h123.4c-27.8-22.9-42.1-47.1-49.2-72.2-1.9-6.7-10.7-10-16.7-10-6.1 0-14.8 3.3-16.7 10zm184 80.2h-12c-2.4 0-4.6-1.5-5.5-3.8-3.4-8.1-13.8-11.8-19.4-4.8l-1.4 1.7c-3.1 3.9-8.4 4.7-12.4 1.8l-18.7-13.3c-28.7-20.4-63.5-31.2-99.1-31.2s-70.4 10.8-99.1 31.2L109.7 40c-4-2.9-9.3-2.1-12.4 1.8l-1.4 1.7c-5.6 7-16 3.3-19.4-4.8-.9-2.3-3.1-3.8-5.5-3.8h-11.3c-11.6 0-21.7 8.3-23.7 19.8l-27.1 155.1c-1.3 7.8 4.7 14.8 12.6 14.8h3.3c3.8 0 7.4-1.9 9.5-5.1 6.8-10.1 17.1-17.7 29.5-21.6l8.8-2.8c4.3-1.4 9-.1 12.1 3.3l49.3 54.4c14.2 15.7 34.4 24.6 55.6 24.6s41.4-8.9 55.6-24.6l49.3-54.4c3.1-3.4 7.8-4.7 12.1-3.3l8.8 2.8c12.4 3.9 22.7 11.5 29.5 21.6 2.1 3.2 5.7 5.1 9.5 5.1h3.3c7.9 0 13.9-7 12.6-14.8L419.2 108c-2-11.5-12.1-19.8-23.7-19.8zM448 312c0-13.3-10.7-24-24-24h-1.1c-11.6 0-21.7 8.3-23.7 19.8l-27.1 155.1c-1.3 7.8 4.7 14.8 12.6 14.8H424c13.3 0 24-10.7 24-24V312zm-381.2 11c-2-11.5-12.1-19.8-23.7-19.8H42c-13.3 0-24 10.7-24 24v141.1c0 13.3 10.7 24 24 24h37.3c7.9 0 13.9-7 12.6-14.8l-27.1-155.1z"/></svg>
                    </i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0 f-w-600 me-2">Manual Tax</h6>
                        <span class="badge bg-light-success text-success border-success f-s-10"><i class="ph ph-circle-fill me-1 f-s-8"></i> Active</span>
                    </div>
                    <p class="text-muted f-s-13 mb-0">Free service</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Base taxes -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h6 class="mb-0 f-w-600">Base taxes</h6>
        </div>
        <div class="settings-card-body p-0">
            <div class="p-4 bg-light-subtle border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <span class="f-w-600 text-uppercase f-s-12 text-muted">Regions</span>
                    </div>
                    <div class="col-md-9 text-end">
                        <button type="button" class="btn btn-shopify-secondary btn-sm">Reset to default tax rates</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table align-middle mb-0">
                    <tbody>
                        <!-- Country Base Tax -->
                        <tr class="bg-light-grey">
                            <td class="ps-4 py-3" style="width: 300px;">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('flags/'.$country->flag) }}" class="country-flag me-3" style="width: 24px;">
                                    <span class="f-w-700">{{ $country->name }}</span>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="input-group input-group-sm" style="max-width: 120px;">
                                    <input type="number" step="0.01" name="base_tax_rate" class="form-control text-end" value="{{ $baseTax->tax_rate ?? 0 }}">
                                    <span class="input-group-text bg-white">%</span>
                                </div>
                            </td>
                            <td class="py-3">
                                <input type="text" name="base_tax_name" class="form-control form-control-sm" value="{{ $baseTax->tax_name ?? 'Tax' }}" placeholder="Tax name">
                            </td>
                            <td class="pe-4 py-3"></td>
                        </tr>

                        <!-- Regional Overrides -->
                        @foreach($country->zones as $zone)
                        @php $override = $country->taxOverrides->where('country_zone_id', $zone->id)->first(); @endphp
                        <tr>
                            <td class="ps-5 py-3">
                                <span class="f-s-14 text-dark">{{ $zone->name }}</span>
                            </td>
                            <td class="py-3">
                                <div class="input-group input-group-sm" style="max-width: 120px;">
                                    <input type="number" step="0.01" name="overrides[{{ $zone->id }}][tax_rate]" class="form-control text-end" value="{{ $override->tax_rate ?? '' }}" placeholder="—">
                                    <span class="input-group-text bg-white f-s-12">%</span>
                                </div>
                            </td>
                            <td class="py-3">
                                <input type="text" name="overrides[{{ $zone->id }}][tax_name]" class="form-control form-control-sm" value="{{ $override->tax_name ?? '' }}" placeholder="Override name">
                            </td>
                            <td class="pe-4 py-3">
                                <select name="overrides[{{ $zone->id }}][tax_type]" class="form-select form-select-sm f-s-12">
                                    <option value="added" {{ ($override->tax_type ?? '') == 'added' ? 'selected' : '' }}>added to {{ $baseTax->tax_rate ?? 0 }}% federal tax</option>
                                    <option value="instead" {{ ($override->tax_type ?? '') == 'instead' ? 'selected' : '' }}>instead of {{ $baseTax->tax_rate ?? 0 }}% federal tax</option>
                                    <option value="compounded" {{ ($override->tax_type ?? '') == 'compounded' ? 'selected' : '' }}>compounded on {{ $baseTax->tax_rate ?? 0 }}% federal tax</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

@push('scripts:after')
<script>
    $('#taxForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                window.toast.success('Tax settings saved and updated seamlessly!');
                submitBtn.prop('disabled', false).text('Save');
            },
            error: function() {
                window.toast.error('Something went wrong. Please try again.');
                submitBtn.prop('disabled', false).text('Save');
            }
        });
    });
</script>
@endpush
@endsection
