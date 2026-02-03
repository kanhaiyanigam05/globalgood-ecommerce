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
                <h4 class="main-title">Collections</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                            <span>
                                <i class="ph-duotone ph-table f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Collections</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- Data Table start -->
        <div class="row">
            <div class="col-12">
                <div class="card ">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Collections</h5>
                        <a href="{{ route('admin.collections.create') }}" class="btn btn-primary">Create Collection</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="app-datatable-default overflow-auto">
                            <table class="display app-data-table" id="collection-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
            $('#collection-table').DataTable({
                ajax: "{{ route('admin.collections.index') }}",
                processing: true,
                serverSide: true,
                columns: [
                    { data: 'DT_RowIndex', name: 'id' },
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'type', name: 'type' },
                    { data: 'products_count', name: 'products_count', searchable: false },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush
