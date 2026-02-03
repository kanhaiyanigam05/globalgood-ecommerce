@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header start -->
        <div class="row m-1 align-items-center mb-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm"><i class="ph ph-arrow-left"></i></a>
                    <h4 class="main-title mb-0">{{ $order->order_number }}</h4>
                    <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning text-dark' : ($order->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                        {{ ucfirst($order->status ?? 'Draft') }}
                    </span>
                    <span class="badge bg-{{ $order->fulfillment_status === 'fulfilled' ? 'success' : 'secondary' }}">
                        {{ ucfirst($order->fulfillment_status ?? 'Unfulfilled') }}
                    </span>
                    <span class="badge bg-light text-dark border">Archived</span>
                </div>
                <div class="text-muted small mt-1">
                    {{ $order->created_at->format('F j, Y \a\t g:i a') }} from Draft Orders
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <div class="btn-group gap-2">
                    <button class="btn btn-outline-secondary btn-sm">Refund</button>
                    <button class="btn btn-outline-secondary btn-sm">Return</button>
                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">Print</button>
                    </div>
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">More actions</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header end -->

        <div class="row">
            <div class="col-lg-8">
                <div class="card bg-{{ $order->fulfillment_status === 'fulfilled' ? 'success' : 'warning' }}-subtle border-{{ $order->fulfillment_status === 'fulfilled' ? 'success' : 'warning' }} mb-4">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center gap-2 text-{{ $order->fulfillment_status === 'fulfilled' ? 'success' : 'warning' }}">
                            @if($order->fulfillment_status === 'fulfilled')
                                <i class="ph-fill ph-check-circle f-s-20"></i>
                                <span class="fw-medium">Fulfilled</span>
                            @else
                                <i class="ph ph-clock f-s-20"></i>
                                <span class="fw-medium">Pending Fulfillment</span>
                            @endif
                        </div>
                        <div class="mt-2 small text-dark">
                            Order created on {{ $order->created_at->format('M j, Y, g:i A') }}. You can view the order or <a href="{{ route('admin.orders.create') }}" class="text-decoration-underline">create a new order</a>.
                        </div>
                    </div>
                </div>

                <!-- Product Items -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-{{ $order->fulfillment_status === 'fulfilled' ? 'success' : 'secondary' }} f-s-12 p-1 px-2">
                                <i class="ph ph-package"></i> {{ ucfirst($order->fulfillment_status ?? 'Unfulfilled') }}
                            </span>
                            <span class="fw-medium">Standard Shipping</span>
                        </div>
                        <div class="text-muted small">#{{ $order->order_number }}-F1</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Product</th>
                                        <th class="text-end">Quantity</th>
                                        <th class="text-end pe-3">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border: 1px solid #eee;">
                                                    @if($item->product && $item->product->images->count() > 0)
                                                        <img src="{{ $item->product->images->first()->file_url }}" alt="" class="img-fluid rounded">
                                                    @else
                                                        <i class="ph ph-image text-muted f-s-20"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="#" class="fw-medium text-primary d-block">{{ $item->name }}</a>
                                                    <span class="text-muted small">Requires shipping</span>
                                                    <div class="text-muted small">₹{{ $item->formatted_price }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">{{ $item->quantity }}</td>
                                        <td class="text-end pe-3">₹{{ $item->formatted_total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-end">
                        <button class="btn btn-primary btn-sm">Fulfill items</button>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-subtle text-success p-1 px-2"><i class="ph ph-credit-card"></i> Paid</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>{{ $order->items->count() }} item</span>
                            <span>₹{{ $order->formatted_subtotal }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Discount</span>
                            <span class="text-muted">—</span>
                            <span>₹{{ $order->formatted_discount_amount }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping</span>
                            <span class="text-muted">Standard (0.0 kg)</span>
                            <span>₹{{ $order->formatted_shipping_amount }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Taxes</span>
                            <span class="text-muted">IGST 18% (Included)</span>
                            <span>₹{{ $order->formatted_tax_amount }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold f-s-16">
                            <span>Total</span>
                            <span>₹{{ $order->formatted_total }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Paid by customer</span>
                            <span class="fw-medium">₹{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Timeline</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex gap-2">
                                <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    HS
                                </div>
                                <div class="flex-grow-1">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Leave a comment...">
                                        <button class="btn btn-outline-secondary" type="button">Post</button>
                                    </div>
                                    <div class="mt-2 d-flex gap-2 text-muted">
                                        <i class="ph ph-smiley"></i>
                                        <i class="ph ph-at"></i>
                                        <i class="ph ph-hash"></i>
                                        <i class="ph ph-paperclip"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted small mt-2 ms-5">Only you and other staff can see comments</div>
                        </div>

                        <div class="timeline ps-4 position-relative">
                             <div class="timeline-item position-relative pb-4 ps-4 border-start">
                                <span class="position-absolute start-0 top-0 translate-middle p-1 bg-secondary rounded-circle ms-1 mt-1"></span>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-medium">This order was archived.</span>
                                    <span class="text-muted small">February 2, 4:17 PM</span>
                                </div>
                             </div>
                             <div class="timeline-item position-relative pb-4 ps-4 border-start">
                                <span class="position-absolute start-0 top-0 translate-middle p-1 bg-secondary rounded-circle ms-1 mt-1"></span>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-medium">Payment confirmed.</span>
                                    <span class="text-muted small">January 30, 8:52 PM</span>
                                </div>
                                <div class="text-muted small mt-1">A ₹{{ $order->formatted_total }} INR payment was processed.</div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Notes -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Notes</h6>
                        <button class="btn btn-link btn-sm p-0 text-muted"><i class="ph ph-pencil-simple"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="text-muted small">{{ $order->notes ?: 'No notes from customer' }}</div>
                    </div>
                </div>

                <!-- Customer -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Customer</h6>
                        <button class="btn btn-link btn-sm p-0 text-muted"><i class="ph ph-x"></i></button>
                    </div>
                    <div class="card-body">
                        @if($order->customer)
                            <a href="#" class="fw-medium d-block text-primary mb-1">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</a>
                            <a href="#" class="small text-decoration-underline mb-3 d-block">Check history</a>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h7 class="mb-0 f-s-12 fw-semibold text-muted">CONTACT INFORMATION</h7>
                                <button class="btn btn-link btn-sm p-0 text-muted"><i class="ph ph-pencil-simple"></i></button>
                            </div>
                            <div class="small mb-1">{{ $order->email ?: 'No email provided' }}</div>
                            <div class="small mb-3">{{ $order->phone ?: 'No phone number' }}</div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h7 class="mb-0 f-s-12 fw-semibold text-muted">SHIPPING ADDRESS</h7>
                                <button class="btn btn-link btn-sm p-0 text-muted"><i class="ph ph-pencil-simple"></i></button>
                            </div>
                            @if($order->shipping_address)
                                <div class="small text-muted mb-1">{{ $order->shipping_address['first_name'] ?? '' }} {{ $order->shipping_address['last_name'] ?? '' }}</div>
                                <div class="small text-muted mb-1">{{ $order->shipping_address['address1'] ?? '' }}</div>
                                @if(!empty($order->shipping_address['address2']))
                                    <div class="small text-muted mb-1">{{ $order->shipping_address['address2'] }}</div>
                                @endif
                                <div class="small text-muted mb-1">{{ $order->shipping_address['zip'] ?? '' }} {{ $order->shipping_address['city'] ?? '' }} {{ $order->shipping_address['province'] ?? '' }}, {{ $order->shipping_address['country'] ?? '' }}</div>
                                <div class="small text-muted mb-2">{{ $order->shipping_address['phone'] ?? $order->shipping_address['tel'] ?? '' }}</div>
                            @else
                                <div class="small text-muted mb-3">No shipping address recorded</div>
                            @endif
                            <a href="#" class="small text-primary text-decoration-none">View map</a>

                            <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                                <h7 class="mb-0 f-s-12 fw-semibold text-muted">BILLING ADDRESS</h7>
                            </div>
                            @if($order->billing_address)
                                @if(json_encode($order->shipping_address) === json_encode($order->billing_address))
                                    <div class="small text-muted">Same as shipping address</div>
                                @else
                                    <div class="small text-muted mb-1">{{ $order->billing_address['first_name'] ?? '' }} {{ $order->billing_address['last_name'] ?? '' }}</div>
                                    <div class="small text-muted mb-1">{{ $order->billing_address['address1'] ?? '' }}</div>
                                    <div class="small text-muted mb-1">{{ $order->billing_address['zip'] ?? '' }} {{ $order->billing_address['city'] ?? '' }} {{ $order->billing_address['province'] ?? '' }}, {{ $order->billing_address['country'] ?? '' }}</div>
                                @endif
                            @else
                                <div class="small text-muted">Same as shipping address</div>
                            @endif
                        @else
                            <div class="text-muted small">No customer details available</div>
                        @endif
                    </div>
                </div>

                <!-- Conversion Summary -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Conversion summary</h6>
                    </div>
                    <div class="card-body small">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="ph ph-shopping-cart"></i> This is their 1st order
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="ph ph-eye"></i> 1st session was direct to your store
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="ph ph-monitor"></i> 1 session over 1 day
                        </div>
                        <a href="#" class="text-primary text-decoration-none fw-medium">View conversion details</a>
                    </div>
                </div>

                <!-- Order Risk -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Order risk</h6>
                        <i class="ph ph-shield-check"></i>
                    </div>
                    <div class="card-body">
                         <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 20%"></div>
                        </div>
                        <div class="d-flex justify-content-between f-s-12 fw-semibold mt-1">
                            <span class="text-success">Low</span>
                            <span class="text-muted">Medium</span>
                            <span class="text-muted">High</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles:after')
<style>
    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 1px;
        background: #e9ecef;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .progress-bar {
        border-radius: 10px;
    }
</style>
@endpush
