@extends('layouts.app')

@section('title', 'Discount Management')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>Discount Management</h1>
        <p class="text-muted">Manage discount codes and offers</p>
    </div>
    <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add New Discount
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
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Used</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($discounts as $discount)
                        <tr>
                            <td>#{{ $discount->id }}</td>
                            <td>{{ $discount->name }}</td>
                            <td>
                                @if($discount->code)
                                    <code>{{ $discount->code }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($discount->type) }}</span>
                            </td>
                            <td>
                                @if($discount->type === 'percentage')
                                    {{ $discount->value }}%
                                @else
                                    ${{ number_format($discount->value, 2) }}
                                @endif
                            </td>
                            <td>
                                {{ $discount->used_count }} / {{ $discount->max_uses ?? '∞' }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $discount->is_active ? 'success' : 'secondary' }}">
                                    {{ $discount->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this discount?');">
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
                            <td colspan="8" class="text-center">No discounts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        </div>
        @if($discounts->hasPages())
        <div class="card-footer bg-light p-3">
            {{ $discounts->links() }}
        </div>
        @endif
    </div>
@endsection

