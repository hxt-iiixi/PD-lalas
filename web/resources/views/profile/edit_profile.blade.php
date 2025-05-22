@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">

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
        background-color: #4ade80;
        color: #1e293b;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        font-weight: 500;
        text-align: center;
    }

    .toast-error {
        background-color: #f87171;
    }
</style>

<div class="profile-container">
    <h2>Edit Profile</h2>

    @if (session('success'))
        <div class="toast">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="toast toast-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update.custom') }}">
        @csrf

        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>

        <label class="form-label">New Password <small>(optional)</small></label>
        <input type="password" name="password" class="form-input" placeholder="••••••••">

        <label class="form-label">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••">

        <button type="submit" class="form-button">Save Changes</button>
    </form>
</div>
@endsection
