<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="taxRegionsTable">
        <thead class="bg-light">
            <tr class="f-s-12 text-uppercase text-muted">
                <th class="ps-4 py-3">Region</th>
                <th class="py-3">Status</th>
                <th class="py-3 text-end pe-4">Manage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($countries as $country)
            @php $tax = $country->taxSettings->first(); @endphp
            <tr class="region-row" onclick="window.location.href='{{ route('admin.settings.tax.edit', $country->id) }}'" style="cursor: pointer;">
                <td class="ps-4">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('flags/'.$country->flag) }}" class="country-flag me-3">
                        <span class="f-w-600">{{ $country->name }}</span>
                    </div>
                </td>
                <td>
                    @if($tax && $tax->is_active)
                        <span class="badge bg-light-success text-success border-success f-s-11">
                            <i class="ph ph-check-circle me-1"></i> Collecting
                        </span>
                    @else
                        <span class="badge bg-light text-muted border f-s-11">Not collecting</span>
                    @endif
                </td>
                <td class="text-end pe-4">
                    <i class="ph ph-caret-right text-muted"></i>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="p-3 border-top d-flex align-items-center justify-content-between">
    <div class="f-s-13 text-muted">
        Showing {{ $countries->firstItem() }} to {{ $countries->lastItem() }} of {{ $countries->total() }} regions
    </div>
    <div class="custom-pagination">
        @if($countries->onFirstPage())
            <span class="custom-pg-btn disabled"><i class="ph ph-caret-left"></i></span>
        @else
            <a href="{{ $countries->previousPageUrl() }}" class="custom-pg-btn"><i class="ph ph-caret-left"></i></a>
        @endif

        @if($countries->hasMorePages())
            <a href="{{ $countries->nextPageUrl() }}" class="custom-pg-btn"><i class="ph ph-caret-right"></i></a>
        @else
            <span class="custom-pg-btn disabled"><i class="ph ph-caret-right"></i></span>
        @endif
    </div>
</div>
