<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $students = User::with('group')->where('role_id', 4)->get();
        $teachers = User::with('group')->where('role_id', 3)->get();

        return view('admin.index', compact('students', 'teachers'));
    }
}
