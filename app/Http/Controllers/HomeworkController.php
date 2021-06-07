<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Homework;
use App\Models\Lesson;
use App\Models\LessonType;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomeworkController extends Controller
{
    public function show(Homework $homework)
    {
        return view('homeworks.show', compact('homework'));
    }

    public function create(Discipline $discipline)
    {
        return view('homeworks.create', compact('discipline'));
    }

    public function store(Request $request, Discipline $discipline): RedirectResponse
    {
        $request->validate([
            'description' => [
                'required', 'between:10,16386'
            ],
            'ending_at' => [
                'required', 'after:now',
            ],
        ]);

        Homework::query()->create([
            'description' => strip_tags($request->input('description'), [
                'strong', 'i', 'ul', 'ol', 'li', 'h1', 'h2', 'a', 'p', 'blockquote',
            ]),
            'ending_at' => $request->input('ending_at'),
            'discipline_id' => $discipline->id,
        ]);

        return back()->with('message', 'Домашнє завдання успішно додане.');
    }

    public function edit(Homework $homework)
    {
        return view('homeworks.edit', compact('homework'));
    }

    public function update(Request $request, Homework $homework)
    {
        $request->validate([
            'description' => [
                'required', 'between:10,16386'
            ],
            'ending_at' => [
                'required', 'after:now',
            ],
        ]);

        $homework->update([
            'description' => strip_tags($request->input('description'), [
                'strong', 'i', 'ul', 'ol', 'li', 'h1', 'h2', 'a', 'p', 'blockquote',
            ]),
            'ending_at' => $request->input('ending_at'),
        ]);

        return back()->with('message', 'Домашнє завдання успішно оновлене.');
    }

    public function destroy(Homework $homework): RedirectResponse
    {
        try {
            $homework->delete();
            return back();
        } catch (Exception $exception) {
            return back()->with('message', 'Помилка видалення домашнього завдання');
        }
    }
}
