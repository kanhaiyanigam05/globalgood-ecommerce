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
                <h4 class="main-title">Orders</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                            <span>
                                <i class="ph-duotone ph-table f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Orders</a>
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
                        <h5>Orders</h5>
                        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">Create Order</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="app-datatable-default overflow-auto">
                            <table class="display app-data-table" id="order-table">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Payment</th>
                                        <th>Fulfillment</th>
                                        <th>Total</th>
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
            $('#order-table').DataTable({
                ajax: "{{ route('admin.orders.index') }}",
                processing: true,
                serverSide: true,
                ordering: false,
                columns: [
                    {
                        data: 'order_number',
                        name: 'order_number'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data){
                            return data ? new Date(data).toLocaleDateString() : '';
                        }
                    },
                    {
                        data: 'customer_name',
                        name: 'customer.first_name'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
                    },
                    {
                        data: 'fulfillment_status',
                        name: 'fulfillment_status'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
