<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function create()
    {
        $groups = Group::all();

        return view('students.create', compact('groups'));
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
            'group_id' => [
                'required', 'numeric',
                Rule::in(Group::all('id')->map(fn($group) => $group->id)->all()),
            ],
        ]);

        User::query()->create([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => Hash::make('password'),
            'role_id' => 4,
            'group_id' => $request->input('group_id'),
        ]);

        return back()->with('message', 'Студент успішно доданий.');
    }

    public function edit(User $student)
    {
        $groups = Group::all();

        return view('students.edit', compact('student', 'groups'));
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required', 'string', 'email', 'between:5,255',
                Rule::unique('users', 'email')->ignore($student->email, 'email'),
            ],
            'name' => [
                'required', 'string', 'between:5,255',
                Rule::unique('users', 'name')->ignore($student->name, 'name'),
            ],
            'group_id' => [
                'required', 'numeric',
                Rule::in(Group::all('id')->map(fn($group) => $group->id)->all()),
            ],
        ]);

        $student->update([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'group_id' => $request->input('group_id'),
        ]);

        return back()->with('message', 'Студент успішно оновлений.');
    }

    public function destroy(User $student): RedirectResponse
    {
        try {
            $student->delete();
            return back();
        } catch (Exception $exception) {
            return back()->with('message', 'Студента не вдалося видалити. Скоріш за все, в нього присутні ' .
                'оцінки. Спочатку видаліть їх');
        }
    }
}
