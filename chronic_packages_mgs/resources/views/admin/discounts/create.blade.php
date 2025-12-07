@extends('layouts.app')

@section('title', 'Create Discount')

@section('content')
<div class="page-header">
    <h1>Create New Discount</h1>
    <p class="text-muted">Add a new discount code or offer</p>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.discounts.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Discount Name *</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Discount Code (optional)</label>
                <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" placeholder="Leave blank for automatic">
                <small class="text-muted">If left blank, no code will be required</small>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="type" class="form-label">Discount Type *</label>
                    <select class="form-select" id="type" name="type" required onchange="updateValueLabel()">
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="value" class="form-label" id="valueLabel">Discount Value *</label>
                    <input type="number" step="0.01" class="form-control" id="value" name="value" value="{{ old('value') }}" required>
                    <small class="text-muted" id="valueHelp">Enter percentage (0-100) or fixed amount</small>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="max_uses" class="form-label">Maximum Uses</label>
                    <input type="number" class="form-control" id="max_uses" name="max_uses" value="{{ old('max_uses') }}" min="1">
                    <small class="text-muted">Leave blank for unlimited</small>
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Discount</button>
            </div>
        </form>
    </div>
</div>

<script>
function updateValueLabel() {
    const type = document.getElementById('type').value;
    const label = document.getElementById('valueLabel');
    const help = document.getElementById('valueHelp');
    if (type === 'percentage') {
        label.textContent = 'Discount Percentage (%) *';
        help.textContent = 'Enter percentage value (e.g., 10 for 10%)';
    } else {
        label.textContent = 'Discount Amount ($) *';
        help.textContent = 'Enter fixed dollar amount';
    }
}
updateValueLabel();
</script>
@endsection

