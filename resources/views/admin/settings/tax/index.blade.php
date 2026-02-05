@extends('admin.settings.layout')

@section('settings-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="f-w-700 mb-0">Taxes and duties</h4>
</div>

<!-- Global tax settings -->
<div class="settings-card mb-4">
    <div class="settings-card-header">
        <h6 class="mb-0 f-w-600">Global tax settings</h6>
    </div>
    <div class="settings-card-body">
        <form id="globalTaxForm" action="{{ route('admin.settings.tax.update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <div class="form-check d-flex align-items-start mb-3">
                    <input class="form-check-input mt-1 me-3" type="checkbox" name="settings[tax_included]" value="1" id="taxIncluded" {{ ($globalSettings['tax_included'] ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label f-s-14" for="taxIncluded">
                        <span class="d-block f-w-600">All prices include tax</span>
                        <span class="text-muted f-s-13">Choose this if yours is a tax-inclusive market.</span>
                    </label>
                </div>
                
                <div class="form-check d-flex align-items-start mb-3">
                    <input class="form-check-input mt-1 me-3" type="checkbox" name="settings[tax_on_shipping]" value="1" id="taxOnShipping" {{ ($globalSettings['tax_on_shipping'] ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label f-s-14" for="taxOnShipping">
                        <span class="d-block f-w-600">Charge tax on shipping rates</span>
                        <span class="text-muted f-s-13">Taxes on shipping are calculated based on the destination of the order.</span>
                    </label>
                </div>

                <div class="form-check d-flex align-items-start">
                    <input class="form-check-input mt-1 me-3" type="checkbox" name="settings[tax_on_digital]" value="1" id="taxOnDigital" {{ ($globalSettings['tax_on_digital'] ?? '') ? 'checked' : '' }}>
                    <label class="form-check-label f-s-14" for="taxOnDigital">
                        <span class="d-block f-w-600">Charge tax on digital goods</span>
                        <span class="text-muted f-s-13">Digital goods are taxed based on the customer's billing address.</span>
                    </label>
                </div>
            </div>
            
            <div class="pt-3 border-top text-end">
                <button type="submit" class="btn btn-shopify-primary btn-sm">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Tax regions -->
<div class="settings-card">
    <div class="settings-card-header d-block">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="mb-1 f-w-600">Tax regions</h6>
                <p class="text-muted f-s-13 mb-0">Manage how your store collects and calculates taxes in different regions.</p>
            </div>
        </div>
        <div class="position-relative">
            <input type="text" id="regionSearch" class="form-control form-control-sm ps-5" placeholder="Search regions" style="border-radius: 8px;">
            <i class="ph ph-magnifying-glass position-absolute" style="left: 15px; top: 10px; color: #5c5f62;"></i>
        </div>
    </div>
    <div class="settings-card-body p-0" id="taxTableContainer">
        @include('admin.settings.tax.table')
    </div>
</div>
@endsection

@push('scripts:after')
<script>
    $(document).on('click', '.custom-pg-btn', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (url) {
            loadTaxTable(url);
        }
    });

    function loadTaxTable(url) {
        $('#taxTableContainer').css('opacity', '0.5');
        $.ajax({
            url: url,
            success: function(data) {
                $('#taxTableContainer').html(data).css('opacity', '1');
            },
            error: function() {
                window.toast.error('Could not load tax regions.');
                $('#taxTableContainer').css('opacity', '1');
            }
        });
    }

    document.getElementById('regionSearch').addEventListener('keyup', function() {
        const term = this.value;
        let url = "{{ route('admin.settings.tax') }}";
        if (term) {
            url += '?search=' + encodeURIComponent(term);
        }
        loadTaxTable(url);
    });

    $('#globalTaxForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                window.toast.success('Global tax settings updated.');
                submitBtn.prop('disabled', false).text('Save');
            },
            error: function() {
                window.toast.error('Something went wrong.');
                submitBtn.prop('disabled', false).text('Save');
            }
        });
    });
</script>
@endpush
