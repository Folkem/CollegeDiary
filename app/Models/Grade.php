<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Grade
 * @package App\Models
 * @property int $id
 * @property User $student
 * @property Lesson $lesson
 * @property int $grade
 * @property bool $isPresent
 */
class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'lesson_id',
        'grade',
        'is_present',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
