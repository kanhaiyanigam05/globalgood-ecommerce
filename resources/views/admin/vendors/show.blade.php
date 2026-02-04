@extends('admin.layouts.app')

@section('content')
<div class="row">
    <!-- Stats Row -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-2">
                <div class="card bg-light-primary border-primary">
                    <div class="card-body text-center p-3">
                        <h3 class="mb-1">{{ $stats['approved_products'] }}</h3>
                        <p class="text-primary mb-0 f-w-600">Approved Products</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-light-warning border-warning">
                    <div class="card-body text-center p-3">
                        <h3 class="mb-1">{{ $stats['pending_products'] }}</h3>
                        <p class="text-warning mb-0 f-w-600">Pending Products</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-light-danger border-danger">
                    <div class="card-body text-center p-3">
                        <h3 class="mb-1">{{ $stats['rejected_products'] }}</h3>
                        <p class="text-danger mb-0 f-w-600">Rejected Products</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-light-info border-info">
                    <div class="card-body text-center p-3">
                        <h3 class="mb-1">{{ $stats['total_orders'] }}</h3>
                        <p class="text-info mb-0 f-w-600">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-light-success border-success">
                    <div class="card-body text-center p-3">
                        <h3 class="mb-1">${{ number_format($stats['total_earnings'], 2) }}</h3>
                        <p class="text-success mb-0 f-w-600">Total Earnings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-light-dark border-dark">
                    <div class="card-body text-center p-3">
                        <h3 class="mb-1">{{ $stats['total_refunds'] }}</h3>
                        <p class="text-dark mb-0 f-w-600">Refunds Case</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($vendor->profile && $vendor->profile->logo)
                    <img src="{{ asset('storage/' . $vendor->profile->logo) }}" alt="Logo" class="rounded-circle mb-3" width="100" height="100">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                        <i class="ph ph-storefront f-s-40"></i>
                    </div>
                @endif
                <h5 class="mb-1">{{ $vendor->profile->store_name ?? 'N/A' }}</h5>
                <p class="text-muted mb-3">{{ $vendor->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-{{ $vendor->status === 'verified' ? 'success' : ($vendor->status === 'pending' ? 'warning' : 'danger') }}">
                        Ac: {{ ucfirst($vendor->status) }}
                    </span>
                    <span class="badge bg-{{ $vendor->kyc_status === 'verified' ? 'success' : ($vendor->kyc_status === 'pending' ? 'warning' : 'danger') }}">
                        KYC: {{ ucfirst($vendor->kyc_status) }}
                    </span>
                </div>

                <hr>

                <div class="text-start">
                    <p><strong>Legal Name:</strong> {{ $vendor->legal_name }}</p>
                    <p><strong>Phone:</strong> {{ $vendor->phone }}</p>
                    <p><strong>Joined:</strong> {{ $vendor->created_at->format('d M Y') }}</p>
                </div>

                <hr>
                
                <h6 class="text-start mb-3">Account Action</h6>
                <form action="{{ route('admin.vendors.updateStatus', $vendor->id) }}" method="POST">
                    @csrf
                    <div class="mb-3 text-start">
                        <label class="form-label">Account Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $vendor->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ $vendor->status == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="suspended" {{ $vendor->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">KYC Status</label>
                        <select name="kyc_status" class="form-select">
                            <option value="pending" {{ $vendor->kyc_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ $vendor->kyc_status == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ $vendor->kyc_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Bank Accounts -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Bank Accounts</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bank Name</th>
                                <th>Account Number</th>
                                <th>IFSC</th>
                                <th>Holder Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendor->bankAccounts as $bank)
                                <tr>
                                    <td>{{ $bank->bank_name }}</td>
                                    <td>{{ $bank->account_number }}</td>
                                    <td>{{ $bank->ifsc_code }}</td>
                                    <td>{{ $bank->account_holder_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $bank->status === 'verified' ? 'success' : ($bank->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($bank->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($bank->status === 'pending')
                                            <form action="{{ route('admin.vendors.verifyBank', $bank->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="verified">
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.vendors.verifyBank', $bank->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No bank accounts added.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Verification Documents</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Uploaded At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendor->documents as $doc)
                                <tr>
                                    <td>{{ $doc->document_type }}</td>
                                    <td>
                                        <span class="badge bg-{{ $doc->status === 'verified' ? 'success' : ($doc->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $doc->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ asset('storage/' . $doc->document_file) }}" target="_blank" class="btn btn-sm btn-info me-1">View</a>
                                        
                                        @if($doc->status === 'pending')
                                            <form action="{{ route('admin.vendors.verifyDocument', $doc->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="verified">
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.vendors.verifyDocument', $doc->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No documents uploaded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
