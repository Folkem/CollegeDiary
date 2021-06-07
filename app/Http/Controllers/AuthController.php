<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt($validated)) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('auth.failed'),
        ]);
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();

        return back();
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(['status' => __($status)]);
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }

    public function resetPassword(string $token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'between:3,255', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only(['email', 'password', 'password_confirmation', 'token']),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ]);

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect(route('news.index'));
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }
}
