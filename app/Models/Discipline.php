<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Discipline
 * @package App\Models
 * @property int $id
 * @property Group $group
 * @property User $teacher
 * @property string $subject
 * @property string $forStudent
 * @property string $forTeacher
 * @property string $forAdmin
 * @property Collection $lessonScheduleItems
 * @property Collection $lessons
 * @property Collection $homeworks
 */
class Discipline extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'teacher_id',
        'subject',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lessonScheduleItems(): HasMany
    {
        return $this->hasMany(LessonScheduleItem::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function getForStudentAttribute(): string
    {
        return sprintf("%s (%s)", $this->subject, $this->teacher->name);
    }

    public function getForTeacherAttribute(): string
    {
        return sprintf("%s (%s)", $this->subject, $this->group->humanName);
    }

    public function getForAdminAttribute(): string
    {
        return sprintf("%s (%s — %s)", $this->subject, $this->group->humanName, $this->teacher->name);
    }

    public function homeworks(): HasMany
    {
        return $this->hasMany(Homework::class);
    }
}
