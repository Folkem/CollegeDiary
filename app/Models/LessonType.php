<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class LessonType
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property Collection $lessons
 */
class LessonType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
