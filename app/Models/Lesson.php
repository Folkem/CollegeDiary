<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Lesson
 * @package App\Models
 * @property int $id
 * @property LessonType $lessonType
 * @property Discipline $discipline
 * @property string $description
 */
class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_type_id',
        'discipline_id',
        'description',
    ];

    public function lessonType(): BelongsTo
    {
        return $this->belongsTo(LessonType::class);
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }
}
