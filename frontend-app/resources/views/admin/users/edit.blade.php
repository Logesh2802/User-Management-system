@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Edit User</h2>

    <div id="update-message"></div>
    <form id="updateProfileForm" method="PUT">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $user['name'] }}" class="form-control" required>
             <small class="text-danger" id='name-error'></small>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user['email'] }}" class="form-control" required>
             <small class="text-danger" id='email-error'></small>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="user" {{ $user['role'] === 'user' ? 'selected' : '' }}>User</option>
                <option value="manager" {{ $user['role'] === 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="admin" {{ $user['role'] === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <small class="text-danger" id='role-error'></small>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection

@push ('scripts')
<script>
    $('#updateProfileForm').on('submit', function(e) {
        e.preventDefault();

        const token = "{{ session('token') }}"; // Laravel session-stored token

        $.ajax({
            url: 'http://127.0.0.1:8000/api/admin/users/{{ $user["id"] }}',
            type: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            data: $(this).serialize(),
            success: function(res) {
                $('#update-message').removeClass('d-none bg-danger text-white').addClass('bg-success text-white').text(res.message);
                $('#name-error, #email-error, #password-error').text('');
                setTimeout(() => {
                    $('#update-message').addClass('d-none')
                }, 3000);
            },
            error: function(xhr) {
                $('#update-message').removeClass('d-none bg-success text-white').addClass('bg-danger text-white').text('Update failed.');
                setTimeout(() => {
                    $('#update-message').addClass('d-none')
                }, 3000);
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    $('#name-error').text(errors.name ?? '');
                    $('#email-error').text(errors.email ?? '');
                    $('#role-error').text(errors.role ?? '');
                   
                }
            }
        });
    });
</script>
@endpush