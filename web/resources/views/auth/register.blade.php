<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600&display=swap');
* {
    box-sizing: border-box;
}
.auth-body {
    margin: 0;
    padding: 0;
    height: 100vh;
    background-image: url('/images/auth-bg.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    overflow: hidden;
    font-family: 'Rubik', sans-serif;
}

/* blur effect on its own layer */
.blur-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    backdrop-filter: blur(6px);
    background-color: rgba(255, 255, 255, 0.2); /* soft tint (optional) */
    z-index: 0;
}

.auth-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    position: relative;
    z-index: 1;
}

.auth-container {
    background: white;
    padding: 32px;
    border-radius: 16px;
    max-width: 420px;
    width: 100%;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    z-index: 2;
}


.auth-container h1 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 24px;
    color: #1e293b;
    text-align: center;
}

/* Form */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    font-weight: 600;
    display: block;
    margin-bottom: 6px;
    color: #1e293b;
}

.form-input {
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    width: 100%;
    font-size: 15px;
    background-color: #f8fafc;
}

.form-input:focus {
    border-color: #8b5e3c;
    outline: none;
}

/* Buttons */
.form-button {
    background-color: #059669;
    color: white;
    padding: 10px;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

.form-button:hover {
    background-color: #047857;
}

/* Footer link */
.form-footer {
    margin-top: 16px;
    margin-bottom: 16px;
    text-align: center;
}

.form-footer a {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
}

.form-footer a:hover {
    text-decoration: underline;
}

/* Toast */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 14px 18px;
    border-radius: 8px;
    font-weight: 500;
    z-index: 9999;
    animation: fadeOut 4s forwards;
}

.toast-success {
    background-color: #d1fae5;
    color: #065f46;
    border-left: 5px solid #10b981;
}

.toast-error {
    background-color: #fee2e2;
    color: #991b1b;
    border-left: 5px solid #ef4444;
}

@keyframes fadeOut {
    0%, 90% { opacity: 1; }
    100% { opacity: 0; display: none; }
}
.show-pass-btn {
    position: absolute;
    top: 38px;
    right: 14px;
    border: none;
    background: transparent;
    color: #3b82f6;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    padding: 0;
}

.show-pass-btn:hover {
    text-decoration: underline;
}


</style>
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
