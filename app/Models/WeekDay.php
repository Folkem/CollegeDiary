<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class WeekDay
 * @package App\Models
 * @property string $name
 * @property Collection $lessonScheduleItems
 */
class WeekDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function lessonScheduleItems(): HasMany
    {
        return $this->hasMany(LessonScheduleItem::class);
    }
}
