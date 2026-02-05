@extends('admin.settings.layout')

@section('settings-content')
<div class="mb-4">
    <div class="d-flex align-items-center mb-3 f-s-13">
        <a href="{{ route('admin.settings.shipping') }}" class="text-muted d-flex align-items-center">
            <i class="ph ph-arrow-left me-2"></i> Shipping and delivery
        </a>
        <span class="mx-2 text-muted">/</span>
        <span class="text-dark f-w-600">{{ $profile->name }}</span>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <div class="bg-white p-2 rounded shadow-sm border me-3">
                <i class="ph ph-package f-s-24 text-dark"></i>
            </div>
            <h4 class="f-w-700 mb-0">{{ $profile->name }}</h4>
        </div>
        <div class="d-flex gap-2">
             <button class="btn btn-shopify-secondary btn-sm">Rename</button>
             <button class="btn btn-shopify-primary btn-sm">Save</button>
        </div>
    </div>
</div>

<!-- Products in this profile -->
<div class="settings-card mb-4">
    <div class="settings-card-header">
        <h6 class="mb-0 f-w-600">Products</h6>
        <button class="btn btn-shopify-secondary btn-sm">Done</button>
    </div>
    <div class="settings-card-body p-0">
        @if($profile->is_default)
        <div class="p-4">
            <div class="d-flex align-items-center">
                <div class="bg-light p-2 rounded me-3">
                    <i class="ph ph-package f-s-24"></i>
                </div>
                <div>
                    <h6 class="mb-0 f-w-600">All products</h6>
                    <p class="text-muted f-s-13 mb-0">New products are automatically added to this profile.</p>
                </div>
            </div>
        </div>
        @else
        <div class="p-4">
            @forelse($profile->products ?? [] as $product)
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-light p-1 rounded me-2">
                        <i class="ph ph-tag f-s-16"></i>
                    </div>
                    <span class="f-s-14">{{ $product->name }}</span>
                </div>
            @empty
                <div class="text-center py-3">
                    <p class="text-muted f-s-13 mb-0">No products assigned to this profile.</p>
                    <button class="btn btn-link text-decoration-none f-s-14 f-w-600 p-0 mt-2">+ Add products</button>
                </div>
            @endforelse
        </div>
        @endif
    </div>
</div>

<!-- Origins -->
<div class="settings-card mb-4">
    <div class="settings-card-header">
        <h6 class="mb-0 f-w-600">Shipping origins</h6>
    </div>
    <div class="settings-card-body">
        <div class="d-flex align-items-center">
            <i class="ph ph-map-pin f-s-20 text-muted me-3"></i>
            <div>
                 <span class="f-s-14 f-w-600 d-block">Default Warehouse</span>
                 <span class="text-muted f-s-13">New Delhi, India</span>
            </div>
        </div>
    </div>
</div>

<!-- Shipping zones -->
<div class="settings-card mb-4">
    <div class="settings-card-header">
        <h6 class="mb-0 f-w-600">Shipping zones</h6>
        <button class="btn btn-shopify-secondary btn-sm" onclick="openZoneModal()">Create zone</button>
    </div>
    <div class="settings-card-body p-0">
        @forelse($profile->zones as $zone)
        <div class="p-4 border-bottom zone-item" data-id="{{ $zone->id }}">
            <div class="d-flex justify-content-between align-items-md-center mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-light-primary p-2 rounded-circle me-3">
                        <i class="ph ph-globe f-s-24 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 f-w-700">{{ $zone->name }}</h6>
                        <p class="text-muted f-s-13 mb-0">
                            @foreach($zone->countries->take(8) as $c)
                            <img src="{{ asset('flags/'.$c->flag) }}" class="country-flag"> {{ $c->name }}{{ !$loop->last ? ',' : '' }}
                            @endforeach
                            @if($zone->countries->count() > 8)
                            and {{ $zone->countries->count() - 8 }} more
                            @endif
                        </p>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-shopify-secondary btn-icon btn-sm" data-bs-toggle="dropdown">
                        <i class="ph ph-dots-three-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border">
                        <li><a class="dropdown-item f-s-14" href="javascript:void(0)" onclick="editZone({{ $zone->id }}, '{{ $zone->name }}', {{ $zone->countries->pluck('id') }})"><i class="ph ph-pencil me-2"></i> Edit zone</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item f-s-14 text-danger" href="javascript:void(0)" onclick="deleteZone({{ $zone->id }})"><i class="ph ph-trash me-2"></i> Delete</a></li>
                    </ul>
                </div>
            </div>

            <div class="ms-lg-5">
                <div class="border rounded mt-3">
                    <table class="table table-sm align-middle mb-0 f-s-14 mt-0">
                        <thead class="bg-light">
                            <tr class="f-s-12 text-uppercase text-muted">
                                <th class="ps-3 py-2">Rate name</th>
                                <th class="py-2">Conditions</th>
                                <th class="py-2">Price</th>
                                <th class="text-end pe-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($zone->rates as $rate)
                            <tr>
                                <td class="ps-3 f-w-600">{{ $rate->name }}</td>
                                <td>
                                    <span class="text-muted">
                                        @if($rate->type == 'flat') Free
                                        @elseif($rate->type == 'weight') Based on weight
                                        @else Based on price @endif
                                    </span>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $rate->price > 0 ? '$' . number_format($rate->price, 2) : 'Free' }}</span></td>
                                <td class="text-end pe-3">
                                    <div class="dropdown">
                                        <button class="btn btn-link btn-sm p-0 text-dark" data-bs-toggle="dropdown">
                                            <i class="ph ph-dots-three-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border">
                                            <li><a class="dropdown-item f-s-14" onclick="editRate({{ $zone->id }}, {{ json_encode($rate) }})"><i class="ph ph-pencil me-2"></i> Edit rate</a></li>
                                            <li><a class="dropdown-item f-s-14 text-danger" onclick="deleteRate({{ $rate->id }})"><i class="ph ph-trash me-2"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="ph ph-receipt f-s-24 d-block mb-1"></i>
                                    No rates added. Customers in this zone won't be able to checkout.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-link text-decoration-none f-w-600 f-s-14 mt-3 p-0" onclick="openRateModal({{ $zone->id }})">+ Add rate</button>
            </div>
        </div>
        @empty
        <div class="p-5 text-center text-muted">
            <i class="ph ph-map-pin f-s-48 mb-3 d-block opacity-25"></i>
            <h6 class="text-dark f-w-700">No shipping zones</h6>
            <p class="mb-4">Create a shipping zone to start adding rates.</p>
            <button class="btn btn-shopify-primary" onclick="openZoneModal()">Create zone</button>
        </div>
        @endforelse
    </div>
</div>

<!-- Zone Modal -->
<div class="modal fade" id="zoneModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="zoneForm" class="modal-content" style="border-radius: 12px;">
            @csrf
            <input type="hidden" name="id" id="zoneId">
            <input type="hidden" name="profile_id" value="{{ $profile->id }}">
            <div class="modal-header">
                <h5 class="modal-title f-w-700">Create shipping zone</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-4 border-bottom">
                    <label class="form-label f-w-600 f-s-14">Zone name</label>
                    <input type="text" name="name" id="zoneName" class="form-control" placeholder="e.g. Domestic, International" required>
                    <small class="text-muted">Customers won't see this.</small>
                </div>
                <div class="p-4">
                    <label class="form-label f-w-600 f-s-14">Countries/regions</label>
                    <div class="position-relative mb-3">
                        <input type="text" class="form-control ps-5" id="countrySearch" placeholder="Search countries">
                        <i class="ph ph-magnifying-glass position-absolute" style="left: 15px; top: 12px; color: #5c5f62;"></i>
                    </div>
                    <div class="country-list border rounded" style="max-height: 350px; overflow-y: auto;">
                        @foreach($countries as $country)
                        <div class="form-check p-2 px-4 border-bottom country-item m-0 hover-bg">
                            <input class="form-check-input ms-0 me-3" type="checkbox" name="countries[]" value="{{ $country->id }}" id="country-{{ $country->id }}">
                            <label class="form-check-label d-flex align-items-center f-s-14" for="country-{{ $country->id }}">
                                <img src="{{ asset('flags/'.$country->flag) }}" class="country-flag"> {{ $country->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-shopify-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-shopify-primary">Done</button>
            </div>
        </form>
    </div>
</div>

<!-- Rate Modal -->
<div class="modal fade" id="rateModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="rateForm" class="modal-content" style="border-radius: 12px;">
            @csrf
            <input type="hidden" name="id" id="rateId">
            <input type="hidden" name="zone_id" id="rateZoneId">
            <div class="modal-header">
                <h5 class="modal-title f-w-700">Add rate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div class="mb-4">
                    <label class="form-label f-w-600 f-s-14">Rate name</label>
                    <input type="text" name="name" id="rateName" class="form-control f-s-14" placeholder="Standard, Express, etc." required>
                </div>
                <div class="mb-4">
                    <label class="form-label f-w-600 f-s-14">Price</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">$</span>
                        <input type="number" step="0.01" name="price" id="ratePrice" class="form-control f-s-14" placeholder="0.00">
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="isFree" onchange="document.getElementById('ratePrice').value = this.checked ? 0 : ''">
                        <label class="form-check-label f-s-14" for="isFree">Free shipping</label>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <a href="javascript:void(0)" class="text-primary text-decoration-none f-s-14 f-w-600" onclick="toggleConditions()">+ Add conditions</a>
                </div>
                <div id="conditionsSection" style="display: none;">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="type" value="flat" id="typeFlat" checked onchange="toggleConditionFields()">
                        <label class="form-check-label f-s-14" for="typeFlat">None</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="type" value="weight" id="typeWeight" onchange="toggleConditionFields()">
                        <label class="form-check-label f-s-14" for="typeWeight">Based on item weight</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="type" value="price" id="typePrice" onchange="toggleConditionFields()">
                        <label class="form-check-label f-s-14" for="typePrice">Based on order price</label>
                    </div>

                    <div id="conditionFields" class="row g-2 mt-2" style="display: none;">
                        <div class="col-6">
                            <label class="form-label f-s-12 mb-1">Minimum <span class="condition-unit"></span></label>
                            <input type="number" step="0.01" name="min_value" id="rateMin" class="form-control form-control-sm" placeholder="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label f-s-12 mb-1">Maximum <span class="condition-unit"></span></label>
                            <input type="number" step="0.01" name="max_value" id="rateMax" class="form-control form-control-sm" placeholder="No limit">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-shopify-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-shopify-primary">Done</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts:after')
<script>
    const zoneModal = new bootstrap.Modal(document.getElementById('zoneModal'));
    const rateModal = new bootstrap.Modal(document.getElementById('rateModal'));

    function openZoneModal() {
        document.getElementById('zoneForm').reset();
        document.getElementById('zoneId').value = '';
        document.querySelector('#zoneModal .modal-title').innerText = 'Create shipping zone';
        zoneModal.show();
    }

    function editZone(id, name, countryIds) {
        document.getElementById('zoneForm').reset();
        document.getElementById('zoneId').value = id;
        document.getElementById('zoneName').value = name;
        countryIds.forEach(cid => {
            const el = document.getElementById('country-' + cid);
            if (el) el.checked = true;
        });
        document.querySelector('#zoneModal .modal-title').innerText = 'Edit shipping zone';
        zoneModal.show();
    }

    function openRateModal(zoneId) {
        document.getElementById('rateForm').reset();
        document.getElementById('rateId').value = '';
        document.getElementById('rateZoneId').value = zoneId;
        document.querySelector('#rateModal .modal-title').innerText = 'Add rate';
        rateModal.show();
    }

    function editRate(zoneId, rate) {
        document.getElementById('rateForm').reset();
        document.getElementById('rateId').value = rate.id;
        document.getElementById('rateZoneId').value = zoneId;
        document.getElementById('rateName').value = rate.name;
        document.getElementById('ratePrice').value = rate.price;
        if (rate.price == 0) document.getElementById('isFree').checked = true;
        
        // Conditions
        if (rate.type != 'flat') {
            document.getElementById('conditionsSection').style.display = 'block';
            document.getElementById('rateMin').value = rate.min_value;
            document.getElementById('rateMax').value = rate.max_value;
            if (rate.type == 'weight') document.getElementById('typeWeight').checked = true;
            if (rate.type == 'price') document.getElementById('typePrice').checked = true;
            toggleConditionFields();
        }

        document.querySelector('#rateModal .modal-title').innerText = 'Edit rate';
        rateModal.show();
    }

    function toggleConditionFields() {
        const type = document.querySelector('input[name="type"]:checked').value;
        const fields = document.getElementById('conditionFields');
        const units = document.querySelectorAll('.condition-unit');
        
        if (type === 'flat') {
            fields.style.display = 'none';
        } else {
            fields.style.display = 'flex';
            units.forEach(u => u.innerText = type === 'weight' ? '(kg)' : '($)');
        }
    }

    function toggleConditions() {
        const sec = document.getElementById('conditionsSection');
        sec.style.display = sec.style.display === 'none' ? 'block' : 'none';
    }

    // Search countries
    document.getElementById('countrySearch').addEventListener('keyup', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.country-item').forEach(item => {
            const text = item.innerText.toLowerCase();
            item.style.display = text.includes(term) ? 'block' : 'none';
        });
    });

    // Form Submissions
    document.getElementById('zoneForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('{{ route("admin.settings.shipping.zone.save") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });

    document.getElementById('rateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('{{ route("admin.settings.shipping.rate.save") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });

    function deleteZone(id) {
        if (confirm('Are you sure you want to delete this zone? All rates in this zone will be deleted.')) {
            fetch(`/management/settings/shipping/zone/${id}`, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest' 
                }
            }).then(() => location.reload());
        }
    }

    function deleteRate(id) {
        if (confirm('Are you sure you want to delete this rate?')) {
            fetch(`/management/settings/shipping/rate/${id}`, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest' 
                }
            }).then(() => location.reload());
        }
    }
</script>
@endpush
