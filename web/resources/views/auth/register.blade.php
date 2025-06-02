<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>
<body class="auth-body">
    <div class="blur-overlay"></div>
    <div class="auth-wrapper">
        <div class="auth-container">
            <h1>Create Account</h1>

            @if ($errors->any())
                <div class="toast toast-error">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="toast toast-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
                <button class="form-button">Register</button>

                <div class="form-footer">
                    <a href="{{ route('login') }}">Already have an account?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
