<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use App\Models\Group;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DisciplineController extends Controller
{
    public function teacherIndex()
    {
        $disciplines = Discipline::query()->where('teacher_id', auth()->id())->get();

        return view('disciplines.teacher.index', compact('disciplines'));
    }

    public function studentIndex()
    {
        $disciplines = auth()->user()->group->disciplines;

        return view('disciplines.student.index', compact('disciplines'));
    }

    public function teacherShow(Discipline $discipline)
    {
        $lessons = $discipline->lessons()->with('grades')->orderBy('created_at')->get();
        $homeworks = $discipline->homeworks()->orderBy('created_at')->get();
        $grades = [];
        $dbGrades = $discipline->grades->groupBy(fn($grade) => $grade->student_id);
        $students = $discipline->group->students;

        foreach ($students as $student) {
            $grades[$student->id] = [];
        }

        foreach ($dbGrades as $studentId => $studentGrades) {
            foreach ($studentGrades as $grade) {
                $grades[$studentId][] = [
                    'id' => $grade->id,
                    'student_id' => $studentId,
                    'is_present' => $grade->is_present,
                    'grade' => $grade->grade,
                    'lesson_id' => $grade->lesson_id
                ];
            }
        }

        foreach ($lessons as $lesson) {
            foreach ($grades as &$studentGrades) {
                if (count(array_filter($studentGrades, function ($grade) use ($lesson) {
                    return $grade['lesson_id'] == $lesson->id;
                })) == 0) {
                    $studentGrades[] = [
                        'lesson_id' => $lesson->id,
                        'grade' => null,
                        'is_present' => null,
                    ];
                }
            }
        }

        return view('disciplines.teacher.show',
            compact('discipline', 'lessons', 'homeworks', 'grades', 'students'));
    }

    public function studentShow(Discipline $discipline)
    {
        $lessons = $discipline->lessons()->orderBy('created_at')->get();
        $homeworks = $discipline->homeworks()->orderBy('created_at')->get();
        $grades = $discipline->grades()->where('student_id', auth()->id())->orderby('created_at')->get();

        return view('disciplines.student.show',
            compact('discipline', 'lessons', 'homeworks', 'grades'));
    }

    public function create()
    {
        $groups = Group::all();
        $teachers = User::query()->where('role_id', 3)->get();

        return view('disciplines.create', compact('teachers', 'groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => [
                'required', 'string', 'between:5,255',
            ],
            'teacher_id' => [
                'required', 'numeric',
                Rule::in(User::query()->where('role_id', 3)->get()
                    ->map(fn($teacher) => $teacher->id)->all()),
            ],
            'group_id' => [
                'required', 'numeric',
                Rule::in(Group::all('id')->map(fn($group) => $group->id)->all()),
            ],
        ]);

        Discipline::query()->create($validated);

        return back()->with('message', 'Дисципліна успішно додана.');
    }

    public function edit(Discipline $discipline)
    {
        $groups = Group::all();
        $teachers = User::query()->where('role_id', 3)->get();

        return view('disciplines.edit', compact('discipline', 'teachers', 'groups'));
    }

    public function update(Request $request, Discipline $discipline): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => [
                'required', 'string', 'between:5,255',
            ],
            'teacher_id' => [
                'required', 'numeric',
                Rule::in(User::query()->where('role_id', 3)->get()
                    ->map(fn($teacher) => $teacher->id)->all()),
            ],
            'group_id' => [
                'required', 'numeric',
                Rule::in(Group::all('id')->map(fn($group) => $group->id)->all()),
            ],
        ]);

        $discipline->update($validated);

        return back()->with('message', 'Дисципліна успішно оновлена.');
    }

    public function destroy(Discipline $discipline): RedirectResponse
    {
        try {
            $discipline->delete();
            return back();
        } catch (Exception $exception) {
            return back()->with('message', 'Дисципліну не вдалося видалити. Скоріш за все, вона ' .
                'знаходиться в деяких розкладах та заняттях. Спочатку відредагуйте розклади та видаліть заняття');
        }
    }
}
