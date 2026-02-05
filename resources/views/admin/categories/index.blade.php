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
                <h4 class="main-title">Categories</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                            <span>
                                <i class="ph-duotone ph-table f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Categories</a>
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
                        <h5>Categories</h5>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add Category</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="app-datatable-default overflow-auto">
                            <table class="display app-data-table" id="category-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Products Count</th>
                                        <th>Children Count</th>
                                        <th>Status</th>
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
            $('#category-table').DataTable({
                ajax: "{{ route('admin.categories.index') }}",
                processing: true,
                serverSide: true,
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'products_count',
                        name: 'products_count'
                    },
                    {
                        data: 'children_count',
                        name: 'children_count'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                drawCallback: function(settings) {
                    // Attach event listener to toggle switches after table draw
                    $('.toggle-status').off('change').on('change', function() {
                        const checkbox = $(this);
                        const encryptedId = checkbox.data('id');
                        const url = "{{ route('admin.categories.status', ':id') }}".replace(
                            ':id', encryptedId);

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
                                // Revert checkbox on error
                                checkbox.prop('checked', !checkbox.prop('checked'));
                                window.toast.error('Failed to update status');
                            }
                        });
                    });
                }
            });
        });
    </script>
@endpush
