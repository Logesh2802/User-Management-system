@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-primary" href="#">Add New User</a>

            <div class="d-flex ms-auto">
                <form method="POST" action="{{ route('frontend.logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <form id="createProfileForm" method="POST">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
            <small class="text-danger" id='name-error'></small> 
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
            <small class="text-danger" id='email-error'></small>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
            <small class="text-danger" id='password-error'></small>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="user">User</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>
            <small class="text-danger" id='role-error' ></small>
        </div>

        <button class="btn btn-success">Create</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection

@push ('scripts')
<script>
    $('#createProfileForm').on('submit', function(e) {
        e.preventDefault();

        const token = "{{ session('token') }}"; // Laravel session-stored token

        $.ajax({
            url: 'http://127.0.0.1:8000/api/admin/users',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            data: $(this).serialize(),
            success: function(res) {
                $('#create-message').removeClass('d-none bg-danger text-white').addClass('bg-success text-white').text(res.message);
                $('#name-error, #email-error, #password-error').text('');
                setTimeout(() => {
                    $('#create-message').addClass('d-none')
                }, 3000);
            },
            error: function(xhr) {
                $('#create-message').removeClass('d-none bg-success text-white').addClass('bg-danger text-white').text('Update failed.');
                setTimeout(() => {
                    $('#create-message').addClass('d-none')
                }, 3000);
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    $('#name-error').text(errors.name ?? '');
                    $('#email-error').text(errors.email ?? '');
                    $('#password-error').text(errors.password ?? '');
                    $('#role-error').text(errors.role ?? '');
                   
                }
            }
        });
    });
</script>
@endpush