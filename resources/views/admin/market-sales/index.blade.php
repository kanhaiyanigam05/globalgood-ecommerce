@extends('admin.layouts.app')

@push('styles:after')
<style>
    .sale-card { background: #fff; border-radius: 12px; border: 1px solid #ebebeb; overflow: hidden; }
    .table thead th { background: #f9f9f9; text-transform: uppercase; font-size: 11px; font-weight: 700; color: #6d7175; border-top: none; padding: 12px 20px; }
    .table tbody td { padding: 16px 20px; vertical-align: middle; border-bottom: 1px solid #f1f2f3; }
    .btn-shopify { background: #303030; color: #fff; font-weight: 600; padding: 8px 16px; border-radius: 8px; border: none; }
    .btn-shopify:hover { background: #1a1a1a; color: #fff; }
    .badge-status { padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: 500; }
    .badge-active { background: #e3f9e5; color: #007f5f; }
    .badge-inactive { background: #fff4e5; color: #9a5b13; }
    .status-switch { width: 36px; height: 20px; }
    .btn-action { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; padding: 0; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 f-w-700">Market sales</h4>
            <p class="text-muted mb-0">Manage your scheduled sales and promotions.</p>
        </div>
        <a href="{{ route('admin.market-sales.create') }}" class="btn btn-shopify">Create sale</a>
    </div>

    <div class="sale-card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Sale</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Applied On</th>
                        <th>Duration</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td>
                            <div class="f-w-600 text-dark">{{ $sale->title }}</div>
                            <small class="text-muted">{{ $sale->slug }}</small>
                        </td>
                        <td>
                            @php
                                $encryptedId = Illuminate\Support\Facades\Crypt::encryptString($sale->id);
                            @endphp
                            <x-forms.switch 
                                name="status" 
                                id="status-{{ $sale->id }}" 
                                :value="$sale->status == 'active' ? 1 : 0" 
                                :checked="$sale->status == 'active'" 
                                class="toggle-status"
                                :attributes="new Illuminate\View\ComponentAttributeBag(['data-id' => $encryptedId])"
                            />
                        </td>
                        <td><span class="text-capitalize">{{ $sale->sale_type }}</span></td>
                        <td>
                            @if($sale->sale_type == 'percentage')
                                {{ $sale->sale_value }}%
                            @else
                                ${{ number_format($sale->sale_value, 2) }}
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark text-capitalize">{{ $sale->applied_on }}s</span>
                        </td>
                        <td>
                            <div class="f-s-13">
                                <div><i class="ph ph-calendar-check me-1"></i> {{ $sale->starts_at->format('M d, Y') }}</div>
                                @if($sale->ends_at)
                                    <div class="text-muted"><i class="ph ph-calendar-x me-1"></i> {{ $sale->ends_at->format('M d, Y') }}</div>
                                @else
                                    <div class="text-success">No end date</div>
                                @endif
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.market-sales.edit', $sale->id) }}" class="btn btn-light btn-sm rounded-circle btn-action" title="Edit">
                                    <i class="ph ph-pencil-simple f-s-18"></i>
                                </a>
                                <form action="{{ route('admin.market-sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this sale?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle text-danger btn-action" title="Delete">
                                        <i class="ph ph-trash f-s-18"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="mb-3"><i class="ph ph-ticket f-s-48 text-muted"></i></div>
                            <h6>No market sales found</h6>
                            <p class="text-muted">Start by creating your first sale promotion.</p>
                            <a href="{{ route('admin.market-sales.create') }}" class="btn btn-shopify">Create sale</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sales->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts:after')
<script>
    $(document).ready(function() {
        $('.toggle-status').off('change').on('change', function() {
            const checkbox = $(this);
            const id = checkbox.data('id');

            $.ajax({
                url: `/management/market-sales/${id}/status`,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.toast.success('Status updated successfully');
                    }
                },
                error: function() {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    window.toast.error('Failed to update status');
                }
            });
        });
    });
</script>
@endpush
