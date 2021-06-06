<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        // todo:
    }

    public function update(Request $request, User $student)
    {
        // todo:
    }

    public function destroy(User $student): RedirectResponse
    {
        $student->delete();

        return back();
    }
}
