<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class ForgotPasswordController extends Controller
{

    public function showForm() {
        return view('auth.forgot-password');
    }

    public function sendOTP(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Session::put('otp_email', $request->email);

        Mail::raw("Your OTP is: $otp", function($message) use ($request) {
            $message->to($request->email)
                    ->subject('Password Reset OTP');
        });

        return redirect()->route('password.verify')->with('success', 'OTP sent to your email.');
    }

    public function showVerifyForm() {
        return view('auth.verify-otp');
    }

    public function verifyOTP(Request $request) {
        $request->validate(['otp' => 'required']);

        if ($request->otp == Session::get('otp')) {
            return view('auth.reset-password'); // show new password form
        }

        return back()->withErrors(['otp' => 'Invalid OTP.']);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'password' => 'required|confirmed|min:8'
        ]);

        $user = User::where('email', Session::get('otp_email'))->first();
        $user->password = Hash::make($request->password);
        $user->save();

        Session::forget(['otp', 'otp_email']);

        return redirect()->route('login')->with('success', 'Password reset successful.');
    }
}
