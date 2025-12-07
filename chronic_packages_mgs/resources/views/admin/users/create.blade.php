@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="page-header">
    <h1>Create New User</h1>
    <p class="text-muted">Add a new user to the system</p>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">Password *</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="role" class="form-label">Role *</label>
                    <select class="form-select" id="role" name="role" required onchange="toggleRoleFields()">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                        <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                        <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Doctor Fields -->
            <div id="doctorFields" style="display: none;">
                <h5 class="mt-4 mb-3">Doctor Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="specialty" class="form-label">Specialty *</label>
                        <input type="text" class="form-control" id="specialty" name="specialty" value="{{ old('specialty') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="license_number" class="form-label">License Number</label>
                        <input type="text" class="form-control" id="license_number" name="license_number" value="{{ old('license_number') }}">
                    </div>
                </div>
            </div>

            <!-- Agent Fields -->
            <div id="agentFields" style="display: none;">
                <h5 class="mt-4 mb-3">Agent Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="employee_id" class="form-label">Employee ID</label>
                        <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ old('employee_id') }}">
                    </div>
                </div>
            </div>

            <!-- Patient Fields -->
            <div id="patientFields" style="display: none;">
                <h5 class="mt-4 mb-3">Patient Information</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="city" class="form-label">City *</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="village" class="form-label">Village *</label>
                        <input type="text" class="form-control" id="village" name="village" value="{{ old('village') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="age" class="form-label">Age *</label>
                        <input type="number" class="form-control" id="age" name="age" value="{{ old('age') }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleRoleFields() {
    const role = document.getElementById('role').value;
    document.getElementById('doctorFields').style.display = role === 'doctor' ? 'block' : 'none';
    document.getElementById('agentFields').style.display = role === 'agent' ? 'block' : 'none';
    document.getElementById('patientFields').style.display = role === 'patient' ? 'block' : 'none';
}
// Initialize on page load
if (document.getElementById('role').value) {
    toggleRoleFields();
}
</script>
@endsection

