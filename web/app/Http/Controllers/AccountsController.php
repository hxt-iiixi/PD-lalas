<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AccountsController extends Controller
{
    public function index()
    {
        $users = User::all();
        $pendingUsers = User::where('is_approved', false)->get();

        return view('admin.accounts.index', compact('users', 'pendingUsers'));
    }

    public function destroy(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Cannot delete another admin.');
        }

        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    // ✅ Add this here
    public function approve(User $user)
    {
        $user->is_approved = true;
        $user->save(); // Make sure you are calling save()
        return back()->with('success', 'User approved successfully.');
    }
    public function reject(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Cannot reject an admin.');
        }

        $user->delete();
        return back()->with('success', 'User has been rejected and deleted.');
    }

}
