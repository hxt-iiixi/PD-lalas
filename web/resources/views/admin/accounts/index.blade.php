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
           
            @endforeach
            </tr>
        </tbody>
    </table>
    <h3 class="section-title">Pending Approvals</h3>
    <table class="table table-bordered table-hover align-middle mt-3">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pendingUsers as $pending)
            <tr>
                <td>{{ $pending->name }}</td>
                <td>{{ $pending->email }}</td>
                <td class="text-center d-flex gap-2 justify-content-center">
                    <form method="POST" action="{{ route('admin.accounts.approve', $pending->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.accounts.reject', $pending->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
