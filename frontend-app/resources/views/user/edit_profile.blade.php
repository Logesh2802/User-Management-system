@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="col-md-6 offset-md-3">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-primary text-white fw-bold">
                ✏️ Edit User
            </div>
            <div class="card-body">
                <div id="update-message"></div>
                <form method="POST" id="updateProfileForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user['name']) }}" required>
                       <small class="text-danger" id='name-error'></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user['email']) }}" required>
                       <small class="text-danger" id='email-error'></small>
                    </div>

                    <div class="d-flex justify-content-between">
                            
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save2 me-1"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push ('scripts')
<script>
    $('#updateProfileForm').on('submit', function(e) {
        e.preventDefault();

        const token = "{{ session('token') }}"; // Laravel session-stored token

        $.ajax({
            url: 'http://127.0.0.1:8000/api/user/profile',
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
                    $('#password-error').text(errors.password ?? '');
                }
            }
        });
    });
</script>
@endpush