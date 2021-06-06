<?php

namespace App\Http\Controllers;

use App\Models\CallScheduleItem;
use App\Models\Group;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $callScheduleItems = CallScheduleItem::all();
        $groups = Group::all();
        $lessonSchedules = $groups->map(fn($group) => $group->lessonSchedule);

        return view('schedules', compact('callScheduleItems', 'groups', 'lessonSchedules'));
    }
}
