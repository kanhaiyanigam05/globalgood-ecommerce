@extends('admin.settings.layout')

@section('settings-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="f-w-700 mb-0">Shipping and delivery</h4>
</div>

<!-- Shipping Profiles -->
<div class="settings-card mb-4">
    <div class="settings-card-header">
        <div>
            <h6 class="mb-1 f-w-600">Shipping</h6>
            <p class="text-muted f-s-13 mb-0">Choose where you ship and how much you charge at checkout.</p>
        </div>
    </div>
    <div class="settings-card-body p-0">
        <!-- General Shipping Rates -->
        @php $generalProfile = $profiles->where('is_default', true)->first(); @endphp
        @if($generalProfile)
        <div class="p-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-2 rounded me-3">
                        <i class="ph ph-package f-s-24"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 f-w-700">General shipping rates</h6>
                        <p class="text-muted f-s-13 mb-0">All products • {{ $generalProfile->zones->count() }} areas</p>
                    </div>
                </div>
                <a href="{{ route('admin.settings.shipping.edit', $generalProfile->id) }}" class="btn btn-shopify-secondary btn-sm">Manage</a>
            </div>
            <div class="ms-lg-5">
                <div class="d-flex flex-wrap gap-2 mt-2">
                    @foreach($generalProfile->zones as $zone)
                        <span class="badge bg-light text-dark border f-s-11 px-2 py-1">
                            <i class="ph ph-globe me-1"></i> {{ $zone->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Custom Shipping Rates -->
        <div class="p-4 bg-light-grey">
            <h6 class="f-s-14 f-w-600 mb-0 text-muted text-uppercase">Custom shipping rates</h6>
        </div>
        
        @foreach($profiles->where('is_default', false) as $profile)
        <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-2 rounded me-3">
                        <i class="ph ph-tag f-s-24"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 f-w-700">{{ $profile->name }}</h6>
                        <p class="text-muted f-s-13 mb-0">{{ $profile->products->count() }} products • {{ $profile->zones->count() }} areas</p>
                    </div>
                </div>
                <a href="{{ route('admin.settings.shipping.edit', $profile->id) }}" class="btn btn-shopify-secondary btn-sm">Manage</a>
            </div>
        </div>
        @endforeach

        @if($profiles->where('is_default', false)->isEmpty())
        <div class="p-5 text-center bg-white border-top">
             <i class="ph ph-tag-chevron f-s-48 text-muted opacity-25 mb-3 d-block"></i>
             <p class="text-muted f-s-14 mb-0">No custom profiles. Use them to charge different rates for specific products.</p>
        </div>
        @endif
    </div>
    <div class="settings-card-footer bg-light p-3">
        <button class="btn btn-link text-decoration-none f-w-600 f-s-14 p-0">+ Create new profile</button>
    </div>
</div>

<!-- Local Pickup & Delivery -->
<div class="settings-card mb-4">
    <div class="settings-card-header">
        <h6 class="mb-1 f-w-600">Local delivery and pickup</h6>
    </div>
    <div class="settings-card-body p-0">
        <div class="row g-0">
            <div class="col-md-6 border-end">
                <div class="p-4">
                    <div class="d-flex align-items-center mb-3">
                         <i class="ph ph-bicycle f-s-24 me-3 text-primary"></i>
                         <h6 class="mb-0 f-w-600">Local delivery</h6>
                    </div>
                    <p class="text-muted f-s-13 mb-4">Deliver orders directly to customers nearby.</p>
                    <button class="btn btn-shopify-secondary btn-sm">Set up</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4">
                    <div class="d-flex align-items-center mb-3">
                         <i class="ph ph-storefront f-s-24 me-3 text-primary"></i>
                         <h6 class="mb-0 f-w-600">Local pickup</h6>
                    </div>
                    <p class="text-muted f-s-13 mb-4">Allow customers to pick up their orders in person.</p>
                    <button class="btn btn-shopify-secondary btn-sm">Set up</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Expected Delivery & Operational -->
<div class="row">
    <div class="col-md-6">
        <div class="settings-card">
            <div class="settings-card-header">
                <h6 class="mb-0 f-w-600">Processing time</h6>
            </div>
            <div class="settings-card-body">
                <p class="text-muted f-s-13 mb-4">Set how long it takes to process an order before shipping.</p>
                <div class="d-flex align-items-center p-3 border rounded">
                     <i class="ph ph-clock me-3 f-s-20 text-muted"></i>
                     <span class="f-s-14">No processing time set</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="settings-card">
            <div class="settings-card-header">
                <h6 class="mb-0 f-w-600">Packing slips</h6>
            </div>
            <div class="settings-card-body">
                <p class="text-muted f-s-13 mb-4">Customize the form that is included in the package you ship.</p>
                <button class="btn btn-shopify-secondary btn-sm">Edit template</button>
            </div>
        </div>
    </div>
</div>
@endsection
