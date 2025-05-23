<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth-container">
    <h1>Enter OTP</h1>

    @if(session('success'))
        <div class="toast-message show" style="background-color:#4ade80;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="toast-message show" style="background-color:#f87171;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.verifyOtp') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">6-digit OTP</label>
            <input type="text" name="otp" class="form-input" required>
        </div>
        <button type="submit" class="form-button">Verify</button>
    </form>
</div>
</body>
</html>
