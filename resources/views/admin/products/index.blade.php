@extends('admin.layouts.app')
@push('styles:before')
    <!-- Data Table css-->
    <link href="{{ asset('admins/vendor/datatable/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admins/css/custom-table.css') }}" rel="stylesheet" type="text/css">
    <style>
        .app-datatable-default .dataTables_wrapper .dataTables_filter {
    padding: 1rem 1.25rem;
    display: none;
}
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Products</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                            <span>
                                <i class="ph-duotone ph-table f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Products</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Breadcrumb end -->

        <!-- Data Table start -->
        <div class="row">
            <!-- Default Datatable start -->
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <h5 class="f-w-700 text-dark">Product Inventory 123</h5>
                    </div>
                    <div class="card-body px-4">
                        <!-- Top Bar: Search and Actions -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                            <!-- Left: Filter Pills -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="filter-tabs pill-style">
                                    <div class="filter-tab active" data-status="all">All</div>
                                    <div class="filter-tab" data-status="active">Active</div>
                                    <div class="filter-tab" data-status="on_hold">On Hold</div>
                                    <div class="filter-tab" data-status="vendor_only">Vendor Products</div>
                                    <div class="filter-tab" data-status="out_of_stock">Out of Stock</div>
                                </div>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary d-flex align-items-center gap-2 py-2 px-3">
                                    <i class="ph-bold ph-plus f-s-16"></i> <span>Add Product</span>
                                </a>
                            </div>

                            <!-- Right: Search Bar -->
                            <div class="search-container" style="flex: 1; max-width: 320px;">
                                <div id="datatable-search-container"></div>
                            </div>
                        </div>

                        <div class="app-datatable-default overflow-auto">
                            <table class="display app-data-table" id="product-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Vendor</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <!-- <th>Approved</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Default Datatable end -->
        </div>
        <!-- Data Table end -->
    </div>
@endsection
@push('scripts:before')
    <!-- Data Table js-->
    <script src="{{ asset('admins/vendor/datatable/jquery.dataTables.min.js') }}"></script>
@endpush
@push('scripts:after')
    <script>
        $(function() {
            var table = $('#product-table').DataTable({
                ajax: {
                    url: "{{ route('admin.products.index') }}",
                    data: function (d) {
                        d.vendor_id = new URLSearchParams(window.location.search).get('vendor_id');
                        d.status_filter = $('.filter-tab.active').data('status');
                    }
                },
                processing: true,
                serverSide: true,
                dom: '<"top"f>rt<"bottom"ip><"clear">',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search products...",
                    paginate: {
                        next: '<i class="ph ph-caret-right"></i>',
                        previous: '<i class="ph ph-caret-left"></i>'
                    }
                },
                initComplete: function() {
                    // Move the search input to the custom wrapper
                    var searchInput = $('.dataTables_filter input').detach();
                    var searchBox = $('<div class="search-box-container"></div>').append(
                        '<i class="ph ph-magnifying-glass search-icon"></i>'
                    ).append(searchInput);
                    
                    $('#datatable-search-container').empty().append(searchBox);
                    
                    searchInput.addClass('form-control w-100').css({
                        'margin': '0',
                        'padding': '10px 15px 10px 40px',
                        'border-radius': '8px',
                        'border': '1px solid #e2e8f0',
                        'background': '#fff'
                    });
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id',
                        class: 'text-center'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'category',
                        name: 'category.title'
                    },
                    {
                        data: 'vendor',
                        name: 'vendor.legal_name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                drawCallback: function(settings) {
                    // Clickable Status Badge handler
                    $('.toggle-status-btn').off('click').on('click', function() {
                        const btn = $(this);
                        const encryptedId = btn.data('id');
                        const url = "{{ route('admin.products.status') }}";

                        btn.prop('disabled', true).css('opacity', '0.7');

                        $.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            headers: {
                                'Accept': 'application/json'
                            },
                            data: { 
                                _token: '{{ csrf_token() }}',
                                id: encryptedId 
                            },
                            success: function(response) {
                                if (response.success) {
                                    window.toast.success(response.message);
                                    table.ajax.reload(null, false); 
                                } else {
                                    window.toast.error(response.message || 'Failed to update status');
                                    btn.prop('disabled', false).css('opacity', '1');
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                window.toast.error('Failed to update status');
                                btn.prop('disabled', false).css('opacity', '1');
                            }
                        });
                    });

                    // Clickable Approval Badge handler
                    $('.toggle-approval-btn').off('click').on('click', function() {
                        const btn = $(this);
                        const encryptedId = btn.data('id');
                        const url = "{{ route('admin.products.approve', ':id') }}".replace(':id', encryptedId);

                        btn.prop('disabled', true).css('opacity', '0.7');

                        $.ajax({
                            url: url,
                            type: 'PUT',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                if (response.success) {
                                    window.toast.success(response.message);
                                    table.ajax.reload(null, false);
                                }
                            },
                            error: function(xhr) {
                                window.toast.error('Failed to update approval status');
                                btn.prop('disabled', false).css('opacity', '1');
                            }
                        });
                    });
                }
            });

            // Filter Tab Click Event
            $('.filter-tab').on('click', function() {
                $('.filter-tab').removeClass('active');
                $(this).addClass('active');
                table.draw();
            });
        });
    </script>
@endpush
