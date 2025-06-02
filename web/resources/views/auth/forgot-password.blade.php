<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
     @vite('resources/css/app.css')
</head>
<body>
<div class="auth-container">
    <h1>Forgot Password</h1>

    @if(session('success'))
        <div class="toast-message show" style="background-color:#4ade80;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="toast-message show" style="background-color:#f87171;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.sendOtp') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" required>
        </div>
        <button type="submit" class="form-button">Send OTP</button>
        <div class="form-footer">
            <a href="{{ route('login') }}">Back to login</a>
        </div>
    </form>
</div>
</body>
</html>
