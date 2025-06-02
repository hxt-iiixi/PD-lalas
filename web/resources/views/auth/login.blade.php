<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>
<body class="auth-body">
    <div class="blur-overlay"></div>
    <div class="auth-wrapper">
        <div class="auth-container">
            <h1>Login</h1>

            @if (session('error'))
                <div class="toast toast-error">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="toast toast-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-input" type="email" name="email" required autofocus>
                </div>

                <div class="form-group" style="position: relative;">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-input" type="password" name="password" required>
                    <button type="button" onclick="togglePassword()" class="show-pass-btn">Show</button>
                </div>

                <div class="form-footer">
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>

                <button type="submit" class="form-button">Login</button>

                <div class="form-footer">
                    <a href="{{ route('register') }}">Don't have an account?</a>
                </div>
            </form>
        </div>
     </div>
    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            const btn = document.querySelector('.show-pass-btn');
            if (pass.type === "password") {
                pass.type = "text";
                btn.textContent = "Hide";
            } else {
                pass.type = "password";
                btn.textContent = "Show";
            }
        }
    </script>

</body>
</html>
