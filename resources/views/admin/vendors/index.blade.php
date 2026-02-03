@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Vendors</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Store Name</th>
                                <th>Legal Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>KYC Status</th>
                                <th>Joined At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendors as $vendor)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($vendor->profile && $vendor->profile->logo)
                                            <img src="{{ asset('storage/' . $vendor->profile->logo) }}" class="rounded-circle me-2" width="40" height="40" alt="Logo">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-storefront"></i>
                                            </div>
                                        @endif
                                        <span>{{ $vendor->profile->store_name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>{{ $vendor->legal_name }}</td>
                                <td>{{ $vendor->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $vendor->status === 'verified' ? 'success' : ($vendor->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($vendor->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $vendor->kyc_status === 'verified' ? 'success' : ($vendor->kyc_status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($vendor->kyc_status) }}
                                    </span>
                                </td>
                                <td>{{ $vendor->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-sm btn-light-primary">
                                        <i class="ph ph-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.products.index', ['vendor_id' => $vendor->id]) }}" class="btn btn-sm btn-light-info">
                                        <i class="ph ph-package"></i> Products
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No vendors found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $vendors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
