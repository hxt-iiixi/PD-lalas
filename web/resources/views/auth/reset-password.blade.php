<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth-container">
    <h1>Reset Password</h1>

    @if($errors->any())
        <div class="toast-message show" style="background-color:#f87171;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.reset') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-input" required>
        </div>

        <button type="submit" class="form-button">Update Password</button>
    </form>
</div>
</body>
</html>
