<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\Validator;

class CabinetController extends Controller
{
    public function index()
    {
        return view('cabinet.index');
    }

    public function notices()
    {
//        $notices = auth()->user()->notices;
//        return view('cabinet.notices', compact('notices'));

        return view('cabinet.notices');
    }

    public function updatePassword(Request $request, ViewErrorBag $b): RedirectResponse
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|between:3,255|string|confirmed'
        ]);

        if (!Hash::check($request->input('old_password'), auth()->user()->getAuthPassword())) {
            return back()->withErrors([
                'old_password' => 'Неправильний попередній пароль.',
            ]);
        }

        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('message', 'Пароль успішно оновлено.');
    }
}
