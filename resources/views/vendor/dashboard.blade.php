@extends('vendor.layouts.app')

@section('content')
    <div class="row">
        <!-- Profile Overview -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            @if($vendor->profile && $vendor->profile->logo)
                                <img src="{{ asset('storage/' . $vendor->profile->logo) }}" alt="Logo" class="rounded-circle" width="60" height="60">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="ph ph-storefront f-s-30"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $vendor->profile->store_name ?? 'Store Setup Pending' }}</h5>
                            <p class="text-muted mb-0">{{ $vendor->legal_name }}</p>
                            <span class="badge bg-{{ $vendor->status === 'verified' ? 'success' : ($vendor->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($vendor->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Status -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Compliance & Verification</h5>
                </div>
                <div class="card-body">
                    @if($vendor->kyc_status !== 'verified')
                        <div class="alert alert-warning">
                            <i class="ph ph-warning-circle me-1"></i>
                            Your account requires verification before you can start selling. Please upload the required documents.
                        </div>
                    @endif

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Document Type</th>
                                    <th>Status</th>
                                    <th>Uploaded At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $uploadedTypes = $vendor->documents->pluck('document_type')->toArray();
                                    $requiredTypes = ['GST', 'PAN', 'ID'];
                                    $allUploaded = count(array_intersect($requiredTypes, $uploadedTypes)) === count($requiredTypes);
                                @endphp
                                @forelse($vendor->documents as $doc)
                                    <tr>
                                        <td>{{ $doc->document_type }}</td>
                                        <td>
                                            <span class="badge bg-{{ $doc->status === 'verified' ? 'success' : ($doc->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($doc->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $doc->created_at->format('d M Y') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-light-primary" onclick="toggleEditDoc('{{ $doc->document_type }}')">
                                                <i class="ph ph-pencil"></i> Update
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No documents uploaded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="upload-form-container" style="{{ $allUploaded ? 'display:none;' : '' }}">
                        <h6 class="mb-3">Upload New Document</h6>
                        <x-forms.form method="POST" action="{{ route('vendor.documents.upload') }}" enctype="multipart/form-data">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <x-forms.select id="document_type" name="document_type" label="Document Type" :options="['GST' => 'GST Certificate', 'PAN' => 'PAN Card', 'ID' => 'Government ID']" required />
                                </div>
                                <div class="col-md-5">
                                    <x-forms.file id="document_file" name="document_file" label="Select File" required :error="$errors->first('document_file')" />
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Upload</button>
                                </div>
                            </div>
                        </x-forms.form>
                    </div>
                </div>
            </div>

            <!-- Bank Accounts -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Bank Account Details</h5>
                    @if($vendor->bankAccounts->isNotEmpty())
                        <button type="button" class="btn btn-sm btn-outline-primary" id="edit-bank-btn" onclick="toggleBankEdit()">
                            <i class="ph ph-pencil"></i> Edit Details
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @php
                        $bankAccount = $vendor->bankAccounts->where('is_primary', true)->first();
                    @endphp

                    @if($bankAccount)
                        <div id="bank-display-section">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Bank Name</label>
                                    <p class="mb-0 fw-bold">{{ $bankAccount->bank_name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Account Holder Name</label>
                                    <p class="mb-0 fw-bold">{{ $bankAccount->account_holder_name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Account Number</label>
                                    <p class="mb-0 fw-bold">{{ $bankAccount->account_number }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">IFSC Code</label>
                                    <p class="mb-0 fw-bold">{{ $bankAccount->ifsc_code }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Verification Status</label>
                                    <br>
                                    <span class="badge bg-{{ $bankAccount->status === 'verified' ? 'success' : ($bankAccount->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($bankAccount->status ?? 'pending') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div id="bank-form-section" style="{{ $bankAccount ? 'display:none;' : '' }}">
                        <x-forms.form method="POST" action="{{ route('vendor.bank_details.update') }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-forms.input id="bank_name" name="bank_name" label="Bank Name" :value="$bankAccount->bank_name ?? ''" required />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input id="account_holder_name" name="account_holder_name" label="Account Holder Name" :value="$bankAccount->account_holder_name ?? ''" required />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input id="account_number" name="account_number" label="Account Number" :value="$bankAccount->account_number ?? ''" required />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input id="ifsc_code" name="ifsc_code" label="IFSC Code" :value="$bankAccount->ifsc_code ?? ''" required />
                                </div>
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Save Bank Details</button>
                                    @if($bankAccount)
                                        <button type="button" class="btn btn-light ms-2" onclick="toggleBankEdit()">Cancel</button>
                                    @endif
                                </div>
                            </div>
                        </x-forms.form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEditDoc(type) {
    const container = document.getElementById('upload-form-container');
    container.style.display = 'block';
    const select = document.getElementById('document_type');
    select.value = type;
    // Highlight the form or scroll to it
    container.scrollIntoView({ behavior: 'smooth' });
}

function toggleBankEdit() {
    const display = document.getElementById('bank-display-section');
    const form = document.getElementById('bank-form-section');
    const btn = document.getElementById('edit-bank-btn');

    if (form.style.display === 'none') {
        form.style.display = 'block';
        if (display) display.style.display = 'none';
        btn.innerHTML = '<i class="ph ph-x"></i> Cancel Edit';
    } else {
        form.style.display = 'none';
        if (display) display.style.display = 'block';
        btn.innerHTML = '<i class="ph ph-pencil"></i> Edit Details';
    }
}
</script>
@endsection