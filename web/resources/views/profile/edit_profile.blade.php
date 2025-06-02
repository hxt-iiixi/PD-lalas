@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
@vite(['resources/css/app.css', 'resources/js/app.js'])


<style>
    .profile-container {
        max-width: 520px;
        margin: 40px auto;
        background-color: #fff;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        font-family: 'Rubik', sans-serif;
    }

    .profile-container h2 {
        font-weight: 600;
        margin-bottom: 24px;
        text-align: center;
        color: #1e293b;
    }

    .form-label {
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .form-input {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        margin-bottom: 16px;
    }

    .form-button {
        background-color: #059669;
        color: white;
        padding: 10px;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        width: 100%;
        transition: 0.3s ease;
    }

    .form-button:hover {
        background-color: #047857;
    }

   .toast {
        position: fixed;
        bottom: 20px;
        right: 24px;
        background-color: #4ade80;
        color: #1e293b;
        padding: 12px 18px;
        border-radius: 12px;
        font-weight: 600;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        max-width: 300px;
        z-index: 1000;
        opacity: 1;
        animation: fadeOut 4s ease forwards;
    }
    @keyframes fadeOut {
        0%   { opacity: 1; }
        90%  { opacity: 1; }
        100% { opacity: 0; display: none; }
    }

    .toast-error {
        background-color: #f87171;
    }

   .input-wrapper {
        position: relative;
        margin-bottom: 16px;
    }

    .input-wrapper .form-input {
        padding-right: 60px; /* make space for the Show button */
    }

    .show-pass-btn {
        position: absolute;
        top: 50%;
        right: 16px;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #3b82f6;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        padding: 0;
        height: auto;
        line-height: 1;
        padding-bottom: 13px;
    }

</style>

<div class="profile-container">
    <h2>Edit Profile</h2>

    @if (session('success'))
        <div class="toast toast-success show">{{ session('success') }}</div>
    @endif


    @if ($errors->any())
        <div class="toast toast-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update.custom') }}">
        @csrf

        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>

            <label class="form-label">New Password <small>(optional)</small></label>
            <div class="input-wrapper">
                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••">
                <button type="button" onclick="togglePassword('password', this)" class="show-pass-btn">Show</button>
            </div>

            <label class="form-label">Confirm New Password</label>
            <div class="input-wrapper">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••">
                <button type="button" onclick="togglePassword('password_confirmation', this)" class="show-pass-btn">Show</button>
            </div>



        <button type="submit" class="form-button">Save Changes</button>
    </form>
</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        btn.textContent = "Hide";
    } else {
        input.type = "password";
        btn.textContent = "Show";
    }
}
</script>
@endsection
