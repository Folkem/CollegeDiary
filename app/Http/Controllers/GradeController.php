<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        info($request->input('student-id'));
        info($request->input('lesson-id'));
        info($request->input('grade-id'));
        info($request->input('value'));

        $allowedGradeValues = ['-1', 'absent', 'present'];
        for ($i = 0; $i <= 100; $i++) $allowedGradeValues[] = $i;
        $request->validate([
            'student-id' => [
                'required', 'numeric',
                Rule::in(User::all()->map(fn($user) => $user->id)),
            ],
            'lesson-id' => [
                'required', 'numeric',
                Rule::in(Lesson::all()->map(fn($user) => $user->id)),
            ],
            'value' => [
                'required',
                Rule::in($allowedGradeValues),
            ],
        ]);

        if ($request->input('grade-id') !== null) {
            $grade = Grade::query()->findOrFail($request->input('grade-id'));
            if (in_array($request->input('value'), ['absent', 'present'])) {
                $grade->update([
                    'is_present' => $request->input('value') == 'present',
                    'grade' => null,
                ]);
            } elseif ($request->input('value') == '-1') {
                $grade->delete();
            } else {
                $grade->update([
                    'is_present' => true,
                    'grade' => $request->input('value'),
                ]);
            }
        } elseif ($request->input('value') !== '-1') {
            Grade::query()->create([
                'student_id' => $request->input('student-id'),
                'lesson_id' => $request->input('lesson-id'),
                'is_present' => $request->input('value') == 'present',
                'grade' => (in_array($request->input('value'), ['present', 'absent']))
                    ? null
                    : $request->input('value')
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
