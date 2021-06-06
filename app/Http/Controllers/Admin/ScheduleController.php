<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function edit(Group $group)
    {
        return view('schedules.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        // todo
    }
}
