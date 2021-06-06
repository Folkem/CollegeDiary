<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use App\Models\Group;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DisciplineController extends Controller
{
    public function create()
    {
        $groups = Group::all();
        $teachers = User::query()->where('role_id', 3)->get();

        return view('disciplines.create', compact('teachers', 'groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => [
                'required', 'string', 'between:5,255',
            ],
            'teacher_id' => [
                'required', 'numeric',
                Rule::in(User::query()->where('role_id', 3)->get()
                    ->map(fn($teacher) => $teacher->id)->all()),
            ],
            'group_id' => [
                'required', 'numeric',
                Rule::in(Group::all('id')->map(fn($group) => $group->id)->all()),
            ],
        ]);

        Discipline::query()->create($validated);

        return back()->with('message', 'Дисципліна успішно додана.');
    }

    public function edit(Discipline $discipline)
    {
        $groups = Group::all();
        $teachers = User::query()->where('role_id', 3)->get();

        return view('disciplines.edit', compact('discipline', 'teachers', 'groups'));
    }

    public function update(Request $request, Discipline $discipline): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => [
                'required', 'string', 'between:5,255',
            ],
            'teacher_id' => [
                'required', 'numeric',
                Rule::in(User::query()->where('role_id', 3)->get()
                    ->map(fn($teacher) => $teacher->id)->all()),
            ],
            'group_id' => [
                'required', 'numeric',
                Rule::in(Group::all('id')->map(fn($group) => $group->id)->all()),
            ],
        ]);

        $discipline->update($validated);

        return back()->with('message', 'Дисципліна успішно оновлена.');
    }

    public function destroy(Discipline $discipline): RedirectResponse
    {
        try {
            $discipline->delete();
            return back();
        } catch (Exception $exception) {
            return back()->with('message', 'Дисципліну не вдалося видалити. Скоріш за все, вона ' .
                'знаходиться в деяких розкладах та заняттях. Спочатку відредагуйте розклади та видаліть заняття');
        }
    }
}
