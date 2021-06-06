<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required', 'string', 'email', 'between:5,255',
                Rule::unique('users', 'email'),
            ],
            'name' => [
                'required', 'string', 'between:5,255',
                Rule::unique('users', 'name'),
            ],
        ]);

        User::query()->create([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => Hash::make('password'),
            'role_id' => 3,
        ]);

        return back()->with('message', 'Викладач успішно доданий.');
    }

    public function edit(User $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required', 'string', 'email', 'between:5,255',
                Rule::unique('users', 'email')->ignore($teacher->email, 'email'),
            ],
            'name' => [
                'required', 'string', 'between:5,255',
                Rule::unique('users', 'name')->ignore($teacher->name, 'name'),
            ],
        ]);

        $teacher->update([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'group_id' => $request->input('group_id'),
        ]);

        return back()->with('message', 'Викладач успішно оновлений.');
    }

    public function destroy(User $teacher): RedirectResponse
    {
        $teacher->delete();

        return back();
    }
}
