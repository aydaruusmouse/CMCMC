@extends('layouts.app')

@section('title', 'Package Management')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>Package Management</h1>
        <p class="text-muted">Manage health care packages</p>
    </div>
    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add New Package
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Bookings</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>#{{ $package->id }}</td>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->type ?? '-' }}</td>
                            <td>${{ number_format($package->price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $package->is_active ? 'success' : 'secondary' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $package->bookings_count ?? $package->bookings()->count() }}</td>
                            <td>
                                <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this package?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No packages found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        </div>
        @if($packages->hasPages())
        <div class="card-footer bg-light p-3">
            {{ $packages->links() }}
        </div>
        @endif
    </div>
@endsection

