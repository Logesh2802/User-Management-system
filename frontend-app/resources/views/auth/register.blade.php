@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow rounded-4 p-4" style="width: 100%; max-width: 450px;">
        <h3 class="text-center mb-4">Register</h3>
        <form method="POST" id="registerForm">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Captcha</label>
                <span class="p-3 bg-light border rounded text-center fw-bold mb-2">
                    {{ session('captcha_num1') }} + {{ session('captcha_num2') }}
                </span>
                <input type="text" name="captcha_answer" class="form-control" placeholder="Enter your answer" required>
                <small class="text-danger" id="captcha-error"></small>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-2">Register</button>
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">Already have an account? Login</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#registerForm').on('submit', function (e) {
            e.preventDefault();

            const captchaAnswer = $('input[name="captcha_answer"]').val().trim();
            const num1 = {{ session('captcha_num1') }};
            const num2 = {{ session('captcha_num2') }};
            const expectedAnswer = num1 + num2;

            // Clear previous errors
            $('#captcha-error, #name-error, #email-error, #password-error').text('');
            $('#error-message').addClass('d-none').text('');

            // Client-side captcha validation
            if (parseInt(captchaAnswer) !== expectedAnswer) {
                $('#captcha-error').text('Incorrect answer. Please try again.');
                return;
            }

            $.ajax({
                url: 'http://127.0.0.1:8000/api/register',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    fetch('/store-token', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ token: response.token })
                    }).then(() => {
                        window.location.href = '/dashboard';
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        const errors = xhr.responseJSON.errors;
                        if (errors.name) $('#name-error').text(errors.name[0]);
                        if (errors.email) $('#email-error').text(errors.email[0]);
                        if (errors.password) $('#password-error').text(errors.password[0]);
                        if (errors.captcha_answer) $('#captcha-error').text(errors.captcha_answer[0]);
                    } else {
                        $('#error-message').removeClass('d-none').text(xhr.responseJSON?.message || 'Registration failed.');
                    }
                }
            });
        });
    });
</script>

@endpush

