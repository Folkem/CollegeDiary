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

        return view('schedules.index', compact('callScheduleItems', 'groups'));
    }
}
