<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\LessonScheduleItem;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function edit(Group $group)
    {
        $disciplines = $group->disciplines;

        return view('schedules.edit', compact('group', 'disciplines'));
    }

    public function updateLessonSchedule(Request $request, Group $group): JsonResponse
    {
        try {
            $request->validate([
                'schedule' => 'json',
            ]);

            $schedule = json_decode($request->input('schedule'), true);

            if ([1, 2, 3, 4, 5] !== array_keys($schedule)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Розклад повинен містити дані по всім п\'яти днім тижня',
                ]);
            }

            $group->lessonScheduleItems->each(fn($lsi) => $lsi->delete());

            foreach ($schedule as $weekDayId => $daySchedule) {
                foreach ($daySchedule as $number => $numberSchedule) {
                    foreach ($numberSchedule as $variant => $disciplineId) {
                        if ($disciplineId !== '') {
                            LessonScheduleItem::query()->create([
                                'week_day_id' => $weekDayId,
                                'discipline_id' => $disciplineId,
                                'call_schedule_item_id' => $number,
                                'variant' => $variant,
                            ]);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $exception) {
            logger()->error($exception);
            return response()->json([
                'success' => false,
                'message' => 'Серверна помилка обробки розкладу',
            ], 500);
        }
    }
}
