@extends('layouts.app')
@section('title', 'Accounts')

@section('content')
<div class="page-section">
    <h4>Registered Accounts</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Admin</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                <td>
                    @if ($user->is_active)
                        <span style="color: green;">● Online</span>
                    @else
                        <span style="color: gray;">● Offline</span>
                    @endif
                </td>


                <td>
                    @if (!$user->is_admin)
                    <form method="POST" action="{{ route('admin.accounts.delete', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Remove</button>
                    </form>
                    @else
                        <span class="text-muted">Protected</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
