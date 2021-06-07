<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Homework $homework)
    {
        return view('homeworks.show', compact('homework'));
    }

    public function edit(Homework $homework)
    {
        //
    }

    public function update(Request $request, Homework $homework)
    {
        //
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
