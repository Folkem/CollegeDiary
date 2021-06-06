<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Lesson;
use App\Models\LessonType;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function create()
    {
        $types = LessonType::all();

        return view('lessons.create', compact('types'));
    }

    public function store(Request $request, Discipline $discipline)
    {
        //
    }

    public function edit(Lesson $lesson)
    {
        //
    }

    public function update(Request $request, Lesson $lesson)
    {
        //
    }

    public function destroy(Lesson $lesson)
    {
        //
    }
}
