<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Group;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $students = User::with('group')->where('role_id', 4)->get();
        $teachers = User::with('group')->where('role_id', 3)->get();
        $disciplines = Discipline::all();
        $newsList = News::all();
        $groups = Group::all();

        return view('admin.index',
            compact('students', 'teachers', 'disciplines', 'newsList', 'groups'));
    }
}
