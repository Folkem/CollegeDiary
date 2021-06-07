<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Lesson;
use App\Models\LessonType;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LessonController extends Controller
{
    public function show(Lesson $lesson)
    {
        return view('lessons.show', compact('lesson'));
    }

    public function create(Discipline $discipline)
    {
        $types = LessonType::all();

        return view('lessons.create', compact('types', 'discipline'));
    }

    public function store(Request $request, Discipline $discipline): RedirectResponse
    {
        $request->validate([
            'lesson_type_id' => [
                'required', 'numeric',
                Rule::in(LessonType::all()->map(fn($type) => $type->id)),
            ],
            'description' => [
                'required', 'between:10,16386'
            ],
        ]);

        Lesson::query()->create([
            'lesson_type_id' => $request->input('lesson_type_id'),
            'description' => strip_tags($request->input('description'), [
                'strong', 'i', 'ul', 'ol', 'li', 'h1', 'h2', 'a', 'p', 'blockquote',
            ]),
            'discipline_id' => $discipline->id,
        ]);

        return back()->with('message', 'Заняття успішно додане.');
    }

    public function edit(Lesson $lesson)
    {
        $types = LessonType::all();

        return view('lessons.edit', compact('types', 'lesson'));
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $request->validate([
            'lesson_type_id' => [
                'required', 'numeric',
                Rule::in(LessonType::all()->map(fn($type) => $type->id)),
            ],
            'description' => [
                'required', 'between:10,16386'
            ],
        ]);

        $lesson->update([
            'lesson_type_id' => $request->input('lesson_type_id'),
            'description' => strip_tags($request->input('description'), [
                'strong', 'i', 'ul', 'ol', 'li', 'h1', 'h2', 'a', 'p', 'blockquote',
            ]),
        ]);

        return back()->with('message', 'Заняття успішно оновлено.');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        try {
            $lesson->delete();
            return back();
        } catch (Exception $exception) {
            return back()->with('message', 'Помилка видалення заняття');
        }
    }
}
