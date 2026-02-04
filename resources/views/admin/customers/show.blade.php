@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="main-title mb-0"><a href="{{ route('admin.customers.index') }}" class="text-muted"><i class="ph ph-arrow-left"></i></a> {{ $customer->full_name }}</h4>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            More actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.customers.edit', Crypt::encryptString($customer->id)) }}">Edit Customer</a></li>
                            <li>
                                <form action="{{ route('admin.customers.destroy', Crypt::encryptString($customer->id)) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">Delete Customer</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <div class="row mt-3">
            <div class="col-lg-8">
                <!-- Header Stats -->
                <div class="card mb-3">
                    <div class="card-body p-0">
                        <div class="row g-0 text-center border-bottom">
                            <div class="col-md-3 border-end p-3">
                                <label class="text-muted f-s-12 d-block">Amount spent</label>
                                <span class="f-w-600">${{ number_format($customer->total_spent, 2) }}</span>
                            </div>
                            <div class="col-md-3 border-end p-3">
                                <label class="text-muted f-s-12 d-block">Orders</label>
                                <span class="f-w-600">{{ $customer->total_orders }}</span>
                            </div>
                            <div class="col-md-3 border-end p-3">
                                <label class="text-muted f-s-12 d-block">Customer since</label>
                                <span class="f-w-600">{{ $customer->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="col-md-3 p-3">
                                <label class="text-muted f-s-12 d-block">RFM group</label>
                                <span class="f-w-600">—</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Last Order Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Last order placed</h5>
                    </div>
                    <div class="card-body text-center p-5">
                        <div class="mb-3">
                            <img src="https://cdn-icons-png.flaticon.com/512/1170/1170576.png" width="80" alt="No orders" class="opacity-50">
                        </div>
                        <p class="text-muted">This customer hasn't placed any orders yet</p>
                        <button class="btn btn-primary">Create order</button>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-container">
                            <div class="d-flex gap-3 mb-4">
                                <div class="avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="border rounded p-2">
                                        <input type="text" class="form-control border-0" placeholder="Leave a comment...">
                                        <div class="d-flex justify-content-between align-items-center mt-2 p-1 border-top pt-2">
                                            <div class="d-flex gap-2 text-muted">
                                                <i class="ph ph-smiley"></i>
                                                <i class="ph ph-at"></i>
                                                <i class="ph ph-hash"></i>
                                                <i class="ph ph-paperclip"></i>
                                            </div>
                                            <button class="btn btn-sm btn-light" disabled>Post</button>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">Only you and other staff can see comments</small>
                                </div>
                            </div>

                            <div class="timeline-item d-flex gap-3">
                                <div class="timeline-line bg-light mx-auto" style="width: 2px; height: 100%; position: absolute; left: 19px; z-index: -1;"></div>
                                <div class="timeline-dot bg-secondary rounded-circle" style="width: 10px; height: 10px; margin-left: 15px; margin-top: 5px;"></div>
                                <div class="flex-grow-1 pb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="f-w-500">You created this customer.</span>
                                        <span class="text-muted f-s-12">{{ $customer->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Customer Details Sidebar -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Customer</h5>
                        <i class="ph ph-dots-three-outline"></i>
                    </div>
                    <div class="card-body">
                        <h6 class="f-s-14 f-w-600 mb-2">Contact information</h6>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-1"><a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a> <i class="ph ph-copy text-muted cursor-pointer"></i></li>
                            <li class="mb-1 text-muted">{{ $customer->phone ?: 'No phone number' }}</li>
                            <li class="text-muted">Will receive notifications in {{ $customer->language === 'en' ? 'English' : $customer->language }}</li>
                        </ul>

                        <h6 class="f-s-14 f-w-600 mb-2">Default address</h6>
                        @if($customer->defaultAddress)
                            <address class="text-muted mb-4">
                                {{ $customer->defaultAddress->first_name }} {{ $customer->defaultAddress->last_name }}<br>
                                {{ $customer->defaultAddress->address1 }}<br>
                                {{ $customer->defaultAddress->city }}, {{ $customer->defaultAddress->country }}
                            </address>
                        @else
                            <p class="text-muted mb-4">No default address</p>
                        @endif

                        <h6 class="f-s-14 f-w-600 mb-2">Marketing</h6>
                        <ul class="list-unstyled mb-4">
                            <li class="text-muted"><i class="ph ph-circle f-s-10"></i> {{ $customer->email_marketing_consent ? 'Email subscribed' : 'Email not subscribed' }}</li>
                            <li class="text-muted"><i class="ph ph-circle f-s-10"></i> {{ $customer->sms_marketing_consent ? 'SMS subscribed' : 'SMS not subscribed' }}</li>
                        </ul>

                        <h6 class="f-s-14 f-w-600 mb-2">Tax details</h6>
                        <p class="text-muted mb-0">{{ ucwords($customer->tax_setting) }} tax</p>
                    </div>
                </div>

                <!-- Store Credit Card -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Store credit</h5>
                        <i class="ph ph-pencil"></i>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">${{ number_format($customer->store_credit, 2) }}</p>
                    </div>
                </div>

                <!-- Tags Card -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Tags</h5>
                        <i class="ph ph-pencil"></i>
                    </div>
                    <div class="card-body">
                        @if($customer->tags)
                            @foreach(explode(',', $customer->tags) as $tag)
                                <span class="badge bg-light text-dark border p-2 mb-1">{{ trim($tag) }}</span>
                            @endforeach
                        @else
                            <p class="text-muted mb-0">No tags</p>
                        @endif
                    </div>
                </div>

                <!-- Notes Card -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Notes</h5>
                        <i class="ph ph-pencil"></i>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">{{ $customer->notes ?: 'None' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles:after')
<style>
    .timeline-container {
        position: relative;
    }
    .timeline-dot {
        flex-shrink: 0;
        z-index: 1;
    }
    .timeline-line {
        position: absolute;
        top: 0;
        bottom: 0;
    }
</style>
@endpush
