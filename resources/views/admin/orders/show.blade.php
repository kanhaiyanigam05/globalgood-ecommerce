@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header start -->
        <div class="row m-1 align-items-center mb-4 order-header-section">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm back-btn"
                        title="Back to orders">
                        <i class="ph ph-arrow-left"></i>
                    </a>
                    <h4 class="main-title mb-0 order-number-title">{{ $order->order_number }}</h4>
                    <span
                        class="badge status-badge badge-{{ $order->order_status === 'open' ? 'primary' : ($order->order_status === 'canceled' ? 'danger' : 'secondary') }}">
                        <i class="ph ph-circle me-1"></i>{{ ucfirst($order->order_status ?? 'Open') }}
                    </span>
                    <span
                        class="badge status-badge badge-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : ($order->payment_status === 'refunded' ? 'danger' : 'secondary')) }}">
                        <i class="ph ph-credit-card me-1"></i>{{ ucfirst($order->payment_status ?? 'Pending') }}
                    </span>
                    <span
                        class="badge status-badge badge-{{ $order->fulfillment_status === 'fulfilled' ? 'success' : 'info' }}">
                        <i class="ph ph-package me-1"></i>{{ ucfirst($order->fulfillment_status ?? 'Unfulfilled') }}
                    </span>
                </div>
                <div class="text-muted small mt-2 order-meta">
                    <i class="ph ph-clock me-1"></i>{{ $order->created_at->format('F j, Y \a\t g:i a') }} from Draft Orders
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                    {{-- <button class="btn btn-outline-secondary btn-sm action-btn">
                        <i class="ph ph-arrow-counter-clockwise me-1"></i>Refund
                    </button> --}}
                    {{-- <button class="btn btn-outline-secondary btn-sm action-btn">
                        <i class="ph ph-package me-1"></i>Return
                    </button> --}}
                    <a href="{{ route('admin.orders.edit', $order->id) }}"
                        class="btn btn-outline-primary btn-sm action-btn">
                        <i class="ph ph-pencil-simple me-1"></i>Edit
                    </a>
                    {{-- <div class="dropdown d-inline-block">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle action-btn" type="button" data-bs-toggle="dropdown">
                            <i class="ph ph-printer me-1"></i>Print
                        </button>
                    </div> --}}
                    {{-- <div class="dropdown d-inline-block">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle action-btn" type="button" data-bs-toggle="dropdown">
                            More actions
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>
        <!-- Header end -->

        <div class="row">
            <div class="col-lg-8">
                <div
                    class="card status-alert-card border-0 mb-4 {{ $order->order_status === 'canceled' ? 'alert-danger' : ($order->order_status === 'archived' ? 'alert-secondary' : ($order->fulfillment_status === 'fulfilled' ? 'alert-success' : 'alert-warning')) }}">
                    <div class="card-body py-3 px-4">
                        <div class="d-flex align-items-center gap-3">
                            @if ($order->order_status === 'canceled')
                                <div class="status-icon-wrapper warning"
                                    style="color: #dc3545; background: rgba(220, 53, 69, 0.15);">
                                    <i class="ph ph-x-circle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block mb-1 status-title">Order Canceled</span>
                                    <div class="small text-dark-emphasis">
                                        This order was canceled. You can view the details or <a
                                            href="{{ route('admin.orders.create') }}"
                                            class="text-decoration-none fw-medium link-primary">create a new order</a>.
                                    </div>
                                </div>
                            @elseif($order->order_status === 'archived')
                                <div class="status-icon-wrapper"
                                    style="color: #6c757d; background: rgba(108, 117, 125, 0.15);">
                                    <i class="ph ph-archive"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block mb-1 status-title">Order Archived</span>
                                    <div class="small text-dark-emphasis">
                                        This order is archived. It is no longer active in your daily workflow.
                                    </div>
                                </div>
                            @elseif($order->fulfillment_status === 'fulfilled')
                                <div class="status-icon-wrapper success">
                                    <i class="ph-fill ph-check-circle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block mb-1 status-title">Fulfilled</span>
                                    <div class="small text-dark-emphasis">
                                        Order created on {{ $order->created_at->format('M j, Y, g:i A') }}. You can view
                                        the order or <a href="{{ route('admin.orders.create') }}"
                                            class="text-decoration-none fw-medium link-primary">create a new order</a>.
                                    </div>
                                </div>
                            @else
                                <div class="status-icon-wrapper warning">
                                    <i class="ph ph-clock"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block mb-1 status-title">Pending Fulfillment</span>
                                    <div class="small text-dark-emphasis">
                                        Order created on {{ $order->created_at->format('M j, Y, g:i A') }}. You can view
                                        the order or <a href="{{ route('admin.orders.create') }}"
                                            class="text-decoration-none fw-medium link-primary">create a new order</a>.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Items -->
                <div class="card modern-card mb-4">
                    <div class="card-header bg-gradient-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span
                                class="badge modern-badge badge-{{ $order->fulfillment_status === 'fulfilled' ? 'success' : 'secondary' }}">
                                <i
                                    class="ph ph-package me-1"></i>{{ ucfirst($order->fulfillment_status ?? 'Unfulfilled') }}
                            </span>
                            <span class="fw-semibold text-dark">Standard Shipping</span>
                        </div>
                        <div class="text-muted small fw-medium">#{{ $order->order_number }}-F1</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table modern-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Product</th>
                                        <th class="text-end">Quantity</th>
                                        <th class="text-end pe-4">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr class="product-item-row">
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="product-image-wrapper">
                                                        @if ($item->product && $item->product->images->count() > 0)
                                                            <img src="{{ route('file.path', ['path' => $item->product->images->first()->file, 'w' => 60, 'h' => 60]) }}"
                                                                alt="{{ $item->name }}" class="product-image">
                                                        @else
                                                            <i class="ph ph-image text-muted f-s-24"></i>
                                                        @endif
                                                    </div>
                                                    <div class="product-info">
                                                        <a href="#" class="product-name">{{ $item->name }}</a>
                                                        <div class="product-meta">
                                                            <i class="ph ph-truck me-1"></i>Requires shipping
                                                        </div>
                                                        <div class="product-price">₹{{ $item->formatted_price }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="quantity-badge">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <span class="item-total">₹{{ $item->formatted_total }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-gradient-light text-end border-top-0">
                        @if ($order->fulfillment_status !== 'fulfilled')
                            <form action="{{ route('admin.orders.fulfill', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm px-4 fulfill-btn">
                                    <i class="ph ph-check-circle me-2"></i>Fulfill items
                                </button>
                            </form>
                        @else
                            <button class="btn btn-success btn-sm px-4 fulfill-btn" disabled>
                                <i class="ph ph-check-circle me-2"></i>Fulfilled
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="card modern-card mb-4">
                    <div class="card-header bg-gradient-light">
                        <div class="d-flex align-items-center gap-2">
                            <span
                                class="badge modern-badge badge-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                <i class="ph ph-credit-card me-1"></i>{{ ucfirst($order->payment_status ?? 'Pending') }}
                            </span>
                            <span class="fw-semibold text-dark">Payment Summary</span>
                        </div>
                    </div>
                    <div class="card-body px-4 py-4">
                        <div class="payment-line-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="line-label">Subtotal</span>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="line-meta">{{ $order->items->count() }}
                                        {{ Str::plural('item', $order->items->count()) }}</span>
                                    <span class="line-value">₹{{ $order->formatted_subtotal }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="payment-line-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="line-label">Discount</span>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="line-meta">—</span>
                                    <span class="line-value">₹{{ $order->formatted_discount_amount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="payment-line-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="line-label">Shipping</span>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="line-meta">Standard (0.0 kg)</span>
                                    <span class="line-value">₹{{ $order->formatted_shipping_amount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="payment-line-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="line-label">Taxes</span>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="line-meta">IGST 18% (Included)</span>
                                    <span class="line-value">₹{{ $order->formatted_tax_amount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="payment-divider"></div>
                        <div class="payment-total">
                            <span class="total-label">Total</span>
                            <span class="total-value">₹{{ $order->formatted_total }}</span>
                        </div>
                        <div class="payment-divider"></div>
                        <div class="payment-paid-section">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ph-fill ph-check-circle text-success f-s-18"></i>
                                    <span class="paid-label">Paid by customer</span>
                                </div>
                                <span class="paid-value">₹{{ $order->formatted_total }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card modern-card mb-4">
                    <div class="card-header bg-gradient-light">
                        <h6 class="mb-0 fw-semibold text-dark">Timeline</h6>
                    </div>
                    <div class="card-body px-4 py-4">
                        <div class="mb-4">
                            <div class="d-flex gap-3">
                                <div class="avatar-circle">
                                    HS
                                </div>
                                <div class="flex-grow-1">
                                    <div class="comment-input-group">
                                        <input type="text" class="form-control comment-input"
                                            placeholder="Leave a comment...">
                                        <button class="btn btn-outline-primary comment-btn" type="button">Post</button>
                                    </div>
                                    <div class="mt-3 d-flex gap-3 comment-actions">
                                        <i class="ph ph-smiley" title="Add emoji"></i>
                                        <i class="ph ph-at" title="Mention"></i>
                                        <i class="ph ph-hash" title="Add tag"></i>
                                        <i class="ph ph-paperclip" title="Attach file"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted small mt-3 ms-5 ps-2 comment-privacy">
                                <i class="ph ph-lock-simple me-1"></i>Only you and other staff can see comments
                            </div>
                        </div>

                        <div class="timeline-wrapper">
                            <div class="timeline-item">
                                <span class="timeline-dot bg-secondary"></span>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <span class="timeline-text">This order was archived.</span>
                                        <span class="timeline-time">February 2, 4:17 PM</span>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <span class="timeline-dot bg-success"></span>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div>
                                            <span class="timeline-text d-block">Payment confirmed.</span>
                                            <div class="timeline-detail">A ₹{{ $order->formatted_total }} INR payment was
                                                processed.</div>
                                        </div>
                                        <span class="timeline-time">January 30, 8:52 PM</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Notes -->
                <div class="card modern-card mb-4">
                    <div class="card-header bg-gradient-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold text-dark">Notes</h6>
                        <button class="btn btn-link btn-sm p-0 edit-icon-btn" title="Edit notes">
                            <i class="ph ph-pencil-simple"></i>
                        </button>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="notes-content">
                            @if ($order->notes)
                                <i class="ph ph-note me-2 text-primary"></i>{{ $order->notes }}
                            @else
                                <em class="text-muted">No notes from customer</em>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer -->
                <div class="card modern-card mb-4">
                    <div class="card-header bg-gradient-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold text-dark">Customer</h6>
                        <button class="btn btn-link btn-sm p-0 edit-icon-btn" title="Remove customer">
                            <i class="ph ph-x"></i>
                        </button>
                    </div>
                    <div class="card-body px-4 py-3">
                        @if ($order->customer)
                            <a href="#" class="customer-name">
                                {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                            </a>
                            <a href="#" class="customer-link">
                                <i class="ph ph-clock-counter-clockwise me-1"></i>Check history
                            </a>

                            <div class="customer-section">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="section-label">CONTACT INFORMATION</div>
                                    <button class="btn btn-link btn-sm p-0 edit-icon-btn" title="Edit contact">
                                        <i class="ph ph-pencil-simple"></i>
                                    </button>
                                </div>
                                <div class="contact-item">
                                    <i
                                        class="ph ph-envelope me-2 text-primary"></i>{{ $order->email ?: 'No email provided' }}
                                </div>
                                <div class="contact-item">
                                    <i class="ph ph-phone me-2 text-primary"></i>{{ $order->phone ?: 'No phone number' }}
                                </div>
                            </div>

                            <div class="customer-section">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="section-label">SHIPPING ADDRESS</div>
                                    <button class="btn btn-link btn-sm p-0 edit-icon-btn" title="Edit address">
                                        <i class="ph ph-pencil-simple"></i>
                                    </button>
                                </div>
                                @if ($order->shipping_address)
                                    <div class="address-line">{{ $order->shipping_address['first_name'] ?? '' }}
                                        {{ $order->shipping_address['last_name'] ?? '' }}</div>
                                    <div class="address-line">{{ $order->shipping_address['address1'] ?? '' }}</div>
                                    @if (!empty($order->shipping_address['address2']))
                                        <div class="address-line">{{ $order->shipping_address['address2'] }}</div>
                                    @endif
                                    <div class="address-line">{{ $order->shipping_address['zip'] ?? '' }}
                                        {{ $order->shipping_address['city'] ?? '' }}
                                        {{ $order->shipping_address['province'] ?? '' }},
                                        {{ $order->shipping_address['country'] ?? '' }}</div>
                                    <div class="contact-item mb-2">
                                        <i
                                            class="ph ph-phone me-2 text-primary"></i>{{ $order->shipping_address['phone'] ?? ($order->shipping_address['tel'] ?? '') }}
                                    </div>
                                @else
                                    <div class="address-line"><em>No shipping address recorded</em></div>
                                @endif
                                @if ($order->shipping_address)
                                    @php
                                        $address = $order->shipping_address;
                                        $query = urlencode(
                                            implode(
                                                ', ',
                                                array_filter([
                                                    $address['address1'] ?? null,
                                                    $address['address2'] ?? null,
                                                    $address['city'] ?? null,
                                                    $address['province'] ?? null,
                                                    $address['zip'] ?? null,
                                                    $address['country'] ?? null,
                                                ]),
                                            ),
                                        );
                                        $mapUrl = "https://www.google.com/maps/search/?api=1&query={$query}";
                                    @endphp
                                    <a href="{{ $mapUrl }}" target="_blank" class="customer-link">
                                        <i class="ph ph-map-pin me-1"></i>View map
                                    </a>
                                @endif
                            </div>

                            <div class="customer-section mb-0">
                                <div class="section-label mb-2">BILLING ADDRESS</div>
                                @if ($order->billing_address)
                                    @if (json_encode($order->shipping_address) === json_encode($order->billing_address))
                                        <div class="address-line"><em>Same as shipping address</em></div>
                                    @else
                                        <div class="address-line">{{ $order->billing_address['first_name'] ?? '' }}
                                            {{ $order->billing_address['last_name'] ?? '' }}</div>
                                        <div class="address-line">{{ $order->billing_address['address1'] ?? '' }}</div>
                                        <div class="address-line">{{ $order->billing_address['zip'] ?? '' }}
                                            {{ $order->billing_address['city'] ?? '' }}
                                            {{ $order->billing_address['province'] ?? '' }},
                                            {{ $order->billing_address['country'] ?? '' }}</div>
                                    @endif
                                @else
                                    <div class="address-line"><em>Same as shipping address</em></div>
                                @endif
                            </div>
                        @else
                            <div class="text-muted small"><em>No customer details available</em></div>
                        @endif
                    </div>
                </div>

                <!-- Conversion Summary -->
                <div class="card modern-card mb-4">
                    <div class="card-header bg-gradient-light">
                        <h6 class="mb-0 fw-semibold text-dark">Conversion summary</h6>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="conversion-item">
                            <i class="ph ph-shopping-cart"></i>
                            <span>This is their <strong>1st order</strong></span>
                        </div>
                        <div class="conversion-item">
                            <i class="ph ph-eye"></i>
                            <span>1st session was direct to your store</span>
                        </div>
                        <div class="conversion-item mb-3">
                            <i class="ph ph-monitor"></i>
                            <span><strong>1 session</strong> over 1 day</span>
                        </div>
                        <a href="#" class="conversion-link">
                            View conversion details <i class="ph ph-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Order Risk -->
                <div class="card modern-card mb-4">
                    <div class="card-header bg-gradient-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold text-dark">Order risk</h6>
                        <i class="ph ph-shield-check text-success f-s-22"></i>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="risk-progress-wrapper">
                            <div class="risk-progress-bar">
                                <div class="risk-progress-fill" style="width: 20%;"></div>
                            </div>
                        </div>
                        <div class="risk-labels">
                            <span class="risk-label active">● Low</span>
                            <span class="risk-label">● Medium</span>
                            <span class="risk-label">● High</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles:after')
    <style>
        /* ===== Header Section ===== */
        .order-header-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 1.5rem 1rem !important;
            margin-bottom: 1.5rem !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .order-number-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: -0.5px;
        }

        .order-meta {
            font-size: 0.875rem;
            opacity: 0.85;
        }

        .back-btn {
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .back-btn:hover {
            transform: translateX(-3px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* ===== Status Badges ===== */
        .status-badge {
            padding: 0.45rem 0.85rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            transition: all 0.3s ease;
            animation: fadeInScale 0.4s ease;
        }

        .status-badge i {
            font-size: 0.95rem;
        }

        .badge-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .badge-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .badge-archived {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            border: 1px solid #dee2e6;
        }

        /* ===== Action Buttons ===== */
        .action-btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            border-width: 1.5px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }


        /* ===== Status Alert Card ===== */
        .status-alert-card {
            border-radius: 12px;
            overflow: hidden;
            animation: slideInUp 0.5s ease;
        }

        .status-alert-card.alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid #28a745;
        }

        .status-alert-card.alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 4px solid #ffc107;
        }

        .status-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .status-icon-wrapper.success {
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .status-icon-wrapper.warning {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .status-title {
            font-size: 1.1rem;
            color: #1a1a1a;
        }

        /* ===== Modern Cards ===== */
        .modern-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .modern-card .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.25rem;
        }

        .bg-gradient-light {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .modern-badge {
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
        }

        /* ===== Product Table ===== */
        .modern-table {
            font-size: 0.9375rem;
        }

        .modern-table thead {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .modern-table thead th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            padding: 1rem;
            border: none;
        }

        .product-item-row {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-item-row:hover {
            background: linear-gradient(90deg, rgba(13, 110, 253, 0.02) 0%, rgba(13, 110, 253, 0.05) 100%);
        }

        .product-image-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transition: all 0.3s ease;
        }

        .product-item-row:hover .product-image-wrapper {
            border-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-name {
            font-weight: 600;
            color: #0d6efd;
            text-decoration: none;
            display: block;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
        }

        .product-name:hover {
            color: #0b5ed7;
            text-decoration: underline;
        }

        .product-meta {
            font-size: 0.8125rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .product-price {
            font-size: 0.875rem;
            color: #495057;
            font-weight: 500;
        }

        .quantity-badge {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            padding: 0.35rem 0.85rem;
            border-radius: 6px;
            font-weight: 600;
            color: #495057;
        }

        .item-total {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .fulfill-btn {
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .fulfill-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(13, 110, 253, 0.3);
        }

        /* ===== Payment Summary ===== */
        .payment-line-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .payment-line-item:last-of-type {
            border-bottom: none;
        }

        .line-label {
            font-size: 0.9375rem;
            color: #495057;
            font-weight: 500;
        }

        .line-meta {
            font-size: 0.8125rem;
            color: #6c757d;
            min-width: 120px;
            text-align: right;
        }

        .line-value {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1a1a1a;
            min-width: 100px;
            text-align: right;
        }

        .payment-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #dee2e6 50%, transparent 100%);
            margin: 1rem 0;
        }

        .payment-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .total-label {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .total-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0d6efd;
            letter-spacing: -0.5px;
        }

        .payment-paid-section {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid #c3e6cb;
        }

        .paid-label {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #155724;
        }

        .paid-value {
            font-size: 1.25rem;
            font-weight: 800;
            color: #155724;
        }

        /* ===== Timeline ===== */
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
        }

        .comment-input-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .comment-input {
            flex: 1;
            border-radius: 8px;
            border: 1.5px solid #dee2e6;
            padding: 0.65rem 1rem;
            transition: all 0.3s ease;
        }

        .comment-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        .comment-btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            transition: all 0.3s ease;
        }

        .comment-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .comment-actions {
            font-size: 1.125rem;
            color: #6c757d;
        }

        .comment-actions i {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .comment-actions i:hover {
            color: #0d6efd;
            transform: scale(1.15);
        }

        .comment-privacy {
            opacity: 0.75;
        }

        .timeline-wrapper {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-wrapper::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, #dee2e6 0%, #f8f9fa 100%);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -2.35rem;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            z-index: 1;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 3px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .timeline-content:hover {
            background: #e9ecef;
            border-left-color: #0d6efd;
        }

        .timeline-text {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 0.9375rem;
        }

        .timeline-detail {
            font-size: 0.8125rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .timeline-time {
            font-size: 0.8125rem;
            color: #6c757d;
            white-space: nowrap;
        }

        /* ===== Sidebar Cards ===== */
        .notes-content {
            font-size: 0.9375rem;
            color: #495057;
            line-height: 1.6;
        }

        .edit-icon-btn {
            color: #6c757d;
            transition: all 0.2s ease;
        }

        .edit-icon-btn:hover {
            color: #0d6efd;
            transform: scale(1.1);
        }

        .customer-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: #0d6efd;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
        }

        .customer-name:hover {
            color: #0b5ed7;
            text-decoration: underline;
        }

        .customer-link {
            font-size: 0.875rem;
            color: #0d6efd;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .customer-link:hover {
            color: #0b5ed7;
            text-decoration: underline;
        }

        .customer-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .customer-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .section-label {
            font-size: 0.6875rem;
            font-weight: 800;
            letter-spacing: 1px;
            color: #6c757d;
            text-transform: uppercase;
        }

        .contact-item {
            font-size: 0.9375rem;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .address-line {
            font-size: 0.9375rem;
            color: #495057;
            margin-bottom: 0.35rem;
            line-height: 1.5;
        }

        .conversion-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0;
            font-size: 0.9375rem;
            color: #495057;
        }

        .conversion-item i {
            font-size: 1.25rem;
            color: #0d6efd;
        }

        .conversion-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .conversion-link:hover {
            color: #0b5ed7;
            text-decoration: underline;
        }

        .risk-progress-wrapper {
            margin-bottom: 1rem;
        }

        .risk-progress-bar {
            height: 10px;
            background: linear-gradient(90deg, #e9ecef 0%, #dee2e6 100%);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .risk-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
            border-radius: 10px;
            transition: width 0.6s ease;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.4);
        }

        .risk-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 0.75rem;
        }

        .risk-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .risk-label.active {
            color: #28a745;
        }

        /* ===== Animations ===== */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== Responsive Design ===== */
        @media (max-width: 768px) {
            .order-number-title {
                font-size: 1.25rem;
            }

            .status-badge {
                font-size: 0.75rem;
                padding: 0.35rem 0.65rem;
            }

            .action-btn {
                font-size: 0.875rem;
                padding: 0.45rem 0.85rem;
            }

            .product-image-wrapper {
                width: 50px;
                height: 50px;
            }

            .total-value {
                font-size: 1.25rem;
            }

            .paid-value {
                font-size: 1.1rem;
            }

            .modern-card .card-header {
                padding: 0.875rem 1rem;
            }

            .modern-card .card-body {
                padding: 1rem !important;
            }
        }

        @media (max-width: 576px) {
            .order-header-section {
                padding: 1rem 0.75rem !important;
            }

            .timeline-wrapper {
                padding-left: 1.5rem;
            }

            .timeline-dot {
                left: -1.85rem;
            }
        }
    </style>
@endpush
