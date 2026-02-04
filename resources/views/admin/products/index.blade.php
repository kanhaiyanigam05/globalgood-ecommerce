@extends('admin.layouts.app')
@push('styles:before')
    <!-- Data Table css-->
    <link href="{{ asset('admins/vendor/datatable/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
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
                <div class="card ">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Products</h5>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
                    </div>
                    <div class="card-body p-0">
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
                                        <th>Approved</th>
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
            $('#product-table').DataTable({
                ajax: {
                    url: "{{ route('admin.products.index') }}",
                    data: function (d) {
                        d.vendor_id = new URLSearchParams(window.location.search).get('vendor_id');
                    }
                },
                processing: true,
                serverSide: true,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
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
                        data: 'vendor_name',
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
                        data: 'approval_status',
                        name: 'is_approved'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                drawCallback: function(settings) {
                    // Attach event listener to toggle switches after table draw
                    $('.toggle-status').off('change').on('change', function() {
                        const checkbox = $(this);
                        const statusId = checkbox.attr('id');
                        const productId = statusId.replace('status-', '');

                        const encryptedId = checkbox.data('id');

                        const url = "{{ route('admin.products.status', ':id') }}".replace(':id',
                            encryptedId);

                        $.ajax({
                            url: url,
                            type: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    window.toast.success(response.message);
                                }
                            },
                            error: function(xhr) {
                                checkbox.prop('checked', !checkbox.prop('checked'));
                                window.toast.error('Failed to update status');
                            }
                        });
                    });

                    // Approval toggle
                    $('.toggle-approval').off('change').on('change', function() {
                        const checkbox = $(this);
                        const statusId = checkbox.attr('id');
                        const encryptedId = checkbox.data('id');

                        const url = "{{ route('admin.products.approve', ':id') }}".replace(':id',
                            encryptedId);

                        $.ajax({
                            url: url,
                            type: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    window.toast.success(response.message);
                                }
                            },
                            error: function(xhr) {
                                checkbox.prop('checked', !checkbox.prop('checked'));
                                window.toast.error('Failed to update approval status');
                            }
                        });
                    });
                }
            });
        });
    </script>
@endpush
