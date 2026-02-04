@extends('admin.layouts.app')

@push('styles:after')
<style>
    .settings-sidebar-container {
        background: #ffffff;
        border-right: 1px solid #dee2e6;
        padding-top: 20px;
        border-radius: 12px;
    }
    .settings-content-container {
        padding: 30px;
        background: #f4f7f6;
    }
    .settings-nav-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: #495057;
        text-decoration: none !important;
        border-radius: 6px;
        margin-bottom: 5px;
        font-size: 14px;
        font-weight: 500;
        transition: 0.2s;
    }
    .settings-nav-item i {
        font-size: 18px;
        margin-right: 12px;
        color: #6c757d;
    }
    .settings-nav-item:hover {
        background: #e9ecef;
        color: #212529;
    }
    .settings-nav-item.active {
        background: #e9ecef;
        color: #1a1c1d;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        font-weight: 600;
    }
    .settings-nav-item.active i {
        color: #1a1c1d;
    }
    
    /* Shopify UI Components */
    .settings-card {
        background: #fff;
        border: 1px solid #e3e8ee;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .settings-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e3e8ee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .settings-card-body {
        padding: 24px;
    }
    
    .btn-shopify-primary {
        background: #1a1c1d;
        color: #fff !important;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
    }
    .btn-shopify-secondary {
        background: #fff;
        color: #1a1c1d !important;
        border: 1px solid #d1d5db;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
    }
    .btn-shopify-secondary:hover {
        background: #f9fafb;
    }
    .country-flag {
        width: 22px;
        margin-right: 12px;
        border-radius: 2px;
    }
    .dropdown-menu {
        min-width: 180px;
        padding: 8px 0;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        border: 1px solid #e5e7eb !important;
    }
    .dropdown-item {
        padding: 8px 16px;
        font-weight: 500;
        color: #374151;
    }
    .dropdown-item i {
        margin-right: 10px;
        color: #6b7280;
    }
    .dropdown-item:hover {
        background-color: #f3f4f6;
        color: #111827;
    }
    .dropdown-item.text-danger:hover {
        background-color: #fee2e2;
        color: #991b1b;
    }
    /* Manual Pagination Styles (Bypassing theme conflicts) */
    .custom-pagination {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 8px !important;
        position: relative !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .custom-pg-btn {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 36px !important;
        height: 36px !important;
        background: #ffffff !important;
        border: 1px solid #dcdfe3 !important;
        border-radius: 8px !important;
        color: #5c5f62 !important;
        text-decoration: none !important;
        font-size: 18px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        line-height: normal !important;
        position: static !important;
        float: none !important;
    }
    .custom-pg-btn:hover:not(.disabled) {
        background: #f6f7f7 !important;
        border-color: #c4cdd5 !important;
        color: #1a1c1d !important;
    }
    .custom-pg-btn.disabled {
        background: #fdfdfd !important;
        color: #d1d4d7 !important;
        cursor: not-allowed !important;
        border-color: #e1e3e5 !important;
    }
</style>
@endpush

@section('content')
<div class="row g-0 flex-nowrap" style="min-height: 100vh;">
    <!-- Settings Sidebar -->
    <div class="col-auto settings-sidebar-container" style="width: 280px; flex: 0 0 280px;">
        <div class="px-4 mb-4">
            <h5 class="f-w-700 mb-4" style="color: #1a1c1d;">Settings</h5>
            <!-- <div class="position-relative">
                <input type="text" class="form-control form-control-sm ps-5" style="border-radius: 8px;" placeholder="Search settings">
                <i class="ph ph-magnifying-glass position-absolute" style="left: 15px; top: 10px; color: #6c757d;"></i>
            </div> -->
        </div>
        
        <div class="px-3">
            <a href="{{ route('admin.settings.index') }}" class="settings-nav-item {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                <i class="ph ph-house"></i> General
            </a>
            <a href="#" class="settings-nav-item">
                <i class="ph ph-identification-card"></i> Plan
            </a>
            <a href="#" class="settings-nav-item">
                <i class="ph ph-credit-card"></i> Billing
            </a>
            <a href="#" class="settings-nav-item">
                <i class="ph ph-users"></i> Users and permissions
            </a>
            <a href="#" class="settings-nav-item">
                <i class="ph ph-currency-circle-dollar"></i> Payments
            </a>
            <a href="#" class="settings-nav-item">
                <i class="ph ph-shopping-bag"></i> Checkout
            </a>
            <a href="{{ route('admin.settings.shipping') }}" class="settings-nav-item {{ request()->routeIs('admin.settings.shipping*') ? 'active' : '' }}">
                <i class="ph ph-truck"></i> Shipping and delivery
            </a>
            <a href="{{ route('admin.settings.tax') }}" class="settings-nav-item {{ request()->routeIs('admin.settings.tax*') ? 'active' : '' }}">
                <i class="ph ph-percentage"></i> Taxes and duties
            </a>
            <a href="#" class="settings-nav-item">
                <i class="ph ph-map-pin"></i> Locations
            </a>
            <a href="#" class="settings-nav-item">
                <i class="ph ph-globe"></i> Markets
            </a>
            <a href="#" class="settings-nav-item">
                <i class="ph ph-app-window"></i> Apps and sales channels
            </a>
            <a href="#" class="settings-nav-item">
                <i class="ph ph-browser"></i> Domains
            </a>
        </div>
    </div>
    
    <!-- Settings Content -->
    <div class="col settings-content-container" style="flex: 1; min-width: 0;">
        <div class="mx-auto" style="max-width: 1080px;">
            @yield('settings-content')
        </div>
    </div>
</div>
@endsection
