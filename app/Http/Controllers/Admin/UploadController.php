<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use App\Models\Group;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use SimpleXLSX;

class UploadController extends Controller
{
    public function studentsShow()
    {
        return view('upload.students');
    }

    public function teachersShow()
    {
        return view('upload.teachers');
    }

    public function disciplinesShow()
    {
        return view('upload.disciplines');
    }

    public function studentsUpload(Request $request): RedirectResponse
    {
        $request->validate([
            'excel' => ['required', 'file']
        ]);

        $excelFile = $request->file('excel');
        $parsedData = SimpleXLSX::parse($excelFile->getPathname());

        if ($parsedData === false) {
            return back()->with('success', 'Файл не вдалося відкрити');
        }

        $startRowIndex = 3;
        $nameCellIndex = 1;
        $emailCellIndex = 6;

        $rows = $parsedData->rows();
        $rows = array_filter(
            $rows,
            fn($rowIndex) => $rowIndex >= $startRowIndex,
            ARRAY_FILTER_USE_KEY
        );
        $rows = array_map(
            function ($row) use ($nameCellIndex, $emailCellIndex) {
                $nameCell = $row[$nameCellIndex];
                $emailCell = $row[$emailCellIndex];
                return [$nameCell, $emailCell];
            },
            $rows
        );

        $hashedPassword = Hash::make('password');

        $message = '';

        foreach ($rows as $row) {
            try {
                $name = $row[0];
                $email = $row[1];

                Validator::validate([
                    'name' => $name,
                    'email' => $email
                ], [
                    'name' => [
                        'required', 'between:3,255', 'string',
                    ],
                    'email' => [
                        'required', 'between:3,255', 'email', 'string',
                    ],
                ]);

                User::query()->create([
                    'name' => $name,
                    'email' => $email,
                    'role_id' => 4,
                    'group_id' => null,
                    'password' => $hashedPassword,
                ]);
            } catch (ValidationException $validationException) {
                $message .= sprintf('Студент %s (%s) не пройшов валідацію. \n', $name, $email);
            } catch (Exception $exception) {
                logger()->warning($exception->getTraceAsString());
                $message .= sprintf('Студента %s (%s) не вдалося добавити. Скоріш за все, обліковий запис ' .
                    'зі вказаними даними вже існує. ', $name, $email);
            }
        }

        return back()->with('message', empty($message) ? 'Всі студенти успішно додані. ' : $message);
    }

    public function teachersUpload(Request $request): RedirectResponse
    {
        $request->validate([
            'excel' => ['required', 'file']
        ]);

        $excelFile = $request->file('excel');
        $parsedData = SimpleXLSX::parse($excelFile->getPathname());

        if ($parsedData === false) {
            return back()->with('success', 'Файл не вдалося відкрити');
        }

        $startRowIndex = 3;
        $nameCellIndex = 1;
        $emailCellIndex = 5;

        $rows = $parsedData->rows();
        $rows = array_filter(
            $rows,
            fn($rowIndex) => $rowIndex >= $startRowIndex,
            ARRAY_FILTER_USE_KEY
        );
        $rows = array_map(
            function ($row) use ($nameCellIndex, $emailCellIndex) {
                $nameCell = $row[$nameCellIndex];
                $emailCell = $row[$emailCellIndex];
                return [$nameCell, $emailCell];
            },
            $rows
        );

        $hashedPassword = Hash::make('password');

        $message = '';

        foreach ($rows as $row) {
            try {
                $name = $row[0];
                $email = $row[1];

                Validator::validate([
                    'name' => $name,
                    'email' => $email
                ], [
                    'name' => [
                        'required', 'between:3,255', 'string',
                    ],
                    'email' => [
                        'required', 'between:3,255', 'email', 'string',
                    ],
                ]);

                User::query()->create([
                    'name' => $name,
                    'email' => $email,
                    'role_id' => 3,
                    'group_id' => null,
                    'password' => $hashedPassword,
                ]);
            } catch (ValidationException $validationException) {
                $message .= sprintf('Викладач %s (%s) не пройшов валідацію. \n', $name, $email);
            } catch (Exception $exception) {
                logger()->warning($exception->getTraceAsString());
                $message .= sprintf('Викладача %s (%s) не вдалося добавити. Скоріш за все, обліковий запис ' .
                    'зі вказаними даними вже існує. ', $name, $email);
            }
        }

        return back()->with('message', empty($message) ? 'Всі викладачі успішно додані. ' : $message);
    }

    public function disciplinesUpload(Request $request): RedirectResponse
    {
        $request->validate([
            'excel' => ['required', 'file']
        ]);

        $excelFile = $request->file('excel');
        $parsedData = SimpleXLSX::parse($excelFile->getPathname());

        if ($parsedData === false) {
            return back()->with('success', 'Файл не вдалося відкрити');
        }

        $startRowIndex = 6;
        $subjectCellIndex = 0;
        $teacherCellIndex = 1;
        $groupCellIndex = 26;

        $rows = $parsedData->rows();
        $rows = array_filter(
            $rows,
            fn($rowIndex) => $rowIndex >= $startRowIndex,
            ARRAY_FILTER_USE_KEY
        );
        $rows = array_map(
            function ($row) use ($subjectCellIndex, $teacherCellIndex, $groupCellIndex) {
                $subjectCell = $row[$subjectCellIndex];
                $teacherCell = $row[$teacherCellIndex];
                $groupCell = $row[$groupCellIndex];
                return [$subjectCell, $teacherCell, $groupCell];
            },
            $rows
        );

        $message = '';

        $teachers = User::query()->where('role_id', 3)->get()->groupBy(function (User $user) {
            $fullName = explode(' ', $user->name);
            $initials = mb_substr($fullName[1], 0, 1) .
                (count($fullName) == 3 ? mb_substr($fullName[2], 0, 1) : '');
            return (string)Str::of($fullName[0] . $initials)->lower()->replace([' ', '-', '.'], '')->trim();
        });

        $groups = Group::all()->groupBy(function (Group $group) {
            return (string)Str::of($group->machineName)->lower();
        });

        $alreadySentMessages = [];

        foreach ($rows as $row) {
            try {
                $originalTeacher = $row[1];
                $originalGroup = $row[2];

                $teacher = null;
                $group = null;

                $subject = $row[0];
                $teacherName = (string)Str::of($row[1])->lower()->replace([' ', '-', '.'], '')->trim();
                $groupName = (string)Str::of($row[2])->lower()->replace(' ', '')->trim();

                $continue = false;

                if (Str::of($subject)->length() < 3 || Str::of($subject)->length() > 255) {
                    $continue = true;
                    $message .= sprintf('Предмет (%s) повинен бути в довжину від 3 до 255 символів. ', $subject);
                }
                $contains = false;
                foreach ($teachers as $name => $obj) {
                    if ($teacherName == $name) {
                        $contains = true;
                        $teacher = $obj->first();
                        break;
                    }
                }
                if (!$contains) {
                    $continue = true;
                    $errorMessage = sprintf('Викладач %s не був знайдений. ', $originalTeacher);
                    if (!in_array($errorMessage, $alreadySentMessages)) {
                        $message .= $errorMessage;
                    }
                    $alreadySentMessages[] = $errorMessage;
                }
                $contains = false;
                foreach ($groups as $name => $obj) {
                    if ($groupName == $name) {
                        $contains = true;
                        $group = $obj->first();
                        break;
                    }
                }
                if (!$contains) {
                    $continue = true;
                    $errorMessage = sprintf('Група %s не була знайдена. ', $originalGroup);
                    if (!in_array($errorMessage, $alreadySentMessages)) {
                        $message .= $errorMessage;
                    }
                    $alreadySentMessages[] = $errorMessage;
                }

                if ($continue) continue;

                Discipline::query()->create([
                    'subject' => $subject,
                    'teacher_id' => $teacher->id,
                    'group_id' => $group->id
                ]);
            } catch (Exception $exception) {
                info($teacher);
                info($group);
                info($exception->getMessage() . ' - ' . $exception->getTraceAsString());
                $message .= sprintf('Дисципліну %s (%s — %s) не вдалося добавити. ',
                    $subject, $originalTeacher, $originalGroup);
            }
        }

        return back()->with('message', empty($message) ? 'Всі дисципліни успішно додані. ' : $message);
    }
}
