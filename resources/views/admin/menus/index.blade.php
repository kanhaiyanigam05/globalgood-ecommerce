@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Menus</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('admin.dashboard') }}">
                            <span>
                                <i class="ph-duotone ph-table f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Menus</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->
    <div class="row">
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Navigation Menus</h5>
                    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm">
                        <i class="ph ph-plus me-1"></i> Add Menu
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive app-scroll">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Handle</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-dark f-w-500">
                                            {{ $menu->name }}
                                        </a>
                                    </td>
                                    <td><code>{{ $menu->handle }}</code></td>
                                    <td>{{ $menu->items_count }}</td>
                                    <td>
                                        <span class="badge {{ $menu->status ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                            {{ $menu->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-light-primary btn-xs">
                                                <i class="ph ph-pencil-simple"></i>
                                            </a>
                                            <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light-danger btn-xs">
                                                    <i class="ph ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-muted mb-0">No menus found. Create your first menu to start organizing your site navigation.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
