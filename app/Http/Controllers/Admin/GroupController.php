<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Speciality;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    public function create()
    {
        $specialities = Speciality::all();

        return view('groups.create', compact('specialities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'speciality_id' => [
                'required', 'numeric',
                Rule::in(Speciality::all('id')->map(fn($speciality) => $speciality->id)->all()),
            ],
            'year' => [
                'required', 'numeric', 'between:1,9'
            ],
            'group_number' => [
                'required', 'numeric', 'between:1,9'
            ],
        ]);

        if (Group::query()->where($request->only(['speciality_id']))->where($request->only('year'))
            ->where('number', $request->input('group_number'))->exists()) {
            return back()->with('message', 'Така група вже існує');
        }

        Group::query()->create([
            'speciality_id' => $request->input('speciality_id'),
            'year' => $request->input('year'),
            'number' => $request->input('group_number'),
        ]);

        return back()->with('message', 'Група успішно додана.');
    }

    public function edit(Group $group)
    {
        $specialities = Speciality::all();

        return view('groups.edit', compact('group', 'specialities'));
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        $request->validate([
            'speciality_id' => [
                'required', 'numeric',
                Rule::in(Speciality::all('id')->map(fn($speciality) => $speciality->id)->all()),
            ],
            'year' => [
                'required', 'numeric', 'between:1,9'
            ],
            'group_number' => [
                'required', 'numeric', 'between:1,9'
            ],
        ]);

        $existingGroup = Group::query()
            ->where($request->only(['speciality_id']))
            ->where($request->only('year'))
            ->where('number', $request->input('group_number'))
            ->first();

        if (isset($existingGroup) && $existingGroup->id !== $group->id) {
            return back()->with('message', 'Така група вже існує');
        }

        $group->update([
            'speciality_id' => $request->input('speciality_id'),
            'year' => $request->input('year'),
            'number' => $request->input('group_number'),
        ]);

        return back()->with('message', 'Група успішно оновлена.');
    }

    public function destroy(Group $group): RedirectResponse
    {
        try {
            $group->delete();
            return back();
        } catch (Exception $exception) {
            return back()->with('message', 'Групу не вдалося видалити. Скоріш за все, вона використовується в ' .
                'дисциплінах чи розкладах. Спочатку оновіть дисципліни та розклади з нею');
        }
    }
}
