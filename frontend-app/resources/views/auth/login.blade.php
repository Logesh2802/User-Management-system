@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow rounded-4 p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Login</h3>
        <form id="loginForm" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" required>
                <small class="text-danger" id="email-error"></small>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
                <small class="text-danger" id="password-error"></small>
            </div>

            <div class="mb-3">
                <label class="form-label">Captcha</label>
                <span class="bg-light border rounded text-center p-2 fw-bold mb-2">
                    {{ session('captcha_num1') }} + {{ session('captcha_num2') }}
                </span>
                <input type="text" name="captcha_answer" class="form-control" placeholder="Enter your answer" required>
                <small class="text-danger" id="captcha-error"></small>
            </div>

            <div id="error-message" class="alert alert-danger d-none"></div>

            <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
            <div class="text-center mt-3">
                <a href="{{ route('frontend.register') }}" class="text-decoration-none">Don't have an account? Register</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#loginForm').on('submit', function (e) {
            e.preventDefault();
            const captchaAnswer = $('input[name="captcha_answer"]').val().trim();
            const num1 = {{ session('captcha_num1') }};
            const num2 = {{ session('captcha_num2') }};
            const expectedAnswer = num1 + num2;

            // Clear previous error
            $('#captcha-error').text('');

        if (parseInt(captchaAnswer) !== expectedAnswer) {
            $('#captcha-error').text('Incorrect answer. Please try again.');
            return;
        }
            // Clear errors
            $('#error-message').addClass('d-none').text('');
            $('#email-error, #password-error, #captcha-error').text('');

            $.ajax({
                url: 'http://127.0.0.1:8000/api/login',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    fetch('/store-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // for session security
                    },
                    body: JSON.stringify({ token: response.token })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            window.location.href = '/dashboard';
                        }
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        const errors = xhr.responseJSON.errors;
                        $('#email-error').text(errors.email ? errors.email[0] : '');
                        $('#password-error').text(errors.password ? errors.password[0] : '');
                        $('#captcha-error').text(errors.captcha_answer ? errors.captcha_answer[0] : '');
                    } else {
                        $('#error-message').removeClass('d-none').text(xhr.responseJSON?.message || 'Login failed.');
                    }

                    // Optionally reload CAPTCHA via AJAX
                    // location.reload(); // or refresh CAPTCHA only if implemented
                }
            });
        });
    });
</script>
@endpush
