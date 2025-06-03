@extends('layouts.app')
@section('title', 'Accounts')

@section('content')

<style>
    .section-title {
    font-size: 18px;
    font-weight: 600;
    color: #8b5e3c;
    margin-top: 30px;
    padding-left: 10px;
    border-left: 4px solid #8b5e3c;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px 12px;
    text-align: left;
}

thead {
    background-color: #f9f9f9;
    font-weight: 600;
}
.status-online {
    color: green;
}

.status-offline {
    color: gray;
}

.page-section {
    background: #ffffff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
    margin-bottom: 30px;
}

.page-section h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    padding-left: 10px;
    border-left: 4px solid #8b5e3c;
    color: #8b5e3c;
}
</style>


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
